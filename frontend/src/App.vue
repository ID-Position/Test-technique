<template>
  <v-app>
    <v-app-bar color="primary" elevation="2">
      <template v-slot:prepend>
        <v-icon size="large">mdi-check-circle</v-icon>
      </template>
      <v-toolbar-title class="font-weight-bold">Todo Manager</v-toolbar-title>

      <template v-slot:append v-if="isLoggedIn">
        <v-chip class="ma-2" color="white" variant="outlined">
          <v-icon start>mdi-account</v-icon>
          {{ currentUser?.name }}
        </v-chip>
        <v-btn icon @click="logout">
          <v-icon>mdi-logout</v-icon>
        </v-btn>
      </template>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>
  </v-app>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const isLoggedIn = computed(() => {
  return localStorage.getItem('user') !== null
})

const currentUser = computed(() => {
  const user = localStorage.getItem('user')
  return user ? JSON.parse(user) : null
})

const logout = () => {
  localStorage.removeItem('user')
  localStorage.removeItem('token')
  router.push('/')
}
</script>
