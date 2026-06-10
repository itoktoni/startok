<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Logistics</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Inbound Management</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Inbound Management
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Track incoming shipments, process receiving, and manage inbound logistics.
      </p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 form-card">
        <div class="flex items-center gap-3 mb-3">
          <span class="material-symbols-outlined text-primary bg-primary-fixed/50 p-2 rounded-lg">local_shipping</span>
          <span class="font-label-caps text-label-caps text-on-surface-variant">Pending</span>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface">12</p>
      </div>
      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 form-card">
        <div class="flex items-center gap-3 mb-3">
          <span class="material-symbols-outlined text-secondary bg-secondary-fixed/50 p-2 rounded-lg">schedule</span>
          <span class="font-label-caps text-label-caps text-on-surface-variant">In Transit</span>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface">8</p>
      </div>
      <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 form-card">
        <div class="flex items-center gap-3 mb-3">
          <span class="material-symbols-outlined text-on-surface bg-surface-container/50 p-2 rounded-lg">check_circle</span>
          <span class="font-label-caps text-label-caps text-on-surface-variant">Received Today</span>
        </div>
        <p class="font-headline-md text-headline-md text-on-surface">5</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Inbound Form -->
      <div class="lg:col-span-1">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 md:p-6 form-card sticky top-24">
          <h3 class="font-headline-md text-headline-md text-on-surface mb-6">New Inbound</h3>
          <form class="space-y-4" @submit.prevent="handleSubmit">
            <FormInput
              v-model="form.poNumber"
              label="PO Number"
              placeholder="PO-2024-001"
              :mono="true"
            />
            <FormInput
              v-model="form.supplier"
              label="Supplier"
              placeholder="Supplier name"
            />
            <FormTomSelect
              v-model="form.warehouse"
              label="Destination Warehouse"
              placeholder="Select warehouse"
              :options="warehouseOptions"
            />
            <FormInput
              v-model="form.quantity"
              label="Expected Quantity"
              placeholder="0"
              type="number"
              :mono="true"
            />
            <FormTextarea
              v-model="form.notes"
              label="Notes"
              placeholder="Additional notes..."
              :rows="2"
            />
            <button
              type="submit"
              class="btn-primary w-full"
            >
              <span class="material-symbols-outlined">add_box</span>
              Create Inbound
            </button>
          </form>
        </div>
      </div>

      <!-- Inbound List -->
      <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between mb-2">
          <h3 class="font-headline-md text-headline-md text-on-surface">Recent Inbound</h3>
          <FormInput
            v-model="searchQuery"
            placeholder="Search PO..."
            class="w-64"
          />
        </div>

        <div
          v-for="item in filteredInbound"
          :key="item.id"
          class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 form-card hover:shadow-md transition-shadow"
        >
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex-1">
              <div class="flex items-center gap-3 mb-2">
                <span
                  class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] uppercase tracking-wider font-semibold"
                  :class="getStatusBadge(item.status)"
                >
                  {{ item.status }}
                </span>
                <span class="font-data-mono text-data-mono text-on-surface">{{ item.poNumber }}</span>
              </div>
              <p class="font-body-sm font-semibold text-on-surface mb-1">{{ item.supplier }}</p>
              <p class="text-body-sm text-on-surface-variant">{{ item.description }}</p>
            </div>
            <div class="flex items-center gap-6">
              <div class="text-right">
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Qty</p>
                <p class="font-data-mono text-data-mono text-on-surface">{{ item.quantity }}</p>
              </div>
              <div class="text-right">
                <p class="text-[10px] uppercase tracking-wider text-on-surface-variant">Date</p>
                <p class="text-body-sm text-on-surface-variant">{{ item.date }}</p>
              </div>
              <button class="p-2 hover:bg-surface-container rounded-full transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined">more_vert</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import FormInput from '../components/FormInput.vue'
import FormTomSelect from '../components/FormTomSelect.vue'
import FormTextarea from '../components/FormTextarea.vue'

const searchQuery = ref('')

const warehouseOptions = [
  { value: 'wh-01', label: 'Warehouse A - Main' },
  { value: 'wh-02', label: 'Warehouse B - Cold Storage' },
  { value: 'wh-03', label: 'Warehouse C - Hazmat' },
]

const form = reactive({
  poNumber: '',
  supplier: '',
  warehouse: '',
  quantity: '',
  notes: '',
})

const inboundItems = ref([
  {
    id: 1,
    poNumber: 'PO-2024-089',
    supplier: 'Shanghai Motors Co.',
    description: 'Industrial servo motors, 2kW variant. Bulk order Q2.',
    quantity: 150,
    date: 'Jun 8, 2026',
    status: 'Pending',
  },
  {
    id: 2,
    poNumber: 'PO-2024-088',
    supplier: 'Rheinmetall GmbH',
    description: 'Hydraulic assemblies and spare seals.',
    quantity: 45,
    date: 'Jun 7, 2026',
    status: 'In Transit',
  },
  {
    id: 3,
    poNumber: 'PO-2024-087',
    supplier: 'SafetyFirst Inc.',
    description: 'Emergency stop buttons and warning lights.',
    quantity: 500,
    date: 'Jun 6, 2026',
    status: 'Received',
  },
  {
    id: 4,
    poNumber: 'PO-2024-086',
    supplier: 'PCB Solutions Ltd.',
    description: 'Control board PCB-X4 batch production run.',
    quantity: 200,
    date: 'Jun 5, 2026',
    status: 'In Transit',
  },
  {
    id: 5,
    poNumber: 'PO-2024-085',
    supplier: 'SteelWorks Asia',
    description: 'Steel gear modules, Grade 4 hardened.',
    quantity: 80,
    date: 'Jun 4, 2026',
    status: 'Received',
  },
])

const filteredInbound = computed(() => {
  if (!searchQuery.value) return inboundItems.value
  const q = searchQuery.value.toLowerCase()
  return inboundItems.value.filter(
    (item) =>
      item.poNumber.toLowerCase().includes(q) ||
      item.supplier.toLowerCase().includes(q)
  )
})

const getStatusBadge = (status) => {
  switch (status) {
    case 'Pending':
      return 'bg-secondary-fixed text-on-secondary-fixed-variant'
    case 'In Transit':
      return 'bg-primary-fixed text-on-primary-fixed-variant'
    case 'Received':
      return 'bg-surface-container-highest text-on-surface-variant'
    default:
      return 'bg-surface-container text-on-surface-variant'
  }
}

const handleSubmit = () => {
  console.log('Inbound form submitted:', { ...form })
  alert('Inbound order created!')
  // Reset form
  form.poNumber = ''
  form.supplier = ''
  form.warehouse = ''
  form.quantity = ''
  form.notes = ''
}
</script>
