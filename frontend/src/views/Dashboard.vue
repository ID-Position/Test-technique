<template>
  <v-container fluid class="pa-6">
    <v-row>
      <v-col cols="12">
        <div class="d-flex justify-space-between align-center mb-6">
          <h1 class="text-h3 font-weight-bold">Mes Tâches</h1>
          <v-btn
            color="primary"
            size="large"
            prepend-icon="mdi-plus"
            @click="openCreateDialog"
          >
            Nouvelle Tâche
          </v-btn>
        </div>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" md="3">
        <v-card elevation="2">
          <v-card-title class="bg-primary">
            <v-icon class="mr-2">mdi-filter</v-icon>
            Filtres
          </v-card-title>
          <v-card-text>
            <v-select
              v-model="statusFilter"
              :items="statusOptions"
              label="Statut"
              prepend-icon="mdi-flag"
              variant="outlined"
              density="comfortable"
              @update:model-value="applyFilters"
            />

            <v-text-field
              v-model="searchQuery"
              label="Rechercher"
              prepend-icon="mdi-magnify"
              variant="outlined"
              density="comfortable"
              clearable
              @update:model-value="applyFilters"
              class="mt-4"
            />
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" md="9">
        <TaskList
          :tasks="taskStore.tasks"
          :loading="taskStore.loading"
          @edit="openEditDialog"
          @delete="handleDelete"
          @status-change="handleStatusChange"
        />
      </v-col>
    </v-row>

    <TaskForm
      v-model="dialogOpen"
      :task="selectedTask"
      :users="taskStore.users"
      @save="handleSave"
    />
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useTaskStore } from '../store'
import TaskList from '../components/TaskList.vue'
import TaskForm from '../components/TaskForm.vue'

const taskStore = useTaskStore()
const dialogOpen = ref(false)
const selectedTask = ref(null)
const statusFilter = ref('all')
const searchQuery = ref('')

const statusOptions = [
  { title: 'Tous', value: 'all' },
  { title: 'À faire', value: 'todo' },
  { title: 'En cours', value: 'in_progress' },
  { title: 'Terminé', value: 'done' }
]

onMounted(async () => {
  await taskStore.fetchUsers()
  await taskStore.fetchTasks()
})

const openCreateDialog = () => {
  selectedTask.value = null
  dialogOpen.value = true
}

const openEditDialog = (task) => {
  selectedTask.value = task
  dialogOpen.value = true
}

const handleSave = async () => {
  dialogOpen.value = false
  await applyFilters()
}

const handleDelete = async (taskId) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
    await taskStore.deleteTask(taskId)
  }
}

const handleStatusChange = async (task, newStatus) => {
  await taskStore.updateTask(task.id, { ...task, status: newStatus })
}

const applyFilters = async () => {
  const status = statusFilter.value === 'all' ? null : statusFilter.value
  const search = searchQuery.value || null
  await taskStore.fetchTasks(status, search)
}
</script>

<style scoped>
.v-container {
  background-color: #f5f5f5;
  min-height: calc(100vh - 64px);
}
</style>
