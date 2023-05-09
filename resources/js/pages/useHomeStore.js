import axios from "@axios";
import { defineStore } from "pinia";

export const useHomeStore = defineStore("HomeStore", {
  actions: {
    // 👉 Fetch all Slide
    fetchBanners(params) {
      return axios.get("/banner", { params });
    },
    fetchNews(params) {
      return axios.get("/news", { params });
    },
    fetchNewsTypes(params) {
      return axios.get("/news-type", { params });
    },
    
  },
});
