<script setup>
import icon1 from "@images/icons/home/icon_new1.png";
import icon2 from "@images/icons/home/icon_new2.png";
import icon3 from "@images/icons/home/icon_new3.png";
import icon4 from "@images/icons/home/icon_new4.png";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";

import { Navigation, Pagination, Scrollbar } from "swiper";
import { Swiper, SwiperSlide } from "swiper/vue";
import { useDisplay } from "vuetify";
import { useHomeStore } from "../useHomeStore";
// End Import

const homeStore = useHomeStore();
const { t } = useI18n();
const { mobile } = useDisplay();
const modules = [Navigation, Pagination, Scrollbar];
const isOverlay = ref(true);
// 👉 Fetching Banner
const banners = ref([{}, {}]);
const news = ref([
  {
    id: 1,
    title: "กล้องจุลทรรศน์อิเล็กตรอนแบบส่องกราด : FEI รุ่น QUANTA 450",
    news_file: 'http://localhost:8115/storage/equipment/1.png',
    news_file: 'http://localhost:8115/storage/equipment/1.png',
    title_en: "Scanning Electron Microscope",
    news_type_id: 1
  },
]);
const newsTypes = ref([
  {
    id: 1,
    title: "Scanning Instrument",
    title_en: "Scanning Instrument",
  },
  {
    id: 2,
    title: "Scanning Instrument2",
    title_en: "Scanning Instrument2",
  },
]);
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

// const fetchBanners = () => {
//   isOverlay.value = true;
//   homeStore
//     .fetchBanners({
//       is_publish: 1,
//     })
//     .then((response) => {
//       if (response.data.message == "success") {
//         banners.value = response.data.data;
//         isOverlay.value = false;
//         // options.value.type = "loop";
//       } else {
//         console.log("error");
//       }
//     })
//     .catch((error) => {
//       console.error(error);
//       isOverlay.value = false;
//     });
// };
// fetchBanners();

// const fetchNewsTypes = () => {
//   isOverlay.value = true;
//   homeStore
//     .fetchNewsTypes({
//       is_publish: 1,
//     })
//     .then((response) => {
//       if (response.data.message == "success") {
//         newsTypes.value = response.data.data;

//         newsTypes.value.unshift({
//           id: 99,
//           title: "ข่าวทั้งหมด",
//           title_en: "All News",
//         });
//         fetchNews();
//         isOverlay.value = false;
//         // options.value.type = "loop";
//       } else {
//         console.log("error");
//       }
//     })
//     .catch((error) => {
//       console.error(error);
//       isOverlay.value = false;
//     });
// };
// fetchNewsTypes();

// const fetchNews = () => {
//   isOverlay.value = true;
//   homeStore
//     .fetchNews({
//       is_publish: 1,
//     })
//     .then((response) => {
//       if (response.data.message == "success") {
//         news.value = response.data.data;
//         newsTypes.value = newsTypes.value.map((it) => {
//           if (it.id == 99) {
//             it.news = news.value.filter((x) => {
//               let isEng = true;
//               if (lang.value != "th") {
//                 if (x.title_en == null || x.title_en == "") {
//                   isEng = false;
//                 }
//               }
//               return isEng;
//             });

//             it.news = it.news.slice(0, 8);
//           } else {
//             it.news = news.value.filter((x) => {
//               let isEng = true;
//               if (lang.value != "th") {
//                 if (x.title_en == null || x.title_en == "") {
//                   isEng = false;
//                 }
//               }
//               return x.news_type_id == it.id && isEng;
//             });

//             it.news = it.news.slice(0, 8);
//           }
//           return it;
//         });
//         isOverlay.value = false;
//       } else {
//         console.log("error");
//       }
//     })
//     .catch((error) => {
//       console.error(error);
//       isOverlay.value = false;
//     });
// };

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

</script>

<style lang="scss">
.card-icon-home {
  border-radius: 0;
  background-color: #ccc;
  padding-block: 2em 0;
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
  filter:
    invert(5%) sepia(40%) saturate(8%) hue-rotate(48deg) brightness(90%)
    contrast(99%);
}

.news-tag {
  color: #bbb;
  font-size: 0.7em;
}

.news-card {
  border: solid 1px #ddd;
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

.v-theme--light > .v-application__wrap > .layout-wrapper {
  background-color: #fff !important;
}
</style>
<!--  -->
<template>
  <div>
    <!-- Main -->
    <main class="layout-page-content mt-6 mb-6">
      <v-row>
        <v-col>
          <h2>Equipment & Rate</h2>
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
                      $router.push({ name: 'equipment-id', params: { id: nw.id } })
                    "
                    class="news-card cursor-pointer"
                  >
                    <VImg
                      :src="lang == 'th' ? nw.news_file : nw.news_en_file"
                      cover
                    />

                    <VCardText style="min-height: 200px !important;">
                      <h2 style="font-size: 1.25rem; font-weight: 500;">
                        {{ lang == "th" ? nw.title : nw.title_en }}
                      </h2>
                    </VCardText>
                    <VCardActions>
                      <VBtn
                        @click="isCardDetailsVisible = !isCardDetailsVisible"
                      >
                      ดูข้อมูล <VIcon icon="tabler-arrow-right" />
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
    </main>
  </div>
</template>

<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
