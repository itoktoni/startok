<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Operations</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Putaway Process</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Put Away Process
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Scan items, locate target racks, and confirm placement for inbound storage.
      </p>
    </div>

    <!-- Progress Indicator -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 mb-6">
      <div class="flex items-center justify-between relative mb-2">
        <div class="absolute top-1/2 left-0 w-full h-[2px] bg-outline-variant -z-10 -translate-y-1/2"></div>
        <div class="absolute top-1/2 left-0 h-[2px] bg-secondary -z-10 -translate-y-1/2" :style="{ width: ((currentStep - 1) / 2 * 100) + '%' }"></div>
        <div
          v-for="step in steps"
          :key="step.num"
          class="flex flex-col items-center gap-1 z-10"
        >
          <div
            class="w-8 h-8 rounded-full flex items-center justify-center font-body-sm text-body-sm font-bold border-4 border-surface-container-lowest"
            :class="step.num <= currentStep ? 'bg-secondary text-white' : 'bg-surface-container-highest text-on-surface-variant'"
          >{{ step.num }}</div>
          <span
            class="font-body-sm text-body-sm"
            :class="step.num <= currentStep ? 'text-secondary' : 'text-on-surface-variant'"
          >{{ step.label }}</span>
        </div>
      </div>
    </div>

    <!-- Scan Item Section -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden mb-6">
      <div class="p-4">
        <label class="block font-body-sm text-body-sm font-bold text-on-surface-variant mb-2">Item Barcode</label>
        <div class="flex gap-2">
          <div class="relative flex-grow">
            <input
              v-model="barcodeInput"
              class="w-full h-12 border border-outline rounded-lg px-4 focus:ring-2 focus:ring-primary-container focus:outline-none bg-surface-container-lowest font-body-sm"
              placeholder="Scan or enter SKU"
              type="text"
            />
            <span class="material-symbols-outlined absolute right-3 top-3 text-on-surface-variant">qr_code_2</span>
          </div>
          <button
            class="btn-primary px-4"
            @click="verifyItem"
          >Verify</button>
        </div>
      </div>
      <!-- Product Preview -->
      <div class="bg-surface-container-low p-4 border-t border-outline-variant flex gap-4 items-center">
        <div class="w-20 h-20 bg-surface-container-lowest border border-outline-variant rounded-lg flex items-center justify-center shrink-0">
          <span class="material-symbols-outlined text-slate-400 text-3xl">inventory_2</span>
        </div>
        <div>
          <h3 class="font-body-sm text-body-sm font-bold text-primary">SKU: IND-992-BX</h3>
          <p class="font-body-sm text-body-sm text-on-surface-variant">Industrial Strength Sealant</p>
          <div class="mt-1">
            <span class="bg-green-50 text-green-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">In-Transit</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Suggested Location -->
    <div class="grid grid-cols-2 gap-4 mb-6">
      <div class="col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-4">
        <div class="flex justify-between items-start mb-3">
          <div>
            <span class="font-label-caps text-label-caps text-on-surface-variant">Suggested Rack</span>
            <h4 class="font-headline-lg text-headline-lg text-primary">ZONE-A / B4-R2</h4>
          </div>
          <span class="material-symbols-outlined text-secondary text-4xl" style="font-variation-settings: 'FILL' 1;">location_on</span>
        </div>
        <div class="flex justify-between items-center py-2 border-t border-outline-variant">
          <span class="text-on-surface-variant font-body-sm text-body-sm">Current Capacity</span>
          <span class="font-body-sm text-body-sm font-bold text-primary">65% Full</span>
        </div>
      </div>
      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col justify-center items-center text-center">
        <span class="material-symbols-outlined text-on-surface-variant mb-1">inventory_2</span>
        <span class="font-body-sm text-body-sm text-on-surface-variant">Qty to Put Away</span>
        <span class="font-headline-md text-headline-md text-primary">12 Units</span>
      </div>
      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col justify-center items-center text-center">
        <span class="material-symbols-outlined text-on-surface-variant mb-1">forklift</span>
        <span class="font-body-sm text-body-sm text-on-surface-variant">Handling</span>
        <span class="font-headline-md text-headline-md text-primary">Heavy</span>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3">
      <button
        class="btn-primary w-full"
        @click="confirmPlacement"
      >
        <span class="material-symbols-outlined">check_circle</span>
        Confirm Placement
      </button>
      <button class="btn-primary-outline w-full">
        Flag Issue / Full Rack
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const currentStep = ref(1)
const barcodeInput = ref('')

const steps = [
  { num: 1, label: 'Scan Item' },
  { num: 2, label: 'Rack Loc' },
  { num: 3, label: 'Confirm' },
]

const verifyItem = () => {
  if (barcodeInput.value) {
    currentStep.value = 2
  }
}

const confirmPlacement = () => {
  currentStep.value = 3
  alert('Placement confirmed!')
}
</script>
