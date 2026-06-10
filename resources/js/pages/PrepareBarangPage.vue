<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Operations</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Prepare Barang</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Prepare Barang
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Search products and find optimal rack locations for picking operations.
      </p>
    </div>

    <!-- Search Bar -->
    <section class="mb-6">
      <div class="relative group">
        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
          <span class="material-symbols-outlined text-outline">search</span>
        </div>
        <input
          v-model="searchQuery"
          class="w-full h-12 bg-surface-container-lowest border border-outline-variant focus:border-primary text-on-surface pl-12 pr-12 rounded-xl shadow-sm transition-all font-body-lg outline-none"
          placeholder="Search Product ID or Name..."
          type="text"
        />
        <div class="absolute inset-y-0 right-4 flex items-center cursor-pointer text-secondary">
          <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">barcode_scanner</span>
        </div>
      </div>
    </section>

    <!-- Selected Product Highlight -->
    <section class="mb-8">
      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <p class="font-label-caps text-on-surface-variant uppercase mb-1">SELECTED PRODUCT</p>
            <h2 class="font-headline-md text-headline-md text-primary tracking-wider">SKU-BRG-2024-X9</h2>
            <p class="font-body-sm text-on-surface">Industrial Precision Bearing - Type Z</p>
          </div>
          <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex flex-col items-end min-w-[140px]">
            <p class="font-label-caps text-secondary uppercase mb-1">TOTAL AVAILABLE</p>
            <div class="flex items-baseline gap-2">
              <span class="font-headline-lg text-headline-lg text-primary">1,450</span>
              <span class="font-label-caps text-on-surface-variant/70">PCS</span>
            </div>
          </div>
        </div>
        <div class="bg-surface-container-low px-4 py-3 flex gap-3 overflow-x-auto border-t border-outline-variant">
          <span class="bg-blue-100 text-primary px-3 py-1.5 rounded-full font-label-caps text-label-caps flex items-center gap-1 shrink-0">
            <span class="material-symbols-outlined text-[14px]">category</span> Mechanical
          </span>
          <span class="bg-red-50 text-error px-3 py-1.5 rounded-full font-label-caps text-label-caps flex items-center gap-1 shrink-0 border border-red-200">
            <span class="material-symbols-outlined text-[14px]">warning</span> Low Stock Alert
          </span>
        </div>
      </div>
    </section>

    <!-- Rack Suggestions -->
    <section>
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-headline-md text-headline-md text-on-surface flex items-center gap-2">
          <span class="material-symbols-outlined text-primary">analytics</span> Rack Suggestions
        </h3>
        <button class="text-primary font-label-caps text-label-caps flex items-center gap-1">
          <span class="material-symbols-outlined text-[16px]">sort</span> SORTED BY QTY
        </button>
      </div>
      <div class="space-y-3">
        <div
          v-for="(rack, index) in rackSuggestions"
          :key="rack.id"
          class="group relative bg-surface-container-lowest border transition-all duration-200 p-4 rounded-xl shadow-sm"
          :class="index === 0 ? 'border-2 border-primary shadow-md' : 'border-outline-variant hover:border-secondary'"
        >
          <div class="flex justify-between items-start mb-4">
            <div class="flex flex-col">
              <div class="flex items-center gap-2 mb-1">
                <span class="material-symbols-outlined" :class="index === 0 ? 'text-primary' : 'text-secondary'">location_on</span>
                <span class="font-headline-md text-headline-md text-on-surface">{{ rack.name }}</span>
              </div>
              <div class="flex items-center gap-2">
                <span class="font-data-mono text-data-mono text-on-surface-variant bg-surface-container-high px-1.5 py-0.5 rounded">{{ rack.stockId }}</span>
                <span class="bg-green-50 text-green-700 font-label-caps text-label-caps px-2 py-0.5 rounded-full border border-green-200">STOCK TYPE: IN</span>
              </div>
            </div>
            <div class="text-right">
              <p class="font-headline-md text-headline-md text-on-surface tracking-wider">{{ rack.qty }} <span class="font-label-caps text-label-caps text-on-surface-variant">PCS</span></p>
              <p class="text-[10px] font-bold text-green-700 uppercase">AVAILABLE</p>
            </div>
          </div>
          <button
            class="btn-primary w-full font-label-caps text-label-caps"
            @click="pickFromRack(rack)"
          >
            <span class="material-symbols-outlined">front_loader</span> PICK FROM THIS RACK
          </button>
        </div>
      </div>
    </section>

    <!-- Operational Info Card -->
    <section class="mt-8">
      <div class="relative overflow-hidden rounded-xl bg-primary text-on-primary p-6 shadow-lg">
        <div class="relative z-10">
          <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined">security</span>
            <h4 class="font-headline-md text-headline-md">Operational Insight</h4>
          </div>
          <p class="font-body-sm text-on-primary/80 max-w-[300px] leading-relaxed">
            Suggestion engine optimized for <span class="text-primary-fixed-dim font-bold">First-Expired-First-Out (FEFO)</span> and distance proximity. Secure logistics protocols active.
          </p>
        </div>
        <div class="absolute -right-6 -bottom-6 opacity-10">
          <span class="material-symbols-outlined text-[140px] font-bold">shield</span>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const searchQuery = ref('')

const rackSuggestions = ref([
  { id: 1, name: 'Rack A-101', stockId: 'STK-008291', qty: 200 },
  { id: 2, name: 'Rack B-204', stockId: 'STK-009110', qty: 156 },
  { id: 3, name: 'Rack C-005', stockId: 'STK-007743', qty: 84 },
])

const pickFromRack = (rack) => {
  alert(`Picking from ${rack.name} - ${rack.qty} PCS available`)
}
</script>
