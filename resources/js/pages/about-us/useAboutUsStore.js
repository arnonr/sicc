import axios from "@axios";
import { defineStore } from "pinia";

export const useAboutUsStore = defineStore("AboutUstore", {
  actions: {
    // 👉 Fetch all Slide
    fetchAboutUses(params) {
      return axios.get("/about-us", { params });
    },

    fetchAboutUs({ id }) {
      return axios.get(`/about-us/${id}`);
    },
  },
});
