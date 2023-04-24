<script setup>
import { requiredValidator } from "@validators";

import router from "../../../../router";
import { useBannerStore } from "../useBannerStore";
// const route = useRoute();
const route = useRoute();
const bannerStore = useBannerStore();

const item = ref({
  id: null,
  title: "",
  title_en: "",
  link_url: "",
  link_url_en: "",
  is_publish: 1,
  banner_file: [],
  banner_file_old: "",
  banner_en_file: [],
  banner_en_file_old: "",
});
const isOverlay = ref(false);
const isFormValid = ref(false);
const refForm = ref();

bannerStore
  .fetchBanner({
    id: route.params.id,
  })
  .then((response) => {
    if (response.data.message == "success") {
      const { data } = response.data;
      item.value = { ...data };

      item.value.banner_file_old = null;
      if (data.banner_file != null) {
        item.value.banner_file_old = data.banner_file;
      }
      item.value.banner_file = [];
      console.log(item.value);

      item.value.banner_en_file_old = null;
      if (data.banner_en_file != null) {
        item.value.banner_en_file_old = data.banner_en_file;
      }
      item.value.banner_en_file = [];

      console.log(item.value);
    } else {
      console.log("error");
    }
  })
  .catch((error) => {
    console.error(error);
    isOverLay.value = false;
  });

const onSubmit = () => {
  isOverlay.value = true;
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      bannerStore
        .editBanner({
          ...item.value,
          banner_file:
            item.value.banner_file.length !== 0
              ? item.value.banner_file[0]
              : null,
          banner_en_file:
            item.value.banner_en_file.length !== 0
              ? item.value.banner_en_file[0]
              : null,
        })
        .then((response) => {
          if (response.data.message == "success") {
            localStorage.setItem("updated", 1);
            nextTick(() => {
              // refForm.value?.reset();
              // refForm.value?.resetValidation();
              router.push({
                path: "/admin/banner/view/" + response.data.data.id,
              });
            });
          } else {
            isOverlay.value = false;
            console.log("error");
          }
        })
        .catch((error) => {
          console.error(error);
          //   isOverLay.value = false;
        });
    }
    isOverlay.value = false;
  });
};
</script>

<template>
  <main class="layout-page-content mb-6 mt-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol>
          <h2>Edit Banner</h2>
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
                        <label for="link_url">ไฟล์ Banner (1920*370)</label>
                      </VCol>

                      <VCol cols="12" md="7">
                        <VFileInput
                          label="Upload Banner"
                          id="banner_file"
                          v-model="item.banner_file"
                          persistent-placeholder
                          suffix="@gmail.com"
                        />
                      </VCol>

                      <VCol cols="12" md="2" class="pl-2">
                        <a :href="item.banner_file_old" target="_blank">
                          <VBtn style="width: 100%"> View Old File </VBtn></a
                        >
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="title">ชื่อ Banner</label>
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
                        <label for="link_url">ลิงค์</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="link_url"
                          v-model="item.link_url"
                          placeholder="Link"
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
                    <hr />
                  </VCol>

                  <VCol cols="12">
                    <h3>ENG</h3>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="banner_en_file">ไฟล์ Banner (1920*370)</label>
                      </VCol>

                      <VCol cols="12" md="7">
                        <VFileInput
                          label="Upload Banner"
                          id="banner_en_file"
                          v-model="item.banner_en_file"
                          persistent-placeholder
                        />
                      </VCol>

                      <VCol cols="12" md="2" class="pl-2">
                        <a
                          :href="
                            item.banner_en_file_old != null
                              ? item.banner_en_file_old
                              : '/'
                          "
                          target="_blank"
                        >
                          <VBtn style="width: 100%"> View Old File </VBtn></a
                        >
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
