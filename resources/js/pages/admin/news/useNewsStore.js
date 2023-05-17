import axios from "@axios";
import { defineStore } from "pinia";

export const useNewsStore = defineStore("NewsStore", {
  actions: {
    // 👉 Fetch all Slide

    fetchNewses(params) {
      return axios.get("/news", { params });
    },

    fetchNews({ id }) {
      return axios.get(`/news/${id}`);
    },

    async addNews(dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }
      return await axios.post("/news", form_data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },

    async editNews(dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }
      form_data.append("_method", "PUT");

      return await axios.post(`/news/${dataSend.id}`, form_data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },

    deleteNews({ id }) {
      return axios.delete(`/news/${id}`);
    },
    fetchNewsTypes(params) {
      return axios.get("/news-type", { params });
    },

    fetchNewsGallery(params) {
      return axios.get("/news-gallery", { params });
    },
    deleteGallery({ id }) {
      return axios.delete(`/news-gallery/${id}`);
    },
  },
});
