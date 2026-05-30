<template>
  <section class="py-24 bg-slate-950 relative overflow-hidden font-sans min-h-screen flex items-center">
    <!-- Hiệu ứng radial-gradient nền siêu ảo diệu -->
    <div class="pointer-events-none absolute inset-0" style="background-image: radial-gradient(600px 300px at 15% 20%, rgba(59, 130, 246, 0.25), transparent), radial-gradient(500px 300px at 90% 80%, rgba(14, 165, 233, 0.2), transparent);"></div>

    <div class="relative mx-auto max-w-6xl px-4 md:px-6 z-10 w-full">
      <!-- Tiêu đề -->
      <div class="mb-14 mx-auto max-w-2xl text-center">
        <h2 class="text-3xl font-extrabold tracking-tight sm:text-4xl text-white mb-3">Tại sao chọn chúng tôi?</h2>
        <p class="text-lg text-slate-300 mt-3">Được 2.368 khách hàng tin tưởng</p>
      </div>

      <!-- Lưới 6 thẻ -->
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        
        <!-- Card 1 -->
        <!-- Đã thêm dark:border-white/20 để viền sáng hơn khi ở chế độ Dark Mode -->
        <div class="group relative overflow-hidden rounded-2xl border border-white/10 dark:border-white/20 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-white/30 hover:bg-white/10">
          <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 group-hover:opacity-100" style="background-image: linear-gradient(rgba(96, 165, 250, 0.08) 1px, transparent 1px); background-size: 100% 8px; mask-image: linear-gradient(to bottom, transparent, black 30%, black 70%, transparent);"></div>
          
          <div class="relative mx-auto mb-5 h-16 w-16 flex items-center justify-center rounded-full border border-white/10 dark:border-white/20 bg-[#0F172A] overflow-hidden shadow-[0_0_15px_rgba(56,189,248,0.1)] group-hover:shadow-[0_0_20px_rgba(56,189,248,0.2)] transition-all duration-300">
            <div class="absolute top-1/2 -translate-y-1/2 left-0 w-12 h-12 bg-sky-500/50 rounded-full blur-[10px] transition-transform duration-500 -translate-x-6 group-hover:translate-x-10"></div>
            <svg class="relative z-10 w-7 h-7 text-sky-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
          </div>
          <h3 class="relative mb-2 text-base font-bold text-white">Nhanh nhất thị trường</h3>
          <p class="relative text-sm leading-relaxed text-slate-300">15 phút, không phải 1 - 7 ngày như tự làm</p>
        </div>

        <!-- Card 2 -->
        <div class="group relative overflow-hidden rounded-2xl border border-white/10 dark:border-white/20 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-white/30 hover:bg-white/10">
          <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 group-hover:opacity-100" style="background-image: linear-gradient(rgba(96, 165, 250, 0.08) 1px, transparent 1px); background-size: 100% 8px; mask-image: linear-gradient(to bottom, transparent, black 30%, black 70%, transparent);"></div>
          
          <div class="relative mx-auto mb-5 h-16 w-16 flex items-center justify-center rounded-full border border-white/10 dark:border-white/20 bg-[#0F172A] overflow-hidden shadow-[0_0_15px_rgba(56,189,248,0.1)] group-hover:shadow-[0_0_20px_rgba(56,189,248,0.2)] transition-all duration-300">
            <div class="absolute top-1/2 -translate-y-1/2 left-0 w-12 h-12 bg-sky-500/50 rounded-full blur-[10px] transition-transform duration-500 -translate-x-6 group-hover:translate-x-10"></div>
            <svg class="relative z-10 w-7 h-7 text-sky-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
          </div>
          <h3 class="relative mb-2 text-base font-bold text-white">Tỉ lệ 100% thành công</h3>
          <p class="relative text-sm leading-relaxed text-slate-300">Làm xong tích xanh mới phải thanh toán,<br>không mất tiền oan</p>
        </div>

        <!-- Card 3 -->
        <div class="group relative overflow-hidden rounded-2xl border border-white/10 dark:border-white/20 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-white/30 hover:bg-white/10">
          <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 group-hover:opacity-100" style="background-image: linear-gradient(rgba(96, 165, 250, 0.08) 1px, transparent 1px); background-size: 100% 8px; mask-image: linear-gradient(to bottom, transparent, black 30%, black 70%, transparent);"></div>
          
          <div class="relative mx-auto mb-5 h-16 w-16 flex items-center justify-center rounded-full border border-white/10 dark:border-white/20 bg-[#0F172A] overflow-hidden shadow-[0_0_15px_rgba(56,189,248,0.1)] group-hover:shadow-[0_0_20px_rgba(56,189,248,0.2)] transition-all duration-300">
            <div class="absolute top-1/2 -translate-y-1/2 left-0 w-12 h-12 bg-sky-500/50 rounded-full blur-[10px] transition-transform duration-500 -translate-x-6 group-hover:translate-x-10"></div>
            <svg class="relative z-10 w-7 h-7 text-sky-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><path d="M4.93 4.93l4.24 4.24M14.83 14.83l4.24 4.24M14.83 9.17l4.24-4.24M4.93 19.07l4.24-4.24"/></svg>
          </div>
          <h3 class="relative mb-2 text-base font-bold text-white">Hỗ trợ trọn đời</h3>
          <p class="relative text-sm leading-relaxed text-slate-300">Gặp sự cố gì chúng tôi xử lý miễn phí</p>
        </div>

        <!-- Card 4 -->
        <div class="group relative overflow-hidden rounded-2xl border border-white/10 dark:border-white/20 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-white/30 hover:bg-white/10">
          <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 group-hover:opacity-100" style="background-image: linear-gradient(rgba(96, 165, 250, 0.08) 1px, transparent 1px); background-size: 100% 8px; mask-image: linear-gradient(to bottom, transparent, black 30%, black 70%, transparent);"></div>
          
          <div class="relative mx-auto mb-5 h-16 w-16 flex items-center justify-center rounded-full border border-white/10 dark:border-white/20 bg-[#0F172A] overflow-hidden shadow-[0_0_15px_rgba(56,189,248,0.1)] group-hover:shadow-[0_0_20px_rgba(56,189,248,0.2)] transition-all duration-300">
            <div class="absolute top-1/2 -translate-y-1/2 left-0 w-12 h-12 bg-sky-500/50 rounded-full blur-[10px] transition-transform duration-500 -translate-x-6 group-hover:translate-x-10"></div>
            <svg class="relative z-10 w-7 h-7 text-sky-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="10" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          </div>
          <h3 class="relative mb-2 text-base font-bold text-white">An toàn 100%</h3>
          <p class="relative text-sm leading-relaxed text-slate-300">Chỉ dùng phương pháp chính thức của Meta</p>
        </div>

        <!-- Card 5 -->
        <div class="group relative overflow-hidden rounded-2xl border border-white/10 dark:border-white/20 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-white/30 hover:bg-white/10">
          <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 group-hover:opacity-100" style="background-image: linear-gradient(rgba(96, 165, 250, 0.08) 1px, transparent 1px); background-size: 100% 8px; mask-image: linear-gradient(to bottom, transparent, black 30%, black 70%, transparent);"></div>
          
          <div class="relative mx-auto mb-5 h-16 w-16 flex items-center justify-center rounded-full border border-white/10 dark:border-white/20 bg-[#0F172A] overflow-hidden shadow-[0_0_15px_rgba(56,189,248,0.1)] group-hover:shadow-[0_0_20px_rgba(56,189,248,0.2)] transition-all duration-300">
            <div class="absolute top-1/2 -translate-y-1/2 left-0 w-12 h-12 bg-sky-500/50 rounded-full blur-[10px] transition-transform duration-500 -translate-x-6 group-hover:translate-x-10"></div>
            <svg class="relative z-10 w-7 h-7 text-sky-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M3 18v-6a9 9 0 0118 0v6"/><path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/></svg>
          </div>
          <h3 class="relative mb-2 text-base font-bold text-white">Tư vấn 24/7</h3>
          <p class="relative text-sm leading-relaxed text-slate-300">Luôn có nhân viên trực hotline và Zalo</p>
        </div>

        <!-- Card 6 -->
        <div class="group relative overflow-hidden rounded-2xl border border-white/10 dark:border-white/20 bg-white/5 p-8 text-center backdrop-blur-sm transition-all duration-300 hover:-translate-y-1 hover:border-white/30 hover:bg-white/10">
          <div class="pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-500 group-hover:opacity-100" style="background-image: linear-gradient(rgba(96, 165, 250, 0.08) 1px, transparent 1px); background-size: 100% 8px; mask-image: linear-gradient(to bottom, transparent, black 30%, black 70%, transparent);"></div>
          
          <div class="relative mx-auto mb-5 h-16 w-16 flex items-center justify-center rounded-full border border-white/10 dark:border-white/20 bg-[#0F172A] overflow-hidden shadow-[0_0_15px_rgba(56,189,248,0.1)] group-hover:shadow-[0_0_20px_rgba(56,189,248,0.2)] transition-all duration-300">
            <div class="absolute top-1/2 -translate-y-1/2 left-0 w-12 h-12 bg-sky-500/50 rounded-full blur-[10px] transition-transform duration-500 -translate-x-6 group-hover:translate-x-10"></div>
            <svg class="relative z-10 w-7 h-7 text-sky-400 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
          </div>
          <h3 class="relative mb-2 text-base font-bold text-white">Uy tín #1 Việt Nam</h3>
          <p class="relative text-sm leading-relaxed text-slate-300">Được review 5 sao bởi hàng nghìn khách hàng</p>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
</script>
