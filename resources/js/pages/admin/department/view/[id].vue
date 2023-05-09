<script setup>
import { useRoute, useRouter } from "vue-router";
import { useDepartmentStore } from "../useDepartmentStore";

const route = useRoute();
const router = useRouter();

const departmentStore = useDepartmentStore();

const item = ref({
  id: null,
  title: "",
  title_en: "",
  is_publish: 1,
});

const isOverlay = ref(false);
const isSnackbarVisible = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");
const isDialogVisible = ref(false);

if (localStorage.getItem("added") == 1) {
  snackbarText.value = "Added Department";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("added");
}

if (localStorage.getItem("updated") == 1) {
  snackbarText.value = "Updated Department";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("updated");
}

departmentStore
  .fetchDepartment({
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
  departmentStore
    .deleteDepartment({
      id: id,
    })
    .then((response) => {
      if (response.data.message == "success") {
        localStorage.setItem("deleted", 1);
        router.push({
          path: "/admin/department",
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
          <h2>View Department</h2>
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
                  <span class="font-weight-bold mw-120">ฝ่ายงาน : </span>
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
                  <span class="font-weight-bold mw-120">สถานะ : </span>
                </VCol>
                <VCol>
                  <span class="font-italic">
                    <VChip color="success" v-if="item.is_publish == 1">
                      Publish
                    </VChip>
                    <VChip color="default" v-else> Unpublish </VChip>
                  </span>
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
                  <span class="font-weight-bold mw-120">ฝ่ายงาน : </span>
                </VCol>
                <VCol>
                  <span class="font-italic">{{ item.title_en }}</span>
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
                    name: 'admin-department-edit-id',
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
