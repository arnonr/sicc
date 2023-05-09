<script setup>
import { useTeamStore } from "./useTeamStore";

const teamStore = useTeamStore();

const rowPerPage = ref(20);
const currentPage = ref(1);
const totalPage = ref(1);
const totalItems = ref(0);
const items = ref([]);
const isOverlay = ref(true);
const orderBy = ref("level");
const order = ref("asc");

const advancedSearch = reactive({
  firstname: "",
  surname: "",
  department_id: "",
  firstname_en: "",
  surname_en: "",
  position: "",
  position_type: "",
});

const resetAdvancedSearch = () => {
  advancedSearch.firstname = "";
  advancedSearch.surname = "";
  advancedSearch.firstname_en = "";
  advancedSearch.surname_en = "";
  advancedSearch.department_id = "";
  advancedSearch.position_type = "";
  advancedSearch.position = "";
};

const selectOptions = ref({
  departments: [],
  perPage: [
    { title: "20", value: 20 },
    { title: "40", value: 40 },
    { title: "60", value: 60 },
  ],
  orderBy: [{ title: "ลำดับ/level", value: "level" }],
  order: [
    { title: "น้อย -> มาก", value: "asc" },
    { title: "มาก -> น้อย", value: "desc" },
  ],
});

const fetchDepartments = () => {
  teamStore
    .fetchDepartments({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        selectOptions.value.departments = response.data.data.map((d) => {
          return {
            title: d.title,
            value: d.id,
          };
        });
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
fetchDepartments();

// 👉 Fetching
const fetchItems = () => {
  isOverlay.value = true;
  let search = {
    ...advancedSearch,
  };

  teamStore
    .fetchTeams({
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

if (localStorage.getItem("deleted") == 1) {
  snackbarText.value = "Deleted Team";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("deleted");
}

const handleLevelClick = (id, type) => {
  teamStore
    .editLevelTeam({
      id: id,
      type: type,
    })
    .then((response) => {
      if (response.data.message == "success") {
        fetchItems();
        // Success
        snackbarText.value = "Ordered";
        isSnackbarVisible.value = true;
        snackbarColor.value = "success";
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
          <VCard
            title="ค้นหา/Search"
            style="overflow: inherit; position: inherit"
          >
            <!-- 👉 Filters -->
            <VCardText>
              <VRow>
                <!-- 👉 Search Title -->
                <VCol cols="12" sm="4">
                  <VTextField
                    v-model="advancedSearch.firstname"
                    label="ชื่อ/Firstname"
                    density="compact"
                  />
                </VCol>
                <!-- 👉 Search Title EN -->
                <VCol cols="12" sm="4">
                  <VTextField
                    v-model="advancedSearch.surname"
                    label="นามสกุล/Surname"
                    density="compact"
                  />
                </VCol>

                <!-- 👉 Search Title EN -->
                <VCol cols="12" sm="4">
                  <VSelect
                    id="news_type_id"
                    v-model="advancedSearch.department_id"
                    label="ฝ่ายงาน/Department"
                    :items="selectOptions.departments"
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
              </VRow>
              <VSpacer />
              <div class="text-right">
                <!-- 👉 Add user button -->
                <VBtn
                  prepend-icon="tabler-plus"
                  variant="outlined"
                  class="float-right"
                  :to="{
                    name: 'admin-team-add',
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
                  <th scope="col">รูป</th>
                  <th scope="col">ชื่อ</th>
                  <th scope="col">นามสกุล</th>
                  <th scope="col">ฝ่ายงาน</th>
                  <th scope="col" class="text-center">สถานะ</th>
                  <th scope="col" class="text-center">ลำดับ</th>
                  <th scope="col" class="text-center">จัดการ</th>
                </tr>
              </thead>
              <!-- 👉 table body -->
              <tbody>
                <tr v-for="it in items" :key="it.id" style="height: 3.75rem">
                  <!-- 👉 User -->
                  <td>
                    <VImg :src="it.team_file" width="200" class="ma-1" />
                  </td>
                  <td>
                    <span>
                      {{ it.firstname }}
                    </span>
                  </td>
                  <td>
                    <span>
                      {{ it.surname }}
                    </span>
                  </td>
                  <td>
                    <span>
                      {{ it.department_name }}
                    </span>
                  </td>
                  <td class="text-center" style="min-width: 110px">
                    <VChip color="success" v-if="it.is_publish == 1">
                      Publish
                    </VChip>
                    <VChip color="default" v-else> Unpublish </VChip>
                  </td>
                  <td class="text-center" style="min-width: 100px">
                    <VBtn icon size="x-small" color="info">
                      <VIcon
                        size="22"
                        icon="tabler-arrow-up"
                        @click="handleLevelClick(it.id, 'DC')"
                      />
                    </VBtn>
                    <VBtn icon size="x-small" color="warning" class="ml-1">
                      <VIcon
                        size="22"
                        icon="tabler-arrow-down"
                        @click="handleLevelClick(it.id, 'IC')"
                      />
                    </VBtn>
                  </td>

                  <!-- 👉 Actions -->
                  <td class="text-center" style="min-width: 80px">
                    <VBtn
                      icon
                      size="x-small"
                      color="primary"
                      :to="{
                        name: 'admin-team-view-id',
                        params: { id: it.id },
                      }"
                    >
                      <VIcon size="22" icon="tabler-eye" />
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
  </main>
</template>

<style lang="scss"></style>

<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
