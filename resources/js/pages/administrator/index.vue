<script setup>
import { useDisplay } from "vuetify";
import { useAdministratorStore } from "./useAdministratorStore";
// import { defineComponent } from "vue";
const administratorStore = useAdministratorStore();
const { t } = useI18n();

const { mobile } = useDisplay();
const isOverlay = ref(true);

const lang = ref("th");
if (localStorage.getItem("currentLang") === "en") {
  lang.value = localStorage.getItem("currentLang");
}

onMounted(() => {});

// 👉 Fetching C
const items = ref([]);
const departments = ref([]);

const fetchAdministrators = () => {
  isOverlay.value = true;
  administratorStore
    .fetchAdministrators({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        items.value = response.data.data;

        //
        departments.value = departments.value.map((it) => {
          it.administrators = items.value.filter((x) => {
            // let isEng = true;
            // if (lang.value != "th") {
            //   if (x.title_en == null || x.title_en == "") {
            //     isEng = false;
            //   }
            // }
            return x.department_id == it.id;
          });
          return it;
        });

        isOverlay.value = false;
        // options.value.type = "loop";
      } else {
        console.log("error");
      }
    })
    .catch((error) => {
      console.error(error);
      isOverlay.value = false;
    });
};

const fetchDepartments = () => {
  isOverlay.value = true;
  administratorStore
    .fetchDepartments({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        departments.value = response.data.data;
        fetchAdministrators();
        isOverlay.value = false;

        // options.value.type = "loop";
      } else {
        console.log("error");
      }
    })
    .catch((error) => {
      console.error(error);
      isOverlay.value = false;
    });
};
fetchDepartments();
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
      <VRow>
        <VCol
          cols="12"
          sm="12"
          md="12"
          style="border-left: solid 0.7em #ffcb05"
        >
          <h2>
            {{ t("Administrator") }}
          </h2>
          <h2 style="color: #ffcb05">
            {{ t("Scientific Instrument") }}
            <br />
            {{ t("and High Performance Computing Center") }}
          </h2>
        </VCol>
      </VRow>

      <VRow class="mt-16">
        <VCol cols="12" sm="12" md="12" style="font-size: 1.3rem">
          <div v-for="dp in departments">
            <div style="background-color: #ccc" class="pa-3">
              <h4 class="font-weight-bold" style="color: #000">
                {{ lang == "th" ? dp.title : dp.title_en }}
              </h4>
            </div>
            <div class="pa-3 v-row" v-for="ad in dp.administrators">
              <div class="v-col-12 v-col-sm-3">
                <VImg
                  :src="ad.team_file"
                  height="320"
                  width="250"
                  style="border: solid 0.5em #ffcb05"
                />
              </div>

              <div class="v-col-12 v-col-sm-9" style="position: relative">
                <div
                  style="
                    margin-block-end: 0rem;
                    position: absolute;
                    top: 30%;
                    width: 50%;
                  "
                >
                  <div v-if="ad.position_type" class="font-weight-bold">
                    {{ lang == "th" ? ad.position_type : ad.position_type_en }}
                  </div>
                  <div v-if="ad.position" class="font-weight-bold">
                    {{ lang == "th" ? ad.position : ad.position_en }}
                  </div>
                  <div v-if="ad.position_type || ad.position"><hr class=" mt-2  mb-2" /></div>
                  <div class="font-weight-bold" style="margin-block-end: 0rem;">
                    {{
                      lang == "th"
                        ? ad.prefix + ad.firstname + " " + ad.surname
                        : ad.prefix + ad.firstname + " " + ad.surname
                    }}
                  </div>
                  <div class="font-weight-bold" style="margin-block-end: 0rem;">
                    {{
                    lang == "th"
                      ? ad.prefix_en + ad.firstname_en + " " + ad.surname_en
                      : ad.prefix_en + ad.firstname_en + " " + ad.surname_en
                  }}
                  </div>
                  <div style="margin-block-end: 0rem;">
                    Email : {{ ad.email }}
                  </div>
                </div>
                <!-- <p v-if="ad.position" class="font-weight-bold" style="margin-block-end: 0rem;">
                  {{ lang == "th" ? ad.position : ad.position }}
                </p>
                <hr v-if="ad.position_type || ad.position" />
                <p  class="font-weight-bold" style="margin-block-end: 0rem;">
                  {{
                    lang == "th"
                      ? ad.prefix + ad.firstname + " " + ad.surname
                      : ad.prefix + ad.firstname + " " + ad.surname
                  }}
                </p>
                <p  class="font-weight-bold" style="margin-block-end: 0rem;">
                  {{
                    lang == "th"
                      ? ad.prefix_en + ad.firstname_en + " " + ad.surname_en
                      : ad.prefix_en + ad.firstname_en + " " + ad.surname_en
                  }}
                </p>
                <p style="margin-block-end: 0rem;">Email : {{ ad.email }}</p> -->
              </div>
            </div>
          </div>

          <!-- <div
            v-html="lang == 'th' ? contactUs.detail : contactUs.detail_en"
          ></div> -->
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
