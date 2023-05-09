import axios from "@axios";
import { defineStore } from "pinia";

export const useTeamStore = defineStore("TeamStore", {
  actions: {
    // 👉 Fetch all Slide

    fetchDepartments(params) {
      return axios.get("/department", { params });
    },

    fetchTeams(params) {
      return axios.get("/team", { params });
    },

    fetchTeam({ id }) {
      return axios.get(`/team/${id}`);
    },

    async addTeam(dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }
      return await axios.post("/team", form_data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },

    async editTeam(dataSend) {
      var form_data = new FormData();

      for (var key in dataSend) {
        form_data.append(key, dataSend[key]);
      }
      form_data.append("_method", "PUT");

      return await axios.post(`/team/${dataSend.id}`, form_data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
    },

    deleteTeam({ id }) {
      return axios.delete(`/team/${id}`);
    },

    editLevelTeam(dataSend) {
      return axios.put(`/team/level/${dataSend.id}`, dataSend);
    },
  },
});
