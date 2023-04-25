<script setup>
import { requiredValidator } from "@validators";

import router from "../../../router";
import { useNewsStore } from "./useNewsStore";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
// const route = useRoute();

dayjs.extend(buddhistEra);

const newsStore = useNewsStore();

const item = reactive({
  id: null,
  title: "",
  title_en: "",
  news_type_id: "",
  news_file: "",
  news_en_file: "",
  detail: "",
  detail_en: "",
  is_publish: 1,
});
const isOverlay = ref(false);
const isFormValid = ref(false);
const refForm = ref();

const selectOptions = ref({
  news_types: [],
});

newsStore
  .fetchNewsTypes({
    is_publish: 1,
  })
  .then((response) => {
    if (response.data.message == "success") {
      selectOptions.value.news_types = response.data.data;
      isOverlay.value = false;
    } else {
      console.log("error");
    }
  })
  .catch((error) => {
    console.error(error);
    isOverlay.value = false;
  });

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
        console.log(selectOptions.value.news_type);
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

const onSubmit = () => {
  isOverlay.value = true;
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      newsStore
        .addNews({
          ...item,
          news_file: item.news_file != null ? item.news_file[0] : null,
          news_en_file:
            item.banner_en_file != null ? item.news_en_file[0] : null,
        })
        .then((response) => {
          if (response.data.message == "success") {
            localStorage.setItem("added", 1);
            nextTick(() => {
              // refForm.value?.reset();
              // refForm.value?.resetValidation();
              router.push({
                path: "/admin/news/view/" + response.data.data.id,
              });
            });
          } else {
            isOverlay.value = false;
            console.log("error");
          }
        })
        .catch((error) => {
          console.error(error);
          //   isOverlay.value = false;
        });
    }
    isOverlay.value = false;
  });
};

const format = (date) => {
  const day = dayjs(date).locale("th").format("DD");
  const month = dayjs(date).locale("th").format("MMM");
  const year = date.getFullYear() + 543;

  return `${day} ${month} ${year}`;
};
</script>

<template>
  <main class="layout-page-content mb-6 mt-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol>
          <h2>ADD News</h2>
          <hr />
        </VCol>
      </VRow>
      <VRow class="mt-6">
        <VCol>
          <VCard title="" class="pb-5 pt-5">
            <VCardText>
              <VForm
                ref="refForm"
                v-model="isFormValid"
                @submit.prevent="onSubmit"
              >
                <VRow>
                  <VCol cols="12">
                    <h3>THA</h3>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="news_type_id">ประเภทข่าว</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VSelect
                          id="news_type_id"
                          v-model="item.news_type_id"
                          label="News Type"
                          :items="selectOptions.news_types"
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="title">หัวข้อข่าว</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="title"
                          :rules="[requiredValidator]"
                          v-model="item.title"
                          placeholder="Title"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="news_file">รูปปกข่าว</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VFileInput
                          label="Upload Picture"
                          :rules="[requiredValidator]"
                          id="news_file"
                          v-model="item.news_file"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="detail">เนื้อหาข่าว</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="detail"
                          v-model="item.detail"
                          placeholder="Detail"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <!-- 👉 Mobile -->
                      <VCol cols="12" md="3">
                        <label for="is_publish">สถานะ</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VSelect
                          id="is_publish"
                          v-model="item.is_publish"
                          label="Pubish"
                          :items="[
                            { title: 'Publish', value: 1 },
                            { title: 'UnPublish', value: 0 },
                          ]"
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <!-- 👉 Mobile -->
                      <VCol cols="12" md="3">
                        <label for="is_publish">วันที่ลงข่าว</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VueDatePicker
                          v-model="item.created_at"
                          :enable-time-picker="false"
                          locale="th"
                          auto-apply
                          :format="format"
                        >
                          <template #year-overlay-value="{ text }">
                            {{ parseInt(text) + 543 }}
                          </template>
                          <template #year="{ year }">
                            {{ year + 543 }}
                          </template>
                        </VueDatePicker>
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <hr />
                  </VCol>

                  <VCol cols="12">
                    <h3>ENG</h3>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="link_url_en">ไฟล์ Banner (1280*768)</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VFileInput
                          label="Upload Banner"
                          id="banner_en_file"
                          v-model="item.banner_en_file"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="title_en">ชื่อ Banner</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="title_en"
                          v-model="item.title_en"
                          placeholder="Title"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="link_url_en">ลิงค์</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="link_url_en"
                          v-model="item.link_url_en"
                          placeholder="Link"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <!-- 👉 submit and reset button -->
                  <VCol offset-md="3" cols="12" md="9" class="d-flex gap-4">
                    <VBtn type="submit"> Submit </VBtn>
                    <!-- <VBtn color="secondary" variant="tonal" type="reset">
                      Reset
                    </VBtn> -->
                  </VCol>
                </VRow>
              </VForm>
            </VCardText>
            <VOverlay
              v-model="isOverlay"
              contained
              persistent
              class="align-center justify-center"
            >
              <VProgressCircular indeterminate />
            </VOverlay>
          </VCard>
        </VCol>
      </VRow>
    </section>
  </main>
</template>

<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
