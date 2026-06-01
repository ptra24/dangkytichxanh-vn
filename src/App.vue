<script setup>
import { computed, onMounted, ref } from 'vue';
import {
  Award,
  BadgeCheck,
  BarChart3,
  Check,
  ChevronDown,
  Clock,
  LockKeyhole,
  Mail,
  MapPin,
  Menu,
  MessageCircle,
  Moon,
  Phone,
  ShieldCheck,
  Star,
  Sun,
  TrendingUp,
  Trophy,
  WalletCards
} from '@lucide/vue';
import { articles as articlePosts } from './data/articles';

const isDark = ref(false);
const activeFaq = ref(0);
const leadName = ref('');
const leadPhone = ref('');
const leadLink = ref('');
const selectedArticle = ref(null);
const selectedHeroService = ref(null);

const applyTheme = (dark) => {
  isDark.value = dark;
  document.documentElement.classList.toggle('dark', dark);
  localStorage.theme = dark ? 'dark' : 'light';
};

const openArticle = (article) => {
  selectedArticle.value = article;
  window.history.pushState({}, '', `/tin-tuc/${article.id}`);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const closeArticle = () => {
  selectedArticle.value = null;
  window.history.pushState({}, '', '/#tin-tuc');
  setTimeout(() => document.getElementById('tin-tuc')?.scrollIntoView({ behavior: 'smooth' }), 0);
};

const defaultHero = {
  title: 'Tích Xanh Fanpage Instagram - Không Cần Báo Chí',
  desc: 'Đăng Ký Tích Xanh Fanpage, Tích Xanh Instagram hoàn thành sau 15 phút. Lên tích ngay.',
  badge: 'Dịch Vụ Uy Tín #1 Việt Nam',
  features: [
    'Áp dụng chính sách mới nhất Meta Verified',
    'Tên tài khoản có tích xanh, không mất khi đổi tên',
    'Thanh toán khi có tích xanh, không mất tiền oan',
    '100% thành công, 2.368 khách tin tưởng'
  ]
};

const servicePages = [
  {
    id: 'tich-xanh-fanpage',
    name: 'Tích xanh Fanpage',
    title: 'Dịch vụ đăng ký Tích Xanh Fanpage',
    desc: 'Hỗ trợ kiểm tra và đăng ký tích xanh cho Fanpage bán hàng, thương hiệu, cộng đồng. Làm xong mới thanh toán, tư vấn rõ điều kiện trước khi triển khai.',
    features: ['Không cần báo chí trong nhiều trường hợp', 'Tăng độ tin cậy khi khách hàng tìm kiếm', 'Hạn chế giả mạo thương hiệu', 'Phù hợp shop online và doanh nghiệp'],
    price: '1.500.000đ',
    meta: 'Phí Meta Verified được tư vấn theo từng hồ sơ'
  },
  {
    id: 'tich-xanh-instagram',
    name: 'Tích xanh Instagram',
    title: 'Dịch vụ đăng ký Tích Xanh Instagram',
    desc: 'Dành cho cá nhân, creator, shop online và business cần tăng uy tín tài khoản Instagram khi bán hàng hoặc xây dựng thương hiệu.',
    features: ['Kiểm tra điều kiện trước khi làm', 'Tối ưu hồ sơ Instagram', 'Hỗ trợ shop và creator', 'Theo sát tới khi có kết quả'],
    price: '1.500.000đ',
    meta: 'Tư vấn phí nền tảng nếu có'
  },
  {
    id: 'tich-xanh-tiktok',
    name: 'Tích xanh TikTok',
    title: 'Dịch vụ đăng ký Tích Xanh TikTok',
    desc: 'Tư vấn và hỗ trợ hồ sơ xác minh TikTok cho creator, thương hiệu, shop và người bán hàng cần tăng độ tin cậy.',
    features: ['Rà soát hồ sơ TikTok', 'Tư vấn điều kiện xác minh', 'Hỗ trợ định vị thương hiệu', 'Cập nhật theo chính sách nền tảng'],
    price: 'Tư vấn theo hồ sơ',
    meta: 'Báo rõ chi phí trước khi triển khai'
  },
  {
    id: 'tich-xanh-facebook-ca-nhan',
    name: 'Tích xanh Facebook cá nhân',
    title: 'Dịch vụ đăng ký Tích Xanh Facebook cá nhân',
    desc: 'Hỗ trợ cá nhân, KOL, chủ shop và người xây dựng thương hiệu cá nhân đăng ký xác minh tài khoản Facebook.',
    features: ['Tối ưu tên và hồ sơ cá nhân', 'Tư vấn giấy tờ cần chuẩn bị', 'Không yêu cầu mật khẩu', 'Hỗ trợ sau khi lên tích xanh'],
    price: '1.500.000đ',
    meta: 'Chi phí nền tảng được tư vấn riêng'
  },
  {
    id: 'tich-xanh-whatsapp',
    name: 'Tích xanh WhatsApp',
    title: 'Dịch vụ đăng ký Tích Xanh WhatsApp',
    desc: 'Dành cho doanh nghiệp dùng WhatsApp Business cần xác minh chính chủ, tăng độ tin cậy khi chăm sóc khách hàng.',
    features: ['Kiểm tra tài khoản doanh nghiệp', 'Tư vấn hồ sơ xác minh', 'Phù hợp đội sale/chăm sóc khách hàng', 'Hỗ trợ cấu hình thông tin doanh nghiệp'],
    price: 'Tư vấn theo hồ sơ',
    meta: 'Báo phí sau khi kiểm tra tài khoản'
  }
];

const openServicePage = (service) => {
  selectedArticle.value = null;
  selectedHeroService.value = service;
  window.history.pushState({}, '', `/dich-vu/${service.id}`);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const closeServicePage = () => {
  selectedHeroService.value = null;
  window.history.pushState({}, '', '/');
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const currentHero = computed(() => selectedHeroService.value ? { ...defaultHero, ...selectedHeroService.value } : defaultHero);

onMounted(() => {
  const savedTheme = localStorage.theme;
  const prefersDark = window.matchMedia?.('(prefers-color-scheme: dark)').matches;
  applyTheme(savedTheme ? savedTheme === 'dark' : prefersDark);
  const serviceId = window.location.pathname.startsWith('/dich-vu/')
    ? window.location.pathname.split('/').pop()
    : null;
  if (serviceId) {
    selectedHeroService.value = servicePages.find((service) => service.id === serviceId) || null;
  }
  const articleId = window.location.pathname.startsWith('/tin-tuc/')
    ? window.location.pathname.split('/').pop()
    : null;
  if (articleId) {
    selectedArticle.value = articlePosts.find((article) => article.id === articleId) || null;
  }
});

const stats = [
  ['2.368', 'Trang đã cấp tích xanh'],
  ["15'", 'Thời gian trung bình'],
  ['100%', 'Tỉ lệ thành công'],
  ['1.5tr', 'Trọn gói']
];

const benefits = [
  [ShieldCheck, 'Xác minh chính chủ', 'Khách hàng còn lại sự tin tưởng, không nghi ngờ giả mạo'],
  [TrendingUp, 'Ưu tiên hiển thị', 'Lượt tiếp cận tự nhiên tăng 20-40%, hiển thị top khi tìm kiếm'],
  [BarChart3, 'Tăng doanh thu', 'Tỉ lệ chốt đơn trên page tích xanh tăng trung bình 28%'],
  [BadgeCheck, 'Bảo vệ thương hiệu', 'Facebook tự động gỡ các trang mạo danh bạn']
];

const priceCards = [
  {
    name: 'Tích Xanh Fanpage',
    desc: 'Cho Fanpage Facebook',
    price: '1.500.000đ',
    features: ['Tên Fanpage nào cũng lên được', 'Không cần báo chí', '15 phút hoàn thành', 'Bảo hành trọn đời'],
    button: 'Đăng ký Fanpage'
  },
  {
name: 'Tích Xanh Instagram',
    desc: 'Cho Instagram cá nhân & business',
    price: '1.500.000đ',
    features: ['Cá nhân & doanh nghiệp đều được', 'Tăng độ tin cậy cho shop online', '15 phút hoàn thành', 'Hỗ trợ 24/7'],
    button: 'Đăng ký Instagram'
  },
  {
    name: 'Combo Fanpage + IG',
    desc: '2 nền tảng, 1 lần thanh toán',
    price: '2.500.000đ',
    popular: true,
    features: ['Đăng ký cả Fanpage + Instagram', 'Tiết kiệm hơn đăng ký riêng', 'Khuyến dùng cho shop lớn', 'Bảo hành trọn đời'],
    button: 'Đăng ký Combo'
  }
];

const process = [
  ['1', 'Liên hệ tư vấn', 'Gọi hoặc nhắn Zalo để được tư vấn miễn phí trong 5 phút'],
  ['2', 'Cung cấp thông tin', 'Gửi link Fanpage + ảnh CMND/CCCD qua Zalo'],
  ['3', 'Chúng tôi xử lý', 'Đội ngũ kỹ thuật hoàn tất trong 15-60 phút'],
  ['4', 'Nhận tích xanh & thanh toán', 'Xem tích xanh live trên Fanpage, thanh toán khi hài lòng']
];

const reasons = [
  [Award, 'Nhanh nhất thị trường', '15 phút, không phải 1-7 ngày như tự làm'],
  [BadgeCheck, 'Tỉ lệ 100% thành công', 'Làm xong mới thanh toán, không mất tiền oan'],
  [WalletCards, 'Hỗ trợ trọn đời', 'Gặp sự cố gì chúng tôi xử lý miễn phí'],
  [LockKeyhole, 'An toàn 100%', 'Chỉ dùng phương pháp chính thức của Meta'],
  [Phone, 'Tư vấn 24/7', 'Luôn có nhân viên trực hotline và Zalo'],
  [Trophy, 'Uy tín AZ Media', 'Được nhiều cá nhân và doanh nghiệp tin tưởng']
];

const reviews = [
  ['Nguyễn Văn Minh', 'Chủ shop thời trang', 'NV', 'Sau khi có tích xanh, inbox hỏi mua tăng gần gấp đôi. Khách không còn hỏi "có phải shop thật không?" nữa.'],
  ['Trần Thị Lan', 'Chủ spa làm đẹp', 'TT', 'Thủ tục cực nhanh, đúng 20 phút là có tích xanh. Quan trọng là không phải trả tiền trước.'],
  ['Phạm Hồng Quân', 'KOL kinh doanh', 'PH', 'Nhờ có tích xanh mà nhãn hàng chịu booking giá cao hơn trước. ROI quá hời.']
];

const faqs = [
  ['Đăng ký tích xanh mất bao lâu?', 'Chỉ 15-60 phút sau khi nhận đủ thông tin. Trường hợp đặc biệt có thể lên tới 24h, chúng tôi sẽ báo trước.'],
  ['Fanpage mới tạo có đăng ký được không?', 'Có thể kiểm tra trước. Chúng tôi sẽ tư vấn điều kiện cụ thể để tránh mất thời gian.'],
  ['Chi phí đăng ký cụ thể là bao nhiêu?', 'Gói phổ biến từ 1.500.000đ, làm xong mới thanh toán.'],
  ['Phải trả phí trong giai đoạn hay gói tháng?', 'Phí dịch vụ được báo rõ trước khi làm. Nếu có phí nền tảng từ Meta, chuyên viên sẽ tư vấn riêng.'],
  ['Tích xanh có bị mất không?', 'Chúng tôi hỗ trợ đúng quy trình và tư vấn cách giữ tài khoản an toàn sau khi lên tích xanh.'],
  ['Có an toàn không? Tài khoản có bị khóa không?', 'Quy trình không yêu cầu mật khẩu và ưu tiên phương pháp chính thức, minh bạch.'],
  ['Tích xanh khác tích xám như thế nào?', 'Tích xanh thể hiện xác minh chính chủ, phù hợp thương hiệu, cá nhân, shop và doanh nghiệp.'],
  ['Nếu không thành công thì sao?', 'Không thành công thì không thu phí dịch vụ.']
];
const articles = articlePosts.slice(0, 6);

const quickServices = [
  ['Tích xanh Fanpage', 'from-violet-600 to-purple-700'],
  ['Tích xanh Facebook cá nhân', 'from-blue-600 to-blue-700'],
  ['Tích xanh TikTok', 'from-slate-800 to-slate-950'],
  ['Tích xanh Instagram', 'from-pink-600 to-rose-700']
];
</script>

<template>
  <div class="site-page min-h-screen bg-[#f7fbff] text-slate-950 transition-colors duration-300 dark:bg-[#07101f] dark:text-white">
    <header class="site-header mx-auto flex max-w-[760px] items-center justify-between px-5 py-5">
      <a href="/" class="site-logo flex items-center gap-2 text-[13px] font-black text-slate-900 dark:text-white">
        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white">
          <Check class="h-3.5 w-3.5 stroke-[4]" />
        </span>
        <span>Đăng Ký Tích Xanh<small>.vn</small></span>
      </a>
      <nav class="site-nav">
        <a href="#">Trang chủ</a>
        <div class="site-service-menu">
          <button type="button">Dịch vụ <ChevronDown class="site-service-chevron" /></button>
          <div class="site-service-dropdown">
            <a v-for="service in servicePages" :key="service.id" :href="`/dich-vu/${service.id}`" @click.prevent="openServicePage(service)">{{ service.name }}</a>
          </div>
        </div>
        <a href="#loi-ich">Lợi ích</a>
        <a href="#bang-gia">Bảng giá</a>
        <a href="#quy-trinh">Quy trình</a>
        <a href="#tin-tuc">Tin tức</a>
        <a href="#contact">Liên hệ</a>
      </nav>
      <div class="site-actions flex items-center gap-3">
        <a class="site-fast-register" href="#contact">🚀 Đăng ký nhanh</a>
        <button class="site-theme-button text-slate-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-white" @click="applyTheme(!isDark)" aria-label="Đổi giao diện">
          <Sun v-if="isDark" class="h-4 w-4" />
          <Moon v-else class="h-4 w-4" />
        </button>
        <a class="site-header-phone" href="tel:0968825068">0968.825.068</a>
        <Menu class="site-menu-icon h-4 w-4 text-slate-500 dark:text-slate-400" />
      </div>
    </header>

    <main v-if="selectedArticle" class="article-modern">
      <div class="article-modern-shell">
        <div class="article-modern-wrap">
          <nav class="article-breadcrumb" aria-label="Đường dẫn bài viết">
            <button type="button" @click="closeArticle">Trang chủ</button>
            <span>/</span>
            <button type="button" @click="closeArticle">Tin tức</button>
            <span>/</span>
            <span>{{ selectedArticle.title }}</span>
          </nav>

          <div class="article-modern-grid">
            <article class="article-modern-main">
              <h1>{{ selectedArticle.title }}</h1>

              <div class="article-tldr">
                <strong>💡 TL;DR - TÓM TẮT NHANH</strong>
                <p>{{ selectedArticle.description }}</p>
              </div>

              <div class="article-modern-meta">
                <span>{{ selectedArticle.author || 'Đăng Ký Tích Xanh Team' }}</span>
                <span>·</span>
                <span>{{ selectedArticle.category }}</span>
                <span>·</span>
                <span>{{ selectedArticle.date }}</span>
                <span>·</span>
                <span>{{ selectedArticle.readTime }}</span>
                <button type="button">Lưu bài</button>
              </div>

              <div class="article-hero-img">
                <img :src="selectedArticle.banner" :alt="selectedArticle.title" />
              </div>

              <div class="article-modern-content" v-html="selectedArticle.content"></div>
            </article>

            <aside class="article-modern-sidebar">
              <h3>☷ Mục lục</h3>
              <a v-for="item in articles.filter(a => a.id !== selectedArticle.id).slice(0, 6)" :key="item.id" href="#" @click.prevent="openArticle(item)">
                {{ item.title }}
              </a>
              <a href="#contact" @click="selectedArticle = null">Dịch vụ hỗ trợ đăng ký tích xanh</a>
            </aside>
          </div>
        </div>
      </div>
    </main>

    <main v-else>
      <section class="mx-auto grid max-w-[760px] gap-8 px-5 pb-14 pt-7 md:grid-cols-[1.05fr_0.95fr] md:items-center md:pt-14">
        <div>
          <div class="mb-5 inline-flex rounded-full bg-blue-50 px-3 py-1.5 text-[11px] font-bold text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
            {{ currentHero.badge }}
          </div>
          <h1 class="max-w-[360px] text-[39px] font-black leading-[0.95] tracking-tight text-slate-950 dark:text-white md:text-[43px]">
            {{ currentHero.title }}
          </h1>
          <p class="mt-4 max-w-[390px] text-[13px] font-medium leading-6 text-slate-600 dark:text-slate-300">
            {{ currentHero.desc }}
          </p>

          <div class="mt-4 grid max-w-[390px] grid-cols-2 gap-2">
<div v-for="item in currentHero.features" :key="item" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-[10px] font-semibold leading-4 text-slate-700 shadow-sm dark:border-white/10 dark:bg-[#0e1b31] dark:text-slate-200">
              <Check class="mb-1 h-3.5 w-3.5 text-emerald-500" />
              {{ item }}
            </div>
          </div>

          <div class="mt-5 flex flex-wrap gap-3">
            <a href="#contact" class="rounded-lg bg-blue-600 px-5 py-3 text-[12px] font-black text-white shadow-lg shadow-blue-600/20 hover:bg-blue-500">Đăng Ký Ngay</a>
            <a href="tel:0968825068" class="rounded-lg border border-slate-200 bg-white px-5 py-3 text-[12px] font-black text-slate-800 shadow-sm hover:border-blue-300 dark:border-white/10 dark:bg-[#0e1b31] dark:text-white">0968.825.068</a>
          </div>
        </div>

        <div class="mx-auto w-full max-w-[330px] rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-xl shadow-slate-900/8 dark:border-white/10 dark:bg-[#14223b] dark:shadow-none">
          <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-blue-600 text-2xl font-black text-white">DK</div>
          <div class="mt-4 flex items-center justify-center gap-1 text-sm font-black text-slate-950 dark:text-white">
            Đăng Ký Tích Xanh
            <BadgeCheck class="h-4 w-4 fill-blue-600 text-white" />
          </div>
          <p class="mt-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">Meta Verified · Trang chính chủ</p>
          <div class="mt-5 grid grid-cols-3 border-t border-slate-100 pt-4 text-center dark:border-white/10">
            <div v-for="stat in [['2.368', 'Trang đã cấp'], ['99%', 'Tỷ lệ thành công'], ['15’', 'Thời gian']]" :key="stat[1]">
              <p class="text-sm font-black text-blue-600 dark:text-blue-400">{{ stat[0] }}</p>
              <p class="mt-1 text-[9px] font-semibold text-slate-500 dark:text-slate-400">{{ stat[1] }}</p>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-[#092247] py-7 text-white">
        <div class="mx-auto grid max-w-[760px] grid-cols-4 px-5 text-center">
          <div v-for="item in stats" :key="item[1]">
            <p class="text-xl font-black">{{ item[0] }}</p>
            <p class="mt-1 text-[10px] font-semibold text-blue-100/80">{{ item[1] }}</p>
          </div>
        </div>
      </section>

      <section class="bg-white py-16 dark:bg-[#07101f]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="mx-auto max-w-[560px] text-center">
            <h2 class="text-2xl font-black leading-tight text-slate-950 dark:text-white">Tích Xanh Đem Lại Gì Cho Fanpage & Business Của Bạn?</h2>
<p class="mt-3 text-[12px] font-medium text-slate-500 dark:text-slate-400">Tín hiệu Uy Tín cao nhất trên Meta, tăng tiếp cận tự nhiên và doanh số ngay lập tức</p>
          </div>
          <div class="mt-10 grid gap-4 sm:grid-cols-2">
            <div v-for="[Icon, title, desc] in benefits" :key="title" class="rounded-xl border border-slate-200 bg-white p-7 text-center shadow-sm dark:border-white/10 dark:bg-[#122039]">
              <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                <component :is="Icon" class="h-5 w-5" />
              </div>
              <h3 class="mt-5 text-sm font-black text-slate-950 dark:text-white">{{ title }}</h3>
              <p class="mt-2 text-[11px] font-medium leading-5 text-slate-500 dark:text-slate-400">{{ desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <section id="bang-gia" class="bg-[#f3f8ff] py-16 dark:bg-[#0b1528]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="mb-9 text-center">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">Bảng giá dịch vụ</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-500 dark:text-slate-400">Làm xong mới thanh toán, cam kết 100% thành công</p>
          </div>
          <div class="grid gap-4 md:grid-cols-3">
            <article v-for="card in priceCards" :key="card.name" class="relative rounded-xl border bg-white p-5 shadow-sm dark:bg-[#111d34]" :class="card.popular ? 'border-blue-500 ring-2 ring-blue-500/20 dark:border-blue-400' : 'border-slate-200 dark:border-white/10'">
              <span v-if="card.popular" class="absolute -top-3 right-4 rounded-full bg-emerald-400 px-3 py-1 text-[10px] font-black text-white">Phổ biến nhất</span>
              <h3 class="text-sm font-black text-slate-950 dark:text-white">{{ card.name }}</h3>
              <p class="mt-1 text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ card.desc }}</p>
              <p class="mt-5 text-2xl font-black text-blue-600 dark:text-blue-400">{{ card.price }}</p>
              <p class="text-[10px] font-bold text-slate-400">trọn gói</p>
              <ul class="mt-5 space-y-2">
                <li v-for="feature in card.features" :key="feature" class="flex gap-2 text-[10px] font-semibold leading-4 text-slate-600 dark:text-slate-300">
                  <Check class="h-3.5 w-3.5 shrink-0 text-emerald-500" />
                  {{ feature }}
                </li>
              </ul>
              <div class="mt-5 rounded-lg bg-slate-50 px-3 py-2 text-[9px] font-semibold text-slate-500 dark:bg-[#0a1325] dark:text-slate-400">Phí Meta Verified: tư vấn khi kiểm tra hồ sơ</div>
<a href="#contact" class="mt-5 block rounded-lg border border-blue-600 px-4 py-2.5 text-center text-[11px] font-black text-blue-600 hover:bg-blue-600 hover:text-white dark:text-blue-300">{{ card.button }}</a>
            </article>
          </div>
        </div>
      </section>

      <section id="quy-trinh" class="bg-white py-16 dark:bg-[#07101f]">
        <div class="mx-auto max-w-[560px] px-5">
          <div class="text-center">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">Quy trình 4 bước</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-500 dark:text-slate-400">Đơn giản, minh bạch, hoàn thành trong 15 phút</p>
          </div>
          <div class="mt-10 space-y-6">
            <div v-for="step in process" :key="step[0]" class="flex gap-5">
              <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-600 text-sm font-black text-white shadow-lg shadow-blue-600/25">{{ step[0] }}</div>
              <div class="pt-1">
                <h3 class="text-sm font-black text-slate-950 dark:text-white">{{ step[1] }}</h3>
                <p class="mt-1 text-[12px] font-medium leading-5 text-slate-500 dark:text-slate-400">{{ step[2] }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="loi-ich" class="why-choice-section bg-[#0c1930] py-16 text-white dark:bg-[#0c1930]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="why-choice-heading mb-9 text-center">
            <h2 class="text-2xl font-black">Tại sao chọn chúng tôi?</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-300">Được 2.368 khách hàng tin tưởng</p>
          </div>
          <div class="why-choice-grid grid gap-4 sm:grid-cols-2">
            <div v-for="[Icon, title, desc] in reasons" :key="title" class="why-choice-card rounded-xl bg-white/7 p-6 text-center">
              <div class="why-choice-icon mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-cyan-500/20 text-cyan-300">
                <component :is="Icon" class="h-5 w-5" />
              </div>
              <h3 class="mt-4 text-sm font-black">{{ title }}</h3>
              <p class="mt-2 text-[11px] font-medium leading-5 text-slate-300">{{ desc }}</p>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-white py-16 dark:bg-[#07101f]">
        <div class="mx-auto max-w-[760px] px-5">
          <h2 class="mb-8 text-center text-2xl font-black text-slate-950 dark:text-white">Khách hàng nói gì?</h2>
          <div class="grid gap-4 md:grid-cols-3">
            <div v-for="review in reviews" :key="review[0]" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#111d34]">
              <div class="mb-3 flex text-amber-400">
<Star v-for="n in 5" :key="n" class="h-3.5 w-3.5 fill-current" />
              </div>
              <p class="min-h-20 text-[11px] font-medium italic leading-5 text-slate-600 dark:text-slate-300">"{{ review[3] }}"</p>
              <div class="mt-5 flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-[10px] font-black text-white">{{ review[2] }}</div>
                <div>
                  <p class="text-[11px] font-black text-slate-950 dark:text-white">{{ review[0] }}</p>
                  <p class="text-[10px] font-medium text-slate-500 dark:text-slate-400">{{ review[1] }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="bg-[#f8fbff] py-16 dark:bg-[#0b1528]">
        <div class="mx-auto max-w-[640px] px-5">
          <h2 class="mb-8 text-center text-2xl font-black leading-tight text-slate-950 dark:text-white">Đăng Ký Tích Xanh Có An Toàn Không? <br />- Giải Đáp 8 Câu Hỏi Top</h2>
          <div class="space-y-3">
            <div v-for="(faq, index) in faqs" :key="faq[0]" class="rounded-lg border border-slate-200 bg-white dark:border-white/10 dark:bg-[#111d34]">
              <button class="flex w-full items-center justify-between gap-3 px-4 py-3 text-left text-[12px] font-black text-slate-900 dark:text-white" @click="activeFaq = activeFaq === index ? -1 : index">
                {{ faq[0] }}
                <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                  <ChevronDown class="h-3.5 w-3.5 transition" :class="{ 'rotate-180': activeFaq === index }" />
                </span>
              </button>
              <p v-if="activeFaq === index" class="px-4 pb-4 text-[11px] font-medium leading-5 text-slate-500 dark:text-slate-400">{{ faq[1] }}</p>
            </div>
          </div>
        </div>
      </section>

      <section id="tin-tuc" class="bg-[#eef6ff] py-16 dark:bg-[#0d1b31]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="mb-8 text-center">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">Bài viết hữu ích</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-500 dark:text-slate-400">Kiến thức cập nhật trước khi đăng ký tích xanh</p>
          </div>
          <div class="grid gap-3 sm:grid-cols-2">
            <button v-for="item in articles" :key="item.id" type="button" class="flex items-center justify-between rounded-lg bg-white px-4 py-3 text-left text-[12px] font-bold text-slate-700 shadow-sm dark:bg-[#111d34] dark:text-slate-200" @click="openArticle(item)">
              <span>{{ item.title }}</span>
              <ChevronDown class="-rotate-90 h-3.5 w-3.5" />
            </button>
          </div>
<div class="mt-5 grid gap-3 sm:grid-cols-2">
            <a v-for="item in quickServices" :key="item[0]" href="#contact" class="flex items-center justify-between rounded-lg bg-gradient-to-r px-4 py-3 text-[12px] font-black text-white" :class="item[1]">
              {{ item[0] }}
              <ChevronDown class="-rotate-90 h-4 w-4" />
            </a>
          </div>
        </div>
      </section>

      <section class="bg-blue-600 py-14 text-center text-white">
        <div class="mx-auto max-w-[760px] px-5">
          <h2 class="text-2xl font-black">Sẵn sàng có tích xanh trong 15 phút?</h2>
          <p class="mt-3 text-[12px] font-semibold text-blue-100">Hotline 0968.825.068 - tư vấn miễn phí, không ép mua, hỗ trợ cả đêm</p>
          <div class="mt-6 flex justify-center gap-3">
            <a href="#contact" class="rounded-lg bg-white px-5 py-3 text-[12px] font-black text-blue-600">Đăng ký ngay</a>
            <a href="tel:0968825068" class="rounded-lg border border-white/70 px-5 py-3 text-[12px] font-black text-white">0968.825.068</a>
          </div>
        </div>
      </section>

      <section id="contact" class="bg-[#f8fbff] py-16 dark:bg-[#07101f]">
        <div class="mx-auto max-w-[760px] px-5">
          <div class="mb-8 text-center">
            <h2 class="text-2xl font-black text-slate-950 dark:text-white">Liên hệ</h2>
            <p class="mt-2 text-[12px] font-medium text-slate-500 dark:text-slate-400">Chúng tôi trả lời trong 15 phút</p>
          </div>
          <div class="grid gap-5 md:grid-cols-2">
            <div class="space-y-3">
              <div v-for="[Icon, label, value] in [[Phone, 'HOTLINE', '0968.825.068 (Mr. Quang)'], [MessageCircle, 'ZALO', '0968.825.068'], [Mail, 'EMAIL', 'azmedia.com.vn@gmail.com'], [MapPin, 'ĐỊA CHỈ', '84 Nguyễn Hữu Dật, Hòa Cường Bắc, Hải Châu, Đà Nẵng'], [Clock, 'LÀM VIỆC', '8:00 - 12:00, 13:30 - 17:30']]" :key="label" class="contact-info-card flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-[#111d34]">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300">
                  <component :is="Icon" class="h-4 w-4" />
                </div>
                <div>
                  <p class="text-[10px] font-black text-slate-400">{{ label }}</p>
                  <p class="text-[12px] font-black text-slate-900 dark:text-white">{{ value }}</p>
                </div>
              </div>
            </div>
            <form class="contact-form-card rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-[#111d34]" @submit.prevent>
              <h3 class="text-sm font-black text-slate-950 dark:text-white">Đăng ký tư vấn miễn phí</h3>
<p class="mt-1 text-[11px] font-medium text-slate-500 dark:text-slate-400">Điền 3 thông tin, chúng tôi gọi lại trong 15 phút</p>
              <div class="mt-4 space-y-3">
                <label class="contact-label">Họ tên *</label>
                <input v-model="leadName" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[12px] text-slate-900 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-[#07101f] dark:text-white" placeholder="Nguyễn Văn A" />
                <label class="contact-label">Số điện thoại *</label>
                <input v-model="leadPhone" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[12px] text-slate-900 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-[#07101f] dark:text-white" placeholder="0912345678" />
                <label class="contact-label">Lời nhắn *</label>
                <textarea v-model="leadLink" class="min-h-20 w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-[12px] text-slate-900 outline-none focus:border-blue-500 dark:border-white/10 dark:bg-[#07101f] dark:text-white" placeholder="VD: Tôi muốn đăng ký tích xanh cho Fanpage ABC..."></textarea>
                <label class="flex items-center gap-2 text-[11px] font-medium text-slate-500 dark:text-slate-400">
                  <input type="checkbox" class="rounded border-slate-300" />
                  Tôi xác nhận không phải robot
                </label>
                <button class="w-full rounded-lg bg-blue-600 py-3 text-[12px] font-black text-white hover:bg-blue-500">Gửi yêu cầu tư vấn</button>
                <p class="text-center text-[10px] font-medium text-slate-400">Cam kết bảo mật thông tin, không spam</p>
              </div>
            </form>
          </div>
        </div>
      </section>
    </main>

    <footer class="bg-[#07101f] py-10 text-slate-400 dark:bg-[#092247]">
      <div class="mx-auto grid max-w-[760px] gap-8 px-5 md:grid-cols-[1.2fr_0.8fr_0.8fr]">
        <div>
          <div class="flex items-center gap-2 text-[13px] font-black text-white">
            <span class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white"><Check class="h-3.5 w-3.5 stroke-[4]" /></span>
            Đăng Ký Tích Xanh
          </div>
          <p class="mt-4 text-[11px] font-medium leading-5">Đăng ký tích xanh Facebook, Instagram 15 phút. Không cần báo chí, làm xong thanh toán. Dịch vụ hỗ trợ bởi AZ Media.</p>
        </div>
        <div>
          <h3 class="text-[12px] font-black text-white">Dịch vụ</h3>
          <ul class="mt-3 space-y-2 text-[11px] font-medium">
            <li>Tích xanh Fanpage</li>
            <li>Tích xanh Instagram</li>
            <li>Tích xanh TikTok</li>
            <li>Tích xanh Cá nhân</li>
          </ul>
        </div>
        <div>
<h3 class="text-[12px] font-black text-white">Liên hệ</h3>
          <ul class="mt-3 space-y-2 text-[11px] font-medium">
            <li>Hotline: 0968.825.068</li>
            <li>Email: azmedia.com.vn@gmail.com</li>
            <li>84 Nguyễn Hữu Dật, Hòa Cường Bắc, Hải Châu, Đà Nẵng</li>
          </ul>
        </div>
      </div>
      <div class="mx-auto mt-8 flex max-w-[760px] flex-wrap justify-between gap-3 border-t border-white/10 px-5 pt-5 text-[10px] font-medium">
        <p>© 2026 Đăng Ký Tích Xanh - Dịch vụ đăng ký tích xanh Facebook, Fanpage, Instagram, TikTok & WhatsApp.</p>
        <p>Giới thiệu · Điều khoản · Bảo mật · DMCA</p>
      </div>
    </footer>

    <a href="tel:0968825068" class="fixed right-5 top-[300px] z-40 flex h-11 w-11 items-center justify-center rounded-full bg-blue-600 text-white shadow-xl shadow-blue-600/30">
      <MessageCircle class="h-5 w-5" />
    </a>
  </div>
</template>






