<script setup>
import { requiredValidator } from "@validators";

import { useTeamStore } from "../useTeamStore";

import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter()
const teamStore = useTeamStore();

const item = ref({
  id: null,
  department_id: "",
  email: "",
  team_file: [],
  team_file_old: "",

  prefix: "",
  firstname: "",
  surname: "",
  position_type: "",
  position: "",

  prefix_en: "",
  firstname_en: "",
  surname_en: "",
  position_type_en: "",
  position_en: "",

  is_publish: 1,
});

const isOverlay = ref(false);
const isFormValid = ref(false);
const refForm = ref();

const selectOptions = ref({
  departments: [],
});

const fetchDepartments = () => {
  teamStore
    .fetchDepartments({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        selectOptions.value.departments = response.data.data.map((d) => {
          return {
            title: d.title,
            value: d.id,
          };
        });
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
fetchDepartments();


teamStore
  .fetchTeam({
    id: route.params.id,
  })
  .then((response) => {

    if (response.data.message == "success") {
      const { data } = response.data;
      item.value = { ...data };

      item.value.team_file_old = null;
      if (data.team_file != null) {
        item.value.team_file_old = data.team_file;
      }
      item.value.team_file = [];

    } else {
      console.log("error");
    }
  })
  .catch((error) => {
    console.error(error);
    isOverlay.value = false;
  });

const onSubmit = () => {
  isOverlay.value = true;
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      teamStore
        .editTeam({
          ...item.value,
          team_file:
            item.value.team_file != null ? item.value.team_file[0] : null,
        })
        .then((response) => {
          if (response.data.message == "success") {
            localStorage.setItem("updated", 1);
            console.log("Team Edit Success");
            nextTick(() => {
              router.push({
                path: "/admin/team/view/" + response.data.data.id,
              });
            });
          } else {
            isOverlay.value = false;
            console.log("error");
          }
        })
        .catch((error) => {
          console.error(error);
        });
    }
    isOverlay.value = false;
  });
};


onMounted(() => {
  window.scrollTo(0, 0);
});
</script>

<template>
  <main class="layout-page-content mb-6 mt-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol>
          <h2>Edit Team</h2>
          <!-- Froala -->
          <hr />
        </VCol>
      </VRow>
      <VRow class="mt-6">
        <VCol>
          <VCard title="" class="pb-5 pt-5">
            <VCardText>
              <VForm
                ref="refForm"
                v-model="isFormValid"
                @submit.prevent="onSubmit"
              >
                <VRow>
                  <VCol cols="12">
                    <h3>THA</h3>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="title">ฝ่ายงาน*</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VSelect
                          id="department_id"
                          v-model="item.department_id"
                          label="Department"
                          :items="selectOptions.departments"
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="team_file">รูป</label>
                      </VCol>

                      <VCol cols="12" md="7">
                        <VFileInput
                          label="Upload Picture"
                          id="team_file"
                          v-model="item.team_file"
                          persistent-placeholder
                        />
                      </VCol>

                      <VCol cols="12" md="2" class="pl-2">
                        <a
                          :href="
                            item.team_file_old != null
                              ? item.team_file_old
                              : '/'
                          "
                          target="_blank"
                        >
                          <VBtn style="width: 100%"> View Old File </VBtn></a
                        >
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="prefix">คำนำหน้า*</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="prefix"
                          :rules="[requiredValidator]"
                          v-model="item.prefix"
                          placeholder="Prefix"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="firstname">ชื่อ*</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="firstname"
                          :rules="[requiredValidator]"
                          v-model="item.firstname"
                          placeholder="Firstname"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="surname">นามสกุล*</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="surname"
                          :rules="[requiredValidator]"
                          v-model="item.surname"
                          placeholder="Surname"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="email">เมล</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="email"
                          v-model="item.email"
                          placeholder="Email"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="position_type">ประเภทงาน</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="position_type"
                          v-model="item.position_type"
                          placeholder="Position Type"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="position">ตำแหน่งงาน</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="position"
                          v-model="item.position"
                          placeholder="Position"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <hr />
                  </VCol>

                  <VCol cols="12">
                    <h3>ENG</h3>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="prefix_en">คำนำหน้า</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="prefix_en"
                          v-model="item.prefix_en"
                          placeholder="Prefix"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="firstname_en">ชื่อ</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="firstname_en"
                          v-model="item.firstname_en"
                          placeholder="Firstname"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="surname_en">นามสกุล</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="surname_en"
                          v-model="item.surname_en"
                          placeholder="Surname"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="position_type_en">ประเภทงาน</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="position_type_en"
                          v-model="item.position_type_en"
                          placeholder="Position Type"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="position_en">ตำแหน่งงาน</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="position_en"
                          v-model="item.position_en"
                          placeholder="Position"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <!-- 👉 submit and reset button -->
                  <VCol offset-md="3" cols="12" md="9" class="d-flex gap-4">
                    <VBtn type="submit"> Submit </VBtn>
                    <!-- <VBtn color="secondary" variant="tonal" type="reset">
                      Reset
                    </VBtn> -->
                  </VCol>
                </VRow>
              </VForm>
            </VCardText>
            <VOverlay
              v-model="isOverlay"
              contained
              persistent
              class="align-center justify-center"
            >
              <VProgressCircular indeterminate />
            </VOverlay>
          </VCard>
        </VCol>
      </VRow>
    </section>
  </main>
</template>
<route lang="yaml">
meta:
  action: read
  subject: Auth
</route>
