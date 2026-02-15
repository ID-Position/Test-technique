<template>
  <div>
    <v-progress-linear v-if="loading" indeterminate color="primary" class="mb-4" />

    <div v-if="!loading && tasks.length === 0" class="text-center py-12">
      <v-icon size="64" color="grey-lighten-1">mdi-clipboard-text-off</v-icon>
      <p class="text-h6 text-grey mt-4">Aucune tâche à afficher</p>
    </div>

    <v-row v-else>
      <v-col
        v-for="task in tasks"
        :key="task.id"
        cols="12"
        md="6"
        lg="4"
      >
        <TaskCard
          :task="task"
          @edit="$emit('edit', task)"
          @delete="$emit('delete', task.id)"
          @status-change="$emit('status-change', task, $event)"
        />
      </v-col>
    </v-row>
  </div>
</template>

<script setup>
import TaskCard from './TaskCard.vue'

defineProps({
  tasks: {
    type: Array,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  }
})

defineEmits(['edit', 'delete', 'status-change'])
</script>
