<script setup>
import { useDisplay } from "vuetify";
import { useContactUsStore } from "./useContactUsStore";
// import { defineComponent } from "vue";
const contactUsStore = useContactUsStore();
const { t } = useI18n();

const { mobile } = useDisplay();
const isOverlay = ref(true);

onMounted(() => {});

// 👉 Fetching C
const contactUs = ref({
  title: "",
  title_en: "",
  detail: "",
  detail_en: "",
});

const fetchContactUses = () => {
  isOverlay.value = true;
  contactUsStore
    .fetchContactUses({
      is_publish: 1,
      id: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        contactUs.value = response.data.data[0];
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
fetchContactUses();

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

.news-tag {
  color: #bbb;
  font-size: 0.7em;
}

.news-card {
  border: solid 1px #ddd;
}

.v-theme--light > .v-application__wrap > .layout-wrapper {
  background-color: #fff !important;
}
</style>
<template>
  <div>
    <!-- Main -->
    <main class="layout-page-content mt-16 mb-6">
      <VRow>
        <VCol
          cols="12"
          sm="12"
          md="12"
          style="border-left: solid 0.7em #ffcb05"
        >
          <h2>
            {{ lang == "th" ? contactUs.title : contactUs.title_en }}
          </h2>
          <h2 style="color: #ffcb05">
            {{ t("Scientific Instrument") }}
            <br />
            {{ t("and High Performance Computing Center") }}
          </h2>
        </VCol>
      </VRow>

      <VRow class="mt-16">
        <VCol cols="12" sm="12" md="12" style="font-size: 1.1rem">
          <div
            v-html="lang == 'th' ? contactUs.detail : contactUs.detail_en"
          ></div>
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
