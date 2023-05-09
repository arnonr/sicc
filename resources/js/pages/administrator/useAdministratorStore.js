import axios from "@axios";
import { defineStore } from "pinia";

export const useAdministratorStore = defineStore("AdministratorStore", {
  actions: {
    // 👉 Fetch all Slide
    fetchAdministrators(params) {
      return axios.get("/team", { params });
    },
    fetchAdministrator({ id }) {
      return axios.get(`/team/${id}`);
    },
    fetchDepartments(params) {
      return axios.get("/department", { params });
    },
  },
});
