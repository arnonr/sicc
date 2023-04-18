<script setup>
import iconFacebook from "@images/icons/home/fb.png";
const { t } = useI18n();
</script>
<template>
  <VRow
    class="h-100 d-flex pt-6 pb-6 pl-6 pr-6 align-center justify-center bg-top-footer-background"
  >
    <VCol cols="12" sm="6" md="4">
      <h3>ศูนย์เครื่องมือวิทยาศาสตร์และคอมพิวเตอร์สมรรถนะสูง</h3>
      <h3>คณะวิทยาศาสตร์ประยุกต์</h3>
      <div class="mt-1" style="font-size: 0.82em">
        Scientific Instrument and High Performance Computing Center : SICC
        <br />
        Faculty of Applied Science
      </div>

      <div class="mt-3" style="font-size: 0.82em">
        {{ t("1518 Pracharat 1 Road, Wongsawang,") }}
        <br />
        {{ t("Bangsue, Bangkok 10800") }}
      </div>

      <div class="mt-3" style="font-size: 0.82em">
        {{ t("Tel. : +66 2 555-2000 to 4225") }}
      </div>
      <div class="mt-3" style="font-size: 0.82em">
        {{ t("Fax : +66 2 587-4350") }}
      </div>

      <div class="mt-1" style="font-size: 0.82em">
        <a
          href="https://www.facebook.com/profile.php?id=100066870041599"
          target="_blank"
        >
          <div justify="center" class="mt-2 align-center justify-center">
            <!-- @click="window.location = 'https://www.facebook.com/profile.php?id=100066870041599'"> -->
            <VImg :src="iconFacebook" width="25" class="d-inline-block" />
            <div
              class="ml-1 bg-primary pl-2 pr-2 mt-1 d-inline-block"
              style="
                vertical-align: top;
                color: #000 !important;
                border-radius: 1em;
              "
            >
              {{
                t(
                  "Scientific Instrument and High Performance Computing Center"
                )
              }}-SICC
            </div>
          </div>
        </a>
      </div>
    </VCol>
    <VCol cols="12" sm="6" md="4">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3874.2883104876155!2d100.51270202510852!3d13.821718495670929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30e29b9c7c312857%3A0x5d07935904fe2fb8!2sFaculty%20of%20Applied%20Sciences%20(KMUTNB)!5e0!3m2!1sen!2sth!4v1681663973388!5m2!1sen!2sth"
        width="100%"
        height="300"
        style="border: 0"
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
      ></iframe>
    </VCol>
  </VRow>
</template>
