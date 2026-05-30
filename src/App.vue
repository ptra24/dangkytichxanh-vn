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
import ProcessSteps from './components/ProcessSteps.vue';
import Testimonials from './components/Testimonials.vue';
import Footer from './components/Footer.vue';
import { services } from './data/services';
import { articles } from './data/articles';
import ArticleView from './components/ArticleView.vue';

const currentServiceIndex = ref(0);
const isModalOpen = ref(false);
const selectedArticle = ref(null);

const selectService = (index) => {
  currentServiceIndex.value = index;
  selectedArticle.value = null; // Reset article view to show the service landing page
};

const selectArticle = (article) => {
  selectedArticle.value = article;
};

const clearArticle = () => {
  selectedArticle.value = null;
};

const scrollToTop = () => {
  window.location.hash = 'top';
};

const handleHeaderClick = (event) => {
  const target = event.target.closest('a');
  if (target) {
    const text = target.textContent.trim();
    if (text === 'Trang chủ' || text.includes('Đăng Ký Tích Xanh')) {
      clearArticle();
      scrollToTop();
    }
  }
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

  formServiceIndex.value = '';

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
const formServiceIndex = ref("");

// Biến quản lý trạng thái Loading hiển thị ở nút bấm
const isLoading = ref(false);

// Contact section inline form state
const contactName = ref('');
const contactPhone = ref('');
const contactNote = ref('');
const contactRobot = ref(false);
const contactSubmitted = ref(false);
const contactLoading = ref(false);

const handleContactSubmit = async () => {
  if (!contactName.value.trim() || !contactPhone.value.trim()) return;
  contactLoading.value = true;
  try {
    const payload = {
      name: contactName.value,
      phone: contactPhone.value,
      link: contactNote.value,
      service: 'Tư vấn miễn phí'
    };
    const response = await fetch('/api/contact', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await response.json();
    if (response.ok && result.success) {
      contactSubmitted.value = true;
    } else {
      alert(result.message || 'Có lỗi xảy ra khi gửi yêu cầu.');
    }
  } catch (error) {
    console.error('Lỗi kết nối API:', error);
    alert('Không thể kết nối đến máy chủ. Vui lòng kiểm tra lại kết nối mạng hoặc server.');
  } finally {
    contactLoading.value = false;
  }
};

// Xử lý gửi Form (Gửi yêu cầu thực tế lên Backend API Laravel)
const handleSubmit = async () => {
  isLoading.value = true;

  try {
    const selectedService = services[formServiceIndex.value] || services[currentServiceIndex.value];
    const payload = {
      name: name.value,
      phone: phone.value,
      link: link.value,
      service: selectedService ? selectedService.name : 'Tư vấn tích xanh'
    };

    const response = await fetch('/api/contact', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    });

    const result = await response.json();

    if (response.ok && result.success) {
      isSubmitted.value = true;
    } else {
      alert(result.message || 'Có lỗi xảy ra khi gửi đăng ký.');
    }
  } catch (error) {
    console.error('Lỗi kết nối API:', error);
    alert('Không thể kết nối đến máy chủ. Vui lòng kiểm tra lại kết nối mạng hoặc server.');
  } finally {
    isLoading.value = false;
  }
};

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
  
  // THÊM ĐOẠN NÀY: Kiểm tra nếu ô dịch vụ bị bỏ trống
  if (formServiceIndex.value === "") {
    formErrors.service = 'Vui lòng chọn dịch vụ bạn quan tâm';
  }

  errors.value = formErrors;
  return Object.keys(formErrors).length === 0;
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

// FAQ Accordion State and Data
const activeFaqIndexes = ref([0, 1, 2, 3, 4]); // First 5 open by default matching screenshot
const toggleFaq = (index) => {
  const position = activeFaqIndexes.value.indexOf(index);
  if (position > -1) {
    activeFaqIndexes.value.splice(position, 1);
  } else {
    activeFaqIndexes.value.push(index);
  }
};
const isFaqOpen = (index) => activeFaqIndexes.value.includes(index);

const faqs = [
  {
    question: "Đăng ký tích xanh mất bao lâu?",
    answer: "Chỉ 15-60 phút sau khi nhận đủ thông tin. Trường hợp đặc biệt có thể lên tới 24h, chúng tôi sẽ báo trước."
  },
  {
    question: "Fanpage mới tạo có đăng ký được không?",
    answer: "Có. Không giới hạn like/follower, không cần báo chí. Chỉ cần trang sạch, không vi phạm tiêu chuẩn cộng đồng."
  },
  {
    question: "Chi phí đăng ký cụ thể là bao nhiêu?",
    answer: "1.500.000đ trọn gói cho Fanpage hoặc Instagram (combo 2 nền tảng: 2.500.000đ). Không phát sinh chi phí nào khác từ phía chúng tôi."
  },
  {
    question: "Phí 1.5tr là trọn gói hay còn gì thêm?",
    answer: "1.5tr là phí dịch vụ trả 1 lần cho chúng tôi. Ngoài ra Meta thu phí duy trì ~100.000đ/tháng trực tiếp từ Facebook/Instagram (ai cũng trả, dù tự làm hay dùng dịch vụ)."
  },
  {
    question: "Tích xanh có bị mất không?",
    answer: "Tích xanh được duy trì liên tục nếu bạn thanh toán phí hàng tháng cho Meta. Nếu có sự cố từ phía Meta, chúng tôi bảo hành miễn phí trọn đời."
  },
  {
    question: "Có an toàn không? Tài khoản có bị khóa không?",
    answer: "100% an toàn. Chúng tôi chỉ dùng phương pháp chính thức qua Meta Verified – không hack, không dùng công cụ bên thứ 3, không vi phạm chính sách."
  },
  {
    question: "Tích xanh khác tích xám như thế nào?",
    answer: "Tích xanh (Blue) dành cho cá nhân nổi tiếng, thương hiệu – mức độ uy tín cao nhất. Tích xám (Gray) chỉ xác minh doanh nghiệp đơn thuần. Dịch vụ của chúng tôi là tích xanh."
  },
  {
    question: "Nếu không thành công thì sao?",
    answer: "Hoàn tiền 100%. Cam kết 'làm xong mới thanh toán' nên bạn không mất gì ngay từ đầu."
  }
];
</script>

<template>
  <div id="top" class="min-h-screen bg-[#060b13] text-slate-100 flex flex-col selection:bg-blue-600/30 selection:text-blue-200">
    <!-- Header component -->
    <Header 
      :currentServiceIndex="currentServiceIndex" 
      @select-service="selectService" 
      @toggle-theme="handleToggleTheme" 
      @open-register="openModal"
      @click="handleHeaderClick"
    />

    <template v-if="!selectedArticle">
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

      <!-- Quy trình 4 bước Section -->
      <ProcessSteps />

      <!-- Testimonials Section -->
      <Testimonials />

      <!-- FAQ Accordion Section -->
      <section id="faq" class="py-20 bg-[#0d1829] relative overflow-hidden border-t border-white/5">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 relative">
          <!-- Heading -->
          <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <h2 class="font-sans text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
              Đăng Ký Tích Xanh Có An Toàn Không?
            </h2>
            <p class="text-blue-500 text-lg sm:text-xl font-bold tracking-tight">
              — Giải Đáp 8 Câu Hỏi Top
            </p>
          </div>

          <!-- Accordion list -->
          <div class="space-y-4">
            <div 
              v-for="(faq, index) in faqs" 
              :key="index"
              class="rounded-2xl border transition-all duration-300 overflow-hidden bg-[#1e2a44]"
              :class="isFaqOpen(index) ? 'border-blue-500/30 shadow-[0_0_20px_rgba(59,130,246,0.15)]' : 'border-white/5 hover:border-white/10 hover:bg-[#1e2a44]/80'"
            >
              <!-- Accordion Header -->
              <button 
                @click="toggleFaq(index)"
                class="w-full flex items-center justify-between p-5 text-left font-bold text-xs sm:text-sm text-white focus:outline-none select-none transition-colors"
              >
                <span>{{ faq.question }}</span>
                <span 
                  class="flex h-5 w-5 items-center justify-center rounded-full transition-all duration-300"
                  :class="isFaqOpen(index) ? 'bg-blue-600/20 text-blue-400 border border-blue-500/30' : 'bg-slate-800/40 text-slate-500 border border-white/5'"
                >
                  <svg 
                    v-if="isFaqOpen(index)"
                    class="h-2.5 w-2.5" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor" 
                    stroke-width="3"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
                  <span v-else class="text-xs leading-none mt-[-2px]">+</span>
                </span>
              </button>

              <!-- Accordion Body -->
              <div 
                class="transition-all duration-300 ease-in-out overflow-hidden"
                :style="{
                  maxHeight: isFaqOpen(index) ? '200px' : '0px',
                  opacity: isFaqOpen(index) ? 1 : 0
                }"
              >
                <div class="px-5 pb-5 pt-1 text-slate-400 text-xs sm:text-sm leading-relaxed border-t border-white/5">
                  {{ faq.answer }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Bài viết hữu ích (Useful Articles) Section -->
      <section id="bai-viet" class="py-20 bg-[#060b13] relative overflow-hidden border-t border-white/5">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative">
          <!-- Heading -->
          <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
            <h2 class="font-sans text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Bài viết hữu ích</h2>
            <p class="text-slate-400 text-sm sm:text-base font-medium">Kiến thức cần biết trước khi đăng ký tích xanh</p>
          </div>

          <!-- Top grid: 6 thin article cards (2 rows of 3) -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-w-5xl mx-auto mb-8">
            <!-- Row 1 -->
            <a href="#top" @click="selectArticle(articles[0]); scrollToTop();" class="flex items-center justify-between rounded-xl border border-[#24324f] bg-[#141f36]/40 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:bg-[#141f36]/80 group">
              <span class="text-xs sm:text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">Tích xanh Facebook là gì?</span>
              <svg class="h-3.5 w-3.5 text-slate-500 group-hover:text-white transition-all transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
            <a href="#top" @click="selectArticle(articles[1]); scrollToTop();" class="flex items-center justify-between rounded-xl border border-[#24324f] bg-[#141f36]/40 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:bg-[#141f36]/80 group">
              <span class="text-xs sm:text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">Hướng dẫn đăng ký từ A-Z</span>
              <svg class="h-3.5 w-3.5 text-slate-500 group-hover:text-white transition-all transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
            <a href="#top" @click="selectArticle(articles[2]); scrollToTop();" class="flex items-center justify-between rounded-xl border border-[#24324f] bg-[#141f36]/40 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:bg-[#141f36]/80 group">
              <span class="text-xs sm:text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">So sánh tích xanh vs tích xám</span>
              <svg class="h-3.5 w-3.5 text-slate-500 group-hover:text-white transition-all transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>

            <!-- Row 2 -->
            <a href="#top" @click="selectArticle(articles[3]); scrollToTop();" class="flex items-center justify-between rounded-xl border border-[#24324f] bg-[#141f36]/40 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:bg-[#141f36]/80 group">
              <span class="text-xs sm:text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">Giá tích xanh 2026</span>
              <svg class="h-3.5 w-3.5 text-slate-500 group-hover:text-white transition-all transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
            <a href="#top" @click="selectArticle(articles[4]); scrollToTop();" class="flex items-center justify-between rounded-xl border border-[#24324f] bg-[#141f36]/40 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:bg-[#141f36]/80 group">
              <span class="text-xs sm:text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">Đăng ký có an toàn không?</span>
              <svg class="h-3.5 w-3.5 text-slate-500 group-hover:text-white transition-all transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
            <a href="#top" @click="selectArticle(articles[5]); scrollToTop();" class="flex items-center justify-between rounded-xl border border-[#24324f] bg-[#141f36]/40 p-4 transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:bg-[#141f36]/80 group">
              <span class="text-xs sm:text-sm font-semibold text-slate-200 group-hover:text-white transition-colors">Lợi ích cho Fanpage kinh doanh</span>
              <svg class="h-3.5 w-3.5 text-slate-500 group-hover:text-white transition-all transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </a>
          </div>

          <!-- Bottom: 4 thick colored service cards (1 row of 4) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
            <!-- 1. Tích xanh Fanpage (Purple) -->
            <a href="#top" @click="selectService(0); scrollToTop();" class="flex items-center justify-between rounded-2xl bg-gradient-to-r from-[#8b5cf6] to-[#a855f7] p-5 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-purple-500/25 group text-left">
              <div>
                <p class="text-sm font-black text-white leading-tight">Tích xanh Fanpage</p>
              </div>
              <svg class="h-5 w-5 text-white shrink-0 ml-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>

            <!-- 2. Tích xanh Facebook cá nhân (Blue) -->
            <a href="#top" @click="selectService(1); scrollToTop();" class="flex items-center justify-between rounded-2xl bg-gradient-to-r from-[#2563eb] to-[#3b82f6] p-5 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-blue-500/25 group text-left">
              <div>
                <p class="text-sm font-black text-white leading-tight">Tích xanh Facebook</p>
                <p class="text-[10px] font-bold text-blue-100 mt-0.5">cá nhân</p>
              </div>
              <svg class="h-5 w-5 text-white shrink-0 ml-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>

            <!-- 3. Tích xanh TikTok (Dark Navy) -->
            <a href="#top" @click="selectService(3); scrollToTop();" class="flex items-center justify-between rounded-2xl border border-white/10 bg-gradient-to-r from-[#1e293b] to-[#0f172a] p-5 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-slate-800/20 group text-left">
              <div>
                <p class="text-sm font-black text-white leading-tight">Tích xanh TikTok</p>
              </div>
              <svg class="h-5 w-5 text-white shrink-0 ml-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>

            <!-- 4. Tích xanh Instagram (Pink/Magenta) -->
            <a href="#top" @click="selectService(2); scrollToTop();" class="flex items-center justify-between rounded-2xl bg-gradient-to-r from-[#db2777] to-[#ec4899] p-5 shadow-lg transition-all duration-200 hover:-translate-y-1 hover:shadow-pink-500/25 group text-left">
              <div>
                <p class="text-sm font-black text-white leading-tight">Tích xanh Instagram</p>
              </div>
              <svg class="h-5 w-5 text-white shrink-0 ml-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </div>
        </div>
      </section>

      <!-- Liên hệ Section -->
      <section id="lien-he" class="py-20 bg-[#0d1829] relative overflow-hidden">
        <!-- subtle grid bg -->
        <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:64px_64px]"></div>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative">
          <!-- Heading -->
          <div class="text-center max-w-2xl mx-auto mb-14 space-y-2">
            <h2 class="font-sans text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Liên hệ</h2>
            <p class="text-slate-400 text-sm sm:text-base font-medium">Chúng tôi trả lời trong 15 phút</p>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start max-w-5xl mx-auto">
            <!-- Left: contact info cards -->
            <div class="flex flex-col gap-4">
              <!-- Hotline -->
              <div class="flex items-center gap-4 rounded-2xl border border-[#24324f] bg-[#141f36] px-5 py-4 shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)]">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600/15 border border-blue-500/20">
                  <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-0.5">Hotline</p>
                  <p class="text-sm font-bold text-white">O968.825.O68</p>
                </div>
              </div>

              <!-- Zalo -->
              <div class="flex items-center gap-4 rounded-2xl border border-[#24324f] bg-[#141f36] px-5 py-4 shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)]">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600/15 border border-blue-500/20">
                  <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.02 2 11c0 2.77 1.28 5.27 3.3 7.02L4 22l4.22-1.4A10.3 10.3 0 0012 21c5.52 0 10-4.02 10-9S17.52 2 12 2zm.92 13.36l-.44-.45c-.36-.37-.77-.56-1.2-.56-.27 0-.55.07-.84.22l-1.45.74c-.1.05-.2.05-.3-.02l-.3-.25c-1.68-1.47-2.8-3.35-2.8-4.6 0-.14.04-.27.12-.38l.88-1.13c.22-.28.27-.6.13-.9L5.6 7.05a.48.48 0 00-.5-.28c-.52.05-1 .3-1.38.7C3.26 8 3 8.88 3 9.87c0 1.3.46 2.66 1.26 3.87 1.17 1.77 2.87 3.23 4.83 4.1.97.44 1.9.66 2.76.66.9 0 1.7-.24 2.3-.68.5-.36.82-.88.9-1.46z"/>
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-0.5">Zalo</p>
                  <p class="text-sm font-bold text-white">O968.825.O68</p>
                </div>
              </div>

              <!-- Email -->
              <div class="flex items-center gap-4 rounded-2xl border border-[#24324f] bg-[#141f36] px-5 py-4 shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)]">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600/15 border border-blue-500/20">
                  <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-0.5">Email</p>
                  <p class="text-sm font-bold text-white">azmedia.com.vn@gmail.com</p>
                </div>
              </div>

              <!-- Địa chỉ -->
              <div class="flex items-center gap-4 rounded-2xl border border-[#24324f] bg-[#141f36] px-5 py-4 shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)]">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600/15 border border-blue-500/20">
                  <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-0.5">Địa Chỉ</p>
                  <p class="text-sm font-bold text-white">127 Lê Trọng Tấn, Phường An Khê, Đà Nẵng</p>
                </div>
              </div>

              <!-- Giờ làm việc -->
              <div class="flex items-center gap-4 rounded-2xl border border-[#24324f] bg-[#141f36] px-5 py-4 shadow-lg transition-all duration-200 hover:-translate-y-0.5 hover:border-blue-500/60 hover:shadow-[0_8px_20px_rgba(0,0,0,0.4)]">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600/15 border border-blue-500/20">
                  <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-0.5">Giờ Làm Việc</p>
                  <p class="text-sm font-bold text-white">08:00 – 18:00 (T2–T7)</p>
                </div>
              </div>
            </div>

            <!-- Right: registration form -->
            <div class="rounded-2xl border border-[#24324f] bg-[#070d1a] p-6 sm:p-8">
              <template v-if="!contactSubmitted">
                <h3 class="text-xl font-black tracking-tight text-white mb-1">Đăng ký tư vấn miễn phí</h3>
                <p class="text-xs text-slate-400 mb-6">Điền 3 thông tin, chúng tôi gọi lại trong 15 phút</p>

                <form @submit.prevent="handleContactSubmit" class="space-y-4">
                  <div>
                    <label class="block text-xs font-bold text-white uppercase tracking-wider mb-1.5">Họ tên <span class="text-blue-400">*</span></label>
                    <input
                      type="text"
                      v-model="contactName"
                      placeholder="Nguyễn Văn A"
                      required
                      class="w-full rounded-xl border border-white/5 bg-[#141f36] px-4 py-3 text-sm text-white placeholder-[#9aa8c7] transition-colors focus:border-blue-500/50 focus:outline-none focus:ring-1 focus:ring-blue-500/50"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-white uppercase tracking-wider mb-1.5">Số điện thoại <span class="text-blue-400">*</span></label>
                    <input
                      type="tel"
                      v-model="contactPhone"
                      placeholder="0912345678"
                      required
                      class="w-full rounded-xl border border-white/5 bg-[#141f36] px-4 py-3 text-sm text-white placeholder-[#9aa8c7] transition-colors focus:border-blue-500/50 focus:outline-none focus:ring-1 focus:ring-blue-500/50"
                    />
                  </div>

                  <div>
                    <label class="block text-xs font-bold text-white uppercase tracking-wider mb-1.5">Lời nhắn <span class="text-blue-400">*</span></label>
                    <textarea
                      v-model="contactNote"
                      placeholder="VD: Tôi muốn đăng ký tích xanh cho Fanpage ABC..."
                      rows="3"
                      class="w-full rounded-xl border border-white/5 bg-[#141f36] px-4 py-3 text-sm text-white placeholder-[#9aa8c7] transition-colors focus:border-blue-500/50 focus:outline-none focus:ring-1 focus:ring-blue-500/50 resize-none"
                    ></textarea>
                  </div>

                  <div class="flex items-center gap-2">
                    <input
                      id="contact-robot"
                      type="checkbox"
                      v-model="contactRobot"
                      class="h-4 w-4 rounded border border-[#64748b]/40 bg-[#141f36] accent-blue-600 cursor-pointer transition-colors"
                      :class="contactRobot ? 'border-blue-500' : 'border-[#64748b]/40'"
                    />
                    <label for="contact-robot" class="text-xs text-[#64748b] cursor-pointer select-none">Tôi xác nhận không phải robot</label>
                  </div>

                  <button
                    type="submit"
                    :disabled="contactLoading || !contactRobot"
                    class="w-full rounded-xl bg-blue-600 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-blue-500/20 transition-all duration-300 hover:bg-blue-500 active:scale-98 disabled:opacity-60 disabled:cursor-not-allowed"
                  >
                    <span v-if="!contactLoading">Gửi yêu cầu tư vấn</span>
                    <span v-else class="flex items-center justify-center gap-2">
                      <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      Đang xử lý...
                    </span>
                  </button>

                  <p class="text-center text-[10px] text-slate-500">Cam kết bảo mật thông tin, không spam</p>
                </form>
              </template>

              <!-- Success state -->
              <template v-else>
                <div class="text-center py-8 space-y-4">
                  <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 mx-auto">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <h3 class="text-xl font-black text-white">Gửi thành công!</h3>
                  <p class="text-sm text-slate-300">Cảm ơn <strong class="text-white">{{ contactName }}</strong>. Chuyên viên sẽ gọi lại cho bạn trong vòng 15 phút.</p>
                  <button
                    @click="contactSubmitted = false; contactName = ''; contactPhone = ''; contactNote = ''; contactRobot = false;"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-800 hover:bg-slate-700 px-6 py-2.5 text-sm font-semibold text-white transition-colors mt-2"
                  >
                    Gửi yêu cầu khác
                  </button>
                </div>
              </template>
            </div>
          </div>
        </div>
      </section>
    </main>
    </template>

    <template v-else>
      <ArticleView 
        :article="selectedArticle" 
        :allArticles="articles" 
        @back="clearArticle" 
        @select-article="selectArticle" 
        @open-register="openModal" 
        @select-service="selectService" 
      />
    </template>

    <!-- Footer component -->
    <Footer @open-register="openModal"/>

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
                  Họ Và Tên
                </label>
                <input 
                  type="text" 
                  v-model="name"
                  placeholder="Nguyễn Văn A"
                  required
                  class="w-full rounded-xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-white transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                  Số Điện Thoại
                </label>
                <input 
                  type="tel" 
                  v-model="phone"
                  placeholder="0921169999"
                  required
                  pattern="[0-9]{9,11}"
                  title="Vui lòng nhập số điện thoại hợp lệ từ 9 đến 11 số"
                  class="w-full rounded-xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-white transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                  Dịch vụ quan tâm
                </label>
                <select 
                  v-model="formServiceIndex" 
                  required
                  class="w-full rounded-xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-white transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                  <option value="" disabled>-- Chọn dịch vụ --</option>
                  <option 
                    v-for="(service, idx) in services" 
                    :key="service.id" 
                    :value="idx"
                  >
                    {{ service.name }}
                  </option>
                </select>
              </div>

              <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">
                  Link Trang Cá Nhân / Fanpage
                </label>
                <input 
                  type="text" 
                  v-model="link"
                  placeholder="Ví dụ: facebook.com/dangkytichxanh"
                  required
                  class="w-full rounded-xl border border-white/10 bg-slate-900/60 px-4 py-3 text-sm text-white transition-colors focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
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
                :disabled="isLoading"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-extrabold text-white shadow-lg shadow-blue-500/20 hover:bg-blue-500 transition-all duration-300 transform active:scale-98 disabled:opacity-70 disabled:cursor-not-allowed"
              >
                <template v-if="!isLoading">
                  <Send class="h-4 w-4" />
                  Gửi Yêu Cầu Đăng Ký
                </template>
                
                <template v-else>
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Đang xử lý...
                </template>
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
