<script setup>
// import NavBarI18n from '@core/components/I18n.vue';
import NavBarI18n from "@/layouts/components/I18n.vue";
// import { useThemeConfig } from '@core/composable/useThemeConfig';

// const { isAppRtl } = useThemeConfig()
// const router = '@/router';
const router = useRouter();
const route = useRoute();
const { locale } = useI18n({ useScope: "global" });

const i18nCompLanguages = [
  {
    label: "Thai",
    i18nLang: "th",
  },
  {
    label: "English",
    flagpackClass: "i-flagpack-us",
    i18nLang: "en",
  },
];

if (localStorage.getItem("currentLang") === null) {
  localStorage.setItem("currentLang", "th");
}

locale.value = localStorage.getItem("currentLang");

const handleLangChange = (lang) => {
  localStorage.currentLang = lang;
  console.log(localStorage.currentLang);
  // prevent
  setTimeout(() => {
    router.go(route.path);  
  }, 100);

  // console.log(lang);
  // isAppRtl.value = lang === "th";
  // return this.locales.find(l => l.locale === this.$i18n.locale)
};
</script>

<template>
  <NavBarI18n :languages="i18nCompLanguages" @change="handleLangChange" />
</template>
