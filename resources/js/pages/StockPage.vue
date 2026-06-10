<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Inventory</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Stock Management</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Stock Management
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Monitor inventory levels, adjust quantities, and manage stock movements.
      </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
      <div class="bg-primary text-on-primary rounded-2xl p-5 shadow-md">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
            <span class="material-symbols-outlined text-2xl">inventory_2</span>
          </div>
          <span class="font-label-caps text-label-caps bg-white/20 px-2 py-1 rounded-full">+12%</span>
        </div>
        <p class="font-headline-lg text-headline-lg">1,248</p>
        <p class="font-label-caps text-label-caps opacity-80 mt-1">Total Items in Stock</p>
      </div>
      <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-xl bg-secondary-container/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-secondary text-2xl">warning</span>
          </div>
          <span class="font-label-caps text-label-caps bg-secondary-container/20 text-secondary px-2 py-1 rounded-full">Alert</span>
        </div>
        <p class="font-headline-lg text-headline-lg text-on-surface">23</p>
        <p class="font-label-caps text-label-caps text-on-surface-variant mt-1">Low Stock Items</p>
      </div>
      <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-xl bg-error-container/10 flex items-center justify-center">
            <span class="material-symbols-outlined text-error text-2xl">error</span>
          </div>
          <span class="font-label-caps text-label-caps bg-error-container/20 text-error px-2 py-1 rounded-full">Critical</span>
        </div>
        <p class="font-headline-lg text-headline-lg text-on-surface">5</p>
        <p class="font-label-caps text-label-caps text-on-surface-variant mt-1">Out of Stock</p>
      </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-6 form-card">
      <div class="flex flex-col sm:flex-row gap-4 sm:items-end">
        <div class="flex-1">
          <FormInput
            v-model="searchQuery"
            placeholder="Search by product name, SKU, or serial number..."
          />
        </div>
        <div class="sm:w-48">
          <FormTomSelect
            v-model="filterCategory"
            placeholder="All Categories"
            :options="categoryOptions"
          />
        </div>
        <button
          class="btn-primary h-12 px-4 gap-2"
          @click="drawerOpen = true"
        >
          <span class="material-symbols-outlined text-xl">tune</span>
          Filters
        </button>
      </div>
    </div>

    <!-- Mobile: Stacked Card List -->
    <div class="md:hidden space-y-3">
      <div
        v-for="item in filteredStock"
        :key="item.id"
        class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 form-card"
      >
        <div class="flex items-start justify-between mb-3">
          <div class="flex-1 min-w-0">
            <p class="font-body-sm font-semibold text-on-surface truncate">{{ item.name }}</p>
            <p class="font-data-mono text-data-mono text-on-surface-variant">{{ item.sku }}</p>
          </div>
          <span
            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] uppercase tracking-wider font-semibold shrink-0 ml-3"
            :class="getStatusBadge(item.qty)"
          >
            {{ getStatusLabel(item.qty) }}
          </span>
        </div>
        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-outline-variant">
          <div>
            <p class="font-label-caps text-label-caps text-on-surface-variant">Serial</p>
            <p class="font-data-mono text-data-mono text-on-surface">{{ item.serialNo }}</p>
          </div>
          <div class="text-right">
            <p class="font-label-caps text-label-caps text-on-surface-variant">Qty</p>
            <p class="font-headline-md text-headline-md" :class="getQtyColor(item.qty)">{{ item.qty }}</p>
          </div>
        </div>
      </div>
    </div>
Throughput Goal
796 / 850

    <!-- Desktop: Table -->
    <div class="hidden md:block bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden form-card">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="bg-surface-container border-b border-outline-variant">
              <th class="text-left px-5 py-3 font-label-caps text-label-caps text-on-surface-variant">Product</th>
              <th class="text-left px-5 py-3 font-label-caps text-label-caps text-on-surface-variant">SKU</th>
              <th class="text-left px-5 py-3 font-label-caps text-label-caps text-on-surface-variant">Category</th>
              <th class="text-center px-5 py-3 font-label-caps text-label-caps text-on-surface-variant">Qty</th>
              <th class="text-left px-5 py-3 font-label-caps text-label-caps text-on-surface-variant">Status</th>
              <th class="text-right px-5 py-3 font-label-caps text-label-caps text-on-surface-variant">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in filteredStock"
              :key="item.id"
              class="border-b border-outline-variant/30 hover:bg-surface-container-low/50 transition-colors"
            >
              <td class="px-5 py-4">
                <div>
                  <p class="font-body-sm font-semibold text-on-surface">{{ item.name }}</p>
                  <p class="font-data-mono text-data-mono text-on-surface-variant">{{ item.serialNo }}</p>
                </div>
              </td>
              <td class="px-5 py-4 font-data-mono text-data-mono text-on-surface">{{ item.sku }}</td>
              <td class="px-5 py-4 text-body-sm text-on-surface-variant">{{ item.category }}</td>
              <td class="px-5 py-4 text-center font-data-mono text-data-mono" :class="getQtyColor(item.qty)">
                {{ item.qty }}
              </td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] uppercase tracking-wider font-semibold"
                  :class="getStatusBadge(item.qty)"
                >
                  {{ getStatusLabel(item.qty) }}
                </span>
              </td>
              <td class="px-5 py-4 text-right">
                <button class="p-2 hover:bg-surface-container rounded-full transition-colors text-on-surface-variant">
                  <span class="material-symbols-outlined text-sm">more_vert</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Advanced Filter Drawer -->
    <FilterDrawer
      :is-open="drawerOpen"
      @close="drawerOpen = false"
      @reset="resetFilters"
      @apply="applyFilters"
    >
      <FormTomSelect
        v-model="advancedFilters.category"
        label="Category"
        placeholder="All Categories"
        :options="categoryOptions"
      />
      <FormTomSelect
        v-model="advancedFilters.status"
        label="Stock Status"
        placeholder="All Status"
        :options="statusOptions"
      />
      <FormInput
        v-model="advancedFilters.minQty"
        label="Min Quantity"
        type="number"
        placeholder="0"
      />
      <FormInput
        v-model="advancedFilters.maxQty"
        label="Max Quantity"
        type="number"
        placeholder="No limit"
      />
      <FormTomSelect
        v-model="advancedFilters.location"
        label="Storage Zone"
        placeholder="All Zones"
        :options="locationOptions"
      />
      <FormToggle
        v-model="advancedFilters.lowStockOnly"
        label="Low Stock Alert Only"
      />
    </FilterDrawer>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import FormInput from '../components/FormInput.vue'
import FormTomSelect from '../components/FormTomSelect.vue'
import FormToggle from '../components/FormToggle.vue'
import FilterDrawer from '../components/FilterDrawer.vue'

const searchQuery = ref('')
const filterCategory = ref('')
const drawerOpen = ref(false)

const advancedFilters = reactive({
  category: '',
  status: '',
  minQty: '',
  maxQty: '',
  location: '',
  lowStockOnly: false,
})

const categoryOptions = [
  { value: 'electronics', label: 'Industrial Electronics' },
  { value: 'mechanical', label: 'Mechanical Parts' },
  { value: 'safety', label: 'Safety Equipment' },
]

const statusOptions = [
  { value: 'in-stock', label: 'In Stock' },
  { value: 'low-stock', label: 'Low Stock' },
  { value: 'out-of-stock', label: 'Out of Stock' },
]

const locationOptions = [
  { value: 'zone-a', label: 'Zone A' },
  { value: 'zone-b', label: 'Zone B' },
  { value: 'zone-c', label: 'Zone C' },
  { value: 'hazmat', label: 'HAZMAT' },
]

const stockItems = ref([
  { id: 1, name: 'Industrial Servo Motor', sku: 'SRV-001', serialNo: 'SN-44821', category: 'Industrial Electronics', qty: 145, location: 'zone-a' },
  { id: 2, name: 'Hydraulic Pump Assembly', sku: 'HYD-023', serialNo: 'SN-77234', category: 'Mechanical Parts', qty: 8, location: 'zone-b' },
  { id: 3, name: 'Safety Relief Valve', sku: 'SRV-089', serialNo: 'SN-11098', category: 'Safety Equipment', qty: 0, location: 'zone-a' },
  { id: 4, name: 'Control Board PCB-X4', sku: 'PCB-X4', serialNo: 'SN-55612', category: 'Industrial Electronics', qty: 67, location: 'zone-c' },
  { id: 5, name: 'Steel Gear Module 4', sku: 'GRM-M4', serialNo: 'SN-99341', category: 'Mechanical Parts', qty: 3, location: 'zone-b' },
  { id: 6, name: 'Emergency Stop Button', sku: 'EST-001', serialNo: 'SN-22190', category: 'Safety Equipment', qty: 234, location: 'zone-a' },
])

const filteredStock = computed(() => {
  return stockItems.value.filter((item) => {
    const matchesSearch =
      !searchQuery.value ||
      item.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.sku.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      item.serialNo.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = !filterCategory.value || item.category.toLowerCase().includes(filterCategory.value)
    const matchesAdvCategory = !advancedFilters.category || item.category.toLowerCase().includes(advancedFilters.category)
    const matchesStatus =
      !advancedFilters.status ||
      (advancedFilters.status === 'in-stock' && item.qty > 10) ||
      (advancedFilters.status === 'low-stock' && item.qty > 0 && item.qty <= 10) ||
      (advancedFilters.status === 'out-of-stock' && item.qty === 0)
    const matchesMinQty = !advancedFilters.minQty || item.qty >= Number(advancedFilters.minQty)
    const matchesMaxQty = !advancedFilters.maxQty || item.qty <= Number(advancedFilters.maxQty)
    const matchesLocation = !advancedFilters.location || item.location === advancedFilters.location
    const matchesLowStock = !advancedFilters.lowStockOnly || (item.qty > 0 && item.qty <= 10)
    return matchesSearch && matchesCategory && matchesAdvCategory && matchesStatus && matchesMinQty && matchesMaxQty && matchesLocation && matchesLowStock
  })
})

const resetFilters = () => {
  advancedFilters.category = ''
  advancedFilters.status = ''
  advancedFilters.minQty = ''
  advancedFilters.maxQty = ''
  advancedFilters.location = ''
  advancedFilters.lowStockOnly = false
}

const applyFilters = () => {
  drawerOpen.value = false
}

const getQtyColor = (qty) => {
  if (qty === 0) return 'text-error'
  if (qty <= 10) return 'text-secondary'
  return 'text-on-surface'
}

const getStatusBadge = (qty) => {
  if (qty === 0) return 'bg-error-container text-on-error-container'
  if (qty <= 10) return 'bg-secondary-fixed text-on-secondary-fixed-variant'
  return 'bg-primary-fixed text-on-primary-fixed-variant'
}

const getStatusLabel = (qty) => {
  if (qty === 0) return 'Out of Stock'
  if (qty <= 10) return 'Low Stock'
  return 'In Stock'
}
</script>
