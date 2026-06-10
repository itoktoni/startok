import { ref } from 'vue'

const toasts = ref([])
let nextId = 0

export function useToast() {
  const show = (options) => {
    const id = nextId++
    const duration = options.duration ?? (options.type === 'error' ? 0 : 3000)
    const toast = {
      id,
      type: options.type || 'success',
      title: options.title || '',
      message: options.message || '',
      duration,
    }
    toasts.value.push(toast)

    if (duration > 0) {
      setTimeout(() => remove(id), duration)
    }

    return id
  }

  const success = (message, title = 'Success') => show({ type: 'success', title, message })
  const error = (message, title = 'Error') => show({ type: 'error', title, message })
  const warning = (message, title = 'Warning') => show({ type: 'warning', title, message })
  const info = (message, title = 'Info') => show({ type: 'info', title, message })

  const remove = (id) => {
    const index = toasts.value.findIndex((t) => t.id === id)
    if (index !== -1) toasts.value.splice(index, 1)
  }

  return { toasts, show, success, error, warning, info, remove }
}
