<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Inventory</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Register New Product</span>
    </div>

    <!-- Form Title Section -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Registration Desk
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Enter technical specifications and logistical details to ingest a new item.
      </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Main Form Card -->
      <div class="lg:col-span-2">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 md:p-8 form-card">
          <form class="space-y-6" @submit.prevent="handleSubmit">
            <!-- Product Name & SKU -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="md:col-span-2 lg:col-span-1">
                <FormInput
                  v-model="form.productName"
                  label="Product Name"
                  placeholder="e.g. Industrial Servo Motor"
                  suffix-icon="barcode_scanner"
                  @suffix-click="scanProduct"
                />
              </div>
              <div class="grid grid-cols-2 gap-4 md:col-span-2 lg:col-span-1">
                <FormInput
                  v-model="form.sku"
                  label="SKU Code"
                  placeholder="SKU-000"
                  prefix="SKU"
                  :mono="true"
                />
                <FormInput
                  v-model="form.serialNo"
                  label="Serial No."
                  placeholder="SN-000"
                  prefix="SN"
                  :mono="true"
                />
              </div>
            </div>

            <!-- Category & Tags -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <FormTomSelect
                v-model="form.category"
                label="Category"
                placeholder="Select Category"
                :options="categoryOptions"
              />
              <FormTomSelect
                v-model="form.tags"
                label="Tags"
                placeholder="Add tags..."
                :options="tagOptions"
                :max-items="null"
                :create="true"
              />
            </div>

            <!-- Status Toggles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-2">
              <FormToggle
                v-model="form.activeStatus"
                label="Active Status"
              />
              <FormCheckbox
                v-model="form.expirationControl"
                label="Expiration Control"
              />
            </div>

            <!-- Notes -->
            <FormTextarea
              v-model="form.notes"
              label="Catatan Barang (Notes)"
              placeholder="Enter detailed handling instructions..."
              :rows="3"
            />

            <!-- Action Button -->
            <div class="pt-4 border-t border-outline-variant/30">
              <button
                type="submit"
                class="btn-primary w-full md:w-auto px-10"
              >
                <span class="material-symbols-outlined">add_box</span>
                Register Item
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Side Cards -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Image Upload -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 form-card">
          <FileUpload
            v-model="form.image"
            label="Product Visual"
            hint="High-res images aid in automated identification and QR validation."
          />
        </div>

        <!-- System Metadata -->
        <div class="bg-surface-container-highest/30 border border-outline-variant rounded-xl p-5">
          <h3 class="font-label-caps text-label-caps text-on-surface-variant mb-4 flex items-center gap-2 opacity-70">
            <span class="material-symbols-outlined text-xs">database</span>
            SYSTEM METADATA
          </h3>
          <div class="space-y-4">
            <div class="flex justify-between items-center border-b border-outline-variant/20 pb-2">
              <span class="text-body-sm text-on-surface-variant">Storage Zone</span>
              <span class="font-data-mono text-data-mono text-primary bg-primary-fixed/50 px-2 py-0.5 rounded">Z-04-B</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-body-sm text-on-surface-variant">Entry ID</span>
              <span class="font-data-mono text-data-mono text-on-surface">ID_882_X</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import FormInput from '../components/FormInput.vue'
import FormTomSelect from '../components/FormTomSelect.vue'
import FormToggle from '../components/FormToggle.vue'
import FormCheckbox from '../components/FormCheckbox.vue'
import FormTextarea from '../components/FormTextarea.vue'
import FileUpload from '../components/FileUpload.vue'
import { useToast } from '../composables/useToast'

const toast = useToast()

const categoryOptions = [
  { value: 'electronics', label: 'Industrial Electronics' },
  { value: 'mechanical', label: 'Mechanical Parts' },
  { value: 'safety', label: 'Safety Equipment' },
]

const tagOptions = [
  { value: 'Fragile', label: 'Fragile' },
  { value: 'High-Value', label: 'High-Value' },
  { value: 'Hazmat', label: 'Hazmat' },
  { value: 'Cold Storage', label: 'Cold Storage' },
  { value: 'Perishable', label: 'Perishable' },
  { value: 'Bulk', label: 'Bulk' },
]

const form = reactive({
  productName: '',
  sku: '',
  serialNo: '',
  category: '',
  tags: ['Fragile', 'High-Value'],
  activeStatus: true,
  expirationControl: false,
  notes: '',
  image: null,
})

const handleSubmit = () => {
  console.log('Register form submitted:', { ...form })
  toast.success('Item registered successfully!', 'Registered')
}

const scanProduct = () => {
  toast.info('Opening barcode scanner...', 'Scanner')
}
</script>
