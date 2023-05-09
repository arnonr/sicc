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
      return await axios.post("/news-type", dataSend);
    },

    async editNewsType(dataSend) {
      return await axios.put(`/news-type/${dataSend.id}`, dataSend);
    },

    deleteNewsType({ id }) {
      return axios.delete(`/news-type/${id}`);
    },
  },
});
