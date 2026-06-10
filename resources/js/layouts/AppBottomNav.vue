<template>
  <nav
    class="md:hidden fixed bottom-0 left-0 w-full h-16 bg-surface-container-lowest border-t border-outline-variant z-50 shadow-[0_-4px_10px_rgba(0,0,0,0.05)]"
  >
    <div class="flex items-center justify-around h-full px-2">
      <!-- Left 1 -->
      <router-link
        to="/stock"
        class="flex flex-col items-center justify-center transition-all flex-1"
        :class="isActive('/stock') ? 'text-primary opacity-100' : 'text-on-surface-variant opacity-60 hover:opacity-100'"
      >
        <span class="material-symbols-outlined text-[24px]">inventory_2</span>
        <span class="text-[10px] font-bold uppercase tracking-tighter">Stock</span>
      </router-link>

      <!-- Left 2 -->
      <router-link
        to="/active-tasks"
        class="flex flex-col items-center justify-center transition-all flex-1"
        :class="isActive('/active-tasks') ? 'text-primary opacity-100' : 'text-on-surface-variant opacity-60 hover:opacity-100'"
      >
        <span class="material-symbols-outlined text-[24px]">assignment_turned_in</span>
        <span class="text-[10px] font-bold uppercase tracking-tighter">Tasks</span>
      </router-link>

      <!-- Center Button -->
      <div class="flex items-center justify-center flex-1 -mt-5">
        <router-link
          :to="isHome ? '/register' : '/dashboard'"
          class="flex items-center justify-center bg-primary text-on-primary w-14 h-14 rounded-2xl shadow-lg ring-4 ring-surface-container-lowest active:scale-90 transition-all"
        >
          <span class="material-symbols-outlined text-[28px]">
            {{ isHome ? 'add' : 'home' }}
          </span>
        </router-link>
      </div>

      <!-- Right 1 -->
      <router-link
        to="/inbound"
        class="flex flex-col items-center justify-center transition-all flex-1"
        :class="isActive('/inbound') ? 'text-primary opacity-100' : 'text-on-surface-variant opacity-60 hover:opacity-100'"
      >
        <span class="material-symbols-outlined text-[24px]">move_to_inbox</span>
        <span class="text-[10px] font-bold uppercase tracking-tighter">Inbound</span>
      </router-link>

      <!-- Right 2 -->
      <router-link
        to="/outbound-orders"
        class="flex flex-col items-center justify-center transition-all flex-1"
        :class="isActive('/outbound-orders') ? 'text-primary opacity-100' : 'text-on-surface-variant opacity-60 hover:opacity-100'"
      >
        <span class="material-symbols-outlined text-[24px]">output</span>
        <span class="text-[10px] font-bold uppercase tracking-tighter">Outbound</span>
      </router-link>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()

const isHome = computed(() => route.path === '/dashboard')
const isActive = (path) => route.path === path
</script>
