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
    fetchNewsTypes(params) {
      return axios.get("/news-type", { params });
    },
  },
});
