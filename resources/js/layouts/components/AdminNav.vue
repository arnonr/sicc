<script setup>
import { useThemeConfig } from "@core/composable/useThemeConfig";
import { AppContentLayoutNav, NavbarType } from "@layouts/enums";
import { themeConfig } from "@themeConfig";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
import { useTheme } from "vuetify";

const isNavDrawerOpen = ref(false);
const {
  theme,
  skin,
  appRouteTransition,
  navbarType,
  footerType,
  isVerticalNavCollapsed,
  isVerticalNavSemiDark,
  appContentWidth,
  appContentLayoutNav,
  isAppRtl,
  isNavbarBlurEnabled,
  isLessThanOverlayNavBreakpoint,
} = useThemeConfig();

// 👉 Primary Color
const vuetifyTheme = useTheme();

// const vuetifyThemesName = Object.keys(vuetifyTheme.themes.value)
const initialThemeColors = JSON.parse(
  JSON.stringify(vuetifyTheme.current.value.colors)
);

const colors = ["primary", "secondary", "success", "info", "warning", "error"];

const setPrimaryColor = (color) => {
  const currentThemeName = vuetifyTheme.name.value;

  vuetifyTheme.themes.value[currentThemeName].colors.primary = color;
  localStorage.setItem(
    `${themeConfig.app.title}-${currentThemeName}ThemePrimaryColor`,
    color
  );
  localStorage.setItem(`${themeConfig.app.title}-initial-loader-color`, color);
};

const getBoxColor = (color, index) => (index ? color : "#7367F0");
const { width: windowWidth } = useWindowSize();

const headerValues = computed(() => {
  const entries = Object.entries(NavbarType);
  if (appContentLayoutNav.value === AppContentLayoutNav.Horizontal)
    return entries.filter(([_, val]) => val !== NavbarType.Hidden);

  return entries;
});
</script>
<template>
  <!--  v-if="!isLessThanOverlayNavBreakpoint(windowWidth)" -->
  <template v-if="true">
    <VBtn
      icon
      size="small"
      color="second"
      class="app-admin-nav-toggler rounded-s-lg rounded-0"
      style="z-index: 1001"
      @click="isNavDrawerOpen = true"
    >
      <VIcon icon="tabler-list-details" />
    </VBtn>

    <VNavigationDrawer
      v-model="isNavDrawerOpen"
      temporary
      location="end"
      width="300"
      :scrim="false"
      class="app-customizer"
    >
      <!-- 👉 Header -->
      <div class="customizer-heading d-flex align-center justify-space-between">
        <div>
          <h6 class="text-h6">MENU</h6>
          <span class="text-body-1">Admin Manage</span>
        </div>
        <VBtn
          icon
          variant="text"
          color="secondary"
          size="x-small"
          @click="isNavDrawerOpen = false"
        >
          <VIcon icon="tabler-x" size="20" />
        </VBtn>
      </div>

      <VDivider />

      <PerfectScrollbar tag="ul" :options="{ wheelPropagation: false }">
        <!-- SECTION Theming -->
        <CustomizerSection title="" :divider="false">
          <!-- 👉 Skin -->
          <ul class="admin-nav-items">
            <li class="admin-nav-li">
              <a @click="$router.push({ name: 'admin-banner' })" class="admin-nav-link cursor-pointer">
                <VIcon icon="tabler-slideshow" size="24" />
                <span class="admin-nav-item-title"> Banner</span></a
              >
              <hr class="admin-nav-hr" />
            </li>
            <li class="admin-nav-li">
              <a @click="$router.push({ name: 'admin-banner' })" class="admin-nav-link cursor-pointer">
                <VIcon icon="tabler-augmented-reality" size="24" />
                <span class="admin-nav-item-title"> Service</span></a
              >
              <hr class="admin-nav-hr" />
            </li>
            <li class="admin-nav-li">
              <a @click="$router.push({ name: 'admin-news' })" class="admin-nav-link cursor-pointer">
                <VIcon icon="tabler-news" size="24" />
                <span class="admin-nav-item-title"> News</span></a
              >
            </li>
            <li class="admin-nav-li">
              <a @click="$router.push({ name: 'admin-newsType' })" class="admin-nav-link cursor-pointer">
                <VIcon icon="tabler-category" size="24" />
                <span class="admin-nav-item-title"> News Type</span></a
              >
              <hr class="admin-nav-hr" />
            </li>
            <li class="admin-nav-li">
              <a @click="$router.push({ name: 'admin-banner' })" class="admin-nav-link cursor-pointer">
                <VIcon icon="tabler-book-2" size="24" />
                <span class="admin-nav-item-title"> About Us</span></a
              >
              <hr class="admin-nav-hr" />
            </li>
            <li class="admin-nav-li">
              <a @click="$router.push({ name: 'admin-banner' })" class="admin-nav-link cursor-pointer">
                <VIcon icon="tabler-address-book" size="24" />
                <span class="admin-nav-item-title"> Contact Us</span></a
              >
              <hr class="admin-nav-hr" />
            </li>
          </ul>

          <!-- <h6 class="text-base font-weight-regular">Skins</h6> -->

          <!-- <div class="d-flex align-center">
            <VLabel for="pricing-plan-toggle" class="me-3"> Light </VLabel>

            <div>
              <VSwitch
                id="pricing-plan-toggle"
                v-model="theme"
                label="Dark"
                true-value="dark"
                false-value="light"
              />
            </div>
          </div> -->
        </CustomizerSection>
        <!-- !SECTION -->
      </PerfectScrollbar>
    </VNavigationDrawer>
  </template>
</template>

<style lang="scss">
.app-customizer {
  .customizer-section {
    padding: 1.25rem;
  }

  .customizer-heading {
    padding-block: 0.875rem;
    padding-inline: 1.25rem;
  }

  .v-navigation-drawer__content {
    display: flex;
    flex-direction: column;
  }
}

.app-admin-nav-toggler {
  position: fixed !important;
  inset-block-start: 45%;
  inset-inline-end: 0;
  transform: translateY(-50%);

  &:active {
    transform: translateY(-50%) !important;
  }
}

.admin-nav-link {
  color: #666;
  line-height: 3em;
  margin-left: 1em;
}

.admin-nav-link:hover {
  color: #ffcb05;
}

.admin-nav-hr {
  border: 0.5px solid #eee;
}
</style>
