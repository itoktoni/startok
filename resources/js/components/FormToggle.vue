<template>
  <div
    class="flex items-center justify-between h-12 px-4 bg-surface-container-low rounded-lg border border-outline-variant cursor-pointer select-none"
    @click="$emit('update:modelValue', !modelValue)"
  >
    <span class="font-body-sm font-semibold text-on-surface">{{ label }}</span>
    <div class="relative flex-shrink-0 w-11 h-6 rounded-full transition-colors duration-200" :class="modelValue ? 'bg-primary' : 'bg-outline-variant'">
      <div
        class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-md transition-all duration-200"
        :class="modelValue ? 'left-[22px]' : 'left-0.5'"
      ></div>
    </div>
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
