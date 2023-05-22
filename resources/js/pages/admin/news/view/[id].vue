<script setup>
import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";
import { useRoute, useRouter } from "vue-router";

import "swiper/css";
import "swiper/css/free-mode";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";
import "swiper/css/thumbs";

import { FreeMode, Navigation, Pagination, Scrollbar, Thumbs } from "swiper";
import { Swiper, SwiperSlide } from "swiper/vue";
import { useNewsStore } from "../useNewsStore";
// End Import

const newsStore = useNewsStore();
dayjs.extend(buddhistEra);
const route = useRoute();
const router = useRouter();
const newsGalleries = ref([{}, {}, {}, {}]);
const modules = [Navigation, Pagination, Scrollbar, FreeMode, Thumbs];

const isOverlay = ref(false);
const isSnackbarVisible = ref(false);
const snackbarText = ref("");
const snackbarColor = ref("success");
const isDialogVisible = ref(false);
const thumbsSwiper = ref(null);

onMounted(() => {
  window.scrollTo(0, 0);
});

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
  min-width: 120px;
}

.swiper-button-prev:after,
.swiper-rtl .swiper-button-next:after {
  color: #000;
  font-size: 1.5em;
  margin-left: -3px;
  font-weight: 800;
}

.swiper-button-next:after,
.swiper-rtl .swiper-button-next:after {
  color: #000;
  font-size: 1.5em;
  margin-left: 3px;
  font-weight: 800;
}

.swiper-button-next {
  border-radius: 100% !important;
  width: 2.8em;
  background: #ffcb05;
  transform: scale(0.7);
  @media only screen and (max-width: 600px) {
    transform: scale(0.4);
  }
}

.swiper-button-prev {
  border-radius: 100% !important;
  width: 2.8em;
  margin-left: -2px;
  background: #ffcb05;
  transform: scale(0.7);
  @media only screen and (max-width: 600px) {
    transform: scale(0.4);
  }
}
.swiper-button-next,
.swiper-rtl .swiper-button-prev {
  right: var(--swiper-navigation-sides-offset, 0px);
}

.swiper-button-prev,
.swiper-rtl .swiper-button-next {
  left: var(--swiper-navigation-sides-offset, 0px);
}

.swiper-pagination-bullet-active {
  background: #ffcb05;
}

// thumb
.mySwiper .swiper-slide {
  width: 25%;
  height: 100%;
  opacity: 0.4;
}

.mySwiper .swiper-slide-thumb-active {
  opacity: 1;
}

</style>

<template>
  <main class="layout-page-content mb-6 mt-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol>
          <h2>View News</h2>
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
                  <span class="font-weight-bold mw-120">หัวข้อข่าว : </span>
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
                  <span class="font-weight-bold mw-120">ประเภทข่าว :</span>
                </VCol>
                <VCol>
                  <span class="font-italic">{{ item.news_type_title }}</span>
                </VCol>
                <VCol cols="12">
                  <hr />
                </VCol>
              </VRow>

              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                  <span class="font-weight-bold mw-120 d-inline"
                    >รูปภาพปก :
                  </span>
                </VCol>
                <VCol>
                  <div class="justify-center d-inline">
                    <a :href="item.news_file" target="_blank">
                      <VImg
                        :src="item.news_file"
                        :width="500"
                        aspect-ratio="16/9"
                        class="mt-2"
                      />
                    </a>
                  </div>
                </VCol>

                <VCol cols="12">
                  <hr />
                </VCol>
              </VRow>

              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                  <span class="font-weight-bold mw-120 d-inline"
                    >แกลลอรี่ :
                  </span>
                </VCol>
                <VCol>
                  <!-- swiper -->
                  <div style="background-color: #eee; padding: 2em">
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
                      <swiper-slide
                        v-for="(ng, index) in newsGalleries"
                        :key="index"
                      >
                        <img :src="ng.news_gallery_file" style="width: 100%" />
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
                      <swiper-slide
                        v-for="(ng, index) in newsGalleries"
                        :key="index"
                      >
                        <img :src="ng.news_gallery_file" style="width: 100%" />
                      </swiper-slide>
                    </swiper>
                  </div>
                </VCol>
                <VCol cols="12">
                  <hr />
                </VCol>
              </VRow>

              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                  <span class="font-weight-bold mw-120 d-inline"
                    >เนื้อหาข่าว :
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
                  <span class="font-weight-bold mw-120">วันที่ลงข่าว : </span>
                </VCol>
                <VCol>
                  {{ dayjs(item.created_at).locale("th").format("DD MMM BB") }}
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
                  <span class="font-weight-bold mw-120">หัวข้อข่าว : </span>
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
                    >รูปภาพปก :
                  </span>
                </VCol>
                <VCol>
                  <div class="justify-center d-inline">
                    <a :href="item.news_en_file" target="_blank">
                      <VImg
                        :src="item.news_en_file"
                        :width="500"
                        aspect-ratio="16/9"
                        class="mt-2"
                      />
                    </a>
                  </div>
                </VCol>

                <VCol cols="12">
                  <hr />
                </VCol>
              </VRow>

              <VRow class="ma-4 mb-1 mt-1">
                <VCol cols="12" md="2">
                  <span class="font-weight-bold mw-120 d-inline"
                    >เนื้อหาข่าว :
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
                    name: 'admin-news-edit-id',
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
