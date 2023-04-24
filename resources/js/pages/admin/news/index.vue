<script setup>
import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
import { useNewsStore } from "./useNewsStore";
dayjs.extend(buddhistEra);

const newsStore = useNewsStore();

const rowPerPage = ref(20);
const currentPage = ref(1);
const totalPage = ref(1);
const totalItems = ref(0);
const items = ref([]);
const isOverLay = ref(true);
const orderBy = ref("created_at");
const order = ref("desc");
// const dayjs = require('dayjs');

dayjs().format();

const advancedSearch = reactive({
  title: "",
  title_en: "",
  created_at: "",
  news_type_id: "",
  created_at: "",
});

const resetAdvancedSearch = () => {
  advancedSearch.title = "";
  advancedSearch.title_en = "";
  advancedSearch.news_type_id = "";
  advancedSearch.created_at = "";
};

const selectOptions = ref({
  news_types: [],
  perPage: [
    { title: "20", value: 20 },
    { title: "40", value: 40 },
    { title: "60", value: 60 },
  ],
  orderBy: [
    { title: "วันที่ลงข่าว/Created At", value: "created_at" },
    { title: "ประเภทข่าว/News Type", value: "news_type_id" },
  ],
  order: [
    { title: "น้อย -> มาก", value: "asc" },
    { title: "มาก -> น้อย", value: "desc" },
  ],
});

// 👉 Fetching News
const fetchItems = () => {
  isOverLay.value = true;
  let search = { ...advancedSearch };

  newsStore
    .fetchNewses({
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
        isOverLay.value = false;
      } else {
        console.log("error");
      }
    })
    .catch((error) => {
      console.error(error);
      isOverLay.value = false;
    });
};

const fetchNewsTypes = () => {
  newsStore
    .fetchNewsTypes({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        selectOptions.value.news_types = response.data.data.map((d) => {
          return {
            title: d.title,
            value: d.id,
          };
        });
        console.log(selectOptions.value.news_type)
        isOverLay.value = false;
      } else {
        console.log("error");
      }
    })
    .catch((error) => {
      console.error(error);
      isOverLay.value = false;
    });
};
fetchNewsTypes();

watchEffect(fetchItems);

// 👉 watching current page
watchEffect(() => {
  if (currentPage.value > totalPage.value) currentPage.value = totalPage.value;
});

const isSnackbarVisible = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");

if (localStorage.getItem("deleted") == 1) {
  snackbarText.value = "Deleted News";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("deleted");
}
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
                <VCol cols="12" sm="6">
                  <VTextField
                    v-model="advancedSearch.title"
                    label="หัวข่อข่าว/Title"
                    density="compact"
                  />
                </VCol>
                <!-- 👉 Search Title EN -->
                <VCol cols="12" sm="6">
                  <VTextField
                    v-model="advancedSearch.title_en"
                    label="หัวข้อข่าว (EN)/Title EN"
                    density="compact"
                  />
                </VCol>

                <VCol cols="12" sm="6">
                  <VSelect
                    id="news_type_id"
                    v-model="advancedSearch.news_type_id"
                    label="ประเภทข่าว/News Type"
                    :items="selectOptions.news_types"
                  />
                </VCol>

                <VCol cols="12" sm="6">
                  <VTextField
                    v-model="advancedSearch.created_at"
                    label="หัวข้อข่าว (EN)/Title EN"
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
                <VCol cols="12" sm="4">
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
                    name: 'admin-news-add',
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
                  <th scope="col">หัวข้อข่าว</th>
                  <th scope="col">หัวข้อข่าว EN</th>
                  <th scope="col">ประเภทข่าว</th>
                  <th scope="col" class="text-center">รูปปกข่าว</th>
                  <th scope="col" class="text-center">สถานะ</th>
                  <th scope="col" class="text-center">วันที่ลงข่าว</th>
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
                  <td>
                    <span>
                      {{ it.news_type_title }}
                    </span>
                  </td>
                  <td>
                    <VImg :src="it.news_file" width="200" class="ma-1" />
                  </td>
                  <!-- <td>
                    <VImg :src="it.banner_file" width="400" class="ma-1" />
                
                  </td> -->

                  <td class="text-center" style="min-width: 110px">
                    <VChip color="success" v-if="it.is_publish == 1">
                      Publish
                    </VChip>
                    <VChip color="default" v-else> Unpublish </VChip>
                    <!-- <VSwitch
                      v-model="it.is_publish"
                      color="success"
                    /> -->
                  </td>
                  <td style="min-width: 110px">
                    <span>
                      {{
                        dayjs(it.created_at).locale("th").format("DD MMM BB")
                      }}
                    </span>
                  </td>

                  <!-- 👉 Actions -->

                  <td class="text-center" style="min-width: 80px">
                    <VBtn
                      icon
                      size="x-small"
                      color="primary"
                      :to="{
                        name: 'admin-news-view-id',
                        params: { id: it.id },
                      }"
                    >
                      <VIcon size="22" icon="tabler-eye" />
                    </VBtn>

                    <!-- <VBtn
                      icon
                      size="x-small"
                      color="success"
                      class="ml-1"
                      :to="{
                        name: 'admin-banner-edit-id',
                        params: { id: it.id },
                      }"
                    >
                      <VIcon size="22" icon="tabler-edit" />
                    </VBtn> -->

                    <!-- :to="{
                              name: 'apps-user-view-id',
                              params: { id: it.id },
                            }" -->
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
