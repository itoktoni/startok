<template>
  <div class="space-y-2">
    <label v-if="label" class="font-label-caps text-label-caps text-on-surface-variant block">
      {{ label }}
    </label>
    <div
      class="flex flex-wrap gap-2 p-2 bg-surface border border-outline-variant rounded-lg min-h-[48px] items-center"
    >
      <div
        v-for="(tag, index) in modelValue"
        :key="index"
        class="bg-secondary-fixed text-on-secondary-fixed px-3 py-1 rounded-full flex items-center gap-1 font-label-caps text-label-caps"
      >
        {{ tag }}
        <span
          class="material-symbols-outlined text-sm cursor-pointer hover:text-error"
          @click="removeTag(index)"
        >close</span>
      </div>
      <input
        v-model="inputValue"
        class="bg-transparent border-none focus:ring-0 text-body-sm flex-1 min-w-[60px]"
        placeholder="Add..."
        type="text"
        @keydown.enter.prevent="addTag"
        @keydown.tab.prevent="addTag"
        @keydown.backspace="onBackspace"
      />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    default: '',
  },
})

const emit = defineEmits(['update:modelValue'])

const inputValue = ref('')

const addTag = () => {
  const value = inputValue.value.trim()
  if (value && !props.modelValue.includes(value)) {
    emit('update:modelValue', [...props.modelValue, value])
    inputValue.value = ''
  }
}

const removeTag = (index) => {
  const newTags = [...props.modelValue]
  newTags.splice(index, 1)
  emit('update:modelValue', newTags)
}

const onBackspace = () => {
  if (inputValue.value === '' && props.modelValue.length > 0) {
    removeTag(props.modelValue.length - 1)
  }
}
</script>
