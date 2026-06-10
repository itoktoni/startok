<template>
  <div ref="wrapperRef" class="space-y-2 tom-select-wrapper">
    <label v-if="label" class="font-label-caps text-label-caps text-on-surface-variant block">
      {{ label }}
    </label>
    <select
      ref="selectRef"
      :multiple="maxItems === null || maxItems !== 1"
      :value="modelValue"
      @change="handleChange"
    >
      <option v-if="placeholder && (maxItems === 1 || maxItems === undefined)" value="" disabled>{{ placeholder }}</option>
      <option
        v-for="option in options"
        :key="option.value"
        :value="option.value"
      >
        {{ option.label }}
      </option>
    </select>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, nextTick } from 'vue'
import TomSelect from 'tom-select'

const props = defineProps({
  modelValue: {
    type: [String, Number, Array],
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
  options: {
    type: Array,
    default: () => [],
  },
  settings: {
    type: Object,
    default: () => ({}),
  },
  create: {
    type: Boolean,
    default: false,
  },
  maxItems: {
    type: Number,
    default: 1,
    validator: (val) => val === null || val >= 1,
  },
})

const emit = defineEmits(['update:modelValue'])

const selectRef = ref(null)
const wrapperRef = ref(null)
let tomSelectInstance = null

const handleChange = () => {}

onMounted(async () => {
  await nextTick()
  if (selectRef.value) {
    tomSelectInstance = new TomSelect(selectRef.value, {
      create: props.create,
      maxItems: props.maxItems,
      placeholder: props.placeholder,
      allowEmptyOption: true,
      selectOnTab: true,
      openOnFocus: true,
      persist: false,
      hideSelected: true,
      dropdownParent: wrapperRef.value,
      closeAfterSelect: props.maxItems === 1,
      onChange(value) {
        emit('update:modelValue', value)
      },
      ...props.settings,
    })

    if (props.modelValue) {
      tomSelectInstance.setValue(props.modelValue, true)
    }
  }
})

onUnmounted(() => {
  if (tomSelectInstance) {
    tomSelectInstance.destroy()
    tomSelectInstance = null
  }
})

watch(() => props.modelValue, (newVal) => {
  if (tomSelectInstance) {
    const current = tomSelectInstance.getValue()
    if (Array.isArray(newVal)) {
      if (JSON.stringify(current) !== JSON.stringify(newVal)) {
        tomSelectInstance.setValue(newVal, true)
      }
    } else if (current !== newVal) {
      tomSelectInstance.setValue(newVal, true)
    }
  }
})

watch(() => props.options, () => {
  if (tomSelectInstance) {
    tomSelectInstance.clearOptions()
    props.options.forEach(opt => {
      tomSelectInstance.addOption(opt)
    })
    tomSelectInstance.refreshOptions(false)
  }
}, { deep: true })
</script>

<style>
.tom-select-wrapper {
  position: relative;
}

.tom-select-wrapper .ts-wrapper {
  width: 100%;
  position: static;
}

.tom-select-wrapper .ts-wrapper .ts-dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  margin-top: 4px;
  z-index: 9999;
}

.tom-select-wrapper .ts-control {
  width: 100%;
  min-height: 48px !important;
  height: 48px !important;
  padding: 0 16px !important;
  background-color: #f7f9fb !important;
  border: 1px solid #c4c5d5 !important;
  border-radius: 0.5rem !important;
  font-family: 'Inter', sans-serif !important;
  font-size: 16px !important;
  line-height: 24px !important;
  color: #191c1e !important;
  box-shadow: none !important;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 4px;
}

.tom-select-wrapper .ts-control:focus-within,
.tom-select-wrapper .ts-wrapper.focus .ts-control {
  border-color: #0058be !important;
  box-shadow: 0 0 0 2px rgba(0, 88, 190, 0.2) !important;
  outline: none !important;
}

.tom-select-wrapper .ts-wrapper.single .ts-control {
  display: flex;
  align-items: center;
}

.tom-select-wrapper .ts-control > input {
  font-family: 'Inter', sans-serif !important;
  font-size: 16px !important;
  color: #191c1e !important;
  height: 46px !important;
  flex: 1;
  min-width: 60px;
}

.tom-select-wrapper .ts-dropdown {
  background: #ffffff !important;
  border: 1px solid #c4c5d5 !important;
  border-radius: 0.5rem !important;
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
  overflow: hidden;
}

.tom-select-wrapper .ts-dropdown .option {
  padding: 10px 16px !important;
  font-family: 'Inter', sans-serif !important;
  font-size: 14px !important;
  color: #191c1e !important;
  line-height: 20px !important;
  cursor: pointer;
}

.tom-select-wrapper .ts-dropdown .option.active {
  background-color: #dde1ff !important;
  color: #00288e !important;
}

.tom-select-wrapper .ts-dropdown .option:hover {
  background-color: #f2f4f6 !important;
}

.tom-select-wrapper .ts-dropdown .option.selected {
  background-color: #00288e !important;
  color: #ffffff !important;
}

.tom-select-wrapper .ts-wrapper.single .ts-control .item {
  display: flex;
  align-items: center;
  height: 100%;
}

.tom-select-wrapper .ts-control .item {
  font-family: 'Inter', sans-serif !important;
  font-size: 16px !important;
  color: #191c1e !important;
}

.tom-select-wrapper .ts-placeholder {
  color: #757684 !important;
  font-family: 'Inter', sans-serif !important;
  font-size: 16px !important;
}

.tom-select-wrapper .ts-dropdown .create {
  padding: 10px 16px !important;
  font-family: 'Inter', sans-serif !important;
  font-size: 14px !important;
  color: #0058be !important;
}

.tom-select-wrapper .ts-wrapper.multi .ts-control {
  height: auto !important;
  min-height: 48px !important;
  padding: 8px 12px !important;
  align-items: center;
}

.tom-select-wrapper .ts-wrapper.multi .ts-control .item {
  background-color: var(--tag-bg) !important;
  color: var(--tag-text) !important;
  border: none !important;
  border-radius: 0.25rem !important;
  padding: 4px 10px !important;
  font-size: 12px !important;
  font-weight: 600 !important;
  letter-spacing: 0.05em !important;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  line-height: 1.4;
}

.tom-select-wrapper .ts-wrapper.multi .ts-control .item .remove {
  color: var(--tag-text) !important;
  border-left: none !important;
  padding: 0 !important;
  margin: 0 !important;
  margin-left: 4px !important;
  cursor: pointer;
  font-size: 16px;
  line-height: 1;
  display: inline-block;
  visibility: visible;
  opacity: 0.7;
  float: none;
}

.tom-select-wrapper .ts-wrapper.multi .ts-control .item .remove:hover {
  background: none !important;
  color: #ba1a1a !important;
  opacity: 1;
}

.tom-select-wrapper .ts-control input::placeholder {
  color: #757684 !important;
}

.tom-select-wrapper .ts-wrapper.multi .ts-control input {
  height: 32px !important;
}
</style>
