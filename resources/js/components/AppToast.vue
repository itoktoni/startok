<template>
  <Teleport to="body">
    <Transition name="toast">
      <div
        v-if="visible"
        class="fixed top-20 right-4 z-[100] max-w-sm w-full"
      >
        <div
          class="flex items-start gap-3 p-4 rounded-xl shadow-lg border"
          :class="typeClasses"
        >
          <span class="material-symbols-outlined text-xl shrink-0 mt-0.5" :class="iconClass">{{ icon }}</span>
          <div class="flex-1 min-w-0">
            <p v-if="title" class="font-body-sm font-semibold">{{ title }}</p>
            <p class="font-body-sm opacity-90">{{ message }}</p>
          </div>
          <button
            class="p-1 rounded-full hover:bg-black/10 transition-colors shrink-0"
            @click="close"
          >
            <span class="material-symbols-outlined text-lg">close</span>
          </button>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { ref, computed, onUnmounted, watch } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'success',
    validator: (v) => ['success', 'error', 'warning', 'info'].includes(v),
  },
  title: {
    type: String,
    default: '',
  },
  message: {
    type: String,
    default: '',
  },
  duration: {
    type: Number,
    default: 3000,
  },
})

const emit = defineEmits(['close'])

const visible = ref(false)
let timer = null

const typeClasses = computed(() => {
  switch (props.type) {
    case 'success':
      return 'bg-green-50 border-green-200 text-green-800'
    case 'error':
      return 'bg-error-container border-error/30 text-on-error-container'
    case 'warning':
      return 'bg-orange-50 border-orange-200 text-orange-800'
    case 'info':
      return 'bg-blue-50 border-blue-200 text-blue-800'
    default:
      return 'bg-surface-container-lowest border-outline-variant text-on-surface'
  }
})

const iconClass = computed(() => {
  switch (props.type) {
    case 'success': return 'text-green-600'
    case 'error': return 'text-error'
    case 'warning': return 'text-orange-600'
    case 'info': return 'text-blue-600'
    default: return 'text-on-surface-variant'
  }
})

const icon = computed(() => {
  switch (props.type) {
    case 'success': return 'check_circle'
    case 'error': return 'error'
    case 'warning': return 'warning'
    case 'info': return 'info'
    default: return 'info'
  }
})

const show = () => {
  visible.value = true
  if (props.type !== 'error' && props.duration > 0) {
    timer = setTimeout(() => {
      close()
    }, props.duration)
  }
}

const close = () => {
  visible.value = false
  if (timer) {
    clearTimeout(timer)
    timer = null
  }
  emit('close')
}

watch(() => props.message, (newVal) => {
  if (newVal) show()
}, { immediate: true })

onUnmounted(() => {
  if (timer) clearTimeout(timer)
})

defineExpose({ show, close })
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
</style>
