<script setup>
import icon1 from "@images/icons/home/icon1.png";
import icon2 from "@images/icons/home/icon2.png";
import icon3 from "@images/icons/home/icon3.png";
import icon4 from "@images/icons/home/icon4.png";
import pages1 from "@images/pages/1.png";

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
const isOverLay = ref(true);

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
const fetchBanners = () => {
  isOverLay.value = true;
  homeStore
    .fetchBanners({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        banners.value = response.data.data;
        isOverLay.value = false;
        // options.value.type = "loop";
      } else {
        console.log("error");
      }
    })
    .catch((error) => {
      console.error(error);
      isOverLay.value = false;
    });
};
fetchBanners();

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
            <VTab>{{ t("All News") }}</VTab>
            <VTab>{{ t("News Release") }}</VTab>
            <VTab>{{ t("Training") }}</VTab>
          </VTabs>
          <VDivider />

          <VWindow v-model="currentTab" class="mt-5 mb-5">
            <VWindowItem v-for="item in 3" :key="item">
              <!-- {{ tabItemContent }} -->
              <!-- News -->
              <VRow>
                <VCol cols="12" sm="6" md="3" v-for="item1 in 8" :key="item1">
                  <VCard
                    @click="$router.push({ name: 'apps-email' })"
                    class="news-card cursor-pointer"
                  >
                    <VImg :src="pages1" cover />

                    <VCardItem>
                      <VCardTitle>Influencing The Influencer</VCardTitle>
                    </VCardItem>

                    <VCardText>
                      Cancun is back, better than ever! Over a hundred Mexico
                      resorts have reopened and the state tourism minister
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
                        {{ t("News Release") }}</span
                      >
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
          <VBtn color="primary" class="float-right" variant="flat">
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
