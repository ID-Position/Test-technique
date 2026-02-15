<template>
  <v-card elevation="3" class="task-card" hover>
    <v-card-title class="d-flex justify-space-between align-center">
      <span class="text-truncate">{{ task.title }}</span>
      <v-chip
        :color="statusColor"
        size="small"
        variant="flat"
      >
        {{ statusLabel }}
      </v-chip>
    </v-card-title>

    <v-card-text>
      <div v-html="task.description" class="task-description mb-4"></div>

      <div class="d-flex align-center mt-4">
        <v-avatar color="secondary" size="32">
          <span class="text-caption text-white">
            {{ task.assignedTo?.name?.charAt(0).toUpperCase() }}
          </span>
        </v-avatar>
        <span class="ml-2 text-body-2">{{ task.assignedTo?.name }}</span>
      </div>

      <div class="text-caption text-grey mt-2">
        Créée le {{ formatDate(task.createdAt) }}
      </div>
    </v-card-text>

    <v-card-actions>
      <v-select
        :model-value="task.status"
        :items="statusOptions"
        density="compact"
        variant="outlined"
        hide-details
        @update:model-value="$emit('status-change', $event)"
        class="status-select"
      />

      <v-spacer />

      <v-btn
        icon="mdi-pencil"
        size="small"
        variant="text"
        color="primary"
        @click="$emit('edit')"
      />

      <v-btn
        icon="mdi-delete"
        size="small"
        variant="text"
        color="error"
        @click="$emit('delete')"
      />
    </v-card-actions>
  </v-card>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  task: {
    type: Object,
    required: true
  }
})

defineEmits(['edit', 'delete', 'status-change'])

const statusOptions = [
  { title: 'À faire', value: 'todo' },
  { title: 'En cours', value: 'in_progress' },
  { title: 'Terminé', value: 'done' }
]

const statusColor = computed(() => {
  const colors = {
    todo: 'info',
    in_progress: 'warning',
    done: 'success'
  }
  return colors[props.task.status] || 'grey'
})

const statusLabel = computed(() => {
  const labels = {
    todo: 'À faire',
    in_progress: 'En cours',
    done: 'Terminé'
  }
  return labels[props.task.status] || props.task.status
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}
</script>

<style scoped>
.task-card {
  transition: transform 0.2s;
}

.task-card:hover {
  transform: translateY(-4px);
}

.task-description {
  min-height: 60px;
  max-height: 120px;
  overflow: hidden;
  text-overflow: ellipsis;
}

.status-select {
  max-width: 140px;
}
</style>
