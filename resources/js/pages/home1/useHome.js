import axios from '@axios'
import { defineStore } from 'pinia'

export const useProjectStore = defineStore('ProjectStore', {
  actions: {
    // 👉 Fetch all project
    fetchProjects(params) {
      return axios.get('/dashboard/analytics/projects', { params })
    },
  },
})
