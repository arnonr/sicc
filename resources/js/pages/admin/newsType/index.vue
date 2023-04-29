<script setup>
import { useNewsTypeStore } from "./useNewsTypeStore";

const newsTypeStore = useNewsTypeStore();

const rowPerPage = ref(20);
const currentPage = ref(1);
const totalPage = ref(1);
const totalItems = ref(0);
const items = ref([]);
const isOverlay = ref(true);
const orderBy = ref("created_at");
const order = ref("asc");
const IdItemRemove = ref(null);
const isDialogVisible = ref(false);

const advancedSearch = reactive({
  title: "",
  title_en: "",
});

const resetAdvancedSearch = () => {
  advancedSearch.title = "";
  advancedSearch.title_en = "";
};

const selectOptions = ref({
  perPage: [
    { title: "20", value: 20 },
    { title: "40", value: 40 },
    { title: "60", value: 60 },
  ],
  orderBy: [{ title: "วันที่/Created At", value: "created_at" }],
  order: [
    { title: "น้อย -> มาก", value: "asc" },
    { title: "มาก -> น้อย", value: "desc" },
  ],
});

// 👉 Fetching News Type
const fetchItems = () => {
  isOverlay.value = true;
  let search = { ...advancedSearch };

  newsTypeStore
    .fetchNewsTypes({
      perPage: rowPerPage.value,
      currentPage: currentPage.value,
      orderBy: orderBy.value,
      order: order.value,
      ...search,
    })
    .then((response) => {
      if (response.data.message == "success") {
        items.value = response.data.data;
        totalPage.value = response.data.totalPage;
        totalItems.value = response.data.totalData;
        isOverlay.value = false;
      } else {
        console.log("error");
      }
    })
    .catch((error) => {
      console.error(error);
      isOverlay.value = false;
    });
};

watchEffect(fetchItems);

// 👉 watching current page
watchEffect(() => {
  if (currentPage.value > totalPage.value) currentPage.value = totalPage.value;
});

const isSnackbarVisible = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");

// const handleLevelClick = (id, type) => {
//   bannerStore
//     .editLevelBanner({
//       id: id,
//       type: type,
//     })
//     .then((response) => {
//       if (response.data.message == "success") {
//         fetchItems();
//         // Success
//         snackbarText.value = "Ordered";
//         isSnackbarVisible.value = true;
//         snackbarColor.value = "success";
//       } else {
//         console.log("error");
//       }
//     })
//     .catch((error) => {
//       console.error(error);
//       isOverlay.value = false;
//     });
// };
if (localStorage.getItem("added") == 1) {
  snackbarText.value = "Added News Type";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("added");
}

if (localStorage.getItem("updated") == 1) {
  snackbarText.value = "Updated News Type";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("updated");
}

// if (localStorage.getItem("deleted") == 1) {
//   snackbarText.value = "Deleted News Type";
//   snackbarColor.value = "success";
//   isSnackbarVisible.value = true;
//   localStorage.removeItem("deleted");
// }

const onConfirmDelete = (id) => {
  IdItemRemove.value = id;
  isDialogVisible.value = true;
};

const onDelete = (id) => {
  newsTypeStore
    .deleteNewsType({
      id: id,
    })
    .then((response) => {
      if (response.data.message == "success") {
        fetchItems();
        isDialogVisible.value = false;
        snackbarText.value = "Deleted News Type";
        snackbarColor.value = "success";
        isSnackbarVisible.value = true;
      } else {
        console.log("error");
      }
    })
    .catch((error) => {
      console.error(error);
      isOverlay.value = false;
    });
};

onMounted(() => {
  window.scrollTo(0, 0);
});
</script>

<template>
  <main class="layout-page-content mb-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol cols="12">
          <VCard title="ค้นหา/Search">
            <!-- 👉 Filters -->
            <VCardText>
              <VRow>
                <!-- 👉 Search Title -->
                <VCol cols="12" sm="4">
                  <VTextField
                    v-model="advancedSearch.title"
                    label="ประเภทข่าว/Title"
                    density="compact"
                  />
                </VCol>
                <!-- 👉 Search Title EN -->
                <VCol cols="12" sm="4">
                  <VTextField
                    v-model="advancedSearch.title_en"
                    label="ประเภทข่าว (EN)/Title EN"
                    density="compact"
                  />
                </VCol>

                <VCol cols="12">
                  <VBtn
                    variant="outlined"
                    color="error"
                    @click="resetAdvancedSearch()"
                  >
                    Clear
                  </VBtn>
                </VCol>
              </VRow>
            </VCardText>
            <!--  -->
          </VCard>
        </VCol>
      </VRow>
      <!-- End Search -->
      <!-- Table -->
      <VRow>
        <VCol cols="12">
          <VCard title="" class="pb-5 pt-5">
            <VCardText class="d-flex flex-wrap py-4 gap-4">
              <VRow>
                <VCol cols="12" sm="4">
                  <VSelect
                    label="จำนวนรายการ/page"
                    v-model="rowPerPage"
                    density="compact"
                    variant="outlined"
                    :items="selectOptions.perPage"
                  />
                </VCol>
                <!-- <VCol cols="12" sm="4">
                  <VSelect
                    label="ลักษณะการแสดงผล"
                    v-model="orderBy"
                    density="compact"
                    variant="outlined"
                    :items="selectOptions.orderBy"
                  />
                </VCol>
                <VCol cols="12" sm="4">
                  <VSelect
                    label="ลำดับ"
                    v-model="order"
                    density="compact"
                    variant="outlined"
                    :items="selectOptions.order"
                  />
                </VCol> -->
              </VRow>
              <VSpacer />
              <div class="text-right">
                <!-- 👉 Add user button -->
                <VBtn
                  prepend-icon="tabler-plus"
                  variant="outlined"
                  class="float-right"
                  :to="{
                    name: 'admin-newsType-add',
                  }"
                >
                  Add
                </VBtn>
              </div>
            </VCardText>
            <!-- <VDivider class="ml-2 mr-2 mt-2" /> -->

            <!-- class="text-no-wrap" -->

            <VTable class="ml-2 mr-2 admin-table">
              <!-- 👉 table head -->
              <thead>
                <tr>
                  <th scope="col">ประเภทข่าว</th>
                  <th scope="col">ประเภทข่าว EN</th>
                  <th scope="col" class="text-center">สถานะ</th>
                  <th scope="col" class="text-center">จัดการ</th>
                </tr>
              </thead>
              <!-- 👉 table body -->
              <tbody>
                <tr v-for="it in items" :key="it.id" style="height: 3.75rem">
                  <!-- 👉 User -->
                  <td>
                    <span>
                      {{ it.title }}
                    </span>
                  </td>
                  <td>
                    <span>
                      {{ it.title_en }}
                    </span>
                  </td>
                  <td class="text-center" style="min-width: 110px">
                    <VChip color="success" v-if="it.is_publish == 1">
                      Publish
                    </VChip>
                    <VChip color="default" v-else> Unpublish </VChip>
                  </td>

                  <!-- 👉 Actions -->
                  <td class="text-center" style="min-width: 50px">
                    <VBtn
                      icon
                      size="x-small"
                      color="success"
                      class="ml-1"
                      :to="{
                        name: 'admin-newsType-edit-id',
                        params: { id: it.id },
                      }"
                    >
                      <VIcon size="22" icon="tabler-edit" />
                    </VBtn>
                    <!--  -->
                    <VBtn
                      icon
                      size="x-small"
                      color="error"
                      class="ml-1"
                      @click="onConfirmDelete(it.id)"
                    >
                      <VIcon size="22" icon="tabler-trash" />
                    </VBtn>
                  </td>
                </tr>
              </tbody>

              <!-- 👉 table footer  -->
              <tfoot v-show="!items.length">
                <tr>
                  <td colspan="7" class="text-center">No data available</td>
                </tr>
              </tfoot>
              <tfoot v-show="items.length"></tfoot>
            </VTable>

            <VCardText
              class="d-flex align-center flex-wrap justify-space-between gap-4 py-3 px-5"
            >
              <span class="text-sm text-disabled">
                Showing {{ currentPage }} to {{ totalPage }} of
                {{ totalItems }} entries
              </span>

              <VPagination
                v-model="currentPage"
                size="small"
                :total-visible="5"
                :length="totalPage"
              />
            </VCardText>

            <!--  -->
          </VCard>
        </VCol>
      </VRow>

      <VSnackbar
        v-model="isSnackbarVisible"
        location="top end"
        :color="snackbarColor"
      >
        {{ snackbarText }}
        <template #actions>
          <VBtn color="error" @click="isSnackbarVisible = false"> Close </VBtn>
        </template>
      </VSnackbar>

      <VOverlay
        v-model="isOverlay"
        contained
        persistent
        class="align-center justify-center"
      >
        <VProgressCircular indeterminate />
      </VOverlay>
    </section>

    <!-- Delete Dialog -->
    <VDialog v-model="isDialogVisible" persistent class="v-dialog-sm">
      <!-- Dialog close btn -->
      <DialogCloseBtn @click="isDialogVisible = !isDialogVisible" />

      <!-- Dialog Content -->
      <VCard title="Are You Sure?">
        <VCardText>
          But you will still be able to retrieve this file.
        </VCardText>

        <VCardText class="d-flex justify-end gap-3 flex-wrap">
          <VBtn
            color="secondary"
            variant="tonal"
            @click="isDialogVisible = false"
          >
            Cancel
          </VBtn>
          <VBtn @click="onDelete(IdItemRemove)" color="error"> Delete </VBtn>
        </VCardText>
      </VCard>
    </VDialog>
  </main>
</template>

<style lang="scss"></style>

<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
