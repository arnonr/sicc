import axios from "@axios";
import { defineStore } from "pinia";

export const useBannerStore = defineStore("BannerStore", {
  actions: {
    // 👉 Fetch all Slide
    fetchBanners(params) {
      return axios.get("/banner", { params });
    },

    fetchBanner({ id }) {
      return axios.get(`/banner/${id}`);
    },

    async addBanner(dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }
      return await axios.post("/banner", form_data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },

    async editBanner(dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }
      form_data.append("_method", "PUT");

      return await axios.post(`/banner/${dataSend.id}`, form_data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },

    deleteBanner({ id }) {
      return axios.delete(`/banner/${id}`);
    },

    editLevelBanner(dataSend) {
      return axios.put(`/banner/level/${dataSend.id}`, dataSend);
    },
  },
});
