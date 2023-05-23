<script setup>
import icon1 from "@images/icons/home/icon1.png";
import icon2 from "@images/icons/home/icon2.png";
import icon3 from "@images/icons/home/icon3.png";
import icon4 from "@images/icons/home/icon4.png";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";

import { Navigation, Pagination, Scrollbar } from "swiper";
import { Swiper, SwiperSlide } from "swiper/vue";
import { useDisplay } from "vuetify";
import { useHomeStore } from "./useHomeStore";
// End Import

const homeStore = useHomeStore();
const { t } = useI18n();
const { mobile } = useDisplay();
const modules = [Navigation, Pagination, Scrollbar];
const isOverlay = ref(true);
// 👉 Fetching Banner
const banners = ref([{}, {}]);
const news = ref([]);
const newsTypes = ref([]);
const currentTab = ref(0);
const lang = ref("th");

const cardIcon = [
  { title: "Sample Submission", icon: icon1 },
  { title: "Equipment & Rate", icon: icon2 },
  { title: "Direction", icon: icon3 },
  { title: "Department Facility", icon: icon4 },
];

onMounted(() => {});

if (localStorage.getItem("currentLang") === "en") {
  lang.value = localStorage.getItem("currentLang");
}

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
</script>

<style lang="scss">
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

.news-tag {
  color: #bbb;
  font-size: 0.7em;
}

.news-card {
  border: solid 1px #ddd;
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

.v-theme--light > .v-application__wrap > .layout-wrapper {
  background-color: #fff !important;
}
</style>
<!--  -->
<template>
  <div>
    <!-- tp-Banner -->
    <swiper
      :slides-per-view="1"
      :space-between="50"
      :loop="true"
      navigation
      :pagination="{ clickable: true }"
      :modules="modules"
      :autoplay="{
        delay: 5000,
        disableOnInteraction: true,
      }"
    >
      <swiper-slide v-for="bn in banners" :key="bn.id">
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
    </swiper>
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
                    @click="
                      $router.push({ name: 'news-id', params: { id: nw.id } })
                    "
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
                        {{
                          lang == "th"
                            ? nw.news_type_title
                            : nt.news_type_title_en
                        }}
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
