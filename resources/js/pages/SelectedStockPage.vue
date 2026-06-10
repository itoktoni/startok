<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Inventory</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Selected Stock</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Stock Detail
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        View detailed stock information, storage locations, and inventory breakdown.
      </p>
    </div>

    <!-- Search -->
    <div class="mb-8">
      <div class="relative group">
        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
        <input
          v-model="searchQuery"
          class="w-full h-12 pl-10 pr-10 bg-surface-container-lowest border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all font-body-sm"
          placeholder="Search SKU or Barcode..."
          type="text"
        />
        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline cursor-pointer">barcode_scanner</span>
      </div>
    </div>

    <!-- Selected Item Card -->
    <section class="mb-8">
      <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="p-5 border-b border-outline-variant bg-surface-container-low">
          <div class="flex justify-between items-start mb-2">
            <span class="font-label-caps text-label-caps text-on-surface-variant uppercase tracking-widest">Selected Item</span>
            <div class="flex items-center gap-1.5 px-2 py-1 rounded bg-secondary-container text-on-primary">
              <div class="w-2 h-2 rounded-full bg-primary"></div>
              <span class="text-[10px] font-bold">ACTIVE</span>
            </div>
          </div>
          <h2 class="font-headline-md text-headline-md text-on-surface mb-1">Arctic Chill - Organic Blueberry</h2>
          <p class="font-data-mono text-data-mono text-outline">SKU: BLU-2930-OR-V</p>
        </div>

        <div class="grid grid-cols-3 divide-x divide-outline-variant">
          <div class="p-4 text-center">
            <p class="font-label-caps text-label-caps text-outline mb-1">STOCK REAL</p>
            <p class="font-headline-md text-headline-md text-on-surface">1,240</p>
          </div>
          <div class="p-4 text-center">
            <p class="font-label-caps text-label-caps text-outline mb-1">SALES</p>
            <p class="font-headline-md text-headline-md text-on-surface">142</p>
          </div>
          <div class="p-4 text-center">
            <div class="mb-1 inline-flex items-center gap-1">
              <span class="font-label-caps text-label-caps text-primary">AVAILABLE</span>
            </div>
            <p class="font-headline-md text-headline-md text-primary font-bold">1,098</p>
          </div>
        </div>

        <div class="p-5 bg-surface-container-lowest">
          <h3 class="font-label-caps text-label-caps text-outline mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">location_on</span>
            STORAGE LOCATIONS
          </h3>
          <div class="space-y-3">
            <div
              v-for="loc in storageLocations"
              :key="loc.id"
              class="flex items-center justify-between p-3 rounded-lg border border-outline-variant bg-white"
            >
              <div class="flex flex-col">
                <span class="font-data-mono text-data-mono text-on-surface font-bold">{{ loc.rack }}</span>
                <span class="text-[11px] text-outline">Pallet ID: {{ loc.palletId }}</span>
              </div>
              <span
                class="px-2.5 py-1 rounded-full font-label-caps text-label-caps border"
                :class="loc.statusClass"
              >{{ loc.status }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Inventory List Table -->
    <section>
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-headline-md text-headline-md text-on-surface">Inventory List</h3>
        <button class="flex items-center gap-1 text-primary font-label-caps text-label-caps hover:underline">
          <span class="material-symbols-outlined text-[18px]">filter_list</span>
          FILTER
        </button>
      </div>
      <div class="bg-white border border-outline-variant rounded-xl overflow-hidden">
        <div class="grid grid-cols-12 gap-2 p-3 bg-surface-container-high border-b border-outline-variant">
          <div class="col-span-7 font-label-caps text-[10px] text-on-surface-variant">ITEM NAME</div>
          <div class="col-span-3 font-label-caps text-[10px] text-on-surface-variant text-right">SKU</div>
          <div class="col-span-2 font-label-caps text-[10px] text-on-surface-variant text-right">STOCK</div>
        </div>
        <div class="divide-y divide-outline-variant/30">
          <div
            v-for="item in inventoryList"
            :key="item.sku"
            class="grid grid-cols-12 gap-2 p-3 items-center hover:bg-surface-container-low transition-colors"
          >
            <div class="col-span-7">
              <p class="font-data-mono text-data-mono text-on-surface truncate">{{ item.name }}</p>
            </div>
            <div class="col-span-3 text-right">
              <p class="text-[11px] text-outline">{{ item.sku }}</p>
            </div>
            <div class="col-span-2 text-right">
              <p class="font-data-mono text-data-mono" :class="item.stock < 20 ? 'text-error font-bold' : 'text-on-surface'">{{ item.stock }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const searchQuery = ref('')

const storageLocations = ref([
  { id: 1, rack: 'Rack A-12 | Lvl 4', palletId: '#PL-90231', status: 'OPTIMAL', statusClass: 'bg-green-100 text-green-700 border-green-200' },
  { id: 2, rack: 'Rack B-04 | Lvl 2', palletId: '#PL-88129', status: 'LOW TEMP', statusClass: 'bg-orange-100 text-orange-700 border-orange-200' },
])

const inventoryList = ref([
  { name: 'Whole Frozen Strawberry 1kg', sku: 'STR-112', stock: 450 },
  { name: 'Raspberry Coulis Premium', sku: 'RAS-009', stock: 12 },
  { name: 'Wild Honeycomb Chill Pack', sku: 'HON-942', stock: 2105 },
  { name: 'Diced Mango Tropical Mix', sku: 'MAN-310', stock: 89 },
])
</script>
