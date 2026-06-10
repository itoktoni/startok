<template>
  <aside
    class="hidden md:flex flex-col fixed top-16 left-0 h-[calc(100vh-4rem)] w-72 bg-transparent z-40 transition-transform duration-300 px-3 pt-4"
    :class="{ '-translate-x-full': !isOpen }"
  >
    <nav class="flex-1 space-y-2 overflow-y-auto pr-3 sidebar-scroll">
      <router-link
        v-for="item in menuItems"
        :key="item.path"
        :to="item.path"
        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group"
        :class="
          isActive(item.path)
            ? 'bg-primary-fixed text-primary font-semibold'
            : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
        "
      >
        <span
          class="material-symbols-outlined"
          :class="isActive(item.path) ? 'text-primary' : 'text-on-surface-variant group-hover:text-on-surface'"
        >{{ item.icon }}</span>
        <span class="font-body-sm">{{ item.label }}</span>
      </router-link>
    </nav>

    <!-- Bottom section -->
    <div class="p-4 border-t border-outline-variant/30">
      <div class="flex items-center gap-3 px-2 py-2 text-on-surface-variant">
        <span class="material-symbols-outlined text-sm">info</span>
        <span class="text-[11px]">WMS Portal v1.0</span>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { useRoute } from 'vue-router'

defineProps({
  isOpen: {
    type: Boolean,
    default: true,
  },
})

const route = useRoute()

const menuItems = [
  { path: '/dashboard', label: 'Dashboard', icon: 'dashboard' },
  { path: '/register', label: 'Register Product', icon: 'add_box' },
  { path: '/stock', label: 'Stock Management', icon: 'inventory_2' },
  { path: '/inbound', label: 'Inbound', icon: 'move_to_inbox' },
  { path: '/barang', label: 'Inventory', icon: 'inventory_2' },
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
.sidebar-scroll::-webkit-scrollbar {
  width: 4px;
}
.sidebar-scroll::-webkit-scrollbar-track {
  background: transparent;
}
.sidebar-scroll::-webkit-scrollbar-thumb {
  background: #c4c5d5;
  border-radius: 2px;
}
</style>
