<template>
  <div>
    <!-- Navigation Breadcrumb -->
    <div class="mb-6 flex items-center gap-2 text-on-surface-variant font-body-sm">
      <span class="cursor-pointer hover:text-primary transition-colors">Operations</span>
      <span class="material-symbols-outlined text-sm">chevron_right</span>
      <span class="font-medium text-primary">Active Tasks</span>
    </div>

    <!-- Page Title -->
    <div class="mb-8">
      <h2 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-surface">
        Active Tasks
      </h2>
      <p class="font-body-sm text-on-surface-variant mt-2">
        Monitor and manage all ongoing warehouse tasks including putaway and picking operations.
      </p>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
      <button
        v-for="tab in tabs"
        :key="tab"
        class="px-4 py-2 rounded-full font-label-caps text-label-caps whitespace-nowrap transition-colors"
        :class="activeTab === tab ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high'"
        @click="activeTab = tab"
      >
        {{ tab }}
      </button>
    </div>

    <!-- Task List -->
    <div class="space-y-4">
      <div
        v-for="task in filteredTasks"
        :key="task.id"
        class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl shadow-sm hover:shadow-md transition-shadow"
      >
        <div class="flex justify-between items-start mb-3">
          <div>
            <span class="text-label-caps font-label-caps text-outline block mb-1 uppercase">Task ID</span>
            <span class="font-data-mono text-data-mono text-on-surface">{{ task.taskId }}</span>
          </div>
          <div
            class="px-2 py-1 rounded-md flex items-center gap-1"
            :class="task.type === 'Putaway' ? 'bg-green-50 text-green-700' : 'bg-orange-50 text-orange-700'"
          >
            <div class="w-1.5 h-1.5 rounded-full" :class="task.type === 'Putaway' ? 'bg-green-700' : 'bg-orange-700'"></div>
            <span class="font-label-caps text-label-caps uppercase">{{ task.type }}</span>
          </div>
        </div>
        <div class="space-y-3">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <span class="text-label-caps font-label-caps text-outline block mb-1 uppercase">
                {{ task.type === 'Putaway' ? 'Target Rack' : 'Source Rack' }}
              </span>
              <span class="font-headline-md text-headline-md text-on-surface">{{ task.rack }}</span>
            </div>
            <div>
              <span class="text-label-caps font-label-caps text-outline block mb-1 uppercase">Level</span>
              <span class="font-headline-md text-headline-md text-on-surface">{{ task.level }}</span>
            </div>
          </div>
          <div class="pt-3 border-t border-surface-container">
            <span class="text-label-caps font-label-caps text-outline block mb-1 uppercase">SKU Name</span>
            <p class="font-body-sm text-on-surface-variant">{{ task.skuName }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Scan Barcode Button -->
    <div class="mt-8">
      <button class="btn-primary w-full">
        <span class="material-symbols-outlined">barcode_scanner</span>
        Scan Barcode
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const tabs = ['All Tasks', 'Putaway', 'Picking']
const activeTab = ref('All Tasks')

const tasks = ref([
  { id: 1, taskId: '#PW-9842', type: 'Putaway', rack: 'Rack A12', level: 'Tier 3', skuName: 'Frozen Atlantic Salmon - 10kg Crates (Bulk)' },
  { id: 2, taskId: '#PK-1029', type: 'Picking', rack: 'Rack C04', level: 'Tier 1', skuName: 'Organic Spinach Mix - 500g Bags (40 per Box)' },
  { id: 3, taskId: '#PW-9845', type: 'Putaway', rack: 'Rack B09', level: 'Tier 5', skuName: 'Pre-baked Artisan Baguettes (Case of 24)' },
])

const filteredTasks = computed(() => {
  if (activeTab.value === 'All Tasks') return tasks.value
  return tasks.value.filter((t) => t.type === activeTab.value)
})
</script>
