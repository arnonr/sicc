<script setup>
import navItems from "@/navigation/horizontal";
import { isUserLoggedIn } from "@/router/utils";
import { useThemeConfig } from "@core/composable/useThemeConfig";
import { themeConfig } from "@themeConfig";

import logoSCI from "@images/logos/logo-sci.jpeg";
// Components
import Footer from "@/layouts/components/Footer.vue";
import NavBarI18n from "@/layouts/components/NavBarI18n.vue";
import NavbarThemeSwitcher from "@/layouts/components/NavbarThemeSwitcher.vue";
import TopFooter from "@/layouts/components/TopFooter.vue";
import UserProfile from "@/layouts/components/UserProfile.vue";
import { HorizontalNavLayout } from "@layouts";
import { VNodeRenderer } from "@layouts/components/VNodeRenderer";
import AdminNav from "./AdminNav.vue";
// import logoSCI from '@images/logos/logo-sci.jpeg'

const { appRouteTransition } = useThemeConfig();
const isLoggedIn = isUserLoggedIn();
</script>

<template>
  <HorizontalNavLayout :nav-items="navItems">
    <!-- 👉 navbar -->
    <template #navbar>
      <RouterLink to="/" class="app-logo d-flex align-center gap-x-3">
        <VRow>
          <VCol cols="12" class="pb-0">
            <VNodeRenderer :nodes="themeConfig.app.logo" />
          </VCol>
          <VCol cols="12" class="pt-0">
            <h5 class="leading-normal text-second">
              <!-- class="app-title font-weight-bold leading-normal text-xl" -->
              ศูนย์เครื่องมือวิทยาศาสตร์และคอมพิวเตอร์สมรรถนะสูง
              คณะวิทยาศาสตร์ประยุกต์
            </h5>
            <h6 class="leading-normal text-second">
              Scientific Instrument and High Performance Computing Center :
              SICC, Faculty of Applied Science
            </h6>
          </VCol>
        </VRow>
      </RouterLink>
      <VSpacer />

      <!-- <NavSearchBar trigger-btn-class="ms-lg-n3" /> -->

      <!-- <NavbarThemeSwitcher /> -->
      <!-- <NavbarShortcuts /> -->
      <!-- <NavBarNotifications class="me-2" /> -->
      <VRow>
        <VCol cols="12" class="pb-0" align="center" justify="center">
          <VImg :src="logoSCI" :width="70" aspect-ratio="16/9" />
        </VCol>
        <VCol cols="12" class="pb-0" align="center" justify="center">
          <NavbarThemeSwitcher />
          <UserProfile />
          <NavBarI18n />
        </VCol>
      </VRow>
    </template>

    <!-- 👉 Pages -->
    <RouterView v-slot="{ Component, route }">
      <Transition :name="appRouteTransition" mode="out-in">
        <Component :is="Component" :key="route.path" />
      </Transition>
    </RouterView>

    <!-- 👉 Footer -->

    <TopFooter />
    <template #footer>
      <Footer />
    </template>

    <!-- 👉 Customizer -->
    <!-- <TheCustomizer /> -->
    <AdminNav v-if="isLoggedIn" />
  </HorizontalNavLayout>
</template>
