<template>
  <v-form @submit.prevent="handleRegister">
    <v-alert v-if="error" type="error" class="mb-4">
      {{ error }}
    </v-alert>

    <v-alert v-if="success" type="success" class="mb-4">
      Compte créé avec succès ! Vous pouvez maintenant vous connecter.
    </v-alert>

    <v-text-field
      v-model="name"
      label="Nom complet"
      prepend-icon="mdi-account"
      variant="outlined"
      density="comfortable"
      class="mb-4"
    />

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
      S'inscrire
    </v-btn>
  </v-form>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const emit = defineEmits(['register-success'])

const name = ref('')
const email = ref('')
const password = ref('')
const error = ref('')
const success = ref(false)
const loading = ref(false)

const handleRegister = async () => {
  error.value = ''
  success.value = false
  loading.value = true

  try {
    await axios.post('http://localhost:8000/api/register', {
      name: name.value,
      email: email.value,
      password: password.value
    })

    success.value = true

    setTimeout(() => {
      emit('register-success')
    }, 1500)
  } catch (err) {
    error.value = err.response?.data?.error || 'Une erreur est survenue'
  } finally {
    loading.value = false
  }
}
</script>
