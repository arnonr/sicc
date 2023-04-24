import axios from "@axios";
import { defineStore } from "pinia";

export const useNewsTypeStore = defineStore("NewsTypeStore", {
  actions: {
    // 👉 Fetch all Slide
    fetchNewsTypes(params) {
      return axios.get("/news-type", { params });
    },

    fetchNewsType({ id }) {
      return axios.get(`/news-type/${id}`);
    },

    async addNewsType(dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }
      return await axios.post("/news-type", form_data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },

    async editNewsType(dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }
      form_data.append("_method", "PUT");

      return await axios.post(`/news-type/${dataSend.id}`, form_data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },

    deleteNewsType({ id }) {
      return axios.delete(`/news-type/${id}`);
    },
  },
});
