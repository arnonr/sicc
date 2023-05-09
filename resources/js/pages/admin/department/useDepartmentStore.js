import axios from "@axios";
import { defineStore } from "pinia";

export const useDepartmentStore = defineStore("DepartmentStore", {
  actions: {
    // 👉 Fetch all Slide

    fetchDepartments(params) {
      return axios.get("/department", { params });
    },

    fetchDepartment({ id }) {
      return axios.get(`/department/${id}`);
    },

    async addDepartment(dataSend) {
      return await axios.post("/department", dataSend);
    },

    async editDepartment(dataSend) {
      return await axios.put(`/department/${dataSend.id}`, dataSend);
    },

    deleteDepartment({ id }) {
      return axios.delete(`/department/${id}`);
    },

    editLevelDepartment(dataSend) {
      return axios.put(`/department/level/${dataSend.id}`, dataSend);
    },
  },
});
