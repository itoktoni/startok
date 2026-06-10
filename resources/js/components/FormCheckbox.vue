<template>
  <div class="flex items-center gap-4 h-12 px-4 bg-surface-container-low rounded-lg border border-outline-variant">
    <input
      type="checkbox"
      :checked="modelValue"
      class="w-5 h-5 text-primary border-outline rounded focus:ring-primary transition-all flex-shrink-0"
      @change="$emit('update:modelValue', $event.target.checked)"
    />
    <label class="font-body-sm font-semibold text-on-surface">{{ label }}</label>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: Boolean,
    default: false,
  },
  label: {
    type: String,
    default: '',
  },
})

defineEmits(['update:modelValue'])
</script>
