import axios from "@axios";
import { defineStore } from "pinia";

export const useContactUsStore = defineStore("ContactUsStore", {
  actions: {
    // 👉 Fetch all Slide
    fetchContactUses(params) {
      return axios.get("/contact-us", { params });
    },
  },
});
