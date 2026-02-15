<template>
  <v-dialog :model-value="modelValue" @update:model-value="$emit('update:modelValue', $event)" max-width="600">
    <v-card>
      <v-card-title class="bg-primary text-h5 pa-4">
        <v-icon class="mr-2">mdi-clipboard-edit</v-icon>
        {{ isEdit ? 'Modifier la tâche' : 'Nouvelle tâche' }}
      </v-card-title>

      <v-card-text class="pa-6">
        <v-form @submit.prevent="handleSubmit">
          <v-alert v-if="error" type="error" class="mb-4">
            {{ error }}
          </v-alert>

          <v-text-field
            v-model="formData.title"
            label="Titre *"
            variant="outlined"
            density="comfortable"
            prepend-icon="mdi-format-title"
            class="mb-4"
          />

          <v-textarea
            v-model="formData.description"
            label="Description"
            variant="outlined"
            rows="4"
            prepend-icon="mdi-text"
            class="mb-4"
          />

          <v-select
            v-model="formData.status"
            :items="statusOptions"
            label="Statut"
            variant="outlined"
            density="comfortable"
            prepend-icon="mdi-flag"
            class="mb-4"
          />

          <v-select
            v-model="formData.assignedTo"
            :items="userOptions"
            label="Assigné à *"
            variant="outlined"
            density="comfortable"
            prepend-icon="mdi-account"
          />
        </v-form>
      </v-card-text>

      <v-card-actions class="pa-4">
        <v-spacer />
        <v-btn
          variant="text"
          @click="$emit('update:modelValue', false)"
        >
          Annuler
        </v-btn>
        <v-btn
          color="primary"
          variant="elevated"
          :loading="loading"
          @click="handleSubmit"
        >
          {{ isEdit ? 'Modifier' : 'Créer' }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { useTaskStore } from '../store'

const props = defineProps({
  modelValue: Boolean,
  task: {
    type: Object,
    default: null
  },
  users: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update:modelValue', 'save'])

const taskStore = useTaskStore()
const loading = ref(false)
const error = ref('')

const formData = ref({
  title: '',
  description: '',
  status: 'todo',
  assignedTo: null
})

const statusOptions = [
  { title: 'À faire', value: 'todo' },
  { title: 'En cours', value: 'in_progress' },
  { title: 'Terminé', value: 'done' }
]

const userOptions = computed(() => {
  return props.users.map(user => ({
    title: user.name,
    value: user.id
  }))
})

const isEdit = computed(() => props.task !== null)

watch(() => props.task, (newTask) => {
  if (newTask) {
    formData.value = {
      title: newTask.title,
      description: newTask.description || '',
      status: newTask.status,
      assignedTo: newTask.assignedTo?.id
    }
  } else {
    formData.value = {
      title: '',
      description: '',
      status: 'todo',
      assignedTo: null
    }
  }
}, { immediate: true })

const handleSubmit = async () => {
  error.value = ''
  loading.value = true

  try {
    if (isEdit.value) {
      await taskStore.updateTask(props.task.id, formData.value)
    } else {
      await taskStore.createTask(formData.value)
    }

    emit('save')
    emit('update:modelValue', false)
  } catch (err) {
    error.value = 'Une erreur est survenue'
  } finally {
    loading.value = false
  }
}
</script>
