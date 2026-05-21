<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { 
  ChevronDown, 
  Phone, 
  Sun, 
  Moon, 
  Menu, 
  X, 
  Zap
} from '@lucide/vue';
import { services } from '../data/services';

const props = defineProps({
  currentServiceIndex: {
    type: Number,
    required: true
  }
});

const emit = defineEmits(['select-service', 'toggle-theme']);

const isDropdownOpen = ref(false);
const isMobileMenuOpen = ref(false);
const isMobileServicesOpen = ref(false);
const isDarkMode = ref(true);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

const closeDropdown = () => {
  isDropdownOpen.value = false;
};

const handleSelectService = (index) => {
  emit('select-service', index);
  isDropdownOpen.value = false;
  isMobileMenuOpen.value = false;
  isMobileServicesOpen.value = false;
};

const toggleTheme = () => {
  isDarkMode.value = !isDarkMode.value;
  emit('toggle-theme', isDarkMode.value);
};

// Handle clicks outside dropdown to close it
const dropdownRef = ref(null);
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
  <header class="sticky top-0 z-50 w-full border-b border-white/5 bg-[#090f1d]/85 backdrop-blur-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
      
      <!-- Logo -->
      <a href="#" class="flex items-center gap-2 group">
        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 shadow-lg shadow-blue-500/20 group-hover:scale-105 transition-transform duration-300">
          <!-- Custom Premium SVG Check Badge -->
          <svg class="h-5.5 w-5.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <span class="font-sans text-lg font-extrabold tracking-tight text-white sm:text-xl">
          Đăng Ký Tích Xanh<span class="text-xs font-semibold text-slate-500 group-hover:text-blue-400 transition-colors">.vn</span>
        </span>
      </a>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex items-center gap-6">
        <a href="#" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Trang chủ</a>
        
        <!-- Interactive Dropdown -->
        <div class="relative" ref="dropdownRef">
          <button 
            @click="toggleDropdown" 
            class="flex items-center gap-1 text-sm font-medium text-slate-300 hover:text-white transition-colors focus:outline-none"
            :class="{ 'text-blue-400': isDropdownOpen }"
          >
            Dịch vụ
            <ChevronDown class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180 text-blue-400': isDropdownOpen }" />
          </button>

          <!-- Dropdown Options -->
          <Transition name="fade">
            <div 
              v-if="isDropdownOpen" 
              class="absolute left-1/2 mt-3 w-64 -translate-x-1/2 rounded-xl border border-white/10 bg-slate-900/95 p-2 shadow-2xl backdrop-blur-xl ring-1 ring-black ring-opacity-5 focus:outline-none"
            >
              <div class="py-1">
                <button
                  v-for="(service, index) in services"
                  :key="service.id"
                  @click="handleSelectService(index)"
                  class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left text-sm text-slate-300 hover:bg-white/5 hover:text-white transition-all duration-200"
                  :class="{ 'bg-blue-600/10 text-blue-400 font-semibold': currentServiceIndex === index }"
                >
                  <!-- Custom Brand SVG Container -->
                  <div 
                    class="flex h-8 w-8 items-center justify-center rounded-lg"
                    :class="currentServiceIndex === index ? service.mockup.bgColor : 'bg-slate-800'"
                  >
                    <!-- Facebook Page Icon -->
                    <svg 
                      v-if="service.icon === 'facebook'" 
                      class="h-4 w-4 fill-current" 
                      :class="currentServiceIndex === index ? service.mockup.textColor : 'text-slate-400'"
                      viewBox="0 0 24 24"
                    >
                      <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <!-- User / Facebook Personal Profile Icon -->
                    <svg 
                      v-else-if="service.icon === 'user'" 
                      class="h-4 w-4 stroke-current fill-none" 
                      :class="currentServiceIndex === index ? service.mockup.textColor : 'text-slate-400'"
                      stroke-width="2" 
                      viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <!-- Instagram Icon -->
                    <svg 
                      v-else-if="service.icon === 'instagram'" 
                      class="h-4 w-4 stroke-current fill-none" 
                      :class="currentServiceIndex === index ? service.mockup.textColor : 'text-slate-400'"
                      stroke-width="2" 
                      viewBox="0 0 24 24"
                    >
                      <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                      <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
                      <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                    </svg>
                    <!-- TikTok / Music Note Icon -->
                    <svg 
                      v-else-if="service.icon === 'music'" 
                      class="h-4 w-4 fill-current" 
                      :class="currentServiceIndex === index ? service.mockup.textColor : 'text-slate-400'"
                      viewBox="0 0 24 24"
                    >
                      <path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.89-.74-3.94-1.72-.01 2.93-.01 5.87-.02 8.81-.04 1.58-.51 3.25-1.57 4.45-1.62 1.84-4.25 2.58-6.64 2.22-2.31-.35-4.48-1.95-5.27-4.18-.89-2.52-.37-5.5 1.43-7.51 1.42-1.58 3.56-2.42 5.67-2.2v4.03c-1.23-.19-2.57.3-3.27 1.32-.85 1.25-.79 3.06.2 4.15.99 1.1 2.65 1.39 3.86.72.71-.39 1.11-1.12 1.11-1.91V.02z"/>
                    </svg>
                    <!-- WhatsApp Icon -->
                    <svg 
                      v-else-if="service.icon === 'message-circle'" 
                      class="h-4 w-4 fill-current" 
                      :class="currentServiceIndex === index ? service.mockup.textColor : 'text-slate-400'"
                      viewBox="0 0 24 24"
                    >
                      <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.859-4.42 9.863-9.864.002-2.637-1.023-5.117-2.884-6.979C16.59 1.899 14.116.87 11.48.868c-5.442 0-9.866 4.42-9.87 9.866-.002 1.748.455 3.456 1.326 4.966L1.879 21.2l5.068-1.33c.003-.002.003-.002.003-.002z"/>
                    </svg>
                  </div>
                  <div class="flex-1">
                    <p class="font-medium leading-none">{{ service.name }}</p>
                    <span class="text-[10px] text-slate-500 font-normal">Hỗ trợ nhanh trong 15'</span>
                  </div>
                  <div v-if="currentServiceIndex === index" class="h-2 w-2 rounded-full bg-blue-500"></div>
                </button>
              </div>
            </div>
          </Transition>
        </div>

        <a href="#loi-ich" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Lợi ích</a>
        <a href="#bang-gia" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Bảng giá</a>
        <a href="#quy-trinh" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Quy trình</a>
        <a href="#tin-tuc" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Tin tức</a>
      </nav>

      <!-- Right Header Actions (Desktop) -->
      <div class="hidden md:flex items-center gap-4">
        <!-- Quick Register Info -->
        <a href="#dang-ky" class="flex items-center gap-1.5 text-sm font-semibold text-blue-400 hover:text-blue-300 transition-colors">
          <Zap class="h-4 w-4 fill-blue-400/20" />
          Đăng ký nhanh
        </a>

        <!-- Theme Toggle -->
        <button 
          @click="toggleTheme" 
          class="rounded-full p-2 text-slate-400 hover:bg-white/5 hover:text-white transition-colors"
          title="Đổi giao diện"
        >
          <Sun v-if="isDarkMode" class="h-4.5 w-4.5" />
          <Moon v-else class="h-4.5 w-4.5" />
        </button>

        <!-- Phone Button -->
        <a 
          href="tel:0921169999" 
          class="flex items-center gap-2 rounded-full bg-blue-600 px-5 py-2 text-sm font-extrabold text-white shadow-lg shadow-blue-500/20 hover:bg-blue-500 hover:shadow-blue-500/30 transition-all duration-300 transform active:scale-95"
        >
          <Phone class="h-4 w-4 fill-white/10" />
          092.116.9999
        </a>
      </div>

      <!-- Mobile Actions & Hamburger -->
      <div class="flex items-center gap-2 md:hidden">
        <button 
          @click="toggleTheme" 
          class="rounded-full p-2 text-slate-400 hover:bg-white/5 hover:text-white transition-colors"
        >
          <Sun v-if="isDarkMode" class="h-4.5 w-4.5" />
          <Moon v-else class="h-4.5 w-4.5" />
        </button>

        <a 
          href="tel:0921169999" 
          class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-500 transition-colors"
          title="Gọi ngay"
        >
          <Phone class="h-4 w-4 fill-white/10" />
        </a>

        <button 
          @click="isMobileMenuOpen = !isMobileMenuOpen" 
          class="rounded-lg p-2 text-slate-400 hover:bg-white/5 hover:text-white transition-colors"
        >
          <Menu v-if="!isMobileMenuOpen" class="h-6 w-6" />
          <X v-else class="h-6 w-6" />
        </button>
      </div>

    </div>

    <!-- Mobile Drawer Menu -->
    <div 
      v-if="isMobileMenuOpen" 
      class="md:hidden border-t border-white/5 bg-[#090f1d] px-4 py-4 space-y-3"
    >
      <a 
        href="#" 
        @click="isMobileMenuOpen = false" 
        class="block rounded-lg px-3 py-2 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
      >
        Trang chủ
      </a>
      
      <!-- Mobile Dịch Vụ Accordion -->
      <div class="space-y-1">
        <button 
          @click="isMobileServicesOpen = !isMobileServicesOpen" 
          class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
        >
          <span>Dịch vụ</span>
          <ChevronDown class="h-5 w-5 transition-transform duration-200" :class="{ 'rotate-180': isMobileServicesOpen }" />
        </button>
        
        <div 
          v-if="isMobileServicesOpen" 
          class="pl-4 pr-2 py-1 space-y-1 bg-slate-950/40 rounded-lg"
        >
          <button
            v-for="(service, index) in services"
            :key="service.id"
            @click="handleSelectService(index)"
            class="flex w-full items-center gap-3 rounded-md px-3 py-2 text-left text-sm text-slate-400 hover:text-white"
            :class="{ 'text-blue-400 font-semibold bg-blue-600/5': currentServiceIndex === index }"
          >
            <!-- Custom Dynamic Brand SVG for Mobile -->
            <svg 
              v-if="service.icon === 'facebook'" 
              class="h-4 w-4 fill-current" 
              viewBox="0 0 24 24"
            >
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            <svg 
              v-else-if="service.icon === 'user'" 
              class="h-4 w-4 stroke-current fill-none" 
              stroke-width="2" 
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            <svg 
              v-else-if="service.icon === 'instagram'" 
              class="h-4 w-4 stroke-current fill-none" 
              stroke-width="2" 
              viewBox="0 0 24 24"
            >
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
            <svg 
              v-else-if="service.icon === 'music'" 
              class="h-4 w-4 fill-current" 
              viewBox="0 0 24 24"
            >
              <path d="M12.53.02C13.84 0 15.14.01 16.44 0c.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.89-.74-3.94-1.72-.01 2.93-.01 5.87-.02 8.81-.04 1.58-.51 3.25-1.57 4.45-1.62 1.84-4.25 2.58-6.64 2.22-2.31-.35-4.48-1.95-5.27-4.18-.89-2.52-.37-5.5 1.43-7.51 1.42-1.58 3.56-2.42 5.67-2.2v4.03c-1.23-.19-2.57.3-3.27 1.32-.85 1.25-.79 3.06.2 4.15.99 1.1 2.65 1.39 3.86.72.71-.39 1.11-1.12 1.11-1.91V.02z"/>
            </svg>
            <svg 
              v-else-if="service.icon === 'message-circle'" 
              class="h-4 w-4 fill-current" 
              viewBox="0 0 24 24"
            >
              <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.859-4.42 9.863-9.864.002-2.637-1.023-5.117-2.884-6.979C16.59 1.899 14.116.87 11.48.868c-5.442 0-9.866 4.42-9.87 9.866-.002 1.748.455 3.456 1.326 4.966L1.879 21.2l5.068-1.33c.003-.002.003-.002.003-.002z"/>
            </svg>
            {{ service.name }}
          </button>
        </div>
      </div>

      <a 
        href="#loi-ich" 
        @click="isMobileMenuOpen = false" 
        class="block rounded-lg px-3 py-2 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
      >
        Lợi ích
      </a>
      <a 
        href="#bang-gia" 
        @click="isMobileMenuOpen = false" 
        class="block rounded-lg px-3 py-2 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
      >
        Bảng giá
      </a>
      <a 
        href="#quy-trinh" 
        @click="isMobileMenuOpen = false" 
        class="block rounded-lg px-3 py-2 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
      >
        Quy trình
      </a>
      <a 
        href="#tin-tuc" 
        @click="isMobileMenuOpen = false" 
        class="block rounded-lg px-3 py-2 text-base font-medium text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
      >
        Tin tức
      </a>

      <!-- Quick Register mobile link -->
      <a 
        href="#dang-ky" 
        @click="isMobileMenuOpen = false" 
        class="flex items-center gap-1.5 px-3 py-2 text-base font-semibold text-blue-400"
      >
        <Zap class="h-4 w-4 fill-blue-400/20" />
        Đăng ký nhanh
      </a>
    </div>
  </header>
</template>
