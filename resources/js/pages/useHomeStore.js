import axios from "@axios";
import { defineStore } from "pinia";

export const useHomeStore = defineStore("HomeStore", {
  actions: {
    // 👉 Fetch all Slide
    fetchBanners(params) {
      return axios.get("/banner", { params });
    },
  },
});
