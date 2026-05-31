<script setup>
import {
  ArrowLeft,
  Bookmark,
  ChevronRight,
  PhoneCall
} from '@lucide/vue';
import { services } from '../data/services';

defineProps({
  article: {
    type: Object,
    required: true
  },
  allArticles: {
    type: Array,
    required: true
  }
});

const emit = defineEmits(['back', 'select-article', 'open-register', 'select-service']);

const handleSelectArticle = (otherArticle) => {
  emit('select-article', otherArticle);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};
</script>

<template>
  <div class="article-page relative overflow-hidden bg-slate-50 py-12 text-slate-900 dark:bg-[#060b13] dark:text-slate-100">
    <div class="pointer-events-none absolute top-24 left-1/2 hidden h-[520px] w-[520px] -translate-x-1/2 rounded-full bg-blue-600/10 blur-[130px] dark:block"></div>
    <div class="pointer-events-none absolute bottom-10 right-10 hidden h-[320px] w-[320px] rounded-full bg-cyan-500/5 blur-[100px] dark:block"></div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="mb-8 flex items-center justify-between gap-4">
        <button
          @click="emit('back')"
          class="group inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-base font-extrabold text-slate-600 shadow-sm transition-colors duration-200 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 dark:border-white/10 dark:bg-[#0f172a] dark:text-slate-300 dark:hover:border-blue-500/30 dark:hover:bg-[#14213a] dark:hover:text-white"
        >
          <ArrowLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
          <span>Quay lại trang chủ</span>
        </button>

        <span class="rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-xs font-black uppercase tracking-wider text-blue-600 dark:border-blue-500/25 dark:bg-blue-500/10 dark:text-blue-400">
          {{ article.category }}
        </span>
      </div>

      <div class="grid grid-cols-1 items-start gap-8 lg:grid-cols-12">
        <article class="article-card rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 dark:border-white/10 dark:bg-[#0a1020] dark:shadow-none sm:p-8 lg:col-span-8">
          <div class="mb-7">
            <h1 class="max-w-4xl text-4xl font-black leading-[1.08] tracking-tight text-slate-950 dark:text-white sm:text-5xl">
              {{ article.title }}
            </h1>
          </div>

          <div class="relative mb-9 aspect-[21/9] w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-inner dark:border-white/10 dark:bg-slate-900">
            <img
              :src="article.banner"
              :alt="article.title"
              class="h-full w-full object-cover object-center transition-transform duration-700 hover:scale-105"
            />
            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-white/70 to-transparent dark:from-[#060b13]/80"></div>
          </div>

          <div class="article-content max-w-none" v-html="article.content"></div>

          <div class="relative mt-12 overflow-hidden rounded-2xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-500/25 dark:bg-[#111b2f] sm:p-8">
            <div class="pointer-events-none absolute top-0 right-0 h-24 w-24 rounded-full bg-blue-500/10 blur-2xl"></div>
            <div class="relative z-10 flex flex-col justify-between gap-6 md:flex-row md:items-center">
              <div class="space-y-2">
                <h3 class="text-2xl font-black text-slate-950 dark:text-white">Đăng ký lên Tích Xanh ngay hôm nay</h3>
                <p class="text-base font-semibold leading-relaxed text-slate-600 dark:text-slate-300">
                  Bảo mật tuyệt đối, làm xong mới thanh toán, hỗ trợ chuẩn hóa tên 100%.
                </p>
              </div>
              <button
                @click="emit('open-register')"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-7 py-4 text-base font-black text-white shadow-lg shadow-blue-500/20 transition-all hover:bg-blue-500 active:scale-95"
              >
                <PhoneCall class="h-5 w-5" />
                Nhận tư vấn miễn phí
              </button>
            </div>
          </div>
        </article>

        <aside class="space-y-6 lg:col-span-4">
          <div class="sticky top-28 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-lg shadow-slate-900/5 dark:border-white/10 dark:bg-[#0a1020] dark:shadow-none">
              <h3 class="mb-4 flex items-center gap-2 border-b border-slate-200 pb-3 text-base font-black uppercase tracking-wide text-slate-950 dark:border-white/10 dark:text-white">
                <Bookmark class="h-4 w-4 text-blue-500" />
                Dịch vụ của chúng tôi
              </h3>

              <div class="space-y-3">
                <a
                  v-for="(service, idx) in services"
                  :key="service.id"
                  :href="`/dich-vu/${service.id}`"
                  @click.prevent="emit('select-service', idx); emit('back'); window.scrollTo({ top: 0 });"
                  class="group flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4 transition-all hover:border-blue-300 hover:bg-blue-50 dark:border-white/10 dark:bg-[#111b2f] dark:hover:border-blue-500/30 dark:hover:bg-blue-600/10"
                >
                  <div class="space-y-1">
                    <span class="text-base font-extrabold text-slate-900 transition-colors group-hover:text-blue-700 dark:text-slate-100 dark:group-hover:text-white">{{ service.name }}</span>
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ service.speed }} hoàn thành</p>
                  </div>
                  <ChevronRight class="h-5 w-5 text-slate-400 transition-all group-hover:translate-x-0.5 group-hover:text-blue-600 dark:text-slate-500 dark:group-hover:text-white" />
                </a>
              </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-lg shadow-slate-900/5 dark:border-white/10 dark:bg-[#0a1020] dark:shadow-none">
              <h3 class="mb-4 flex items-center gap-2 border-b border-slate-200 pb-3 text-base font-black uppercase tracking-wide text-slate-950 dark:border-white/10 dark:text-white">
                <Bookmark class="h-4 w-4 text-blue-500" />
                Bài viết cùng chuyên mục
              </h3>

              <div class="space-y-3">
                <button
                  v-for="item in allArticles.filter(a => a.id !== article.id)"
                  :key="item.id"
                  @click="handleSelectArticle(item)"
                  class="group flex w-full items-start gap-3 rounded-xl p-2 text-left transition-all hover:bg-slate-100 dark:hover:bg-white/5"
                >
                  <div class="h-14 w-20 shrink-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-100 dark:border-white/10 dark:bg-slate-900">
                    <img :src="item.banner" :alt="item.title" class="h-full w-full object-cover" />
                  </div>
                  <div class="space-y-1">
                    <h4 class="line-clamp-2 text-sm font-extrabold leading-snug text-slate-800 transition-colors group-hover:text-blue-700 dark:text-slate-200 dark:group-hover:text-white">
                      {{ item.title }}
                    </h4>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-500">
                      {{ item.date }}
                    </p>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>
