<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Inventory</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Barcode Generator</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Barcode Generator
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Generate SKU barcode labels for your warehouse inventory quickly and accurately.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Input Form Card -->
      <div class="lg:col-span-2">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 form-card space-y-4">
          <div class="space-y-1">
            <label class="font-body-sm text-body-sm font-bold text-on-surface-variant block">Product Name</label>
            <input
              v-model="form.name"
              class="w-full h-12 px-4 bg-white border border-outline-variant rounded focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all font-body-sm"
              placeholder="e.g. Steel Rod Heavy Duty"
              type="text"
            />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div class="space-y-1">
              <label class="font-body-sm text-body-sm font-bold text-on-surface-variant block">SKU Code</label>
              <input
                v-model="form.sku"
                class="w-full h-12 px-4 bg-white border border-outline-variant rounded focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all font-data-mono text-data-mono"
                placeholder="SKU-8829"
                type="text"
              />
            </div>
            <div class="space-y-1">
              <label class="font-body-sm text-body-sm font-bold text-on-surface-variant block">Label Count</label>
              <input
                v-model.number="form.count"
                class="w-full h-12 px-4 bg-white border border-outline-variant rounded focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all font-body-sm"
                min="1"
                type="number"
              />
            </div>
          </div>
          <div class="space-y-1">
            <label class="font-body-sm text-body-sm font-bold text-on-surface-variant block">Barcode Type</label>
            <FormTomSelect
              v-model="form.type"
              placeholder="Select barcode type"
              :options="barcodeTypeOptions"
            />
          </div>
          <button
            class="btn-primary w-full mt-4"
            @click="generate"
          >
            <span class="material-symbols-outlined">qr_code_2</span>
            Generate Barcode
          </button>
        </div>
      </div>

      <!-- Preview Section -->
      <div class="lg:col-span-1">
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm sticky top-24">
          <div class="p-4 flex flex-col items-center justify-center bg-white min-h-[180px]">
            <div class="w-full max-w-[280px] h-20 flex items-center justify-center">
              <div class="w-full h-full flex gap-[2px] p-2 items-end">
                <div v-for="(h, i) in barcodePattern" :key="i" class="bg-black" :style="{ width: h.w + 'px', height: h.h }"></div>
              </div>
            </div>
            <div class="mt-4 text-center">
              <p class="font-data-mono text-data-mono text-primary uppercase">{{ form.name || 'Product Name' }}</p>
              <p class="font-label-caps text-label-caps text-outline">LOC: WH-ZONE-A</p>
            </div>
          </div>
          <div class="grid grid-cols-2 border-t border-outline-variant">
            <button class="h-12 flex items-center justify-center gap-2 font-headline-md text-headline-md text-primary hover:bg-surface-container-low transition-colors border-r border-outline-variant">
              <span class="material-symbols-outlined text-[20px]">print</span>
              Print
            </button>
            <button class="h-12 flex items-center justify-center gap-2 font-headline-md text-headline-md text-primary hover:bg-surface-container-low transition-colors">
              <span class="material-symbols-outlined text-[20px]">save</span>
              Save
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import FormTomSelect from '../components/FormTomSelect.vue'

const form = reactive({
  name: 'Steel Rod Heavy Duty',
  sku: 'SKU-8829',
  count: 1,
  type: 'code128',
})

const barcodeTypeOptions = [
  { value: 'code128', label: 'Code 128 (Standard)' },
  { value: 'ean13', label: 'EAN-13 (Retail)' },
  { value: 'qrcode', label: 'QR Code (Dense Data)' },
  { value: 'datamatrix', label: 'Data Matrix' },
]

const barcodePattern = ref([
  { w: 1, h: '100%' }, { w: 2, h: '100%' }, { w: 1, h: '80%' }, { w: 3, h: '100%' },
  { w: 1, h: '75%' }, { w: 2, h: '100%' }, { w: 1, h: '100%' }, { w: 1, h: '83%' },
  { w: 2, h: '100%' }, { w: 1, h: '100%' }, { w: 1, h: '90%' }, { w: 3, h: '100%' },
  { w: 1, h: '100%' }, { w: 2, h: '70%' }, { w: 1, h: '100%' },
])

const generate = () => {
  alert(`Barcode generated for ${form.sku} (${form.count} labels)`)
}
</script>
