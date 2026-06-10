<template>
  <div class="space-y-2">
    <label v-if="label" class="font-label-caps text-label-caps text-on-surface-variant block">
      {{ label }}
    </label>
    <div class="flex items-center">
      <span
        v-if="prefix"
        class="flex items-center justify-center h-12 px-3 bg-surface-container border border-outline-variant border-r-0 rounded-l-lg text-on-surface-variant font-body-sm shrink-0"
      >
        {{ prefix }}
      </span>
      <div class="relative flex-1">
        <input
          ref="inputRef"
          :type="type"
          :placeholder="placeholder"
          :value="modelValue"
          :class="[
            'w-full h-12 bg-surface px-4 border border-outline-variant focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition-all text-on-surface placeholder:text-outline',
            mono ? 'font-data-mono text-data-mono' : 'font-body-lg text-body-lg',
            prefix ? 'rounded-l-none' : 'rounded-l-lg',
            suffixIcon ? 'rounded-r-none pr-12' : 'rounded-r-lg',
          ]"
          @input="$emit('update:modelValue', $event.target.value)"
        />
        <button
          v-if="suffixIcon"
          type="button"
          class="absolute right-0 top-0 h-12 w-12 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors rounded-r-lg"
          @click="$emit('suffix-click')"
        >
          <span class="material-symbols-outlined">{{ suffixIcon }}</span>
        </button>
      </div>
      <button
        v-if="suffix"
        type="button"
        class="btn-primary h-12 px-4 rounded-l-none rounded-r-lg shrink-0"
        @click="$emit('suffix-click')"
      >
        {{ suffix }}
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: [String, Number],
    default: '',
  },
  label: {
    type: String,
    default: '',
  },
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: '',
  },
  mono: {
    type: Boolean,
    default: false,
  },
  prefix: {
    type: String,
    default: '',
  },
  suffix: {
    type: String,
    default: '',
  },
  suffixIcon: {
    type: String,
    default: '',
  },
})

defineEmits(['update:modelValue', 'suffix-click'])
</script>
