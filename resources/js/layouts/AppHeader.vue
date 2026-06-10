<template>
  <header
    class="fixed top-0 w-full z-50 bg-surface-container-lowest shadow-sm border-b border-outline-variant flex items-center justify-between px-margin-mobile md:px-margin-desktop h-16"
  >
    <div class="flex items-center gap-4">
      <button
        class="md:hidden p-2 hover:bg-surface-container rounded-full transition-colors"
        @click="$emit('toggle-drawer')"
      >
        <span class="material-symbols-outlined text-on-surface-variant">menu</span>
      </button>
      <button
        class="hidden md:block p-2 hover:bg-surface-container rounded-full transition-colors"
        @click="$emit('toggle-sidebar')"
      >
        <span class="material-symbols-outlined text-on-surface-variant">menu</span>
      </button>
      <router-link to="/" class="font-headline-md text-headline-md font-bold text-primary">
        WMS Portal
      </router-link>
    </div>

    <div class="flex items-center gap-2">
      <!-- Notification Dropdown -->
      <div class="relative" ref="notifRef">
        <button
          class="relative p-2 hover:bg-surface-container rounded-full transition-colors text-on-surface-variant"
          @click="notifOpen = !notifOpen; profileOpen = false"
        >
          <span class="material-symbols-outlined">notifications</span>
          <span
            v-if="unreadCount > 0"
            class="absolute top-1 right-1 w-4 h-4 bg-error text-on-error text-[10px] font-bold rounded-full flex items-center justify-center"
          >{{ unreadCount }}</span>
        </button>

        <Transition name="dropdown">
          <div
            v-if="notifOpen"
            class="absolute right-0 top-full mt-2 w-80 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-lg overflow-hidden z-50"
          >
            <div class="flex items-center justify-between px-4 py-3 border-b border-outline-variant">
              <span class="font-headline-md text-headline-md text-on-surface">Notifications</span>
              <button
                class="font-label-caps text-label-caps text-primary hover:underline"
                @click="markAllRead"
              >Mark all read</button>
            </div>
            <div class="max-h-80 overflow-y-auto">
              <div
                v-for="notif in notifications"
                :key="notif.id"
                class="flex items-start gap-3 px-4 py-3 hover:bg-surface-container-low transition-colors border-b border-outline-variant/30 cursor-pointer"
                :class="{ 'bg-primary-fixed/10': !notif.read }"
              >
                <span
                  class="material-symbols-outlined mt-0.5 shrink-0"
                  :class="notif.iconColor"
                >{{ notif.icon }}</span>
                <div class="flex-1 min-w-0">
                  <p class="font-body-sm text-body-sm text-on-surface" :class="{ 'font-semibold': !notif.read }">{{ notif.title }}</p>
                  <p class="font-label-caps text-label-caps text-on-surface-variant mt-0.5">{{ notif.time }}</p>
                </div>
                <div v-if="!notif.read" class="w-2 h-2 bg-primary rounded-full shrink-0 mt-2"></div>
              </div>
              <div v-if="notifications.length === 0" class="px-4 py-8 text-center">
                <span class="material-symbols-outlined text-3xl text-outline-variant mb-2">notifications_off</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant">No notifications</p>
              </div>
            </div>
            <div class="px-4 py-2 border-t border-outline-variant text-center">
              <button class="font-label-caps text-label-caps text-primary hover:underline">View All Notifications</button>
            </div>
          </div>
        </Transition>
      </div>

      <!-- Profile Dropdown -->
      <div class="relative" ref="profileRef">
        <button
          class="w-8 h-8 rounded-full bg-secondary-container flex items-center justify-center overflow-hidden border border-outline-variant hover:ring-2 hover:ring-primary/20 transition-all"
          @click="profileOpen = !profileOpen; notifOpen = false"
        >
          <span class="material-symbols-outlined text-on-primary text-sm">person</span>
        </button>

        <Transition name="dropdown">
          <div
            v-if="profileOpen"
            class="absolute right-0 top-full mt-2 w-64 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-lg overflow-hidden z-50"
          >
            <div class="px-4 py-3 border-b border-outline-variant">
              <p class="font-body-sm font-semibold text-on-surface">Warehouse Admin</p>
              <p class="font-label-caps text-label-caps text-on-surface-variant">admin@wms.com</p>
            </div>
            <div class="py-1">
              <button
                v-for="item in profileMenu"
                :key="item.label"
                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-surface-container-low transition-colors text-on-surface-variant hover:text-on-surface"
                @click="handleProfileAction(item.action)"
              >
                <span class="material-symbols-outlined text-xl">{{ item.icon }}</span>
                <span class="font-body-sm text-body-sm">{{ item.label }}</span>
              </button>
            </div>
            <div class="border-t border-outline-variant py-1">
              <button
                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-error-container/30 transition-colors text-error"
                @click="handleProfileAction('logout')"
              >
                <span class="material-symbols-outlined text-xl">logout</span>
                <span class="font-body-sm text-body-sm font-semibold">Sign Out</span>
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

defineEmits(['toggle-drawer', 'toggle-sidebar'])

const notifOpen = ref(false)
const profileOpen = ref(false)
const notifRef = ref(null)
const profileRef = ref(null)

const unreadCount = ref(3)

const notifications = ref([
  { id: 1, icon: 'local_shipping', iconColor: 'text-primary', title: 'Inbound shipment #IN-2024-089 arrived at Dock 4', time: '5 min ago', read: false },
  { id: 2, icon: 'warning', iconColor: 'text-error', title: 'Low stock alert: Lithium Battery Packs (48V) — 12 units left', time: '23 min ago', read: false },
  { id: 3, icon: 'assignment_turned_in', iconColor: 'text-secondary', title: 'Putaway task #PW-9842 completed by J. Doe', time: '1 hour ago', read: false },
  { id: 4, icon: 'sync', iconColor: 'text-on-surface-variant', title: 'Stock relocation Zone C → Zone B finished', time: '2 hours ago', read: true },
  { id: 5, icon: 'person', iconColor: 'text-on-surface-variant', title: 'S. Lee started shift at 08:00 AM', time: '3 hours ago', read: true },
])

const profileMenu = [
  { label: 'My Profile', icon: 'person', action: 'profile' },
  { label: 'Settings', icon: 'settings', action: 'settings' },
  { label: 'Help & Support', icon: 'help', action: 'help' },
]

const markAllRead = () => {
  notifications.value.forEach(n => n.read = true)
  unreadCount.value = 0
}

const handleProfileAction = (action) => {
  profileOpen.value = false
  console.log('Profile action:', action)
}

const handleClickOutside = (e) => {
  if (notifRef.value && !notifRef.value.contains(e.target)) {
    notifOpen.value = false
  }
  if (profileRef.value && !profileRef.value.contains(e.target)) {
    profileOpen.value = false
  }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<style scoped>
.dropdown-enter-active,
.dropdown-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
