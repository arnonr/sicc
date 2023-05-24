<script setup>
import { requiredValidator } from "@validators";

import { useRouter } from "vue-router";
import { useAboutUsStore } from "./useAboutUsStore";

import "froala-editor/css/froala_editor.pkgd.min.css";
import FroalaEditor from "froala-editor/js/froala_editor.pkgd.min.js";
// const route = useRoute();
const router = useRouter();

const aboutUsStore = useAboutUsStore();

const item = ref({
  id: null,
  title: "",
  title_en: "",
  detail: "",
  detail_en: "",
  is_publish: 1,
});
const isOverlay = ref(false);
const isFormValid = ref(false);
const refForm = ref();

const onSubmit = () => {
  isOverlay.value = true;
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      aboutUsStore
        .addAboutUs({
          ...item.value,
          detail: item.value.detail.replace(
            '<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>',
            ""
          ),
          detail_en: item.value.detail_en.replace(
            '<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>',
            ""
          ),
        })
        .then((response) => {
          if (response.data.message == "success") {
            localStorage.setItem("added", 1);
            console.log("About Us Add Success");
            nextTick(() => {
              router.push({
                path: "/admin/aboutUs/view/" + response.data.data.id,
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

let baseUrl = "http://143.198.208.110:8115/api";
if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
  baseUrl = "http://localhost:8115/api";
}

const initFroala = () => {
  new FroalaEditor("#detail", {
    height: 300,
    // inlineMode: false,
    pastePlain: true,
    paragraphy: false,
    quickInsertEnabled: false,
    toolbarButtons: [
      "undo",
      "redo",
      "fullscreen",
      "|",
      "fontSize",
      "color",
      "bold",
      "italic",
      "underline",
      "inlineClass",
      "|",
      "paragraphFormat",
      "align",
      "outdent",
      "indent",
      "|",
      "formatOL",
      "formatUL",
      "quote",
      "-",
      "insertLink",
      "insertImage",
      "insertVideo",
      "insertFile",
      "insertTable",
      "|",
      "fontAwesome",
      "insertHR",
      "selectAll",
      "clearFormatting",
      "|",
      "print",
      "getPDF",
      "html",
    ],
    // Change buttons for XS screen.
    toolbarButtonsXS: [
      ["undo", "redo"],
      ["bold", "italic", "underline"],
    ],
    placeholderText: "",
    attribution: false,
    key: "enter-your-license-key-here",
    disableRightClick: true,

    imageUploadURL: baseUrl + "/froala/image",
    imageAllowedTypes: ["jpeg", "jpg", "png"],

    fileUploadURL: baseUrl + "/froala/document",
    videoUploadURL: baseUrl + "/froala/video",

    // fileUpload: false,
    // imageUpload: false,
    imagePaste: false,
    imagePasteProcess: false,
    imageResize: true,
    crossDomain: true,
    events: {
      keyup: function (inputEvent) {
        item.value.detail = this.html.get();
      },
      click: function (clickEvent) {
        item.value.detail = this.html.get();
      },
      "commands.after": function (cmd, param1, param2) {
        item.value.detail = this.html.get();
      },
      "paste.after": function (pasteEvent) {
        item.value.detail = this.html.get();
      },
    },
  });

  new FroalaEditor("#detail_en", {
    height: 300,
    // inlineMode: false,
    pastePlain: true,
    paragraphy: false,
    quickInsertEnabled: false,
    toolbarButtons: [
      "undo",
      "redo",
      "fullscreen",
      "|",
      "fontSize",
      "color",
      "bold",
      "italic",
      "underline",
      "inlineClass",
      "|",
      "paragraphFormat",
      "align",
      "outdent",
      "indent",
      "|",
      "formatOL",
      "formatUL",
      "quote",
      "-",
      "insertLink",
      "insertImage",
      "insertVideo",
      "insertFile",
      "insertTable",
      "|",
      "fontAwesome",
      "insertHR",
      "selectAll",
      "clearFormatting",
      "|",
      "print",
      "getPDF",
      "html",
    ],
    // Change buttons for XS screen.
    toolbarButtonsXS: [
      ["undo", "redo"],
      ["bold", "italic", "underline"],
    ],
    placeholderText: "",
    attribution: false,
    key: "enter-your-license-key-here",
    disableRightClick: true,

    imageUploadURL: baseUrl + "/froala/image",
    // imageAllowedTypes: ['jpeg', 'jpg', 'png'],

    fileUploadURL: baseUrl + "/froala/document",
    videoUploadURL: baseUrl + "/froala/video",

    // fileUpload: false,
    // imageUpload: false,
    imagePaste: false,
    imagePasteProcess: false,
    imageResize: true,
    crossDomain: true,
    events: {
      keyup: function (inputEvent) {
        item.value.detail_en = this.html.get();
      },
      click: function (clickEvent) {
        item.value.detail_en = this.html.get();
      },
      "commands.after": function (cmd, param1, param2) {
        item.value.detail_en = this.html.get();
      },
      "paste.after": function (pasteEvent) {
        item.value.detail_en = this.html.get();
      },
    },
  });
};

onMounted(() => {
  initFroala();
  window.scrollTo(0, 0);
});
</script>
<template>
  <main class="layout-page-content mb-6 mt-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol>
          <h2>ADD About Us</h2>
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
                        <label for="title">หัวข้อ*</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="title"
                          :rules="[requiredValidator]"
                          v-model="item.title"
                          placeholder="Title"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="detail">รายละเอียด</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <div id="detail"></div>
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <!-- 👉 Mobile -->
                      <VCol cols="12" md="3">
                        <label for="is_publish">สถานะ*</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VSelect
                          id="is_publish"
                          v-model="item.is_publish"
                          :rules="[requiredValidator]"
                          label="Pubish"
                          :items="[
                            { title: 'Publish', value: 1 },
                            { title: 'UnPublish', value: 0 },
                          ]"
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
                        <label for="title_en">หัวข้อ</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VTextField
                          id="title_en"
                          v-model="item.title_en"
                          placeholder="Title EN"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="detail_en">รายละเอียด</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <div id="detail_en"></div>
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
