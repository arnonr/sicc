<script setup>
import { useAppAbility } from "@/plugins/casl/useAppAbility";
// import AuthProvider from "@/views/pages/authentication/AuthProvider.vue";
import axios from "@axios";
import { useGenerateImageVariant } from "@core/composable/useGenerateImageVariant";
import authV2LoginIllustrationBorderedDark from "@images/pages/auth-v2-login-illustration-bordered-dark.png";
import authV2LoginIllustrationBorderedLight from "@images/pages/auth-v2-login-illustration-bordered-light.png";
import authV2LoginIllustrationDark from "@images/pages/auth-v2-login-illustration-dark.png";
import authV2LoginIllustrationLight from "@images/pages/auth-v2-login-illustration-light.png";
import authV2MaskDark from "@images/pages/misc-mask-dark.png";
import authV2MaskLight from "@images/pages/misc-mask-light.png";
import { VNodeRenderer } from "@layouts/components/VNodeRenderer";
import { themeConfig } from "@themeConfig";
import { requiredValidator } from "@validators";
import { VForm } from "vuetify/components";

const authThemeImg = useGenerateImageVariant(
  authV2LoginIllustrationLight,
  authV2LoginIllustrationDark,
  authV2LoginIllustrationBorderedLight,
  authV2LoginIllustrationBorderedDark,
  true
);
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark);
const isPasswordVisible = ref(false);
const route = useRoute();
const router = useRouter();
const ability = useAppAbility();

const errors = ref({
  username: undefined,
  password: undefined,
});

const refVForm = ref();
const username = ref("");
const password = ref("");
const rememberMe = ref(false);

const login = () => {
  let baseUrl = "http://143.198.208.110:8115/api";
  if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
    baseUrl = "http://localhost:8115/api";
  }

  axios
    .post(baseUrl + "/auth/login", {
      username: username.value,
      password: password.value,
    })
    .then((r) => {
      if (r.data.message === "success") {
        // const { accessToken, userData, userAbilities } = r.data;
        const { accessToken, userData } = r.data;

        // localStorage.setItem("userAbilities", JSON.stringify(userAbilities));
        // ability.update(userAbilities);
        localStorage.setItem("userAbilities", JSON.stringify(userData.ability));
        ability.update(userData.ability);
        localStorage.setItem("userData", JSON.stringify(userData));
        localStorage.setItem("accessToken", JSON.stringify(accessToken));

        // Redirect to `to` query if exist or redirect to index route
        router.replace(route.query.to ? String(route.query.to) : "/");
      } else {
        console.log(r.data.message)
        const { errors: formErrors } = {errors: {username: [r.data.message]}};
        errors.value = formErrors;
      }
    })
    .catch((e) => {
      const { errors: formErrors } = e.response.data;

      errors.value = formErrors;
      console.error(e.response.data);
    });
};

const onSubmit = () => {
  refVForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) login();
  });
};
</script>

<template>
  <VRow no-gutters class="auth-wrapper">
    <VCol lg="8" class="d-none d-lg-flex">
      <div class="position-relative auth-bg rounded-lg w-100 ma-8 me-0">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg
            max-width="505"
            :src="authThemeImg"
            class="auth-illustration mt-16 mb-2"
          />
        </div>

        <VImg :src="authThemeMask" class="auth-footer-mask" />
      </div>
    </VCol>

    <VCol cols="12" lg="4" class="d-flex align-center justify-center">
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4">
        <VCardText class="text-center">
          <VNodeRenderer
            :nodes="themeConfig.app.logo"
            class="mb-6 align-center justify-center"
            style="width: 50%"
          />

          <h5 class="text-h5 font-weight-semibold mb-1">
            Sign-in to your account
          </h5>
          <!-- <p class="mb-0">
            Please sign-in to your account and start the adventure
          </p> -->
        </VCardText>
        <!-- <VCardText>
          <VAlert color="primary" variant="tonal">
            <p class="text-caption mb-2">
              Admin Email: <strong>admin@demo.com</strong> / Pass:
              <strong>admin</strong>
            </p>
            <p class="text-caption mb-0">
              Client Email: <strong>client@demo.com</strong> / Pass:
              <strong>client</strong>
            </p>
          </VAlert>
        </VCardText> -->
        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onSubmit">
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <VTextField
                  v-model="username"
                  label="ICIT Account"
                  type="text"
                  :rules="[requiredValidator]"
                  :error-messages="errors.username"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <!-- :rules="[requiredValidator]" -->
                <VTextField
                  v-model="password"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :error-messages="errors.password"
                  :append-inner-icon="
                    isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div
                  class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4"
                >
                  <VCheckbox v-model="rememberMe" label="Remember me" />
                  <RouterLink
                    class="text-primary ms-2 mb-1"
                    :to="{ name: 'forgot-password' }"
                  >
                    Forgot Password?
                  </RouterLink>
                </div>

                <VBtn block type="submit"> Sign In </VBtn>
              </VCol>

              <!-- create account -->
              <!-- <VCol cols="12" class="text-center">
                <span>New on our platform?</span>
                <RouterLink
                  class="text-primary ms-2"
                  :to="{ name: 'register' }"
                >
                  Create an account
                </RouterLink>
              </VCol>
              <VCol cols="12" class="d-flex align-center">
                <VDivider />
                <span class="mx-4">or</span>
                <VDivider />
              </VCol> -->

              <!-- auth providers -->
              <!-- <VCol cols="12" class="text-center">
                <AuthProvider />
              </VCol> -->
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
