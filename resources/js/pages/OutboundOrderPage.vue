<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Logistics</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Outbound Orders</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Outbound Orders
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Manage sales orders, work orders, and outbound shipment consolidation.
      </p>
    </div>

    <!-- Segmented Tab Control -->
    <div class="flex p-1 bg-surface-container-high rounded-xl mb-8">
      <button
        v-for="tab in tabs"
        :key="tab"
        class="flex-1 py-2 font-label-caps text-label-caps rounded-lg transition-all"
        :class="activeTab === tab ? 'bg-surface-container-lowest shadow-sm text-primary' : 'text-on-surface-variant'"
        @click="activeTab = tab"
      >
        {{ tab }}
      </button>
    </div>

    <!-- Work Order Summary -->
    <section class="mb-8">
      <h3 class="font-label-caps text-label-caps mb-4 text-on-surface-variant opacity-70">ACTIVE CONSOLIDATION</h3>
      <div class="bg-blue-50 border border-outline-variant rounded-xl p-5">
        <div class="flex justify-between items-start mb-6">
          <div>
            <p class="font-label-caps text-label-caps text-secondary mb-1">TOTAL PICK VOLUME</p>
            <h3 class="font-headline-lg text-headline-lg text-primary tracking-tighter">1,248 <span class="font-body-sm text-secondary">ctns</span></h3>
          </div>
          <span class="material-symbols-outlined text-primary text-3xl">inventory_2</span>
        </div>
        <div class="flex items-center gap-2 mb-4">
          <div class="flex-1 h-1.5 bg-white/50 rounded-full overflow-hidden">
            <div class="h-full bg-primary w-3/4"></div>
          </div>
          <span class="font-label-caps text-label-caps text-on-surface-variant">75%</span>
        </div>
        <p class="font-body-sm text-secondary leading-relaxed">
          Requirement for 8 grouped Sales Orders across 4 Staging Zones.
        </p>
      </div>
    </section>

    <!-- Sales Orders List -->
    <section class="mb-8">
      <div class="flex justify-between items-center mb-4">
        <h3 class="font-label-caps text-label-caps text-on-surface-variant opacity-70">CONFIRMED SALES ORDERS</h3>
        <button class="text-primary font-label-caps text-label-caps flex items-center gap-1">
          VIEW ALL <span class="material-symbols-outlined text-sm">chevron_right</span>
        </button>
      </div>
      <div class="space-y-4">
        <div
          v-for="order in salesOrders"
          :key="order.id"
          class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 transition-colors hover:bg-surface-container-low"
        >
          <div class="flex justify-between items-start mb-3">
            <div>
              <p class="font-label-caps text-label-caps text-on-surface-variant mb-0.5">{{ order.id }}</p>
              <h4 class="font-headline-md text-headline-md font-semibold text-on-surface">{{ order.customer }}</h4>
            </div>
            <span
              class="text-[10px] font-bold px-2 py-0.5 rounded-full flex items-center gap-1"
              :class="order.statusClass"
            >
              <span class="w-1 h-1 rounded-full" :class="order.dotClass"></span>
              {{ order.status }}
            </span>
          </div>
          <div class="flex justify-between items-center pt-3 border-t border-outline-variant">
            <div class="flex items-center gap-2">
              <span class="material-symbols-outlined text-secondary text-lg">{{ order.icon }}</span>
              <span class="font-data-mono text-data-mono text-on-surface-variant">{{ order.items }} Items</span>
            </div>
            <p class="font-data-mono text-data-mono text-secondary">{{ order.zone }}</p>
          </div>
        </div>

        <!-- Work Order Detailed Card -->
        <div class="bg-surface-container border border-outline-variant rounded-xl p-4 border-dashed">
          <div class="flex items-center gap-2 mb-4">
            <span class="material-symbols-outlined text-primary">assignment_turned_in</span>
            <h4 class="font-label-caps text-label-caps text-primary">QUEUED WORK ORDER #WO-402</h4>
          </div>
          <div class="space-y-3">
            <div class="flex justify-between items-center font-data-mono text-data-mono">
              <span class="text-on-surface-variant">Destination Staging</span>
              <span class="text-on-surface font-semibold">Bay 12, Pier 4</span>
            </div>
            <div class="flex justify-between items-center font-data-mono text-data-mono">
              <span class="text-on-surface-variant">Assigned SOs</span>
              <span class="text-on-surface font-semibold">SO-88291, SO-88292</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Create Work Order FAB -->
    <div class="mt-6">
      <button class="btn-primary w-full font-label-caps text-label-caps">
        <span class="material-symbols-outlined">add</span>
        CREATE WORK ORDER
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const tabs = ['Sales Orders', 'Work Orders']
const activeTab = ref('Sales Orders')

const salesOrders = ref([
  {
    id: 'SO-88291',
    customer: 'EverFresh Organics Ltd.',
    status: 'CONFIRMED',
    statusClass: 'bg-green-50 text-green-700',
    dotClass: 'bg-green-700',
    items: 420,
    zone: 'Zone A-04',
    icon: 'ac_unit',
  },
  {
    id: 'SO-88295',
    customer: 'Alpine Distribution Co.',
    status: 'PENDING',
    statusClass: 'bg-yellow-50 text-yellow-700',
    dotClass: 'bg-yellow-700',
    items: 156,
    zone: 'Zone B-12',
    icon: 'kitchen',
  },
])
</script>
