<template>
  <div>
    <label v-if="label" class="font-label-caps text-label-caps text-on-surface-variant block mb-4">
      {{ label }}
    </label>
    <div
      class="group relative aspect-video sm:aspect-square w-full bg-surface border-2 border-dashed border-outline-variant rounded-xl flex flex-col items-center justify-center gap-3 cursor-pointer hover:border-secondary hover:bg-secondary-fixed/5 transition-all overflow-hidden"
      @click="triggerUpload"
      @dragover.prevent
      @drop.prevent="onDrop"
    >
      <span
        class="material-symbols-outlined text-4xl text-outline-variant group-hover:text-secondary transition-colors"
      >cloud_upload</span>
      <div class="text-center">
        <p class="font-body-sm font-medium text-on-surface">{{ title }}</p>
        <p class="text-[10px] uppercase tracking-tighter text-on-surface-variant mt-1">{{ subtitle }}</p>
      </div>
      <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>
    <div v-if="hint" class="mt-4 p-3 bg-tertiary-fixed/20 rounded-lg border border-tertiary-fixed/30 flex gap-3">
      <span class="material-symbols-outlined text-tertiary text-sm mt-0.5">info</span>
      <p class="text-[11px] text-on-tertiary-fixed-variant leading-tight">{{ hint }}</p>
    </div>
    <input
      ref="fileInput"
      type="file"
      class="hidden"
      :accept="accept"
      @change="onFileChange"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  modelValue: {
    type: [File, null],
    default: null,
  },
  label: {
    type: String,
    default: '',
  },
  title: {
    type: String,
    default: 'Upload Image',
  },
  subtitle: {
    type: String,
    default: 'PNG, JPG up to 10MB',
  },
  hint: {
    type: String,
    default: '',
  },
  accept: {
    type: String,
    default: 'image/*',
  },
})

const emit = defineEmits(['update:modelValue'])
const fileInput = ref(null)

const triggerUpload = () => {
  fileInput.value?.click()
}

const onFileChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    emit('update:modelValue', file)
  }
}

const onDrop = (e) => {
  const file = e.dataTransfer.files[0]
  if (file) {
    emit('update:modelValue', file)
  }
}
</script>
