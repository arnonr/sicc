<script setup>
import navItems from '@/navigation/vertical';
import { isUserLoggedIn } from "@/router/utils";
import { useThemeConfig } from '@core/composable/useThemeConfig';

// Components
import Footer from '@/layouts/components/Footer.vue';
import NavBarI18n from '@/layouts/components/NavBarI18n.vue';
import NavSearchBar from '@/layouts/components/NavSearchBar.vue';
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue';
import TopFooter from "@/layouts/components/TopFooter.vue";
import UserProfile from '@/layouts/components/UserProfile.vue';
import AdminNav from "./AdminNav.vue";

// @layouts plugin
import { VerticalNavLayout } from '@layouts';

const { appRouteTransition, isLessThanOverlayNavBreakpoint } = useThemeConfig()
const { width: windowWidth } = useWindowSize()
const isLoggedIn = isUserLoggedIn();
</script>

<template>
  <VerticalNavLayout
    :nav-items="navItems"
  >
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <VBtn
          v-if="isLessThanOverlayNavBreakpoint(windowWidth)"
          icon
          variant="text"
          color="default"
          class="ms-n3"
          size="small"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon
            icon="tabler-menu-2"
            size="24"
          />
        </VBtn>

        <VSpacer />

        <NavBarI18n />
        <NavbarThemeSwitcher />
        <!-- <NavbarShortcuts /> -->
        <!-- <NavBarNotifications class="me-2" /> -->
        <NavSearchBar class="ms-lg-n3" />
        <UserProfile />
      </div>
    </template>

    <!-- 👉 Pages -->
    <RouterView v-slot="{ Component }">
      <Transition
        :name="appRouteTransition"
        mode="out-in"
      >
        <Component :is="Component" />
      </Transition>
    </RouterView>

  
    
    <TopFooter />
    <!-- 👉 Footer -->
    <template #footer>
      
      <Footer />
    </template>

    <!-- 👉 Customizer -->
    <!-- <TheCustomizer /> -->
    <AdminNav v-if="isLoggedIn" />
  </VerticalNavLayout>
</template>
