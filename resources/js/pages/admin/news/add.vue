<script setup>
import { requiredValidator } from "@validators";

import { useRouter } from "vue-router";
import { useNewsStore } from "./useNewsStore";

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

import dayjs from "dayjs";
import "dayjs/locale/th";
import buddhistEra from "dayjs/plugin/buddhistEra";

import "froala-editor/css/froala_editor.pkgd.min.css";
import FroalaEditor from "froala-editor/js/froala_editor.pkgd.min.js";

import Uppy from "@uppy/core";
import { Dashboard } from "@uppy/vue";

// Don't forget the CSS: core and UI components + plugins you are using
import "@uppy/core/dist/style.css";
import "@uppy/dashboard/dist/style.css";
import XHRUpload from "@uppy/xhr-upload";

// Uppy
const r = (Math.random() + 1).toString(36).substring(7);

let baseUrl = "http://143.198.208.110:8115/api";
if (location.hostname === "localhost" || location.hostname === "127.0.0.1") {
  baseUrl = "http://localhost:8115/api";
}

const uppy = new Uppy({
  meta: { news_id: null, secret_key: r, news_gallery_id: null },
  debug: true,
  // autoProceed: true,
  restrictions: {
    // maxFileSize: this.maxFileSizeInBytes,
    // minNumberOfFiles: 1,
    // maxNumberOfFiles: 1,
    allowedFileTypes: [
      "image/*",
      "video/*",
      // "application/msword",
      // "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
      // "application/pdf",
    ],
  },
}).use(XHRUpload, {
  endpoint: baseUrl + "/news-gallery/uppy",
  fieldName: "file",
});

// complete คือ upload เสร็จ
uppy.on("complete", (result) => {
  // console.log("successful files:", result.successful);
  // console.log("failed files:", result.failed);
  // console.log("FREEDOnm");
});

uppy.on("upload-success", (file, response) => {
  uppy.setFileMeta(file.id, {
    url: response.body.link,
    news_id: response.body.news_id,
    news_gallery_id: response.body.news_gallery_id,
  });
});

uppy.on("file-removed", (file, reason) => {
  // console.log(file, reason);
  if (file.meta.news_gallery_id != null) {
    newsStore
      .deleteGallery({
        id: file.meta.news_gallery_id,
      })
      .then((response) => {
        if (response.data.message == "success") {
          console.log("success");
        } else {
          console.log("error");
        }
      })
      .catch((error) => {
        console.error(error);
        isOverlay.value = false;
      });
  }
});

const router = useRouter();

dayjs.extend(buddhistEra);

const newsStore = useNewsStore();

const item = ref({
  id: null,
  title: "",
  title_en: "",
  news_type_id: "",
  news_file: [],
  news_en_file: [],
  detail: "",
  detail_en: "",
  is_publish: 1,
  created_at: dayjs(),
});
const isOverlay = ref(false);
const isFormValid = ref(false);
const refForm = ref();

onMounted(() => {
  initFroala();
});

const selectOptions = ref({
  news_types: [],
});

const fetchNewsTypes = () => {
  newsStore
    .fetchNewsTypes({
      is_publish: 1,
    })
    .then((response) => {
      if (response.data.message == "success") {
        selectOptions.value.news_types = response.data.data.map((d) => {
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
fetchNewsTypes();

const onSubmit = () => {
  isOverlay.value = true;
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      newsStore
        .addNews({
          ...item.value,
          secret_key: r,
          news_file:
            item.value.news_file != null ? item.value.news_file[0] : null,

          news_en_file:
            item.value.news_en_file != null ? item.value.news_en_file[0] : null,

          detail: item.value.detail.replace(
            '<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>',
            ""
          ),
          detail_en: item.value.detail_en.replace(
            '<p data-f-id="pbf" style="text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;">Powered by <a href="https://www.froala.com/wysiwyg-editor?pb=1" title="Froala Editor">Froala Editor</a></p>',
            ""
          ),
          created_at:
            item.value.created_at != ""
              ? dayjs(item.value.created_at).format("YYYY-MM-DD")
              : dayjs().format("YYYY-MM-DD"),
        })
        .then((response) => {
          if (response.data.message == "success") {
            localStorage.setItem("added", 1);
            console.log("News Add Success");
            nextTick(() => {
              router.push({
                path: "/admin/news/view/" + response.data.data.id,
              });
            });
          } else {
            isOverlay.value = false;
            console.log("error");
          }
        })
        .catch((error) => {
          console.error(error);
          //   isOverlay.value = false;
        });
    }
    isOverlay.value = false;
  });
};

const format = (date) => {
  const day = dayjs(date).locale("th").format("DD");
  const month = dayjs(date).locale("th").format("MMM");
  const year = date.getFullYear() + 543;

  return `${day} ${month} ${year}`;
};

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
<style lang="scss">
.uppy-Dashboard-inner {
  width: 100% !important;
}
</style>
<template>
  <main class="layout-page-content mb-6 mt-6">
    <section>
      <!-- Search -->
      <VRow>
        <VCol>
          <h2>ADD News</h2>
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
                        <label for="news_type_id">ประเภทข่าว*</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VSelect
                          id="news_type_id"
                          v-model="item.news_type_id"
                          label="News Type"
                          :items="selectOptions.news_types"
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="title">หัวข้อข่าว*</label>
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
                        <label for="news_file">รูปปกข่าว*</label>
                      </VCol>
                      <VCol cols="12" md="9">
                        <VFileInput
                          label="Upload Picture"
                          :rules="[requiredValidator]"
                          id="news_file"
                          v-model="item.news_file"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="news_file">แกลลอรี</label>
                      </VCol>
                      <VCol cols="12" md="9">
                        <Dashboard
                          :uppy="uppy"
                          ref="dash"
                          style="width: 100%"
                          :props="{
                            doneButtonHandler: null,
                            hideCancelButton: true,
                            showRemoveButtonAfterComplete: true,
                          }"
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="detail">เนื้อหาข่าว</label>
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
                    <VRow no-gutters>
                      <!-- 👉 Mobile -->
                      <VCol cols="12" md="3">
                        <label for="is_publish">วันที่ลงข่าว*</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VueDatePicker
                          v-model="item.created_at"
                          :enable-time-picker="false"
                          locale="th"
                          auto-apply
                          :format="format"
                          :rules="[requiredValidator]"
                        >
                          <template #year-overlay-value="{ text }">
                            {{ parseInt(text) + 543 }}
                          </template>
                          <template #year="{ year }">
                            {{ year + 543 }}
                          </template>
                        </VueDatePicker>
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
                        <label for="title_en">หัวข้อข่าว</label>
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
                        <label for="news_file">รูปปกข่าว</label>
                      </VCol>

                      <VCol cols="12" md="9">
                        <VFileInput
                          label="Upload Picture"
                          id="news_en_file"
                          v-model="item.news_en_file"
                          persistent-placeholder
                        />
                      </VCol>
                    </VRow>
                  </VCol>

                  <VCol cols="12">
                    <VRow no-gutters>
                      <VCol cols="12" md="3">
                        <label for="detail_en">เนื้อหาข่าว</label>
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
