<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Inventory</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Warehouse Inventory</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Warehouse Inventory
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Browse and search all products in the warehouse inventory system.
      </p>
    </div>

    <!-- Search and Filters -->
    <section class="mb-6">
      <div class="relative w-full">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">search</span>
        <input
          v-model="searchQuery"
          class="w-full h-12 pl-12 pr-4 bg-surface-container-lowest border border-outline-variant rounded-lg focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none transition-all font-body-sm"
          placeholder="Search by SKU, Name or ID..."
          type="text"
        />
      </div>
      <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
        <span
          v-for="cat in categories"
          :key="cat"
          class="inline-flex items-center px-3 py-1 rounded-full font-label-caps text-label-sm cursor-pointer transition-colors"
          :class="activeCategory === cat ? 'bg-primary text-on-primary' : 'bg-surface-container border border-outline-variant text-on-surface-variant'"
          @click="activeCategory = cat"
        >{{ cat }}</span>
      </div>
    </section>

    <!-- Product Cards -->
    <div class="space-y-4">
      <div
        v-for="product in filteredProducts"
        :key="product.id"
        class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden active:scale-[0.98] transition-transform"
      >
        <div class="p-4 flex flex-col gap-3">
          <div class="flex justify-between items-start">
            <div class="flex flex-col">
              <h2 class="font-headline-md text-headline-md text-primary">{{ product.name }}</h2>
              <span class="font-label-caps text-label-caps text-outline">ID: {{ product.id }}</span>
            </div>
            <button class="w-12 h-12 flex items-center justify-center bg-surface-container rounded-lg text-primary">
              <span class="material-symbols-outlined">barcode_scanner</span>
            </button>
          </div>
          <div class="flex flex-wrap gap-2">
            <span class="px-2 py-0.5 bg-secondary-container text-on-primary rounded font-label-caps text-label-caps">{{ product.sku }}</span>
            <span class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed rounded font-label-caps text-label-caps">{{ product.category }}</span>
          </div>
          <p class="text-on-surface-variant font-body-sm line-clamp-2">{{ product.description }}</p>
          <div class="flex items-center justify-between border-t border-outline-variant pt-3 mt-2">
            <div class="flex flex-col">
              <span class="font-headline-md text-headline-md text-secondary">{{ product.price }}</span>
              <span class="font-label-caps text-label-caps text-outline">{{ product.unit }}</span>
            </div>
            <div class="h-10 w-24 bg-white border border-outline-variant flex items-center justify-center">
              <div class="w-full h-full flex gap-[2px] p-1 items-end">
                <div v-for="(h, i) in product.barcode" :key="i" class="bg-black" :style="{ width: h.w + 'px', height: h.h }"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const searchQuery = ref('')
const activeCategory = ref('All Products')
const categories = ['All Products', 'Electronics', 'Heavy Machinery', 'Perishables']

const products = ref([
  {
    id: 'PRD-902341-B',
    name: 'Industrial Drill Press X-500',
    sku: 'MACH-99',
    category: 'Heavy Machinery',
    description: 'High-torque industrial drill press with automated depth control and cooling system. Precision calibrated for steel alloys.',
    price: '$1,245.00',
    unit: 'per Piece',
    barcode: [
      { w: 1, h: '100%' }, { w: 2, h: '100%' }, { w: 1, h: '80%' }, { w: 3, h: '100%' },
      { w: 1, h: '75%' }, { w: 2, h: '100%' }, { w: 1, h: '100%' }, { w: 1, h: '83%' },
      { w: 2, h: '100%' }, { w: 1, h: '100%' },
    ],
  },
  {
    id: 'ACC-77120-K',
    name: 'Wireless Logistics Scanner 2',
    sku: 'SCAN-W-22',
    category: 'Electronics',
    description: 'Ruggedized handheld barcode scanner with long-range Bluetooth 5.0 and 12-hour battery life. Drop-tested to 2m.',
    price: '$389.50',
    unit: 'per Unit',
    barcode: [
      { w: 2, h: '100%' }, { w: 1, h: '100%' }, { w: 3, h: '83%' }, { w: 1, h: '100%' },
      { w: 1, h: '75%' }, { w: 2, h: '100%' }, { w: 2, h: '100%' }, { w: 1, h: '100%' },
      { w: 3, h: '100%' },
    ],
  },
  {
    id: 'LOG-0012-P',
    name: 'Reinforced Polymer Pallet',
    sku: 'PAL-STD',
    category: 'Logistics Support',
    description: 'Heavy-duty stackable polymer pallet. Steam-cleanable and resistant to chemicals. Standard 48" x 40" footprint.',
    price: '$84.00',
    unit: 'per Bundle (10x)',
    barcode: [
      { w: 1, h: '100%' }, { w: 4, h: '100%' }, { w: 1, h: '100%' }, { w: 2, h: '67%' },
      { w: 1, h: '100%' }, { w: 2, h: '100%' }, { w: 1, h: '100%' },
    ],
  },
])

const filteredProducts = computed(() => {
  return products.value.filter((p) => {
    const matchesSearch = !searchQuery.value || p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || p.sku.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchesCategory = activeCategory.value === 'All Products' || p.category === activeCategory.value
    return matchesSearch && matchesCategory
  })
})
</script>
