<template>
  <!-- Overlay -->
  <Transition name="fade">
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black/40 z-50 md:hidden"
      @click="$emit('close')"
    ></div>
  </Transition>

  <!-- Drawer panel -->
  <Transition name="slide">
    <div
      v-if="isOpen"
      class="fixed top-0 left-0 h-full w-72 bg-surface-container-lowest z-50 md:hidden shadow-2xl flex flex-col"
    >
      <!-- Drawer header -->
      <div class="flex items-center justify-between px-5 h-16 border-b border-outline-variant">
        <h2 class="font-headline-md text-headline-md font-bold text-primary">WMS Portal</h2>
        <button
          class="p-2 hover:bg-surface-container rounded-full transition-colors"
          @click="$emit('close')"
        >
          <span class="material-symbols-outlined text-on-surface-variant">close</span>
        </button>
      </div>

      <!-- Drawer nav -->
      <nav class="flex-1 py-4 px-3 space-y-2 overflow-y-auto">
        <router-link
          v-for="item in menuItems"
          :key="item.path"
          :to="item.path"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all"
          :class="
            isActive(item.path)
              ? 'bg-primary-fixed text-primary font-semibold'
              : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
          "
          @click="$emit('close')"
        >
          <span
            class="material-symbols-outlined"
            :class="isActive(item.path) ? 'text-primary' : 'text-on-surface-variant'"
          >{{ item.icon }}</span>
          <span class="font-body-sm">{{ item.label }}</span>
        </router-link>
      </nav>

      <!-- Drawer footer -->
      <div class="p-4 border-t border-outline-variant/30">
        <div class="flex items-center gap-3 px-2 py-2 text-on-surface-variant">
          <span class="material-symbols-outlined text-sm">info</span>
          <span class="text-[11px]">WMS Portal v1.0</span>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { useRoute } from 'vue-router'

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
})

defineEmits(['close'])

const route = useRoute()

const menuItems = [
  { path: '/dashboard', label: 'Dashboard', icon: 'dashboard' },
  { path: '/register', label: 'Register Product', icon: 'add_box' },
  { path: '/stock', label: 'Stock Management', icon: 'inventory_2' },
  { path: '/inbound', label: 'Inbound', icon: 'move_to_inbox' },
  { path: '/barang', label: 'Warehouse Inventory', icon: 'inventory_2' },
  { path: '/active-tasks', label: 'Active Tasks', icon: 'assignment_turned_in' },
  { path: '/forklift', label: 'Forklift Tasks', icon: 'forklift' },
  { path: '/generate-barcode', label: 'Barcode Generator', icon: 'qr_code_2' },
  { path: '/opname', label: 'Stock Opname', icon: 'barcode_scanner' },
  { path: '/outbound-orders', label: 'Outbound Orders', icon: 'output' },
  { path: '/prepare-barang', label: 'Prepare Barang', icon: 'front_loader' },
  { path: '/putaway', label: 'Putaway Process', icon: 'warehouse' },
  { path: '/selected-stock', label: 'Stock Detail', icon: 'analytics' },
  { path: '/split-barang', label: 'Outbound Split', icon: 'call_split' },
  { path: '/work-orders', label: 'Work Orders', icon: 'list_alt' },
]

const isActive = (path) => route.path === path
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

.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateX(-100%);
}
</style>
