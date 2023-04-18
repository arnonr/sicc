<script setup>
import { initialAbility } from "@/plugins/casl/ability";
import { useAppAbility } from "@/plugins/casl/useAppAbility";
const { t } = useI18n()
// import userIcon from "@images/avatars/user_icon.png";

const router = useRouter();
const ability = useAppAbility();
const userData = JSON.parse(localStorage.getItem("userData") || "null");

const logout = () => {
  // Remove "userData" from localStorage
  localStorage.removeItem("userData");

  // Remove "accessToken" from localStorage
  localStorage.removeItem("accessToken");
  router.push("/login").then(() => {
    // Remove "userAbilities" from localStorage
    localStorage.removeItem("userAbilities");

    // Reset ability to initial ability
    ability.update(initialAbility);
  });
};
</script>

<template>
  <!-- <VBadge
    dot
    location="bottom right"
    offset-x="3"
    offset-y="3"
    bordered
    color="success"
  > -->
  <div class="d-inline" id="menu-activator">
    <VAvatar
      class="cursor-pointer"
      color="primary"
      variant="tonal"
      size="20"
    >
      <!-- <VImg
      v-if="userData && userData.avatar"
      :src="userIcon"
    /> -->
      <!-- <VIcon v-else icon="tabler-user" /> -->
      <VIcon icon="tabler-user" />
      <!-- SECTION Menu -->

      <!-- !SECTION -->
    </VAvatar>
    <span
      class="ml-2 font-weight-bold cursor-pointer d-none d-md-inline"
      style="font-size: 0.8em"
      >{{ t("Sign In") }}</span
    >
  </div>
  <!-- activator="parent" -->
  <VMenu
    activator="#menu-activator"
    width="230"
    location="bottom end"
    offset="14px"
  >
    <VList>
      <!-- 👉 User Avatar & Name -->
      <VListItem>
        <template #prepend>
          <VListItemAction start>
            <VBadge
              dot
              location="bottom right"
              offset-x="3"
              offset-y="3"
              color="success"
            >
              <VAvatar color="primary" variant="tonal">
                <VImg
                  v-if="userData && userData.avatar"
                  :src="userData.avatar"
                />
                <VIcon v-else icon="tabler-user" />
              </VAvatar>
            </VBadge>
          </VListItemAction>
        </template>

        <VListItemTitle class="font-weight-semibold">
          {{ userData.fullName }}
        </VListItemTitle>
        <VListItemSubtitle>{{ userData.role }}</VListItemSubtitle>
      </VListItem>

      <VDivider class="my-2" />

      <!-- 👉 Profile -->
      <VListItem :to="{ name: 'apps-user-view-id', params: { id: 21 } }">
        <template #prepend>
          <VIcon class="me-2" icon="tabler-user" size="22" />
        </template>

        <VListItemTitle>Profile</VListItemTitle>
      </VListItem>

      <!-- 👉 Settings -->
      <VListItem
        :to="{
          name: 'pages-account-settings-tab',
          params: { tab: 'account' },
        }"
      >
        <template #prepend>
          <VIcon class="me-2" icon="tabler-settings" size="22" />
        </template>

        <VListItemTitle>Settings</VListItemTitle>
      </VListItem>

      <!-- 👉 Pricing -->
      <VListItem :to="{ name: 'pages-pricing' }">
        <template #prepend>
          <VIcon class="me-2" icon="tabler-currency-dollar" size="22" />
        </template>

        <VListItemTitle>Pricing</VListItemTitle>
      </VListItem>

      <!-- 👉 FAQ -->
      <VListItem :to="{ name: 'pages-faq' }">
        <template #prepend>
          <VIcon class="me-2" icon="tabler-help" size="22" />
        </template>

        <VListItemTitle>FAQ</VListItemTitle>
      </VListItem>

      <!-- Divider -->
      <VDivider class="my-2" />

      <!-- 👉 Logout -->
      <VListItem link @click="logout">
        <template #prepend>
          <VIcon class="me-2" icon="tabler-logout" size="22" />
        </template>

        <VListItemTitle>Logout</VListItemTitle>
      </VListItem>
    </VList>
  </VMenu>
  <!-- </VBadge> -->
</template>
