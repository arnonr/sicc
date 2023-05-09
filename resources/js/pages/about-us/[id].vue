<script setup>
import { useDisplay } from "vuetify";
import { useAboutUsStore } from "./useAboutUsStore";

import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

// import { defineComponent } from "vue";
const aboutUsStore = useAboutUsStore();
const { t } = useI18n();

const { mobile } = useDisplay();
const isOverlay = ref(true);

onMounted(() => {});

// 👉 Fetching C
const aboutUs = ref({
  title: "",
  title_en: "",
  detail: "",
  detail_en: "",
});

const fetchAboutUs = () => {
  isOverlay.value = true;
  aboutUsStore
    .fetchAboutUs({
      id: route.params.id,
    })
    .then((response) => {
      if (response.data.message == "success") {
        aboutUs.value = response.data.data;
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
fetchAboutUs();

const lang = ref("th");
if (localStorage.getItem("currentLang") === "en") {
  lang.value = localStorage.getItem("currentLang");
}
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
    <!-- Main -->
    <main class="layout-page-content mt-16 mb-6">

      <VContainer>
      <VRow>
        <VCol
          cols="12"
          sm="12"
          md="12"
          style="border-left: solid 0.7em #ffcb05"
        >
          <h2>
            {{ lang == "th" ? aboutUs.title : aboutUs.title_en }}
          </h2>
          <h2 style="color: #ffcb05">
            {{ t("Scientific Instrument") }}
            <br />
            {{ t("and High Performance Computing Center") }}
          </h2>
        </VCol>
      </VRow>

      <VRow class="mt-6">
        <VCol cols="12" sm="12" md="12" style="font-size: 1.3rem;">
          <div v-html="lang == 'th' ? aboutUs.detail : aboutUs.detail_en">
          </div>
        </VCol>
      </VRow>
    </VContainer>
    </main>
  </div>
</template>

<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
