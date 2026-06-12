<script setup>
const emit = defineEmits(['open-register', 'open-page', 'select-service']);
</script>

<template>
  <div>
    <section class="relative py-16 overflow-hidden bg-orange-500 dark:bg-[#060b13] dark:border-t dark:border-white/5 text-white transition-colors duration-300">
      <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] dark:opacity-5 [background-size:16px_16px]"></div>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-4 text-white dark:text-white">
          Sẵn Sàng Nâng Tầm Thương Hiệu Với Tích Xanh?
        </h2>
        <p class="max-w-2xl mx-auto text-lg text-orange-50 dark:text-slate-400 mb-8">
          Đừng bỏ lỡ cơ hội khẳng định uy tín, tăng tỷ lệ chuyển đổi và bảo vệ tài khoản toàn diện trên mạng xã hội.
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
          
          <button 
            @click="emit('open-register')" 
            class="w-full sm:w-auto px-8 py-4 bg-white dark:bg-orange-500 text-orange-600 dark:text-white font-bold rounded-xl shadow-lg hover:bg-orange-50 dark:hover:bg-orange-600 transition transform hover:-translate-y-0.5 text-center"
          >
            Đăng Ký Tư Vấn Ngay
          </button>
          
          <a href="tel:0968825068" class="w-full sm:w-auto px-8 py-4 bg-transparent border-2 border-white/50 dark:border-white/20 text-white font-bold rounded-xl hover:bg-white/10 transition text-center flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            Hotline: 0968.825.068
          </a>
        </div>
      </div>
    </section>

    <footer class="bg-slate-50 dark:bg-black text-slate-650 dark:text-gray-300 pt-16 pb-8 border-t border-slate-200 dark:border-gray-900 transition-colors duration-300">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-10 mb-12">
        
        <div class="space-y-4">
          <div class="flex items-center gap-2">
            <a href="https://dangkytichxanh.vn" target="_blank" rel="noopener noreferrer" class="text-2xl font-black tracking-wider text-slate-900 dark:text-white hover:text-orange-500 dark:hover:text-orange-400 transition-colors">DANGKYTICHXANH<span class="text-orange-500">.VN</span></a>
          </div>
          <p class="text-sm text-slate-500 dark:text-gray-400 leading-relaxed">
            Đơn vị cung cấp giải pháp tối ưu, hỗ trợ lên tích xanh chính chủ an toàn, nhanh chóng và bảo mật cao cho Cá nhân & Doanh nghiệp.
          </p>
          <div class="pt-2">
            <a href="#" target="_blank" rel="noopener noreferrer" class="inline-block">
              <img src="https://images.dmca.com/badges/dmca_protected_sml_120m.png?ID=your-id" alt="DMCA.com Protection Status" class="h-6" />
            </a>
          </div>
        </div>

        <div>
          <h3 class="text-slate-900 dark:text-white font-bold text-lg mb-4 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-12 after:h-0.5 after:bg-orange-500 pb-2">Dịch Vụ Tích Xanh</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="#" @click.prevent="emit('select-service', 0)" class="hover:text-orange-500 dark:hover:text-orange-400 transition-colors">Tích xanh Facebook Fanpage</a></li>
            <li><a href="#" @click.prevent="emit('select-service', 1)" class="hover:text-orange-500 dark:hover:text-orange-400 transition-colors">Tích xanh Facebook Cá nhân</a></li>
            <li><a href="#" @click.prevent="emit('select-service', 2)" class="hover:text-orange-500 dark:hover:text-orange-400 transition-colors">Tích xanh Instagram</a></li>
            <li><a href="#" @click.prevent="emit('select-service', 3)" class="hover:text-orange-500 dark:hover:text-orange-400 transition-colors">Tích xanh TikTok</a></li>
          </ul>
        </div>

        <div>
          <h3 class="text-slate-900 dark:text-white font-bold text-lg mb-4 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-12 after:h-0.5 after:bg-orange-500 pb-2">Chính Sách & Quy Định</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="/gioi-thieu" @click.prevent="emit('open-page', 'gioi-thieu')" class="hover:text-orange-500 dark:hover:text-orange-400 transition-colors">Giới thiệu</a></li>
            <li><a href="/legal/terms" @click.prevent="emit('open-page', 'legal-terms')" class="hover:text-orange-500 dark:hover:text-orange-400 transition-colors">Điều khoản dịch vụ</a></li>
            <li><a href="/legal/privacy" @click.prevent="emit('open-page', 'legal-privacy')" class="hover:text-orange-500 dark:hover:text-orange-400 transition-colors">Chính sách bảo mật thông tin</a></li>
            <li><a href="/legal/dmca" @click.prevent="emit('open-page', 'legal-dmca')" class="hover:text-orange-500 dark:hover:text-orange-400 transition-colors">DMCA</a></li>
          </ul>
        </div>

        <div>
          <h3 class="text-slate-900 dark:text-white font-bold text-lg mb-4 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-12 after:h-0.5 after:bg-orange-500 pb-2">Thông Tin Liên Hệ</h3>
          <ul class="space-y-3 text-sm text-slate-600 dark:text-gray-400">
            <li class="flex items-start gap-2">
              <span class="text-slate-900 dark:text-white font-semibold">Hotline:</span>
              <a href="tel:0968825068" class="text-orange-600 dark:text-orange-400 hover:underline">0968.825.068</a>
            </li>
            <li class="flex items-start gap-2">
              <span class="text-slate-900 dark:text-white font-semibold">Email:</span>
              <a href="mailto:azmedia.com.vn@gmail.com" class="hover:text-slate-900 dark:hover:text-white transition-colors">azmedia.com.vn@gmail.com</a>
            </li>
            <li class="flex items-start gap-2">
              <span class="text-slate-900 dark:text-white font-semibold flex-shrink-0">Địa chỉ:</span>
              <span>84 Nguyễn Hữu Dật, Hòa Cường Bắc, Hải Châu, Đà Nẵng</span>
            </li>
          </ul>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-slate-200 dark:border-gray-900 text-center text-xs text-slate-500 dark:text-gray-500 flex flex-col sm:flex-row justify-between items-center gap-4">
        <p>© 2026 Dangkytichxanh.vn. All rights reserved. Covered & Optimized by AZ Media.</p>
        <p>Thương hiệu và logo thuộc bản quyền của các nền tảng MXH tương ứng.</p>
      </div>
    </footer>
  </div>
</template>