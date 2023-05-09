import axios from "@axios";
import { defineStore } from "pinia";

export const useContactUsStore = defineStore("ContactUsStore", {
  actions: {

    fetchContactUs({ id }) {
      return axios.get(`/contact-us/${id}`);
    },

    async editContactUs(dataSend) {
      return await axios.put(`/contact-us/${dataSend.id}`, dataSend);
    },
  },
});
