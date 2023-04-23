<script setup>
import banner1 from "@images/banners/banner.jpg";
import banner2 from "@images/banners/banner2.jpg";
import icon1 from "@images/icons/home/icon1.png";
import icon2 from "@images/icons/home/icon2.png";
import icon3 from "@images/icons/home/icon3.png";
import icon4 from "@images/icons/home/icon4.png";
import pages1 from "@images/pages/1.png";
import { Splide, SplideSlide } from "@splidejs/vue-splide";
import "@splidejs/vue-splide/css";
import { useDisplay } from "vuetify";
// import { defineComponent } from "vue";
const { t } = useI18n();

const { mobile } = useDisplay();

onMounted(() => {
  console.log(mobile.value); // false
});

const options = {
  type: "loop",
  cover: true,
  heightRatio: 0.2,
  speed: 2000,
  wheelSleep: 0,
  autoplay: "play",
  // arrows: false,
};

const cardIcon = [
  { title: "Sample Submission", icon: icon1 },
  { title: "Equipment & Rate", icon: icon2 },
  { title: "Direction", icon: icon3 },
  { title: "Department Facility", icon: icon4 },
];

const banner = [
  { title: "banner1", banner_file: banner1 },
  { title: "banner2", banner_file: banner2 },
];

const currentTab = ref(0);
const tabItemContent =
  "Candy canes donut chupa chups candy canes lemon drops oat cake wafer. Cotton candy candy canes marzipan carrot cake. Sesame snaps lemon drops candy marzipan donut brownie tootsie roll. Icing croissant bonbon biscuit gummi bears. Pudding candy canes sugar plum cookie chocolate cake powder croissant.";
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
</style>
<template>
  <div>
    <!-- Slide -->
    <Splide :options="options" aria-label="Slide">
      <SplideSlide data-splide-interval="3000" v-for="bn in banner">
        <img :src="bn.banner_file" :alt="bn.title" />
      </SplideSlide>
    </Splide>
    <!-- End Slide -->

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
    <main class="layout-page-content mt-6  mb-6">
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
  