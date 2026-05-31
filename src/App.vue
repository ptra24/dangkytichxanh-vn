<script setup>
import { ref, nextTick, onMounted, onUnmounted } from 'vue';
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
import BlogPreview from './components/BlogPreview.vue';
import ArticleView from './components/ArticleView.vue';
import Footer from './components/Footer.vue';
import { services } from './data/services';
import { articles } from './data/articles';

// ──────────────────────────────────────────────
// Routing / Page state
// ──────────────────────────────────────────────
// currentPage: 'home' | 'article'
const currentPage = ref('home');
const selectedArticle = ref(null);

const goHome = () => {
  currentPage.value = 'home';
  selectedArticle.value = null;
  if (window.location.pathname !== '/') {
    window.history.pushState({}, '', '/');
  }
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const openArticle = (article) => {
  selectedArticle.value = article;
  currentPage.value = 'article';
  window.history.pushState({}, '', `/tin-tuc/${article.id}`);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// ──────────────────────────────────────────────
// Service selection
// ──────────────────────────────────────────────
const currentServiceIndex = ref(0);

const selectService = (index) => {
  currentServiceIndex.value = index;
};

const navigateHome = () => {
  currentPage.value = 'home';
  selectedArticle.value = null;
  window.history.pushState({}, '', '/');
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const navigateService = (index) => {
  currentServiceIndex.value = index;
  currentPage.value = 'home';
  selectedArticle.value = null;
  window.history.pushState({}, '', `/dich-vu/${services[index].id}`);
  nextTick(() => window.scrollTo({ top: 0, behavior: 'smooth' }));
};

const navigateSection = (sectionId) => {
  currentPage.value = 'home';
  selectedArticle.value = null;
  const path = sectionId === 'tin-tuc' ? '/tin-tuc' : `/#${sectionId}`;
  window.history.pushState({}, '', path);
  nextTick(() => {
    const el = document.getElementById(sectionId);
    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
  });
};

const resolveRoute = () => {
  const path = window.location.pathname;

  if (path.startsWith('/tin-tuc/') && path !== '/tin-tuc/') {
    const articleId = decodeURIComponent(path.split('/').pop());
    const found = articles.find((item) => item.id === articleId);
    if (found) {
      selectedArticle.value = found;
      currentPage.value = 'article';
      return;
    }
  }

  if (path.startsWith('/dich-vu/') && path !== '/dich-vu/') {
    const serviceId = decodeURIComponent(path.split('/').pop());
    const serviceIndex = services.findIndex((item) => item.id === serviceId);
    if (serviceIndex >= 0) currentServiceIndex.value = serviceIndex;
  }

  currentPage.value = 'home';
  selectedArticle.value = null;

  if (path === '/tin-tuc') {
    nextTick(() => document.getElementById('tin-tuc')?.scrollIntoView({ behavior: 'smooth' }));
  }
};

onMounted(() => {
  resolveRoute();
  window.addEventListener('popstate', resolveRoute);
});

onUnmounted(() => {
  window.removeEventListener('popstate', resolveRoute);
});

// ──────────────────────────────────────────────
// Dark / Light mode
// ──────────────────────────────────────────────
const isDark = ref(true);

// Apply initial theme
if (typeof document !== 'undefined') {
  document.documentElement.classList.add('dark');
}

const handleToggleTheme = (dark) => {
  isDark.value = dark;
  if (dark) {
    document.documentElement.classList.add('dark');
    localStorage.theme = 'dark';
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.theme = 'light';
  }
};

// ──────────────────────────────────────────────
// Registration Modal
// ──────────────────────────────────────────────
const isModalOpen = ref(false);
const isSubmitted = ref(false);
const isLoading = ref(false);

// Form fields
const name = ref('');
const phone = ref('');
const link = ref('');
const formServiceIndex = ref('');
const errors = ref({});

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

const validateForm = () => {
  const formErrors = {};
  if (!name.value.trim()) formErrors.name = 'Vui lòng nhập họ và tên';
  if (!phone.value.trim()) {
    formErrors.phone = 'Vui lòng nhập số điện thoại';
  } else if (!/^\d{9,11}$/.test(phone.value.replace(/\s+/g, ''))) {
    formErrors.phone = 'Số điện thoại không hợp lệ (9 - 11 chữ số)';
  }
  if (!link.value.trim()) formErrors.link = 'Vui lòng nhập link tài khoản / Fanpage';
  if (formServiceIndex.value === '') formErrors.service = 'Vui lòng chọn dịch vụ bạn quan tâm';
  errors.value = formErrors;
  return Object.keys(formErrors).length === 0;
};

const handleSubmit = async () => {
  if (!validateForm()) return;
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
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
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

// ──────────────────────────────────────────────
// Pricing cards
// ──────────────────────────────────────────────
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

const trustReasons = [
  {
    title: 'Nhanh nhất thị trường',
    desc: '15 phút, không phải 1-7 ngày như tự làm'
  },
  {
    title: 'Tỉ lệ 100% thành công',
    desc: 'Làm xong tích xanh mới phải thanh toán, không mất tiền oan'
  },
  {
    title: 'Hỗ trợ trọn đời',
    desc: 'Gặp sự cố gì chúng tôi xử lý miễn phí'
  },
  {
    title: 'An toàn 100%',
    desc: 'Chỉ dùng phương pháp chính thức của Meta'
  },
  {
    title: 'Tư vấn 24/7',
    desc: 'Luôn có nhân viên trực hotline và Zalo'
  },
  {
    title: 'Uy tín AZ Media',
    desc: 'Được khách hàng cá nhân, shop và doanh nghiệp tin tưởng'
  }
];

const helpfulLinks = [
  'Tích xanh Facebook là gì?',
  'Hướng dẫn đăng ký từ A-Z',
  'So sánh tích xanh vs tích xám',
  'Giá tích xanh 2026',
  'Đăng ký có an toàn không?',
  'Lợi ích cho Fanpage kinh doanh'
];

const serviceQuickLinks = [
  { label: 'Tích xanh Fanpage', color: 'from-fuchsia-600 to-purple-700', serviceIndex: 0 },
  { label: 'Tích xanh Facebook cá nhân', color: 'from-blue-600 to-blue-700', serviceIndex: 1 },
  { label: 'Tích xanh TikTok', color: 'from-slate-800 to-slate-950', serviceIndex: 3 },
  { label: 'Tích xanh Instagram', color: 'from-pink-600 to-rose-700', serviceIndex: 2 }
];
</script>

<template>
  <!--
    Root wrapper:
    - dark class is toggled on <html> by handleToggleTheme
    - bg/text defaults cover both modes via Tailwind dark: variants
  -->
  <div
    class="app-shell min-h-screen flex flex-col selection:bg-blue-600/30 selection:text-blue-200
           bg-white text-slate-800
           dark:bg-[#060b13] dark:text-slate-100"
  >

    <!-- ─── HEADER (always visible) ──────────────────── -->
    <Header
      :currentServiceIndex="currentServiceIndex"
      @select-service="selectService"
      @navigate-service="navigateService"
      @navigate-home="navigateHome"
      @navigate-section="navigateSection"
      @toggle-theme="handleToggleTheme"
      @open-register="openModal"
    />

    <!-- ═══════════════════════════════════════════════ -->
    <!--  PAGE: HOME                                     -->
    <!-- ═══════════════════════════════════════════════ -->
    <template v-if="currentPage === 'home'">

      <!-- Hero -->
      <HeroSection
        :service="services[currentServiceIndex]"
        @open-register="openModal"
      />

      <!-- Stats Bar -->
      <StatsBar />

      <!-- Why Us -->
      <WhyUs />

      <!-- ── Pricing Section ─────────────────────────── -->
      <main class="w-full">
        <section
          id="bang-gia"
          class="py-20 relative overflow-hidden
                 bg-white dark:bg-[#060b13]"
        >
          <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 space-y-2">
              <h2
                class="font-sans text-3xl font-extrabold tracking-tight sm:text-4xl
                       text-slate-900 dark:text-white"
              >
                Bảng giá dịch vụ Tích Xanh
              </h2>
              <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base font-medium">
                Làm xong mới thanh toán, cam kết 100% thành công
              </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
              <div
                v-for="card in pricingCards"
                :key="card.title"
                class="rounded-2xl p-6 flex flex-col justify-between
                       hover:border-white/15 transition-all duration-300
                       transform hover:-translate-y-1 relative
                       border
                       bg-slate-50 border-slate-200
                       dark:bg-slate-950/40 dark:border-white/5
                       dark:hover:bg-slate-900/60"
                :class="card.isPopular
                  ? 'border-blue-500 shadow-[0_0_25px_rgba(37,99,235,0.15)] ring-1 ring-blue-500/30 dark:border-blue-500'
                  : ''"
              >
                <!-- Popular badge -->
                <span
                  v-if="card.isPopular"
                  class="absolute -top-3 left-1/2 -translate-x-1/2 px-3 py-1 rounded-full bg-[#10b981] text-[9px] font-black text-white uppercase tracking-wider shadow-md"
                >
                  Phổ biến nhất
                </span>

                <div>
                  <h3
                    class="text-lg font-bold tracking-tight
                           text-slate-900 dark:text-white"
                  >{{ card.title }}</h3>
                  <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-6 font-medium">{{ card.subtitle }}</p>

                  <div class="flex items-baseline flex-wrap gap-1 mb-6">
                    <span class="text-3xl font-black text-blue-500 tracking-tight">{{ card.price }}</span>
                    <div class="flex flex-col ml-1">
                      <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">{{ card.unit }}</span>
                      <span v-if="card.subPriceLine" class="text-[10px] text-slate-400 font-bold leading-none mt-0.5">{{ card.subPriceLine }}</span>
                    </div>
                  </div>

                  <div class="h-px w-full bg-slate-200 dark:bg-white/5 mb-6"></div>

                  <ul class="space-y-3 mb-8 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <li v-for="feat in card.features" :key="feat" class="flex items-center gap-2">
                      <svg class="h-4 w-4 text-emerald-400 stroke-[3] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                      </svg>
                      <span>{{ feat }}</span>
                    </li>
                  </ul>
                </div>

                <div>
                  <div
                    class="rounded-lg py-2 px-3 text-center text-[10px] mb-5 font-semibold
                           bg-slate-100 border border-slate-200 text-slate-500
                           dark:bg-slate-800/40 dark:border-white/5 dark:text-slate-400"
                  >
                    {{ card.metaFee }}
                  </div>

                  <button
                    @click="selectService(card.serviceIndex); openModal();"
                    class="w-full py-3 rounded-xl text-xs font-extrabold transition-all duration-300 transform active:scale-98"
                    :class="card.isPopular
                      ? 'bg-blue-600 hover:bg-blue-500 text-white shadow-md shadow-blue-500/10'
                      : 'border border-blue-500/50 hover:bg-blue-500/10 text-blue-600 dark:text-white'"
                  >
                    {{ card.btnText }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>

      <!-- Process Steps -->
      <ProcessSteps />

      <section class="py-20 bg-[#102946] text-white dark:bg-[#0b1628]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="mb-12 text-center">
            <h2 class="text-4xl font-black tracking-tight sm:text-5xl">Tại sao chọn chúng tôi?</h2>
            <p class="mt-4 text-xl font-semibold text-slate-300">Được 2.368 khách hàng tin tưởng</p>
          </div>

          <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <div
              v-for="item in trustReasons"
              :key="item.title"
              class="rounded-2xl border border-white/10 bg-white/[0.06] p-8 text-center shadow-xl shadow-black/10 transition hover:-translate-y-1 hover:border-blue-400/50"
            >
              <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full border border-blue-400/30 bg-blue-500/10 text-blue-300">
                <Check class="h-9 w-9 stroke-[3]" />
              </div>
              <h3 class="text-2xl font-black">{{ item.title }}</h3>
              <p class="mt-4 text-lg font-medium leading-relaxed text-slate-300">{{ item.desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <!-- Testimonials -->
      <Testimonials />

      <!-- Blog / Articles Section -->
      <section
        id="tin-tuc"
        class="py-20 bg-slate-50 dark:bg-[#060b13] border-t border-slate-200 dark:border-white/5"
      >
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-12 space-y-2">
            <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl text-slate-900 dark:text-white">
              Tin tức & Kiến thức
            </h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm sm:text-base font-medium">
              Cập nhật mới nhất về tích xanh và mạng xã hội
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <article
              v-for="article in articles"
              :key="article.id"
              class="group rounded-2xl overflow-hidden border transition-all duration-300 hover:-translate-y-1 cursor-pointer
                     bg-white border-slate-200 hover:border-blue-300 hover:shadow-lg
                     dark:bg-[#0a1020] dark:border-white/5 dark:hover:border-blue-500/30 dark:hover:shadow-blue-500/5"
              @click="openArticle(article)"
            >
              <!-- Thumbnail -->
              <div class="aspect-[16/9] overflow-hidden bg-slate-100 dark:bg-slate-900">
                <img
                  :src="article.banner"
                  :alt="article.title"
                  class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                />
              </div>

              <!-- Content -->
              <div class="p-5 space-y-3">
                <span
                  class="inline-block text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full
                         bg-blue-50 text-blue-600 border border-blue-100
                         dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20"
                >
                  {{ article.category }}
                </span>

                <h3 class="text-sm font-bold leading-snug line-clamp-2 text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                  {{ article.title }}
                </h3>

                <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                  {{ article.description }}
                </p>

                <div class="flex items-center justify-between pt-1">
                  <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">{{ article.date }}</span>
                  <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 group-hover:underline">
                    Đọc thêm →
                  </span>
                </div>
              </div>
            </article>
          </div>
        </div>
      </section>

      <section class="py-20 bg-slate-100 dark:bg-[#0f1d35] border-t border-slate-200 dark:border-white/5">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="mb-12 text-center">
            <h2 class="text-4xl font-black tracking-tight text-slate-950 dark:text-white sm:text-5xl">Bài viết hữu ích</h2>
            <p class="mt-4 text-xl font-semibold text-slate-500 dark:text-slate-400">Kiến thức cần biết trước khi đăng ký tích xanh</p>
          </div>

          <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <button
              v-for="link in helpfulLinks"
              :key="link"
              @click="navigateSection('tin-tuc')"
              class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-6 py-5 text-left text-lg font-extrabold text-slate-900 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:text-blue-600 dark:border-white/10 dark:bg-[#16243d] dark:text-white dark:hover:border-blue-400"
            >
              <span>{{ link }}</span>
              <span class="text-2xl">↗</span>
            </button>
          </div>

          <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            <button
              v-for="item in serviceQuickLinks"
              :key="item.label"
              @click="navigateService(item.serviceIndex)"
              class="flex min-h-28 items-center justify-between rounded-2xl bg-gradient-to-br px-7 py-6 text-left text-xl font-black text-white shadow-lg transition hover:-translate-y-1"
              :class="item.color"
            >
              <span>{{ item.label }}</span>
              <span class="text-3xl">→</span>
            </button>
          </div>
        </div>
      </section>

      <section class="py-20 bg-white dark:bg-[#060b13] border-t border-slate-200 dark:border-white/5">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
          <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-4xl">
              Đăng Ký Tích Xanh Có An Toàn Không?
            </h2>
            <p class="mt-3 text-sm font-medium text-slate-500 dark:text-slate-400">
              Giải đáp nhanh các câu hỏi thường gặp
            </p>
          </div>

          <div class="space-y-3">
            <details open class="group rounded-2xl border border-blue-200 bg-white p-5 shadow-sm dark:border-blue-500/30 dark:bg-[#0f172a]/70">
              <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-extrabold text-slate-950 dark:text-white">
                <span>Đăng ký tích xanh mất bao lâu?</span>
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white group-open:rotate-45">+</span>
              </summary>
              <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                Chỉ 15-60 phút sau khi nhận đủ thông tin. Trường hợp đặc biệt có thể lên tới 24h, chúng tôi sẽ báo trước.
              </p>
            </details>

            <details
              v-for="item in [
                ['Fanpage mới tạo có đăng ký được không?', 'Có thể kiểm tra trước. Chúng tôi sẽ tư vấn điều kiện cụ thể để tránh mất thời gian.'],
                ['Chi phí đăng ký cụ thể là bao nhiêu?', 'Gói phổ biến từ 1.500.000đ, làm xong mới thanh toán.'],
                ['Tích xanh có bị mất không?', 'Chúng tôi hỗ trợ đúng quy trình Meta và bảo hành theo từng gói dịch vụ.'],
                ['Nếu không thành công thì sao?', 'Không thành công thì không thu phí dịch vụ.']
              ]"
              :key="item[0]"
              class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#0f172a]/60"
            >
              <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-extrabold text-slate-950 dark:text-white">
                <span>{{ item[0] }}</span>
                <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 group-open:rotate-45 dark:bg-blue-500/10 dark:text-blue-300">+</span>
              </summary>
              <p class="mt-4 text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ item[1] }}</p>
            </details>
          </div>
        </div>
      </section>

      <section id="contact" class="py-20 bg-slate-50 dark:bg-[#060b13] border-t border-slate-200 dark:border-white/5">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
          <div>
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white sm:text-4xl">
              Liên hệ
            </h2>
            <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-600 dark:text-slate-400">
              Văn phòng giao dịch và thông tin hỗ trợ chính thức của AZ Media.
            </p>

            <div class="mt-8 space-y-4">
              <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-[#0f172a]/70">
                <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Hotline</p>
                <a href="tel:0968825068" class="mt-1 block text-xl font-extrabold text-slate-950 dark:text-white">0968.825.068 (Mr. Quang)</a>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-[#0f172a]/70">
                <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Địa chỉ</p>
                <p class="mt-1 text-base font-bold text-slate-950 dark:text-white">84 Nguyễn Hữu Dật, Hòa Cường Bắc, Hải Châu, Đà Nẵng</p>
              </div>
              <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-white/10 dark:bg-[#0f172a]/70">
                <p class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400">Email / Facebook</p>
                <p class="mt-1 text-base font-bold text-slate-950 dark:text-white">azmedia.com.vn@gmail.com - fb.com/congtyazmedia</p>
              </div>
            </div>
          </div>

          <form
            @submit.prevent="openModal"
            class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 dark:border-white/10 dark:bg-[#0f172a]/80 dark:shadow-none"
          >
            <div class="grid gap-4">
              <input class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-slate-950/50 dark:text-white" placeholder="Họ và tên" />
              <input class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-slate-950/50 dark:text-white" placeholder="Số điện thoại" />
              <input class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-slate-950/50 dark:text-white" placeholder="Link Fanpage / Instagram / TikTok" />
              <textarea class="min-h-28 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-slate-950/50 dark:text-white" placeholder="Bạn cần tư vấn dịch vụ nào?"></textarea>
              <label class="flex items-center gap-2 text-xs font-medium text-slate-600 dark:text-slate-400">
                <input type="checkbox" class="h-4 w-4 rounded border-slate-300" />
                Tôi xác nhận không phải robot
              </label>
              <button class="rounded-xl bg-blue-600 px-4 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-600/20 hover:bg-blue-500">
                Gửi yêu cầu tư vấn
              </button>
              <p class="text-center text-xs text-slate-500 dark:text-slate-400">Cam kết bảo mật thông tin, không spam</p>
            </div>
          </form>
        </div>
      </section>

    </template>

    <!-- ═══════════════════════════════════════════════ -->
    <!--  PAGE: ARTICLE VIEW                             -->
    <!-- ═══════════════════════════════════════════════ -->
    <template v-else-if="currentPage === 'article' && selectedArticle">
      <ArticleView
        :article="selectedArticle"
        :allArticles="articles"
        @back="goHome"
        @select-article="openArticle"
        @open-register="openModal"
        @select-service="selectService"
      />
    </template>

    <!-- ─── FOOTER (always visible) ──────────────────── -->
    <Footer @open-register="openModal" />

    <!-- ─── HOTLINE POPUP ─────────────────────────────── -->

    <!-- ═══════════════════════════════════════════════ -->
    <!--  REGISTRATION MODAL                             -->
    <!-- ═══════════════════════════════════════════════ -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
      @click.self="closeModal"
    >
      <div
        class="relative w-full max-w-lg overflow-hidden rounded-2xl border shadow-2xl transition-all duration-300
               bg-white border-slate-200
               dark:bg-[#0f172a] dark:border-white/10"
      >
        <!-- Top color bar -->
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
          class="absolute top-4 right-4 rounded-lg p-1.5 transition-colors focus:outline-none
                 text-slate-400 hover:text-slate-700 hover:bg-slate-100
                 dark:hover:text-white dark:hover:bg-white/5"
        >
          <X class="h-5 w-5" />
        </button>

        <!-- Form Content -->
        <div class="p-6 sm:p-8 text-left">

          <!-- ── Form ───────────────────────────────── -->
          <div v-if="!isSubmitted">
            <h3 class="text-2xl font-black tracking-tight mb-2 text-slate-900 dark:text-white">
              Đăng Ký Tích Xanh
            </h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">
              Vui lòng điền thông tin bên dưới, chuyên viên sẽ liên hệ tư vấn trong 5 - 15 phút.
            </p>

            <form @submit.prevent="handleSubmit" class="space-y-4">

              <!-- Họ tên -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-500 dark:text-slate-400">
                  Họ Và Tên
                </label>
                <input
                  type="text"
                  v-model="name"
                  placeholder="Nguyễn Văn A"
                  class="w-full rounded-xl px-4 py-3 text-sm transition-colors focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                         border border-slate-300 bg-white text-slate-900 placeholder-slate-400
                         dark:border-white/10 dark:bg-slate-900/60 dark:text-white dark:placeholder-slate-500"
                />
                <p v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name }}</p>
              </div>

              <!-- Số điện thoại -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-500 dark:text-slate-400">
                  Số Điện Thoại
                </label>
                <input
                  type="tel"
                  v-model="phone"
                  placeholder="0968825068"
                  class="w-full rounded-xl px-4 py-3 text-sm transition-colors focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                         border border-slate-300 bg-white text-slate-900 placeholder-slate-400
                         dark:border-white/10 dark:bg-slate-900/60 dark:text-white dark:placeholder-slate-500"
                />
                <p v-if="errors.phone" class="text-xs text-red-500 mt-1">{{ errors.phone }}</p>
              </div>

              <!-- Dịch vụ -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-500 dark:text-slate-400">
                  Dịch vụ quan tâm
                </label>
                <select
                  v-model="formServiceIndex"
                  class="w-full rounded-xl px-4 py-3 text-sm transition-colors focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                         border border-slate-300 bg-white text-slate-900
                         dark:border-white/10 dark:bg-slate-900/60 dark:text-white"
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
                <p v-if="errors.service" class="text-xs text-red-500 mt-1">{{ errors.service }}</p>
              </div>

              <!-- Link tài khoản -->
              <div>
                <label class="block text-xs font-bold uppercase tracking-wider mb-2 text-slate-500 dark:text-slate-400">
                  Link Trang Cá Nhân / Fanpage
                </label>
                <input
                  type="text"
                  v-model="link"
                  placeholder="Ví dụ: facebook.com/dangkytichxanh"
                  class="w-full rounded-xl px-4 py-3 text-sm transition-colors focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500
                         border border-slate-300 bg-white text-slate-900 placeholder-slate-400
                         dark:border-white/10 dark:bg-slate-900/60 dark:text-white dark:placeholder-slate-500"
                />
                <p v-if="errors.link" class="text-xs text-red-500 mt-1">{{ errors.link }}</p>
              </div>

              <!-- Quick Info Badge -->
              <div
                class="rounded-xl p-4 border space-y-2
                       bg-slate-50 border-slate-200
                       dark:bg-slate-950/40 dark:border-white/5"
              >
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-300">
                  <Check class="h-4 w-4 text-emerald-400 stroke-[3]" />
                  <span>Trọn gói {{ services[currentServiceIndex].price }}, không phát sinh thêm phí.</span>
                </div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-600 dark:text-slate-300">
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

          <!-- ── Success Screen ──────────────────────── -->
          <div v-else class="text-center py-6 space-y-4">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-400 mb-2">
              <CheckCircle2 class="h-10 w-10" />
            </div>
            <h3 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">
              Đăng Ký Thành Công!
            </h3>
            <p class="text-slate-600 dark:text-slate-300 max-w-sm mx-auto leading-relaxed">
              Cảm ơn <strong class="text-slate-900 dark:text-white">{{ name }}</strong>. Chúng tôi đã nhận được thông tin cho dịch vụ
              <strong class="text-slate-900 dark:text-white">{{ services[currentServiceIndex].name }}</strong>.
            </p>
            <p class="text-xs text-slate-400 leading-normal">
              Chuyên viên sẽ liên hệ trực tiếp cho bạn qua số
              <strong class="text-slate-700 dark:text-slate-200">{{ phone }}</strong> trong vòng 15 phút tới.
            </p>
            <button
              @click="closeModal"
              class="inline-flex items-center justify-center rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition-colors mt-4 bg-slate-700 hover:bg-slate-600 dark:bg-slate-800 dark:hover:bg-slate-700"
            >
              Đóng cửa sổ
            </button>
          </div>

        </div>
      </div>
    </div>

  </div>
</template>
