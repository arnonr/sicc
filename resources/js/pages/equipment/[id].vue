<script setup>
import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
import { useRoute, useRouter } from "vue-router";

import { FreeMode, Navigation, Pagination, Scrollbar, Thumbs } from "swiper";
import "swiper/css";
import "swiper/css/free-mode";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";
import "swiper/css/thumbs";
import { Swiper, SwiperSlide } from "swiper/vue";

import { useDisplay } from "vuetify";
import { useNewsStore } from "./useNewsStore";
// End Import

dayjs.extend(buddhistEra);
const newsStore = useNewsStore();
const { t } = useI18n();
const { mobile } = useDisplay();
const route = useRoute();
const router = useRouter();
const newsGalleries = ref([{}, {}, {}, {}]);
const modules = [Navigation, Pagination, Scrollbar, FreeMode, Thumbs];
const lang = ref("th");

const isOverlay = ref(false);
const isSnackbarVisible = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");
const isDialogVisible = ref(false);
const thumbsSwiper = ref(null);

onMounted(() => {
  window.scrollTo(0, 0);
});

if (localStorage.getItem("currentLang") === "en") {
  lang.value = localStorage.getItem("currentLang");
}

const setThumbsSwiper = (swiper) => {
  thumbsSwiper.value = swiper;
};

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

      item.value = {
        title: "กล้องจุลทรรศน์อิเล็กตรอนแบบส่องกราด : FEI รุ่น QUANTA 450",
        title_en: "กล้องจุลทรรศน์อิเล็กตรอนแบบส่องกราด : FEI รุ่น QUANTA 450",
        detail: 'กล่องจุลทรรศน์.............'
      };
    } else {
      console.log("error");
    }
  })
  .catch((error) => {
    console.error(error);
    isOverlay.value = false;
  });

const fetchNewsGallery = () => {
  newsStore
    .fetchNewsGallery({
      news_id: route.params.id,
    })
    .then(async (response) => {
      if (response.data.message == "success") {
        newsGalleries.value = response.data.data;
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
fetchNewsGallery();
</script>
<style lang="scss">
.mw-120 {
  min-inline-size: 120px;
}

.swiper-button-prev::after,
.swiper-rtl .swiper-button-next::after {
  color: #000;
  font-size: 1.5em;
  font-weight: 800;
  margin-inline-start: -3px;
}

.swiper-button-next::after,
.swiper-rtl .swiper-button-next::after {
  color: #000;
  font-size: 1.5em;
  font-weight: 800;
  margin-inline-start: 3px;
}

.swiper-button-next {
  border-radius: 100% !important;
  background: #ffcb05;
  inline-size: 2.8em;
  transform: scale(0.7);

  @media only screen and (max-width: 600px) {
    transform: scale(0.4);
  }
}

.swiper-button-prev {
  border-radius: 100% !important;
  background: #ffcb05;
  inline-size: 2.8em;
  margin-inline-start: -2px;
  transform: scale(0.7);

  @media only screen and (max-width: 600px) {
    transform: scale(0.4);
  }
}

.swiper-button-next,
.swiper-rtl .swiper-button-prev {
  inset-inline-end: var(--swiper-navigation-sides-offset, 0);
}

.swiper-button-prev,
.swiper-rtl .swiper-button-next {
  inset-inline-start: var(--swiper-navigation-sides-offset, 0);
}

.swiper-pagination-bullet-active {
  background: #ffcb05;
}

// thumb
.mySwiper .swiper-slide {
  block-size: 100%;

  // opacity: 0.4;
  cursor: pointer;
  inline-size: 25%;
}

.mySwiper .swiper-slide-thumb-active {
  opacity: 1;
}

.v-theme--light > .v-application__wrap > .layout-wrapper {
  background-color: #fff !important;
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
            <div :style="mobile ? 'padding: 2em;' : 'padding: 2em;width:80%;'">
              <!-- tp-Banner -->
              <swiper
                :slidesPerView="1"
                :spaceBetween="10"
                :loop="true"
                :thumbs="{ swiper: thumbsSwiper }"
                :navigation="true"
                :modules="modules"
                :autoplay="{
                  delay: 5000,
                  disableOnInteraction: true,
                }"
                class="mySwiper2"
              >
                <swiper-slide v-for="(ng, index) in newsGalleries" :key="index">
                  <img :src="ng.news_gallery_file" style="width: 100%;" />
                </swiper-slide>
              </swiper>

              <swiper
                @swiper="setThumbsSwiper"
                :spaceBetween="10"
                :slidesPerView="4"
                :freeMode="true"
                :watchSlidesProgress="true"
                :modules="modules"
                class="mySwiper"
              >
                <swiper-slide v-for="(ng, index) in newsGalleries" :key="index">
                  <img :src="ng.news_gallery_file" style="width: 100%;" />
                </swiper-slide>
              </swiper>
            </div>
          </div>
          <div
            v-html="lang == 'th' ? item.detail : item.detail_en"
            :style="
              mobile
                ? 'text-align: justify;'
                : 'padding-left:10em;padding-right:10em;font-size:1.1em;'
            "
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
