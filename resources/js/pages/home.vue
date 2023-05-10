<script setup>
import icon1 from "@images/icons/home/icon1.png";
import icon2 from "@images/icons/home/icon2.png";
import icon3 from "@images/icons/home/icon3.png";
import icon4 from "@images/icons/home/icon4.png";

import { register } from "swiper/element/bundle";
// import Swiper, { Navigation, Pagination } from 'swiper';
// import "swiper/css";
// import "swiper/css/navigation";
// import "swiper/css/pagination";

import { useDisplay } from "vuetify";
import { useHomeStore } from "./useHomeStore";
// import { defineComponent } from "vue";
const homeStore = useHomeStore();
const { t } = useI18n();

const { mobile } = useDisplay();
const isOverlay = ref(true);

register();

onMounted(() => {
  // const swiperEl = document.querySelector("swiper-container");
  // // swiper parameters
  // const swiperParams = {
  //   slidesPerView: 1,
  //   breakpoints: {
  //     640: {
  //       slidesPerView: 2,
  //     },
  //     1024: {
  //       slidesPerView: 1,
  //     },
  //   },
  //   on: {
  //     init() {
  //       // ...
  //     },
  //   },
  // };
  // // now we need to assign all parameters to Swiper element
  // Object.assign(swiperEl, swiperParams);
  // // and now initialize it
  // swiperEl.initialize();
});

const cardIcon = [
  { title: "Sample Submission", icon: icon1 },
  { title: "Equipment & Rate", icon: icon2 },
  { title: "Direction", icon: icon3 },
  { title: "Department Facility", icon: icon4 },
];

// 👉 Fetching Banner
const banners = ref([]);
const news = ref([]);
const newsTypes = ref([]);
const fetchBanners = () => {
  isOverlay.value = true;
  homeStore
    .fetchBanners({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        banners.value = response.data.data;
        isOverlay.value = false;
        // options.value.type = "loop";
      } else {
        console.log("error");
      }
    })
    .catch((error) => {
      console.error(error);
      isOverlay.value = false;
    });
};
fetchBanners();

const fetchNewsTypes = () => {
  isOverlay.value = true;
  homeStore
    .fetchNewsTypes({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        newsTypes.value = response.data.data;

        newsTypes.value.unshift({
          id: 99,
          title: "ข่าวทั้งหมด",
          title_en: "All News",
        });
        fetchNews();
        isOverlay.value = false;
        // options.value.type = "loop";
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

const fetchNews = () => {
  isOverlay.value = true;
  homeStore
    .fetchNews({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        news.value = response.data.data;
        newsTypes.value = newsTypes.value.map((it) => {
          if (it.id == 99) {
            it.news = news.value.filter((x) => {
              let isEng = true;
              if (lang.value != "th") {
                if (x.title_en == null || x.title_en == "") {
                  isEng = false;
                }
              }
              return isEng;
            });

            it.news = it.news.slice(0, 8);
          } else {
            it.news = news.value.filter((x) => {
              let isEng = true;
              if (lang.value != "th") {
                if (x.title_en == null || x.title_en == "") {
                  isEng = false;
                }
              }
              return x.news_type_id == it.id && isEng;
            });

            it.news = it.news.slice(0, 8);
          }
          return it;
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

const currentTab = ref(0);

const lang = ref("th");
if (localStorage.getItem("currentLang") === "en") {
  lang.value = localStorage.getItem("currentLang");
}

// const swiper = new Swiper(".sicc-swiper", {
//   speed: 400,
//   spaceBetween: 100,
//   modules: [Navigation, Pagination],
// });

const strippedTag = (string) => {
  return string.replace(/<\/?[^>]+>/gi, " ");
};
</script>
<style lang="scss">
button.splide__pagination__page.is-active {
  background-color: #ffcb05;
  border: solid 1px #fff;
}

// .row-icon-home {
//   background-color: #eee;
// }

.card-icon-home {
  background-color: #cccccc;
  border-radius: 0px;
  padding-top: 2em;
  padding-bottom: 0px;
}

.card-text-icon-home {
  color: #999;
  font-size: 1rem;
  font-weight: 500;
}

.card-icon-home:hover {
  background-color: #ffcb05;
  .card-text-icon-home {
    color: #000;
  }
  .img-icon-home {
    filter: none;
  }
}

.img-icon-home {
  filter: invert(5%) sepia(40%) saturate(8%) hue-rotate(48deg) brightness(90%)
    contrast(99%);
}

// .v-card--variant-elevated {
//     box-shadow: none;
// }

.news-tag {
  color: #bbb;
  font-size: 0.7em;
}

.news-card {
  border: solid 1px #ddd;
}

.swiper-button-next:after,
.swiper-button-prev:after {
  font-size: 10px !important;
  font-weight: 700px;
}
</style>
<template>
  <div>
    <!-- tp-Banner -->
    <swiper-container
      slides-per-view="1"
      speed="500"
      loop="true"
      css-mode="true"
      :navigation="'true'"
      pagination="true"
      scrollbar="true"
    >
      <swiper-slide v-for="bn in banners">
        <a
          :href="
            lang == 'th'
              ? bn.link_url != 'null'
                ? bn.link_url
                : '#'
              : bn.link_url_en != 'null'
              ? bn.link_url_en
              : '#'
          "
          :alt="lang == 'th' ? bn.title : bn.title_en"
          :title="lang == 'th' ? bn.title : bn.title_en"
        >
          <img
            :src="lang == 'th' ? bn.banner_file : bn.banner_en_file"
            :alt="lang == 'th' ? bn.banner_title : bn.banner_en_title"
            style="width: 100%"
          />
        </a>
      </swiper-slide>
    </swiper-container>

    <!-- End tp-Banner -->

    <!-- Icon -->
    <VRow
      :class="
        mobile
          ? 'pt-10 pb-8'
          : 'pt-10 pb-8 row-icon-home bg-top-footer-background'
      "
      justify="center"
    >
      <VCol cols="12" sm="6" md="2" class="p-0" v-for="ci in cardIcon">
        <VCard class="text-center card-icon-home cursor-pointer">
          <!-- <VImg :src="pages1" cover /> -->
          <VAvatar size="150">
            <!-- <span class="text-h5 text-white">PI</span> -->
            <VImg :src="ci.icon" class="img-icon-home" />
          </VAvatar>

          <VCardItem class="text-center p-0">
            <!-- <VCardTitle class="card-text-icon-home">{{ ci.title }}</VCardTitle> -->
            <span class="card-text-icon-home">{{ ci.title }}</span>
          </VCardItem>
        </VCard>
      </VCol>
    </VRow>

    <!-- End Icon -->

    <!-- Main -->
    <main class="layout-page-content mt-6 mb-6">
      <v-row>
        <v-col>
          <h2>{{ t("News") }}</h2>
        </v-col>
      </v-row>
      <VRow>
        <VCol cols="12" sm="12" md="12">
          <!-- <VCard> -->
          <!--  -->
          <VTabs v-model="currentTab">
            <VTab v-for="nt in newsTypes">{{
              lang == "th" ? nt.title : nt.title_en
            }}</VTab>
          </VTabs>
          <VDivider />

          <VWindow v-model="currentTab" class="mt-5 mb-5">
            <VWindowItem v-for="nt in newsTypes" :key="nt.id">
              <!-- {{ tabItemContent }} -->
              <!-- News -->
              <VRow>
                <VCol
                  cols="12"
                  sm="6"
                  md="3"
                  v-for="nw in nt.news"
                  :key="nw.id"
                >
                  <VCard
                    @click="$router.push({ name: 'news-id',params: {id: nw.id} })"
                    class="news-card cursor-pointer"
                  >
                    <VImg
                      :src="lang == 'th' ? nw.news_file : nw.news_en_file"
                      cover
                    />

                    <VCardText style="min-height: 200px !important">
                      <h2 style="font-size: 1.25rem; font-weight: 500">
                        {{ lang == "th" ? nw.title : nw.title_en }}
                      </h2>
                    </VCardText>
                    <VCardActions>
                      <VBtn
                        @click="isCardDetailsVisible = !isCardDetailsVisible"
                      >
                        {{ t("Details") }} <VIcon icon="tabler-arrow-right" />
                      </VBtn>
                      <VSpacer />
                      <span class="news-tag"
                        ><VIcon icon="tabler-tag" />
                        {{ lang == "th" ? nw.news_type_title : nt.news_type_title_en }}
                      </span>
                    </VCardActions>
                  </VCard>
                </VCol>
              </VRow>
            </VWindowItem>
          </VWindow>
          <!-- </VCard> -->
        </VCol>
      </VRow>

      <v-row>
        <v-col>
          <VBtn
            color="primary"
            class="float-right"
            variant="flat"
            @click="$router.push({ name: 'news' })"
          >
            <VIcon start icon="tabler-news" /> {{ t("All News") }}
          </VBtn>
        </v-col>
      </v-row>
    </main>
  </div>
</template>

<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
