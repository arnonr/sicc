<script setup>
import { watch } from "vue";
const { t, locale } = useI18n();

const year = ref(new Date().getFullYear() + 543);

watch(locale, () => {
  year.value =
    locale.value == "th"
      ? new Date().getFullYear() + 543
      : new Date().getFullYear();
});
</script>
<template>
  <div class="h-100 d-flex align-center justify-center text-center">
    <!-- 👉 Footer: left content -->
    <span
      class="d-flex justify-center align-center font-weight-bold"
      style="line-height: 1em"
    >
      &copy;
      {{ year }}
      <!-- ศูนย์เครื่องมือวิทยาศาสตร์และคอมพิวเตอร์สมรรถนะสูง คณะวิทยาศาสตร์ประยุกต์ -->
      {{
        t(
          "Scientific Instrument and High Performance Computing Center, Faculty of Applied Science"
        )
      }}
      <!-- Made With
      <VIcon
        icon="tabler-heart"
        color="error"
        size="1.25rem"
        class="mx-1"
      />
      By <a
        href="https://pixinvent.com"
        target="_blank"
        rel="noopener noreferrer"
        class="text-primary ms-1"
      >Pixinvent</a> -->
    </span>
    <!-- 👉 Footer: right content -->
    <!-- <span class="d-md-flex gap-x-4 text-primary d-none">
      <a
        href="https://themeforest.net/licenses/standard"
        target="noopener noreferrer"
      >License</a>
      <a
        href="https://pixinvent.com/"
        target="noopener noreferrer"
      >More Themes</a>
      <a
        href="https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/documentation/"
        target="noopener noreferrer"
      >Documentation</a>
    </span> -->
  </div>
</template>
