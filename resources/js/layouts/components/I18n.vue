<script setup>
import enFlag from "@images/flag/en-flag.png";
import thFlag from "@images/flag/th-flag.png";

const props = defineProps({
  languages: {
    type: Array,
    required: true,
  },
  location: {
    type: null,
    required: false,
    default: "bottom end",
  },
});

const emit = defineEmits(["change"]);

const { locale } = useI18n({ useScope: "global" });

const flag = ref(locale.value === "th" ? thFlag : enFlag);
</script>

<style lang="scss"></style>

<template>
  <VBtn icon variant="text" color="default" size="small" class="ml-3 pl-2">
    <!-- <VIcon
      icon="tabler-language"
      size="24"
    /> -->
    <VImg :src="flag" :width="25" />
    <VIcon icon="tabler-chevron-down" size="12" class="font-weight-bold" />
    <!-- Menu -->
    <VMenu activator="parent" :location="props.location" offset="14px">
      <!-- List -->
      <VList min-width="175px">
        <!-- List item -->
        <VListItem
          v-for="lang in props.languages"
          :key="lang.i18nLang"
          :value="lang.i18nLang"
          @click="
            {
              locale = lang.i18nLang;
              flag = lang.i18nLang == 'th' ? thFlag : enFlag;
              $emit('change', lang.i18nLang);
            }
          "
        >
          <!-- Language label -->
          <VListItemTitle>{{ lang.label }}</VListItemTitle>
        </VListItem>
      </VList>
    </VMenu>
  </VBtn>
</template>
