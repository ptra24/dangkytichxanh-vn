<script setup>
import { ref } from 'vue';
import { 
  Check, 
  X, 
  AlertCircle, 
  Send, 
  CheckCircle2 
} from '@lucide/vue';
import Header from './components/Header.vue';
import HeroSection from './components/HeroSection.vue';
import StatsBar from './components/StatsBar.vue';
import WhyUs from './components/WhyUs.vue';
import Footer from './components/Footer.vue';
import { services } from './data/services';

const currentServiceIndex = ref(0);
const isModalOpen = ref(false);

const selectService = (index) => {
  currentServiceIndex.value = index;
};

const handleToggleTheme = (isDark) => {
  if (isDark) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
};

const openModal = () => {
  isModalOpen.value = true;
  isSubmitted.value = false;
  name.value = '';
  phone.value = '';
  link.value = '';
  errors.value = {};
};

const closeModal = () => {
  isModalOpen.value = false;
};

// Form state
const name = ref('');
const phone = ref('');
const link = ref('');
const isSubmitted = ref(false);
const errors = ref({});

const validateForm = () => {
  const formErrors = {};
  if (!name.value.trim()) {
    formErrors.name = 'Vui lòng nhập họ và tên';
  }
  if (!phone.value.trim()) {
    formErrors.phone = 'Vui lòng nhập số điện thoại';
  } else if (!/^\d{9,11}$/.test(phone.value.replace(/\s+/g, ''))) {
    formErrors.phone = 'Số điện thoại không hợp lệ (9 - 11 chữ số)';
  }
  if (!link.value.trim()) {
    formErrors.link = 'Vui lòng nhập link tài khoản / Fanpage';
  }
  errors.value = formErrors;
  return Object.keys(formErrors).length === 0;
};

const handleSubmit = () => {
  if (validateForm()) {
    setTimeout(() => {
      isSubmitted.value = true;
    }, 400);
  }
};

// Pricing cards data (matching target design)
const pricingCards = [
  {
    title: 'Tích Xanh Fanpage',
    subtitle: 'Cho Fanpage Facebook',
    price: '1.500.000đ',
    unit: '/ trọn gói',
    features: [
      'Tên Fanpage nào cũng lên',
      'Không cần báo chí',
      '15 phút hoàn thành',
      'Bảo hành trọn đời'
    ],
    metaFee: 'Phí Meta Verified: ~100.000đ/tháng',
    btnText: 'Đăng ký Fanpage',
    isPopular: false,
    serviceIndex: 0
  },
  {
    title: 'Tích Xanh Instagram',
    subtitle: 'Cho Instagram cá nhân & business',
    price: '1.500.000đ',
    unit: '/ trọn gói',
    features: [
      'Cá nhân & business đều được',
      'Tăng trust cho shop online',
      '15 phút hoàn thành',
      'Hỗ trợ 24/7'
    ],
    metaFee: 'Phí Meta Verified: ~100.000đ/tháng',
    btnText: 'Đăng ký Instagram',
    isPopular: false,
    serviceIndex: 2
  },
  {
    title: 'Combo Fanpage + IG',
    subtitle: '2 nền tảng, 1 lần thanh toán',
    price: '2.500.000đ',
    unit: '/ tiết kiệm',
    subPriceLine: '500.000đ',
    features: [
      'Đăng ký cả Fanpage + Instagram',
      'Tiết kiệm 500.000đ',
      'Khuyên dùng cho shop',
      'Bảo hành trọn đời'
    ],
    metaFee: 'Phí Meta Verified: ~150.000đ/tháng',
    btnText: 'Đăng ký Combo',
    isPopular: true,
    serviceIndex: 0
  }
];
</script>

<template>
  <div class="min-h-screen bg-[#060b13] text-slate-100 flex flex-col selection:bg-blue-600/30 selection:text-blue-200">
    <!-- Header component -->
    <Header 
      :currentServiceIndex="currentServiceIndex" 
      @select-service="selectService" 
      @toggle-theme="handleToggleTheme" 
    />

    <!-- Hero component -->
    <HeroSection 
      :service="services[currentServiceIndex]" 
      @open-register="openModal" 
    />

    <!-- Stats Bar component -->
    <StatsBar />

    <!-- Why Us component -->
    <WhyUs />

    <main class="w-full">
      <!-- Bảng giá (Pricing) Section -->
      <section id="bang-gia" class="py-20 bg-[#060b13] relative overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="text-center max-w-3xl mx-auto mb-12 space-y-2">
            <h2 class="font-sans text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
              Bảng giá dịch vụ Tích Xanh
            </h2>
            <p class="text-slate-400 text-sm sm:text-base font-medium">
              Làm xong mới thanh toán, cam kết 100% thành công
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
            <div 
              v-for="card in pricingCards" 
              :key="card.title"
              class="glass-panel rounded-2xl p-6 flex flex-col justify-between hover:border-white/15 hover:bg-slate-900/60 transition-all duration-300 transform hover:-translate-y-1 relative bg-slate-950/40 border"
              :class="card.isPopular ? 'border-blue-500 shadow-[0_0_25px_rgba(37,99,235,0.15)] ring-1 ring-blue-500/30' : 'border-white/5'"
            >
              <!-- Popular badge -->
              <span 
                v-if="card.isPopular" 
                class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-[#10b981] text-[9px] font-black text-white uppercase tracking-wider shadow-md"
              >
                Phổ biến nhất
              </span>

              <div>
                <h3 class="text-lg font-bold text-white tracking-tight">{{ card.title }}</h3>
                <p class="text-xs text-slate-400 mt-1 mb-6 font-medium">{{ card.subtitle }}</p>
                
                <div class="flex items-baseline flex-wrap gap-1 mb-6">
                  <span class="text-3xl font-black text-blue-500 tracking-tight">{{ card.price }}</span>
                  <div class="flex flex-col ml-1">
                    <span class="text-xs text-slate-400 font-semibold">{{ card.unit }}</span>
                    <span v-if="card.subPriceLine" class="text-[10px] text-slate-400 font-bold leading-none mt-0.5">{{ card.subPriceLine }}</span>
                  </div>
                </div>

                <div class="h-px w-full bg-white/5 mb-6"></div>

                <ul class="space-y-3 mb-8 text-xs font-semibold text-slate-300">
                  <li v-for="feat in card.features" :key="feat" class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-emerald-400 stroke-[3] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ feat }}</span>
                  </li>
                </ul>
              </div>

              <div>
                <!-- Subtext badge before CTA button -->
                <div class="bg-slate-800/40 border border-white/5 rounded-lg py-2 px-3 text-center text-[10px] text-slate-400 mb-5 font-semibold">
                  {{ card.metaFee }}
                </div>

                <button 
                  @click="selectService(card.serviceIndex); openModal();"
                  class="w-full py-3 rounded-xl text-xs font-extrabold transition-all duration-300 transform active:scale-98"
                  :class="card.isPopular 
                    ? 'bg-blue-600 hover:bg-blue-500 text-white shadow-md shadow-blue-500/10' 
                    : 'border border-blue-500/50 hover:bg-blue-500/10 text-white'"
                >
                  {{ card.btnText }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer component -->
    <Footer />

    <!-- Inline Registry Form Modal -->
    <div 
      v-if="isModalOpen" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#030712]/80 backdrop-blur-md"
      @click.self="closeModal"
    >
      <div 
        class="relative w-full max-w-lg overflow-hidden rounded-2xl border border-white/10 bg-[#0f172a] shadow-2xl transition-all duration-300 transform scale-100"
      >
        <!-- Top color bar matching selected service -->
        <div 
          class="h-1.5 w-full bg-blue-600 transition-colors duration-300"
          :class="{
            'bg-blue-600': currentServiceIndex === 0 || currentServiceIndex === 1,
            'bg-pink-600': currentServiceIndex === 2,
            'bg-cyan-500': currentServiceIndex === 3,
            'bg-emerald-500': currentServiceIndex === 4,
          }"
        ></div>

        <!-- Close Button -->
        <button 
          @click="closeModal" 
          class="absolute top-4 right-4 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg p-1.5 transition-colors focus:outline-none"
        >
          <X class="h-5 w-5" />
        </button>

        <!-- Form Content -->
        <div class="p-6 sm:p-8 text-left">
          
          <div v-if="!isSubmitted">
            <h3 class="text-2xl font-black tracking-tight text-white mb-2">
              Đăng Ký Tích Xanh
            </h3>
            <p class="text-sm text-slate-400 mb-6">
              Vui lòng điền thông tin bên dưới, chuyên viên của chúng tôi sẽ liên hệ tư vấn trong 5 - 15 phút.
            </p>

            <form @submit.prevent="handleSubmit" class="space-y-4">
              <!-- Service Selector -->
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                  Loại Dịch Vụ
                </label>
                <select 
                  v-model="currentServiceIndex" 
                  class="w-full rounded-xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors"
                >
                  <option 
                    v-for="(service, idx) in services" 
                    :key="service.id" 
                    :value="idx"
                  >
                    {{ service.name }} ({{ service.price }})
                  </option>
                </select>
              </div>

              <!-- Name Input -->
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                  Họ Và Tên
                </label>
                <input 
                  type="text" 
                  v-model="name"
                  placeholder="Ví dụ: Nguyễn Văn A"
                  class="w-full rounded-xl border px-4 py-3 text-sm text-white bg-slate-900/60 transition-colors focus:outline-none focus:ring-1 border-white/10 focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500/50 focus:border-red-500 focus:ring-red-500': errors.name }"
                />
                <span v-if="errors.name" class="flex items-center gap-1 text-xs text-red-400 mt-1.5 font-medium">
                  <AlertCircle class="h-3.5 w-3.5" />
                  {{ errors.name }}
                </span>
              </div>

              <!-- Phone Input -->
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                  Số Điện Thoại Zalo
                </label>
                <input 
                  type="tel" 
                  v-model="phone"
                  placeholder="Ví dụ: 0921169999"
                  class="w-full rounded-xl border px-4 py-3 text-sm text-white bg-slate-900/60 transition-colors focus:outline-none focus:ring-1 border-white/10 focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500/50 focus:border-red-500 focus:ring-red-500': errors.phone }"
                />
                <span v-if="errors.phone" class="flex items-center gap-1 text-xs text-red-400 mt-1.5 font-medium">
                  <AlertCircle class="h-3.5 w-3.5" />
                  {{ errors.phone }}
                </span>
              </div>

              <!-- Profile/Page Link -->
              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                  Link Trang Cá Nhân / Fanpage
                </label>
                <input 
                  type="text" 
                  v-model="link"
                  placeholder="Ví dụ: facebook.com/dangkytichxanh"
                  class="w-full rounded-xl border px-4 py-3 text-sm text-white bg-slate-900/60 transition-colors focus:outline-none focus:ring-1 border-white/10 focus:border-blue-500 focus:ring-blue-500"
                  :class="{ 'border-red-500/50 focus:border-red-500 focus:ring-red-500': errors.link }"
                />
                <span v-if="errors.link" class="flex items-center gap-1 text-xs text-red-400 mt-1.5 font-medium">
                  <AlertCircle class="h-3.5 w-3.5" />
                  {{ errors.link }}
                </span>
              </div>

              <!-- Quick Info Badge -->
              <div class="rounded-xl bg-slate-950/40 p-4 border border-white/5 space-y-2">
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-300">
                  <Check class="h-4 w-4 text-emerald-400 stroke-[3]" />
                  <span>Trọn gói {{ services[currentServiceIndex].price }}, không phát sinh thêm phí.</span>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-300">
                  <Check class="h-4 w-4 text-emerald-400 stroke-[3]" />
                  <span>Làm xong mới thanh toán – An toàn 100%.</span>
                </div>
              </div>

              <!-- Submit Button -->
              <button 
                type="submit" 
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-blue-500/20 hover:bg-blue-500 hover:shadow-blue-500/30 transition-all duration-300 transform active:scale-98"
              >
                <Send class="h-4 w-4" />
                Gửi Yêu Cầu Đăng Ký
              </button>
            </form>
          </div>

          <!-- Success Screen -->
          <div v-else class="text-center py-6 space-y-4">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 mb-2">
              <CheckCircle2 class="h-10 w-10" />
            </div>
            <h3 class="text-2xl font-black text-white tracking-tight">
              Đăng Ký Thành Công!
            </h3>
            <p class="text-slate-300 max-w-sm mx-auto leading-relaxed">
              Cảm ơn <strong class="text-white">{{ name }}</strong>. Chúng tôi đã nhận được thông tin liên hệ cho dịch vụ <strong class="text-white">{{ services[currentServiceIndex].name }}</strong>. 
            </p>
            <p class="text-xs text-slate-400 leading-normal">
              Chuyên viên hỗ trợ tích xanh sẽ liên hệ trực tiếp cho bạn qua số <strong class="text-slate-200">{{ phone }}</strong> trong vòng 15 phút tới.
            </p>
            <button 
              @click="closeModal" 
              class="inline-flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 px-6 py-2.5 text-sm font-semibold text-white transition-colors mt-4"
            >
              Đóng cửa sổ
            </button>
          </div>

        </div>

      </div>
    </div>
  </div>
</template>
