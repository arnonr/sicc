<script setup>
import { useRoute } from "vue-router";
import { useContactUsStore } from "../useContactUsStore";

const route = useRoute();

const contactUsStore = useContactUsStore();

const item = ref({
  id: null,
  title: "",
  title_en: "",
  detail: "",
  detail_en: "",
  is_publish: 1,
});

const isOverlay = ref(false);
const isSnackbarVisible = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");

if (localStorage.getItem("updated") == 1) {
  snackbarText.value = "Updated Contact Us";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("updated");
}

contactUsStore
  .fetchContactUs({
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


onMounted(() => {
  window.scrollTo(0, 0);
});
</script>
<style lang="scss">
.mw-120 {
  min-width: 120px;
}
</style>

<template>
  <main class="layout-page-content mb-6 mt-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol>
          <h2>View Contact Us</h2>
          <hr />
        </VCol>
      </VRow>
      <VRow class="mt-6">
        <VCol>
          <VCard title="" class="pb-5 pt-5">
            <VCardText>
              
              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                <h1>THA</h1>
                </VCol>
              </VRow>

              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                  <span class="font-weight-bold mw-120">หัวข้อ : </span>
                </VCol>
                <VCol>
                  <span class="font-italic">{{ item.title }}</span>
                </VCol>
                <VCol cols="12">
                  <hr />
                </VCol>
              </VRow>
              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                  <span class="font-weight-bold mw-120 d-inline"
                    >รายละเอียด :
                  </span>
                </VCol>
                <VCol>
                  <div v-html="item.detail" class="d-inline"></div>
                </VCol>
                <VCol cols="12">
                  <hr />
                </VCol>
              </VRow>
              
              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                <h1>ENG</h1>
                </VCol>
              </VRow>

              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                  <span class="font-weight-bold mw-120">หัวข้อ : </span>
                </VCol>
                <VCol>
                  <span class="font-italic">{{ item.title_en }}</span>
                </VCol>
                <VCol cols="12">
                  <hr />
                </VCol>
              </VRow>

              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                  <span class="font-weight-bold mw-120 d-inline"
                    >รายละเอียด :
                  </span>
                </VCol>
                <VCol>
                  <div v-html="item.detail_en"></div>
                </VCol>
                <VCol cols="12">
                  <hr />
                </VCol>
              </VRow>

              <div class="ma-4 mt-10 div-action">
                <VBtn
                  prepend-icon="tabler-edit"
                  variant="outlined"
                  color="success"
                  :to="{
                    name: 'admin-contactUs-edit-id',
                    params: { id: route.params.id },
                  }"
                >
                  Edit
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
  </main>
</template>

<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
