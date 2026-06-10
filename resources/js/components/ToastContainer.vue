<template>
  <Teleport to="body">
    <div class="fixed top-20 right-4 z-[100] max-w-sm w-full space-y-2">
      <TransitionGroup name="toast">
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="flex items-start gap-3 p-4 rounded-xl shadow-lg border"
          :class="getTypeClasses(toast.type)"
        >
          <span class="material-symbols-outlined text-xl shrink-0 mt-0.5" :class="getIconClass(toast.type)">
            {{ getIcon(toast.type) }}
          </span>
          <div class="flex-1 min-w-0">
            <p v-if="toast.title" class="font-body-sm font-semibold">{{ toast.title }}</p>
            <p class="font-body-sm opacity-90">{{ toast.message }}</p>
          </div>
          <button
            class="p-1 rounded-full hover:bg-black/10 transition-colors shrink-0"
            @click="remove(toast.id)"
          >
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { useToast } from '../composables/useToast'

const { toasts, remove } = useToast()

const getTypeClasses = (type) => {
  switch (type) {
    case 'success': return 'bg-green-50 border-green-200 text-green-800'
    case 'error': return 'bg-error-container border-error/30 text-on-error-container'
    case 'warning': return 'bg-orange-50 border-orange-200 text-orange-800'
    case 'info': return 'bg-blue-50 border-blue-200 text-blue-800'
    default: return 'bg-surface-container-lowest border-outline-variant text-on-surface'
  }
}

const getIconClass = (type) => {
  switch (type) {
    case 'success': return 'text-green-600'
    case 'error': return 'text-error'
    case 'warning': return 'text-orange-600'
    case 'info': return 'text-blue-600'
    default: return 'text-on-surface-variant'
  }
}

const getIcon = (type) => {
  switch (type) {
    case 'success': return 'check_circle'
    case 'error': return 'error'
    case 'warning': return 'warning'
    case 'info': return 'info'
    default: return 'info'
  }
}
</script>

<style scoped>
.toast-enter-active {
  transition: all 0.3s ease;
}
.toast-leave-active {
  transition: all 0.2s ease;
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
.toast-move {
  transition: transform 0.3s ease;
}
</style>
