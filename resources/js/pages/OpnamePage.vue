<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Inventory</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Stock Opname</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Stock Opname
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Perform physical inventory audits and reconcile stock levels.
      </p>
    </div>

    <!-- Progress Indicator -->
    <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl shadow-sm mb-6">
      <div class="flex justify-between items-end mb-2">
        <div>
          <span class="font-label-caps text-label-caps text-outline uppercase tracking-wider">Current Location</span>
          <h3 class="font-headline-md text-headline-md text-on-surface">Rack A1-04-B</h3>
        </div>
        <div class="text-right">
          <span class="font-data-mono text-data-mono text-primary font-bold">12 / 20 Items</span>
        </div>
      </div>
      <div class="w-full bg-surface-container-low h-3 rounded-full overflow-hidden mb-1">
        <div class="bg-primary h-full rounded-full" :style="{ width: progressPercent + '%' }"></div>
      </div>
      <p class="font-body-sm text-body-sm text-on-surface-variant">{{ progressPercent }}% Completed — {{ remainingItems }} items remaining in this section.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-2 mb-6">
      <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3">
        <div class="bg-green-100 p-2 rounded-lg">
          <span class="material-symbols-outlined text-green-700">check_circle</span>
        </div>
        <div>
          <p class="font-label-caps text-label-caps text-outline">Matched</p>
          <p class="font-headline-md text-headline-md text-on-surface">12</p>
        </div>
      </div>
      <div class="bg-white border border-outline-variant p-4 rounded-xl flex items-center gap-3">
        <div class="bg-slate-100 p-2 rounded-lg">
          <span class="material-symbols-outlined text-slate-500">pending</span>
        </div>
        <div>
          <p class="font-label-caps text-label-caps text-outline">Pending</p>
          <p class="font-headline-md text-headline-md text-on-surface">08</p>
        </div>
      </div>
    </div>

    <!-- Item List Header -->
    <div class="flex justify-between items-center mb-3">
      <h3 class="font-label-caps text-label-caps text-outline uppercase tracking-wider">Inventory Audit List</h3>
      <button class="flex items-center gap-1 text-primary font-label-caps text-label-caps">
        <span class="material-symbols-outlined text-[16px]">filter_list</span>
        SORT BY SKU
      </button>
    </div>

    <!-- Inventory Items -->
    <div class="space-y-2">
      <div
        v-for="item in auditItems"
        :key="item.sku"
        class="bg-white border border-outline-variant rounded-xl overflow-hidden active:bg-slate-50 transition-all"
        :class="{ 'border-2 border-secondary': item.status === 'Pending' }"
      >
        <div class="p-4 flex items-start gap-3">
          <div class="w-16 h-16 bg-slate-100 rounded-lg flex-shrink-0 overflow-hidden border border-slate-200 flex items-center justify-center">
            <span class="material-symbols-outlined text-slate-400 text-2xl">inventory_2</span>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex justify-between items-start">
              <span class="font-data-mono text-data-mono text-outline">{{ item.sku }}</span>
              <span
                class="px-2 py-0.5 rounded-full text-[10px] font-bold border"
                :class="item.status === 'Matched' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-slate-100 text-slate-500 border-slate-200'"
              >{{ item.status === 'Matched' ? 'Matched' : 'Pending' }}</span>
            </div>
            <h4 class="font-body-sm text-body-sm font-bold text-on-surface truncate">{{ item.name }}</h4>
            <div class="flex items-center gap-4 mt-1">
              <div class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px] text-outline">inventory</span>
                <span class="font-body-sm text-body-sm text-on-surface-variant">Qty: {{ item.qty }}</span>
              </div>
              <div v-if="item.time" class="flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px] text-outline">schedule</span>
                <span class="font-body-sm text-body-sm text-on-surface-variant">{{ item.time }}</span>
              </div>
            </div>
          </div>
          <button
            v-if="item.status === 'Pending'"
            class="bg-secondary-container text-on-primary p-2 rounded-lg active:scale-95 transition-transform"
          >
            <span class="material-symbols-outlined">barcode_scanner</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Scan Barcode Button -->
    <div class="mt-8">
      <button class="btn-primary w-full rounded-full">
        <span class="material-symbols-outlined text-[32px]" style="font-variation-settings: 'FILL' 1;">barcode_scanner</span>
        <span class="font-headline-md text-headline-md">Scan Barcode</span>
      </button>
      <div class="mt-3 text-center">
        <button class="font-label-caps text-label-caps text-secondary tracking-widest uppercase py-2">
          FINISH AUDIT FOR THIS RACK
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const totalItems = 20
const checkedItems = ref(12)
const progressPercent = computed(() => Math.round((checkedItems.value / totalItems) * 100))
const remainingItems = computed(() => totalItems - checkedItems.value)

const auditItems = ref([
  { sku: 'SKU-99210-AX', name: 'Gold Bar 10g - Logam Mulia Antam', qty: 5, status: 'Matched', time: '09:42 AM' },
  { sku: 'SKU-88432-BR', name: 'Diamond Ring Cluster 2.5ct', qty: 1, status: 'Pending', time: null },
  { sku: 'SKU-10293-CW', name: 'Rolex Submariner Date 126610LN', qty: 2, status: 'Pending', time: null },
  { sku: 'SKU-77321-DK', name: 'Assorted Gold Jewelry - Mix Tray C', qty: 12, status: 'Matched', time: '08:15 AM' },
])
</script>
