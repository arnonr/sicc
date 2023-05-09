import axios from "@axios";
import { defineStore } from "pinia";

export const useAboutUsStore = defineStore("AboutUsStore", {
  actions: {
    // 👉 Fetch all Slide

    fetchAboutUses(params) {
      return axios.get("/about-us", { params });
    },

    fetchAboutUs({ id }) {
      return axios.get(`/about-us/${id}`);
    },

    async addAboutUs(dataSend) {
      return await axios.post("/about-us", dataSend);
    },

    async editAboutUs(dataSend) {
      return await axios.put(`/about-us/${dataSend.id}`, dataSend);
    },

    deleteAboutUs({ id }) {
      return axios.delete(`/about-us/${id}`);
    },

    editLevelAboutUs(dataSend) {
      return axios.put(`/about-us/level/${dataSend.id}`, dataSend);
    },
  },
});
