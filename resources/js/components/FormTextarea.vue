<template>
  <div class="space-y-2">
    <label v-if="label" class="font-label-caps text-label-caps text-on-surface-variant block">
      {{ label }}
    </label>
    <textarea
      :placeholder="placeholder"
      :rows="rows"
      :value="modelValue"
      class="w-full bg-surface px-4 py-3 rounded-lg border border-outline-variant focus:outline-none focus:ring-2 focus:ring-secondary transition-all font-body-lg text-on-surface resize-none placeholder:text-outline"
      @input="$emit('update:modelValue', $event.target.value)"
    ></textarea>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  label: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: '',
  },
  rows: {
    type: Number,
    default: 3,
  },
})

defineEmits(['update:modelValue'])
</script>
