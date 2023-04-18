import axios from '@axios'
import { defineStore } from 'pinia'

export const useSlideStore = defineStore('SlideStore', {
  actions: {
    // 👉 Fetch all Slide
    fetchSlides(params) {
      return axios.get('/slide', { params })
    },
  },
})
