<template>
  <Transition name="fade">
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black/40 z-50"
      @click="$emit('close')"
    ></div>
  </Transition>

  <Transition name="slide-right">
    <div
      v-if="isOpen"
      class="fixed top-0 right-0 h-full w-80 bg-surface-container-lowest z-50 shadow-2xl flex flex-col"
    >
      <div class="flex items-center justify-between px-5 h-16 border-b border-outline-variant">
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">Advanced Filters</h2>
        <button
          class="p-2 hover:bg-surface-container rounded-full transition-colors"
          @click="$emit('close')"
        >
          <span class="material-symbols-outlined text-on-surface-variant">close</span>
        </button>
      </div>

      <div class="flex-1 overflow-y-auto p-5 space-y-5">
        <slot />
      </div>

      <div class="p-5 border-t border-outline-variant flex gap-3">
        <button
          class="btn-primary-outline flex-1"
          @click="$emit('reset')"
        >
          Reset
        </button>
        <button
          class="btn-primary flex-1"
          @click="$emit('apply')"
        >
          Apply
        </button>
      </div>
    </div>
  </Transition>
</template>

<script setup>
defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['close', 'reset', 'apply'])
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-right-enter-active,
.slide-right-leave-active {
  transition: transform 0.3s ease;
}
.slide-right-enter-from,
.slide-right-leave-to {
  transform: translateX(100%);
}
</style>
