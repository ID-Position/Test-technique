import { defineStore } from 'pinia'
import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

export const useTaskStore = defineStore('tasks', {
  state: () => ({
    tasks: [],
    users: [],
    loading: false
  }),

  actions: {
    async fetchTasks(status = null, search = null) {
      this.loading = true
      try {
        let url = `${API_URL}/tasks`
        const params = new URLSearchParams()
        if (status) params.append('status', status)
        if (search) params.append('search', search)

        if (params.toString()) {
          url += '?' + params.toString()
        }

        const response = await axios.get(url)
        this.tasks = response.data
      } catch (error) {
        console.error('Error fetching tasks:', error)
      } finally {
        this.loading = false
      }
    },

    async fetchUsers() {
      try {
        const response = await axios.get(`${API_URL}/users`)
        this.users = response.data
      } catch (error) {
        console.error('Error fetching users:', error)
      }
    },

    async createTask(taskData) {
      try {
        const response = await axios.post(`${API_URL}/tasks`, taskData)
        this.tasks.push(response.data)
        return response.data
      } catch (error) {
        console.error('Error creating task:', error)
        throw error
      }
    },

    async updateTask(id, taskData) {
      try {
        const response = await axios.put(`${API_URL}/tasks/${id}`, taskData)
        const index = this.tasks.findIndex(t => t.id === id)
        if (index !== -1) {
          this.tasks[index] = response.data
        }
        return response.data
      } catch (error) {
        console.error('Error updating task:', error)
        throw error
      }
    },

    async deleteTask(id) {
      try {
        await axios.delete(`${API_URL}/tasks/${id}`)
        this.tasks = this.tasks.filter(t => t.id !== id)
      } catch (error) {
        console.error('Error deleting task:', error)
        throw error
      }
    }
  }
})
