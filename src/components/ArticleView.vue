<script setup>
import { 
  ArrowLeft, 
  Calendar, 
  User, 
  Clock, 
  Bookmark, 
  ChevronRight, 
  CheckCircle,
  PhoneCall,
  Send,
  Check
} from '@lucide/vue';
import { services } from '../data/services';

const props = defineProps({
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
  <div class="py-12 bg-slate-50 dark:bg-[#060b13] relative overflow-hidden transition-colors duration-300">
    <!-- Subtle glow backgrounds -->
    <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-10 right-10 w-[300px] h-[300px] bg-[#8b5cf6]/5 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative">
      <!-- Breadcrumb & Back button -->
      <div class="flex items-center justify-between mb-8">
        <button 
          @click="emit('back')" 
          class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-200 group bg-slate-200/50 dark:bg-slate-900/40 hover:bg-slate-300/50 dark:hover:bg-slate-900/80 px-4 py-2 rounded-xl border border-slate-300 dark:border-white/5"
        >
          <ArrowLeft class="h-4 w-4 transition-transform group-hover:-translate-x-1" />
          <span>Quay lại trang chủ</span>
        </button>

        <span class="text-xs font-bold uppercase tracking-wider text-blue-500 bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20">
          {{ article.category }}
        </span>
      </div>

      <!-- Main Layout Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Article Content -->
        <article class="lg:col-span-8 bg-white dark:bg-[#070d1a]/50 border border-slate-200 dark:border-white/5 rounded-3xl p-6 sm:p-8 backdrop-blur-md shadow-xl dark:shadow-2xl">
          <!-- Article Header -->
          <div class="space-y-4 mb-6">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-tight">
              {{ article.title }}
            </h1>
            
          </div>

          <!-- Banner Image -->
          <div class="relative aspect-[21/9] w-full rounded-2xl overflow-hidden mb-8 border border-white/5 shadow-inner">
            <img 
              :src="article.banner" 
              :alt="article.title"
              class="w-full h-full object-cover object-center transform hover:scale-105 transition-transform duration-700"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 dark:from-[#060b13]/80 via-transparent to-transparent"></div>
          </div>

          <!-- Dynamic HTML Content -->
          <div 
            class="prose dark:prose-invert max-w-none prose-p:leading-relaxed prose-headings:text-slate-900 dark:prose-headings:text-white prose-a:text-blue-600 dark:prose-a:text-blue-400"
            v-html="article.content"
          ></div>

          <!-- Article Share / CTA bottom -->
          <div class="mt-12 p-6 sm:p-8 rounded-2xl bg-gradient-to-br from-blue-50 dark:from-[#1e293b]/60 to-white dark:to-[#0f172a]/60 border border-blue-200 dark:border-blue-500/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
              <div class="space-y-2">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white">Đăng ký lên Tích Xanh ngay hôm nay</h3>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-medium">Bảo mật tuyệt đối, làm xong mới thanh toán, hỗ trợ chuẩn hóa tên 100%.</p>
              </div>
              <button 
                @click="emit('open-register')"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-500 px-6 py-3.5 text-xs font-black text-white shadow-lg shadow-blue-500/20 active:scale-98 transition-all shrink-0"
              >
                <PhoneCall class="h-4 w-4" />
                Nhận Tư Vấn Miễn Phí
              </button>
            </div>
          </div>
        </article>

        <!-- Right Sidebar: Quick Registration Form & Related Articles -->
        <aside class="lg:col-span-4 space-y-6">
          
          <!-- Sticky Widget Container -->
          <div class="sticky top-24 space-y-6">
            
            <!-- Quick CTA Service Widget -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/5 bg-white dark:bg-[#070d1a]/40 p-5 backdrop-blur-md shadow-sm dark:shadow-none">
              <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2 border-b border-slate-200 dark:border-white/5 pb-3">
                <Bookmark class="h-4 w-4 text-blue-500" />
                Dịch Vụ Của Chúng Tôi
              </h3>
              
              <div class="space-y-3">
                <a 
                  v-for="(service, idx) in services" 
                  :key="service.id"
                  href="#top"
                  @click="emit('select-service', idx); emit('back'); window.scrollTo({ top: 0 });"
                  class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#141f36]/20 transition-all hover:bg-blue-50 dark:hover:bg-blue-600/10 hover:border-blue-200 dark:hover:border-blue-500/30 group"
                >
                  <div class="space-y-0.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-white transition-colors">{{ service.name }}</span>
                    <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">{{ service.speed }} hoàn thành</p>
                  </div>
                  <ChevronRight class="h-4 w-4 text-slate-400 dark:text-slate-500 group-hover:text-blue-600 dark:group-hover:text-white transition-all transform group-hover:translate-x-0.5" />
                </a>
              </div>
            </div>

            <!-- Other Helpful Articles -->
            <div class="rounded-2xl border border-slate-200 dark:border-white/5 bg-white dark:bg-[#070d1a]/40 p-5 backdrop-blur-md shadow-sm dark:shadow-none">
              <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2 border-b border-slate-200 dark:border-white/5 pb-3">
                <Bookmark class="h-4 w-4 text-blue-500" />
                Bài viết cùng chuyên mục
              </h3>

              <div class="space-y-3">
                <button 
                  v-for="item in allArticles.filter(a => a.id !== article.id)" 
                  :key="item.id"
                  @click="handleSelectArticle(item)"
                  class="flex items-start gap-3 text-left w-full p-2 rounded-xl transition-all hover:bg-slate-100 dark:hover:bg-white/5 group"
                >
                  <!-- small thumbnail replacement -->
                  <div class="h-12 w-16 rounded-lg overflow-hidden shrink-0 border border-slate-200 dark:border-white/5 bg-slate-100 dark:bg-slate-900">
                    <img :src="item.banner" :alt="item.title" class="w-full h-full object-cover" />
                  </div>
                  <div class="space-y-1">
                    <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-white transition-colors line-clamp-2 leading-tight">
                      {{ item.title }}
                    </h4>
                    <p class="text-[9px] font-semibold text-slate-500 flex items-center gap-1">
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

<style scoped>
/* Inline styling overrides to keep typography looking clean and premium inside dark mode */
:deep(.prose) {
  font-size: 0.95rem;
  line-height: 1.75;
}
:deep(.prose p) {
  margin-bottom: 1.25rem;
}
:deep(.prose h2) {
  margin-top: 2rem;
  margin-bottom: 1rem;
}
:deep(.prose ul) {
  list-style-type: disc;
  margin-top: 1rem;
  margin-bottom: 1rem;
  padding-left: 1.5rem;
}
:deep(.prose li) {
  margin-bottom: 0.5rem;
}
:deep(.prose table) {
  margin-top: 1.5rem;
  margin-bottom: 1.5rem;
}
:deep(.prose td), :deep(.prose th) {
  padding: 0.75rem;
}
</style>
