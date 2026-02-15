<template>
  <v-container class="fill-height" fluid>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="8" md="6" lg="4">
        <v-card elevation="12" rounded="lg">
          <v-card-title class="text-h4 text-center py-6 bg-primary">
            <v-icon size="x-large" class="mr-2">mdi-check-circle</v-icon>
            <span class="text-white">Todo App</span>
          </v-card-title>

          <v-card-text class="pa-8">
            <v-tabs v-model="tab" grow>
              <v-tab value="login">Connexion</v-tab>
              <v-tab value="register">Inscription</v-tab>
            </v-tabs>

            <v-window v-model="tab" class="mt-6">
              <v-window-item value="login">
                <LoginForm @login-success="handleLoginSuccess" />
              </v-window-item>

              <v-window-item value="register">
                <RegisterForm @register-success="handleRegisterSuccess" />
              </v-window-item>
            </v-window>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import LoginForm from '../components/LoginForm.vue'
import RegisterForm from '../components/RegisterForm.vue'

const router = useRouter()
const tab = ref('login')

const handleLoginSuccess = () => {
  router.push('/dashboard')
}

const handleRegisterSuccess = () => {
  tab.value = 'login'
}
</script>

<style scoped>
.fill-height {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
