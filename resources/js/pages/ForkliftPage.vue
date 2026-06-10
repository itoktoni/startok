<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Operations</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Forklift Tasks</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        WMS Terminal - Forklift Tasks
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Manage forklift movement tasks, storage assignments, and rack availability.
      </p>
    </div>

    <!-- Urgent Task Indicator -->
    <div class="mb-6">
      <h3 class="font-label-caps text-label-caps text-on-surface-variant mb-3 flex items-center gap-2 uppercase tracking-wider">
        <span class="material-symbols-outlined text-error text-[18px]" style="font-variation-settings: 'FILL' 1;">priority_high</span>
        PENDING TASKS
      </h3>
      <div class="flex flex-col gap-3">
        <div
          v-for="task in pendingTasks"
          :key="task.sku"
          class="bg-surface-container-lowest p-3 flex items-center justify-between shadow-sm rounded-lg border transition-all"
          :class="task.borderColor"
        >
          <div class="flex flex-col gap-1">
            <div class="flex items-center gap-2">
              <span class="font-data-mono text-data-mono text-primary-container bg-primary-fixed px-2 py-0.5 rounded">{{ task.sku }}</span>
              <span
                class="text-[10px] font-bold px-2 py-0.5 rounded"
                :class="task.priorityClass"
              >{{ task.priority }}</span>
            </div>
            <p class="font-body-sm text-on-surface">{{ task.action }} <span class="font-bold">{{ task.target }}</span></p>
            <p class="font-body-sm text-body-sm text-outline">{{ task.due }}</p>
          </div>
          <button class="btn-primary px-4 font-label-caps text-label-caps">
            START
          </button>
        </div>
      </div>
    </div>

    <!-- Available Storage Section -->
    <section class="mt-6">
      <h3 class="font-label-caps text-label-caps text-on-surface-variant mb-3 flex items-center gap-2 uppercase tracking-wider">
        <span class="material-symbols-outlined text-primary text-[18px]">grid_view</span>
        AVAILABLE STORAGE
      </h3>
      <div class="grid grid-cols-2 gap-3">
        <div
          v-for="rack in availableRacks"
          :key="rack.id"
          class="bg-surface-container-lowest p-3 rounded-xl border border-outline-variant flex flex-col items-center justify-center text-center"
          :class="rack.colSpan"
        >
          <span class="font-headline-md text-headline-md text-primary">{{ rack.id }}</span>
          <span class="text-[10px] font-bold px-2 py-1 rounded-full mt-2 bg-green-50 text-green-700">EMPTY</span>
          <p class="font-body-sm text-body-sm text-outline mt-1">{{ rack.zone }}</p>
        </div>
      </div>
    </section>

    <!-- Quick Scan Prompt -->
    <div class="mt-6 bg-primary-container text-on-primary-container p-4 rounded-xl flex items-center gap-4 border border-primary">
      <span class="material-symbols-outlined text-[32px] text-primary-fixed-dim" style="font-variation-settings: 'FILL' 1;">qr_code_2</span>
      <div>
        <h3 class="font-body-sm text-body-sm font-bold">Ready to Scan</h3>
        <p class="font-body-sm text-body-sm opacity-80">Point terminal at any SKU or Rack barcode to begin automated movement task.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const pendingTasks = ref([
  { sku: 'SKU-102', priority: 'URGENT', priorityClass: 'bg-error-container text-error', borderColor: 'border-2 border-error', action: 'Move to', target: 'Rack B-04', due: 'Due: 5 mins ago' },
  { sku: 'SKU-405', priority: 'NORMAL', priorityClass: 'bg-green-50 text-green-700', borderColor: 'border-outline-variant', action: 'Move to', target: 'Rack A-12', due: 'Due: In 20 mins' },
  { sku: 'SKU-089', priority: 'LOW', priorityClass: 'bg-surface-container-highest text-on-surface-variant', borderColor: 'border-outline-variant', action: 'Replenish', target: 'Rack D-01', due: 'Scheduled: 14:00' },
])

const availableRacks = ref([
  { id: 'B-05', zone: 'Zone: Cold', colSpan: '' },
  { id: 'C-22', zone: 'Zone: Ambient', colSpan: '' },
  { id: 'D-09', zone: 'Heavy Goods Optimized', colSpan: 'col-span-2' },
])
</script>
