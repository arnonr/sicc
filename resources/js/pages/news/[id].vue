<script setup>
import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
import { useRoute, useRouter } from "vue-router";
import { useDisplay } from "vuetify";
import { useNewsStore } from "./useNewsStore";
const { mobile } = useDisplay();

const route = useRoute();
const router = useRouter();
dayjs.extend(buddhistEra);
const { t } = useI18n();
const lang = ref("th");
if (localStorage.getItem("currentLang") === "en") {
  lang.value = localStorage.getItem("currentLang");
}

const newsStore = useNewsStore();

const item = ref({
  id: null,
  title: "",
  title_en: "",
  link_url: "",
  link_url_en: "",
  is_publish: 1,
  news_file: [],
  news_file_en: [],
});
const isOverlay = ref(false);
const isSnackbarVisible = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");
const isDialogVisible = ref(false);

if (localStorage.getItem("added") == 1) {
  snackbarText.value = "Added News";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("added");
}

if (localStorage.getItem("updated") == 1) {
  snackbarText.value = "Updated News";
  snackbarColor.value = "success";
  isSnackbarVisible.value = true;
  localStorage.removeItem("updated");
}

newsStore
  .fetchNews({
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
  newsStore
    .deleteNews({
      id: id,
    })
    .then((response) => {
      if (response.data.message == "success") {
        localStorage.setItem("deleted", 1);
        router.push({
          path: "/admin/news",
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
          <h2>{{ lang == "th" ? item.title : item.title_en }}</h2>
          <hr class="mb-6 mt-6" />
          <div class="mb-10">
            <span>
              <VIcon icon="tabler-calendar-check" />
              {{
                lang == "th"
                  ? dayjs(item.created_at).locale("th").format("DD MMM BB")
                  : dayjs(item.created_at).format("DD/MM/BB")
              }}
            </span>
            <span class="ml-10">
              <VIcon icon="tabler-eye-check" />
              {{ 200 }} {{ t("Views") }}
            </span>
          </div>
          <div class="mb-6" align="center">
            <VImg
              align="center"
              :src="lang == 'th' ? item.news_file : item.news_en_file"
              width="800"
            />
          </div>
          <div
            v-html="lang == 'th' ? item.detail : item.detail_en"
            :style="mobile ? '' : 'padding-left:10em;padding-right:10em;'"
          ></div>
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
