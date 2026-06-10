<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Operations</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Outbound Split</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Outbound Split
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Split stock quantities for outbound orders and maintain traceability.
      </p>
    </div>

    <!-- Outbound Request Section -->
    <section class="mb-6">
      <div class="flex items-center justify-between mb-3">
        <h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Outbound Request</h3>
        <span class="bg-error text-on-error px-2 py-0.5 rounded-full font-label-caps text-label-caps">URGENT</span>
      </div>
      <div class="bg-surface-container-lowest p-4 rounded-xl relative overflow-hidden shadow-sm border-2 border-error">
        <div class="flex justify-between items-start mb-2">
          <div>
            <p class="font-headline-md text-headline-md text-primary tracking-wider">SKU-99283-A</p>
            <p class="font-body-sm text-on-surface-variant">Industrial Grade Steel Bearings</p>
          </div>
          <div class="text-right">
            <p class="font-headline-lg text-headline-lg text-on-surface">50 <span class="text-sm font-normal">pcs</span></p>
            <p class="font-label-caps text-label-caps text-on-surface-variant">REQUESTED</p>
          </div>
        </div>
        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-outline-variant">
          <span class="material-symbols-outlined text-primary text-sm">assignment</span>
          <p class="font-data-mono text-data-mono text-on-surface-variant">REF: ORD-2023-X92</p>
        </div>
      </div>
    </section>

    <!-- Selected Stock Source -->
    <section class="mb-6">
      <h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-3">Selected Stock Source</h3>
      <div class="bg-surface-container-lowest p-4 rounded-xl border-2 border-secondary shadow-sm">
        <div class="flex justify-between items-center">
          <div>
            <p class="font-label-caps text-label-caps text-on-surface-variant">STOCK ID</p>
            <p class="font-headline-md text-headline-md text-on-surface">ID-101</p>
          </div>
          <div class="text-right">
            <p class="font-label-caps text-label-caps text-on-surface-variant">TOTAL AVAILABLE</p>
            <p class="font-headline-md text-headline-md text-on-surface">200 <span class="text-sm font-normal text-on-surface-variant">pcs</span></p>
          </div>
        </div>
        <div class="mt-3 flex items-center gap-4 bg-surface-container p-2 rounded-lg">
          <div class="flex items-center gap-1">
            <span class="material-symbols-outlined text-sm text-secondary">location_on</span>
            <span class="font-body-sm text-on-surface">AISLE-04-B2</span>
          </div>
          <div class="flex items-center gap-1 border-l border-outline-variant pl-4">
            <span class="material-symbols-outlined text-sm text-secondary">calendar_today</span>
            <span class="font-body-sm text-on-surface">IN: 12 OCT</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Split Interface -->
    <section class="mb-6">
      <h3 class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest mb-3">Split Quantity Interface</h3>
      <div class="bg-surface-container-lowest p-4 rounded-xl shadow-md border border-outline-variant space-y-6">
        <!-- Before -->
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-surface-container-highest flex items-center justify-center text-primary">
              <span class="material-symbols-outlined">inventory_2</span>
            </div>
            <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant">ORIGINAL STOCK</p>
              <p class="font-headline-md text-headline-md text-on-surface">200 <span class="text-xs font-normal">pcs</span></p>
            </div>
          </div>
          <span class="font-data-mono text-data-mono bg-surface-container px-2 py-1 rounded text-on-surface-variant">ID-101</span>
        </div>

        <!-- Flow Indicator -->
        <div class="relative h-12 flex items-center justify-center">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-dashed border-primary/30"></div>
          </div>
          <div class="relative bg-primary text-on-primary px-4 py-1.5 rounded-full flex items-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-sm animate-pulse">call_split</span>
            <span class="font-label-caps text-label-caps">PROCESSING SPLIT</span>
          </div>
        </div>

        <!-- Results -->
        <div class="grid grid-cols-2 gap-3">
          <div class="bg-blue-50 p-4 rounded-xl border-2 border-blue-200 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 p-1 opacity-10">
              <span class="material-symbols-outlined text-3xl">logout</span>
            </div>
            <p class="font-label-caps text-label-caps text-primary">QTY TO TAKE</p>
            <div class="flex items-end justify-between mt-1">
              <p class="font-headline-lg text-headline-lg text-primary">{{ takeQty }}</p>
              <span class="font-label-caps text-label-caps text-primary/70 mb-1">PCS</span>
            </div>
            <div class="mt-3 p-1.5 bg-blue-100 rounded flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[14px] text-primary">badge</span>
              <span class="text-[10px] font-bold text-primary tracking-tight">ID-101-OUT</span>
            </div>
          </div>
          <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant shadow-sm">
            <p class="font-label-caps text-label-caps text-on-surface-variant">REMAINING</p>
            <div class="flex items-end justify-between mt-1">
              <p class="font-headline-lg text-headline-lg text-on-surface">{{ remainingQty }}</p>
              <span class="font-label-caps text-label-caps text-on-surface-variant mb-1">PCS</span>
            </div>
            <div class="mt-3 p-1.5 bg-surface-container-highest rounded flex items-center gap-1.5">
              <span class="material-symbols-outlined text-[14px] text-on-surface-variant">history</span>
              <span class="text-[10px] font-bold text-on-surface-variant tracking-tight">ID-101</span>
            </div>
          </div>
        </div>

        <!-- Security Note -->
        <div class="bg-surface-container-high border-2 border-outline-variant p-4 rounded-xl flex gap-3 items-start relative">
          <div class="absolute top-2 right-2">
            <span class="material-symbols-outlined text-secondary opacity-20 text-4xl">verified_user</span>
          </div>
          <span class="material-symbols-outlined text-primary mt-0.5">security</span>
          <div class="space-y-1">
            <p class="font-label-caps text-label-caps text-primary">Security Protocol Active</p>
            <p class="text-[11px] leading-relaxed text-on-surface font-medium">
              Split operation will generate a unique parent-child relationship between <strong>ID-101</strong> and the new outbound batch <strong>ID-101-OUT</strong>. Full audit trail will be logged for inventory reconciliation.
            </p>
          </div>
        </div>
      </div>

      <!-- Qty Adjuster -->
      <div class="flex gap-2 p-1 bg-surface-container-low rounded-2xl border border-outline-variant mt-4">
        <button
          class="flex-1 h-12 bg-surface-container-highest border border-outline-variant rounded-xl flex items-center justify-center text-on-surface active:bg-primary active:text-on-primary transition-all"
          @click="adjust(-10)"
        >
          <span class="material-symbols-outlined">remove</span>
        </button>
        <div class="flex-[2] h-12 bg-surface-container-lowest border-2 border-blue-200 rounded-xl flex items-center justify-center">
          <span class="font-headline-md text-headline-md text-primary">{{ takeQty }}</span>
        </div>
        <button
          class="flex-1 h-12 bg-surface-container-highest border border-outline-variant rounded-xl flex items-center justify-center text-on-surface active:bg-primary active:text-on-primary transition-all"
          @click="adjust(10)"
        >
          <span class="material-symbols-outlined">add</span>
        </button>
      </div>
    </section>

    <!-- Execute Button -->
    <button
      class="btn-primary w-full uppercase tracking-wide"
      @click="executeSplit"
    >
      <span class="material-symbols-outlined">output</span>
      EXECUTE SPLIT &amp; OUTBOUND
    </button>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const maxStock = 200
const takeQty = ref(50)
const remainingQty = computed(() => maxStock - takeQty.value)

const adjust = (val) => {
  takeQty.value = Math.max(0, Math.min(maxStock, takeQty.value + val))
}

const executeSplit = () => {
  alert(`Split executed: ${takeQty.value} PCS outbound, ${remainingQty.value} PCS remaining`)
}
</script>
