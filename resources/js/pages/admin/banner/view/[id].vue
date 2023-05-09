<script setup>
import { useRoute, useRouter } from 'vue-router';
import { useBannerStore } from "../useBannerStore";

const bannerStore = useBannerStore();
const route = useRoute();
const router = useRouter()

const item = ref({
  id: null,
  title: "",
  title_en: "",
  link_url: "",
  link_url_en: "",
  is_publish: 1,
  banner_file: [],
  banner_file_en: [],
});
const isOverlay = ref(false);
const isSnackbarVisible = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");
const isDialogVisible = ref(false);

if (localStorage.getItem("added") == 1) {
  snackbarText.value = "Added Banner";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("added");
}

if (localStorage.getItem("updated") == 1) {
  snackbarText.value = "Updated Banner";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("updated");
}

bannerStore
  .fetchBanner({
    id: route.params.id,
  })
  .then((response) => {
    if (response.data.message == "success") {
      item.value = response.data.data;
    } else {
      console.log("error");
    }
  })
  .catch((error) => {
    console.error(error);
    isOverlay.value = false;
  });

const onConfirmDelete = () => {
  isDialogVisible.value = true;
};

const onDelete = (id) => {
  bannerStore
    .deleteBanner({
      id: id,
    })
    .then((response) => {
      if (response.data.message == "success") {
        localStorage.setItem("deleted", 1);
        router.push({
          path: "/admin/banner",
        });
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
  window.scrollTo(0,0);
});
</script>

<template>
  <main class="layout-page-content mb-6 mt-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol>
          <h2>View Banner</h2>
          <hr />
        </VCol>
      </VRow>
      <VRow class="mt-6">
        <VCol>
          <VCard title="" class="pb-5 pt-5">
            <VCardText>
              <!-- <div class="mb-3 bg-primary" >
                <h3>ข้อมูลแบนเนอร์</h3>
              </div> -->

              <div class="ma-4">
                <h1>THA</h1>
              </div>
              <div class="ma-4">
                <span class="font-weight-bold">ชื่อแบนเนอร์ : </span>
                <span class="font-italic">{{ item.title }}</span>
              </div>
              <div class="ma-4">
                <hr />
              </div>
              <div class="ma-4">
                <span class="font-weight-bold">รูปภาพ : </span>
                <!-- <span class="font-italic"> -->
                <a :href="item.banner_file" target="_blank">
                  <VImg
                    :src="item.banner_file"
                    style="width: 100%"
                    class="mt-2"
                  />
                </a>
                <!-- </span> -->
              </div>
              <div class="ma-4">
                <hr />
              </div>
              <div class="ma-4">
                <span class="font-weight-bold">ลิงค์ : </span>
                <a :href="item.link_url" target="_blank">
                  <span class="font-italic">{{ item.link_url }}</span></a
                >
              </div>
              <div class="ma-4">
                <hr />
              </div>

              <div class="ma-4">
                <span class="font-weight-bold">ลำดับ : </span>

                <span class="font-italic">{{ item.level }}</span>
              </div>
              <div class="ma-4">
                <hr />
              </div>

              <div class="ma-4">
                <span class="font-weight-bold">สถานะ : </span>

                <span class="font-italic">
                  <VChip color="success" v-if="item.is_publish == 1">
                    Publish
                  </VChip>
                  <VChip color="default" v-else> Unpublish </VChip>
                </span>
              </div>
              <div class="ma-4">
                <hr />
              </div>

              <div class="ma-4 mt-10">
                <h1>ENG</h1>
              </div>

              <div class="ma-4">
                <span class="font-weight-bold">ชื่อแบนเนอร์ : </span>
                <span class="font-italic">{{ item.title_en }}</span>
              </div>
              <div class="ma-4">
                <hr />
              </div>
              <div class="ma-4">
                <span class="font-weight-bold">รูปภาพ : </span>
                <!-- <span class="font-italic"> -->
                  <a :href="item.banner_en_file" target="_blank">
                  <VImg
                    :src="item.banner_en_file"
                    style="width: 100%"
                    class="mt-2"
                  />
                </a>
                <!-- </span> -->
              </div>
              <div class="ma-4">
                <hr />
              </div>
              <div class="ma-4">
                <span class="font-weight-bold">ลิงค์ : </span>
                <a :href="item.link_url_en" target="_blank">
                  <span class="font-italic">{{ item.link_url_en }}</span></a
                >
              </div>
              <div class="ma-4">
                <hr />
              </div>

              <div class="ma-4 mt-10 div-action">
                <VBtn
                  prepend-icon="tabler-edit"
                  variant="outlined"
                  color="success"
                  :to="{
                    name: 'admin-banner-edit-id',
                    params: { id: route.params.id },
                  }"
                >
                  Edit
                </VBtn>

                <VBtn
                  prepend-icon="tabler-trash"
                  variant="outlined"
                  class="ml-2"
                  color="error"
                  @click="onConfirmDelete"
                >
                  Del
                </VBtn>
              </div>
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
          <VBtn @click="onDelete(route.params.id)" color="error"> Delete </VBtn>
        </VCardText>
      </VCard>
    </VDialog>
  </main>
</template>

<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
