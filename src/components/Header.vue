<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { ChevronDown, Phone, Sun, Moon, Menu, X, Zap, Check } from '@lucide/vue';
import { services } from '../data/services';

defineProps({
  currentServiceIndex: {
    type: Number,
    required: true
  }
});

const emit = defineEmits(['select-service', 'navigate-service', 'navigate-home', 'navigate-section', 'toggle-theme', 'open-register']);

const isDropdownOpen = ref(false);
const isMobileMenuOpen = ref(false);
const isMobileServicesOpen = ref(false);
const isDarkMode = ref(true);
const dropdownRef = ref(null);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
  isDropdownOpen.value = false;
};

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

const toggleTheme = () => {
  isDarkMode.value = !isDarkMode.value;
  emit('toggle-theme', isDarkMode.value);
};

const handleClickOutside = (event) => {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    closeDropdown();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <header class="sticky top-0 z-50 w-full border-b border-slate-200 bg-white/95 shadow-sm shadow-slate-900/5 dark:border-[#22304a] dark:bg-[#060b13] dark:shadow-none">
    <div class="mx-auto grid max-w-[1420px] grid-cols-[auto_1fr_auto] items-center gap-8 px-4 py-4 sm:px-6 lg:px-8">
      <a href="/" @click.prevent="handleNavigateHome" class="group flex items-center gap-2 whitespace-nowrap">
        <span class="flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-white shadow-lg shadow-blue-500/25">
          <Check class="h-7 w-7 stroke-[3.5]" />
        </span>
        <span class="font-sans text-[21px] font-extrabold tracking-tight text-slate-950 dark:text-white">
          Đăng Ký Tích Xanh<span class="text-xs font-semibold text-slate-500 dark:text-slate-400">.vn</span>
        </span>
      </a>

      <nav class="hidden items-center justify-center gap-7 whitespace-nowrap md:flex">
        <a href="/" @click.prevent="handleNavigateHome" class="text-[17px] font-bold text-slate-800 transition-colors hover:text-blue-600 dark:text-slate-200 dark:hover:text-white">Trang chủ</a>

        <div ref="dropdownRef" class="relative">
          <button
            @click="toggleDropdown"
            class="flex items-center gap-1 text-[17px] font-bold text-slate-800 transition-colors hover:text-blue-600 focus:outline-none dark:text-slate-200 dark:hover:text-white"
            :class="{ 'text-blue-600 dark:text-blue-400': isDropdownOpen }"
          >
            Dịch vụ
            <ChevronDown class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': isDropdownOpen }" />
          </button>

          <Transition name="fade">
            <div
              v-if="isDropdownOpen"
              class="absolute left-1/2 mt-3 w-72 -translate-x-1/2 rounded-xl border border-slate-200 bg-white/95 p-2 shadow-2xl shadow-slate-900/10 backdrop-blur-xl ring-1 ring-black/5 dark:border-white/10 dark:bg-slate-900/95"
            >
              <a
                v-for="(service, index) in services"
                :key="service.id"
                :href="`/dich-vu/${service.id}`"
                @click.prevent="handleSelectService(index)"
                class="flex w-full items-center gap-3 rounded-lg px-3 py-3 text-left text-sm text-slate-700 transition-all hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white"
                :class="{ 'bg-blue-600/10 font-semibold text-blue-600 dark:text-blue-400': currentServiceIndex === index }"
              >
                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-blue-600 dark:bg-slate-800 dark:text-blue-400">
                  <Check class="h-5 w-5 stroke-[3]" />
                </span>
                <span class="flex-1">
                  <span class="block font-bold leading-none">{{ service.name }}</span>
                  <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">Hỗ trợ nhanh trong 15'</span>
                </span>
              </a>
            </div>
          </Transition>
        </div>

        <a href="/#loi-ich" @click.prevent="handleNavigateSection('loi-ich')" class="text-[17px] font-bold text-slate-800 transition-colors hover:text-blue-600 dark:text-slate-200 dark:hover:text-white">Lợi ích</a>
        <a href="/#bang-gia" @click.prevent="handleNavigateSection('bang-gia')" class="text-[17px] font-bold text-slate-800 transition-colors hover:text-blue-600 dark:text-slate-200 dark:hover:text-white">Bảng giá</a>
        <a href="/#quy-trinh" @click.prevent="handleNavigateSection('quy-trinh')" class="text-[17px] font-bold text-slate-800 transition-colors hover:text-blue-600 dark:text-slate-200 dark:hover:text-white">Quy trình</a>
        <a href="/tin-tuc" @click.prevent="handleNavigateSection('tin-tuc')" class="text-[17px] font-bold text-slate-800 transition-colors hover:text-blue-600 dark:text-slate-200 dark:hover:text-white">Tin tức</a>
      </nav>

      <div class="hidden items-center gap-4 whitespace-nowrap md:flex">
        <button
          @click="emit('open-register')"
          class="flex items-center gap-1.5 text-[17px] font-extrabold text-blue-600 transition-colors hover:text-blue-500 dark:text-blue-400"
        >
          <Zap class="h-4 w-4 fill-blue-400/20" />
          Đăng ký nhanh
        </button>

        <button
          @click="toggleTheme"
          class="rounded-full border border-slate-200 p-3 text-slate-700 transition-colors hover:bg-slate-50 hover:text-slate-950 dark:border-[#22304a] dark:text-slate-200 dark:hover:bg-[#111b2f] dark:hover:text-white"
          title="Đổi giao diện"
        >
          <Sun v-if="isDarkMode" class="h-5 w-5" />
          <Moon v-else class="h-5 w-5" />
        </button>

        <a
          href="tel:0968825068"
          class="flex items-center gap-2 rounded-full bg-blue-600 px-6 py-3 text-[17px] font-extrabold text-white shadow-lg shadow-blue-500/20 transition-all duration-300 hover:bg-blue-500 hover:shadow-blue-500/30 active:scale-95"
        >
          <Phone class="h-4 w-4 fill-white/10" />
          0968.825.068
        </a>
      </div>

      <div class="flex items-center gap-2 md:hidden">
        <button
          @click="toggleTheme"
          class="rounded-full border border-slate-200 p-2 text-slate-700 transition-colors hover:bg-slate-50 hover:text-slate-950 dark:border-white/10 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white"
        >
          <Sun v-if="isDarkMode" class="h-5 w-5" />
          <Moon v-else class="h-5 w-5" />
        </button>

        <a href="tel:0968825068" class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-white transition-colors hover:bg-blue-500" title="Gọi ngay">
          <Phone class="h-4 w-4 fill-white/10" />
        </a>

        <button
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="rounded-lg p-2 text-slate-700 transition-colors hover:bg-slate-100 hover:text-slate-950 dark:text-slate-400 dark:hover:bg-white/5 dark:hover:text-white"
        >
          <Menu v-if="!isMobileMenuOpen" class="h-6 w-6" />
          <X v-else class="h-6 w-6" />
        </button>
      </div>
    </div>

    <div v-if="isMobileMenuOpen" class="border-t border-slate-200 bg-white px-4 py-4 dark:border-white/5 dark:bg-[#090f1d] md:hidden">
      <div class="space-y-2">
        <a href="/" @click.prevent="handleNavigateHome" class="block rounded-lg px-3 py-2 text-base font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white">Trang chủ</a>

        <button @click="isMobileServicesOpen = !isMobileServicesOpen" class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-base font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white">
          <span>Dịch vụ</span>
          <ChevronDown class="h-5 w-5 transition-transform duration-200" :class="{ 'rotate-180': isMobileServicesOpen }" />
        </button>

        <div v-if="isMobileServicesOpen" class="rounded-lg bg-slate-50 py-1 pl-4 pr-2 dark:bg-slate-950/40">
          <a
            v-for="(service, index) in services"
            :key="service.id"
            :href="`/dich-vu/${service.id}`"
            @click.prevent="handleSelectService(index)"
            class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-left text-sm font-semibold text-slate-700 hover:text-blue-700 dark:text-slate-400 dark:hover:text-white"
            :class="{ 'bg-blue-600/5 text-blue-600 dark:text-blue-400': currentServiceIndex === index }"
          >
            <Check class="h-4 w-4" />
            {{ service.name }}
          </a>
        </div>

        <a href="/#loi-ich" @click.prevent="handleNavigateSection('loi-ich')" class="block rounded-lg px-3 py-2 text-base font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white">Lợi ích</a>
        <a href="/#bang-gia" @click.prevent="handleNavigateSection('bang-gia')" class="block rounded-lg px-3 py-2 text-base font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white">Bảng giá</a>
        <a href="/#quy-trinh" @click.prevent="handleNavigateSection('quy-trinh')" class="block rounded-lg px-3 py-2 text-base font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white">Quy trình</a>
        <a href="/tin-tuc" @click.prevent="handleNavigateSection('tin-tuc')" class="block rounded-lg px-3 py-2 text-base font-bold text-slate-800 hover:bg-blue-50 hover:text-blue-700 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white">Tin tức</a>
        <button @click="emit('open-register'); isMobileMenuOpen = false" class="flex w-full items-center gap-1.5 px-3 py-2 text-base font-extrabold text-blue-600 dark:text-blue-400">
          <Zap class="h-4 w-4 fill-blue-400/20" />
          Đăng ký nhanh
        </button>
      </div>
    </div>
  </header>
</template>
