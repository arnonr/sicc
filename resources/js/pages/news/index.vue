<script setup>
import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
import { useNewsStore } from "./useNewsStore";

import "@vuepic/vue-datepicker/dist/main.css";

dayjs.extend(buddhistEra);

const newsStore = useNewsStore();
``;

const rowPerPage = ref(20);
const currentPage = ref(1);
const totalPage = ref(1);
const totalItems = ref(0);
const items = ref([]);
const isOverlay = ref(true);
// const dayjs = require('dayjs');

dayjs().format();

const lang = ref("th");
if (localStorage.getItem("currentLang") === "en") {
  lang.value = localStorage.getItem("currentLang");
}

const advancedSearch = reactive({
  title: "",
  title_en: "",
  created_at: "",
  news_type_id: "",
  year: "",
  month: "",
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
  years: [],
  months: [
    { title: "มกราคม", title_en: "January", value: "01" },
    { title: "กุมภาพันธ์", title_en: "February", value: "02" },
    { title: "มีนาคม", title_en: "March", value: "03" },
    { title: "เมษายน", title_en: "April", value: "04" },
    { title: "พฤษภาคม", title_en: "May", value: "05" },
    { title: "มิถุนายน", title_en: "June", value: "06" },
    { title: "กรกฎาคม", title_en: "July", value: "07" },
    { title: "สิงหาคม", title_en: "August", value: "08" },
    { title: "กันยายน", title_en: "September", value: "09" },
    { title: "ตุลาคม", title_en: "October", value: "10" },
    { title: "ฑฤศจิกายน", title_en: "November", value: "11" },
    { title: "ธันวาคม", title_en: "December", value: "12" },
  ],
});

let year = dayjs().year();
// console.log(year-1);

for (let i = 0; i <= 9; i++) {
  selectOptions.value.years.push({
    title: year - i + 543,
    title_en: year - i,
    value: year - i,
  });
}

console.log(selectOptions.value.years);

onMounted(() => {
  window.scrollTo(0, 0);
});
// 👉 Fetching News
const fetchItems = () => {
  isOverlay.value = true;
  let search = {
    ...advancedSearch,
    created_at:
      advancedSearch.created_at != ""
        ? dayjs(advancedSearch.created_at).format("YYYY-MM-DD")
        : undefined,
  };

  newsStore
    .fetchNewses({
      perPage: rowPerPage.value,
      currentPage: currentPage.value,
      ...search,
      lang: lang.value,
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
            title_en: d.title_en,
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
fetchNewsTypes();

watchEffect(fetchItems);

// 👉 watching current page
watchEffect(() => {
  if (currentPage.value > totalPage.value) currentPage.value = totalPage.value;
});

const format = (date) => {
  const day = dayjs(date).locale("th").format("DD");
  const month = dayjs(date).locale("th").format("MMM");
  const year = date.getFullYear() + 543;

  return `${day} ${month} ${year}`;
};
</script>

<template>
  <main class="layout-page-content mb-6">
    <section>
      <!-- Table -->

      <VRow>
        <VCol cols="12">
          <VCard title="" class="pb-5 pt-5">
            <VCardText class="d-flex flex-wrap py-4 gap-4">
              <!--  -->
              <VContainer>
                <VRow class="mb-6 ml-3">
                  <VCol cols="12" sm="3">
                    <VSelect
                      id="news_type_id"
                      v-model="advancedSearch.news_type_id"
                      label="ประเภทข่าว/News Type"
                      :items="selectOptions.news_types"
                      :item-title="lang == 'th' ? 'title' : 'title_en'"
                      clearable="true"
                    />
                  </VCol>

                  <VCol cols="12" sm="2">
                    <VSelect
                      clearable="true"
                      id="year"
                      v-model="advancedSearch.year"
                      label="ปี/Year"
                      :items="selectOptions.years"
                      :item-title="lang == 'th' ? 'title' : 'title_en'"
                    />
                  </VCol>

                  <VCol cols="12" sm="2">
                    <VSelect
                      clearable="true"
                      id="month"
                      v-model="advancedSearch.month"
                      label="เดือน/Month"
                      :items="selectOptions.months"
                      :item-title="lang == 'th' ? 'title' : 'title_en'"
                    />
                  </VCol>

                  <VCol cols="12" sm="5">
                    <VTextField
                      v-model="advancedSearch.title"
                      label="หัวข่อข่าว/Title"
                      density="compact"
                    />
                  </VCol>
                </VRow>
                <!-- Table -->

                <!-- <VCardText class="d-flex"> -->

                <VRow v-for="it in items" :key="it.id" class="ml-3">
                  <VCol
                    cols="12"
                    sm="2"
                    @click="
                      $router.push({ name: 'news-id', params: { id: it.id } })
                    "
                    style="cursor: pointer"
                  >
                    <VImg
                      :src="lang == 'th' ? it.news_file : it.news_en_file"
                      width="200"
                      class="ma-1"
                    />
                  </VCol>
                  <VCol
                    cols="12"
                    sm="10"
                    @click="
                      $router.push({ name: 'news-id', params: { id: it.id } })
                    "
                    style="cursor: pointer"
                  >
                    <div class="pt-2" style="min-height: 60px">
                      <span
                        class="font-weight-bold"
                        style="font-size: 1.2rem;"
                      >
                        {{ lang == "th" ? it.title : it.title_en }}
                      </span>
                    </div>
                    <div class="mt-5" style="font-size: 0.8em">
                      <span>
                        <VIcon icon="tabler-calendar" />
                        {{
                          dayjs(it.created_at).locale("en").format("DD/MM/BB")
                        }}
                      </span>

                      <span class="ml-5"
                        ><VIcon icon="tabler-tag" />
                        {{
                          lang == "th"
                            ? it.news_type_title
                            : it.news_type_title_en
                        }}
                      </span>
                    </div>
                  </VCol>
                  <VCol cols="12" sm="12">
                    <hr />
                  </VCol>
                </VRow>
                <!-- </VCardText> -->

                <VRow class="ml-3">
                  <VCol>
                    <span class="text-sm text-disabled">
                      Showing {{ currentPage }} to {{ totalPage }} of
                      {{ totalItems }} entries
                    </span>
                  </VCol>
                </VRow>

                <VPagination
                  v-model="currentPage"
                  size="small"
                  :total-visible="5"
                  :length="totalPage"
                />
              </VContainer>
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
