<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Check, ChevronDown, Menu, Moon, Phone, Sun, X, Zap } from '@lucide/vue';
import { services } from '../data/services';

defineProps({
  currentServiceIndex: {
    type: Number,
    required: true
  },
  isDark: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['select-service', 'navigate-service', 'navigate-home', 'navigate-section', 'toggle-theme', 'open-register']);
const isDropdownOpen = ref(false);
const isMobileMenuOpen = ref(false);
const isMobileServicesOpen = ref(false);
const dropdownRef = ref(null);

const handleSelectService = (index) => {
  emit('select-service', index);
  emit('navigate-service', index);
  isDropdownOpen.value = false;
  isMobileMenuOpen.value = false;
  isMobileServicesOpen.value = false;
};

const handleNavigateHome = () => {
  emit('navigate-home');
  isMobileMenuOpen.value = false;
};

const handleNavigateSection = (sectionId) => {
  emit('navigate-section', sectionId);
  isMobileMenuOpen.value = false;
};

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    isDropdownOpen.value = false;
  }
};

onMounted(() => document.addEventListener('click', handleClickOutside));
onUnmounted(() => document.removeEventListener('click', handleClickOutside));
</script>

<template>
  <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 shadow-sm shadow-slate-900/5 backdrop-blur-xl dark:border-white/10 dark:bg-[#060b13]/95 dark:shadow-none">
    <div class="mx-auto grid max-w-[1420px] grid-cols-[auto_1fr_auto] items-center gap-6 px-4 py-4 sm:px-6 lg:px-8">
      <a href="/" class="flex items-center gap-3 whitespace-nowrap" @click.prevent="handleNavigateHome">
        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-600/25">
          <Check class="h-7 w-7 stroke-[3.5]" />
        </span>
        <span class="text-xl font-black tracking-tight text-slate-950 dark:text-white">
          Đăng Ký Tích Xanh<span class="text-xs font-bold text-slate-500 dark:text-slate-400">.vn</span>
        </span>
      </a>

      <nav class="hidden items-center justify-center gap-6 whitespace-nowrap lg:flex">
        <a href="/" class="text-[15px] font-extrabold text-slate-800 hover:text-blue-600 dark:text-slate-200 dark:hover:text-white" @click.prevent="handleNavigateHome">Trang chủ</a>
        <div ref="dropdownRef" class="relative">
          <button class="flex items-center gap-1 text-[15px] font-extrabold text-slate-800 hover:text-blue-600 dark:text-slate-200 dark:hover:text-white" @click="isDropdownOpen = !isDropdownOpen">
            Dịch vụ
            <ChevronDown class="h-4 w-4 transition" :class="{ 'rotate-180': isDropdownOpen }" />
          </button>
          <Transition name="fade">
            <div v-if="isDropdownOpen" class="absolute left-1/2 mt-3 w-80 -translate-x-1/2 rounded-2xl border border-slate-200 bg-white p-2 shadow-2xl shadow-slate-900/10 dark:border-white/10 dark:bg-[#111b2f]">
              <a
                v-for="(service, index) in services"
                :key="service.id"
                :href="`/dich-vu/${service.id}`"
                class="flex gap-3 rounded-xl px-3 py-3 text-left text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white"
                :class="{ 'bg-blue-50 font-bold text-blue-700 dark:bg-blue-500/10 dark:text-blue-300': currentServiceIndex === index }"
                @click.prevent="handleSelectService(index)"
              >
                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                  <Check class="h-5 w-5 stroke-[3]" />
                </span>
                <span>
                  <span class="block font-black">{{ service.name }}</span>
                  <span class="mt-1 block text-xs font-medium text-slate-500 dark:text-slate-400">Tư vấn nhanh trong 5-15 phút</span>
                </span>
              </a>
            </div>
          </Transition>
        </div>
        <a href="/#loi-ich" class="text-[15px] font-extrabold text-slate-800 hover:text-blue-600 dark:text-slate-200 dark:hover:text-white" @click.prevent="handleNavigateSection('loi-ich')">Lợi ích</a>
        <a href="/#bang-gia" class="text-[15px] font-extrabold text-slate-800 hover:text-blue-600 dark:text-slate-200 dark:hover:text-white" @click.prevent="handleNavigateSection('bang-gia')">Bảng giá</a>
        <a href="/#quy-trinh" class="text-[15px] font-extrabold text-slate-800 hover:text-blue-600 dark:text-slate-200 dark:hover:text-white" @click.prevent="handleNavigateSection('quy-trinh')">Quy trình</a>
        <a href="/tin-tuc" class="text-[15px] font-extrabold text-slate-800 hover:text-blue-600 dark:text-slate-200 dark:hover:text-white" @click.prevent="handleNavigateSection('tin-tuc')">Tin tức</a>
        <a href="/#contact" class="text-[15px] font-extrabold text-slate-800 hover:text-blue-600 dark:text-slate-200 dark:hover:text-white" @click.prevent="handleNavigateSection('contact')">Liên hệ</a>
      </nav>

      <div class="hidden items-center gap-3 lg:flex">
        <button class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-sm font-black text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-500/10" @click="emit('open-register')">
          <Zap class="h-4 w-4" />
          Đăng ký nhanh
        </button>
        <button class="rounded-full border border-slate-200 p-3 text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:text-slate-200 dark:hover:bg-white/10" title="Đổi giao diện" @click="emit('toggle-theme', !isDark)">
          <Sun v-if="isDark" class="h-5 w-5" />
          <Moon v-else class="h-5 w-5" />
        </button>
        <a href="tel:0968825068" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-3 text-sm font-black text-white shadow-lg shadow-blue-600/20 hover:bg-blue-500">
          <Phone class="h-4 w-4" />
          0968.825.068
        </a>
      </div>

      <div class="flex items-center justify-end gap-2 lg:hidden">
        <button class="rounded-full border border-slate-200 p-2 text-slate-700 dark:border-white/10 dark:text-slate-200" @click="emit('toggle-theme', !isDark)">
          <Sun v-if="isDark" class="h-5 w-5" />
          <Moon v-else class="h-5 w-5" />
        </button>
        <a href="tel:0968825068" class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-white">
          <Phone class="h-4 w-4" />
        </a>
        <button class="rounded-lg p-2 text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-white/10" @click="isMobileMenuOpen = !isMobileMenuOpen">
          <Menu v-if="!isMobileMenuOpen" class="h-6 w-6" />
          <X v-else class="h-6 w-6" />
        </button>
      </div>
    </div>

    <div v-if="isMobileMenuOpen" class="border-t border-slate-200 bg-white px-4 py-4 dark:border-white/10 dark:bg-[#111b2f] lg:hidden">
      <div class="space-y-1">
        <a href="/" class="block rounded-lg px-3 py-2 font-bold text-slate-800 hover:bg-blue-50 dark:text-slate-200 dark:hover:bg-white/10" @click.prevent="handleNavigateHome">Trang chủ</a>
        <button class="flex w-full items-center justify-between rounded-lg px-3 py-2 font-bold text-slate-800 hover:bg-blue-50 dark:text-slate-200 dark:hover:bg-white/10" @click="isMobileServicesOpen = !isMobileServicesOpen">
          <span>Dịch vụ</span>
          <ChevronDown class="h-5 w-5 transition" :class="{ 'rotate-180': isMobileServicesOpen }" />
        </button>
        <div v-if="isMobileServicesOpen" class="rounded-lg bg-slate-50 p-2 dark:bg-[#060b13]">
          <a
            v-for="(service, index) in services"
            :key="service.id"
            :href="`/dich-vu/${service.id}`"
            class="block rounded-lg px-3 py-2 text-sm font-bold text-slate-700 hover:text-blue-700 dark:text-slate-300 dark:hover:text-white"
            @click.prevent="handleSelectService(index)"
          >
            {{ service.name }}
          </a>
        </div>
        <a href="/#loi-ich" class="block rounded-lg px-3 py-2 font-bold text-slate-800 hover:bg-blue-50 dark:text-slate-200 dark:hover:bg-white/10" @click.prevent="handleNavigateSection('loi-ich')">Lợi ích</a>
        <a href="/#bang-gia" class="block rounded-lg px-3 py-2 font-bold text-slate-800 hover:bg-blue-50 dark:text-slate-200 dark:hover:bg-white/10" @click.prevent="handleNavigateSection('bang-gia')">Bảng giá</a>
        <a href="/#quy-trinh" class="block rounded-lg px-3 py-2 font-bold text-slate-800 hover:bg-blue-50 dark:text-slate-200 dark:hover:bg-white/10" @click.prevent="handleNavigateSection('quy-trinh')">Quy trình</a>
        <a href="/tin-tuc" class="block rounded-lg px-3 py-2 font-bold text-slate-800 hover:bg-blue-50 dark:text-slate-200 dark:hover:bg-white/10" @click.prevent="handleNavigateSection('tin-tuc')">Tin tức</a>
        <button class="flex w-full items-center gap-2 rounded-lg px-3 py-2 font-black text-blue-600 dark:text-blue-400" @click="emit('open-register'); isMobileMenuOpen = false">
          <Zap class="h-4 w-4" />
          Đăng ký nhanh
        </button>
      </div>
    </div>
  </header>
</template>
