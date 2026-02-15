<template>
  <v-form @submit.prevent="handleLogin">
    <v-alert v-if="error" type="error" class="mb-4">
      {{ error }}
    </v-alert>

    <v-text-field
      v-model="email"
      label="Email"
      type="email"
      prepend-icon="mdi-email"
      variant="outlined"
      density="comfortable"
      class="mb-4"
    />

    <v-text-field
      v-model="password"
      label="Mot de passe"
      type="password"
      prepend-icon="mdi-lock"
      variant="outlined"
      density="comfortable"
      class="mb-6"
    />

    <v-btn
      type="submit"
      color="primary"
      size="large"
      block
      :loading="loading"
    >
      Se connecter
    </v-btn>
  </v-form>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['login-success'])

const email = ref('')
const password = ref('')
const error = ref('')
const loading = ref(false)

const handleLogin = async () => {
  error.value = ''
  loading.value = true

  try {
    const response = await axios.post('http://localhost:8000/api/login', {
      email: email.value,
      password: password.value
    })

    localStorage.setItem('user', JSON.stringify(response.data))
    localStorage.setItem('token', response.data.token)

    emit('login-success')
  } catch (err) {
    error.value = 'Email ou mot de passe incorrect'
  } finally {
    loading.value = false
  }
}
</script>
