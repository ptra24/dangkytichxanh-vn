<script setup>
defineEmits(['open-register']);
</script>

<template>
  <div>
    <section class="bg-blue-600 py-16 text-white">
      <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
        <h2 class="text-3xl font-black tracking-tight sm:text-5xl">Sẵn sàng có tích xanh trong 15 phút?</h2>
        <p class="mx-auto mt-5 max-w-3xl text-lg font-semibold leading-8 text-blue-100">
          Hotline 0968.825.068 - tư vấn miễn phí, kiểm tra hồ sơ trước khi triển khai, làm xong mới thanh toán.
        </p>
        <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
          <button class="w-full rounded-xl bg-white px-8 py-4 text-base font-black text-blue-600 shadow-lg transition hover:bg-blue-50 sm:w-auto" @click="$emit('open-register')">
            Đăng ký ngay
          </button>
          <a href="tel:0968825068" class="w-full rounded-xl border border-white/70 px-8 py-4 text-base font-black text-white transition hover:bg-white/10 sm:w-auto">
            Gọi 0968.825.068
          </a>
        </div>
      </div>
    </section>

    <footer class="border-t border-slate-200 bg-white py-16 text-slate-600 dark:border-white/10 dark:bg-[#07111f] dark:text-slate-300">
      <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div>
          <div class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-xl font-black text-white">✓</span>
            <span class="text-2xl font-black text-slate-950 dark:text-white">Đăng Ký Tích Xanh <span class="text-sm text-slate-500">.vn</span></span>
          </div>
          <p class="mt-6 max-w-xl text-base font-medium leading-8 text-slate-600 dark:text-slate-400">
            Dịch vụ tư vấn và hỗ trợ đăng ký tích xanh Facebook, Fanpage, Instagram, TikTok, WhatsApp. Hỗ trợ bởi AZ Media.
          </p>
        </div>

        <div>
          <h3 class="text-lg font-black text-slate-950 dark:text-white">Dịch vụ</h3>
          <ul class="mt-5 space-y-3 text-base font-semibold text-slate-600 dark:text-slate-400">
            <li>Tích xanh Fanpage</li>
            <li>Tích xanh Facebook cá nhân</li>
            <li>Tích xanh Instagram</li>
            <li>Tích xanh TikTok</li>
            <li>Tích xanh WhatsApp</li>
          </ul>
        </div>

        <div>
          <h3 class="text-lg font-black text-slate-950 dark:text-white">Liên hệ</h3>
          <ul class="mt-5 space-y-4 text-base font-semibold leading-7 text-slate-600 dark:text-slate-400">
            <li>Văn phòng giao dịch: 84 Nguyễn Hữu Dật, Hòa Cường Bắc, Hải Châu, Đà Nẵng</li>
            <li>Số điện thoại: <a href="tel:0968825068" class="text-blue-600 hover:underline dark:text-blue-400">0968.825.068 (Mr. Quang)</a></li>
            <li>Email: <a href="mailto:azmedia.com.vn@gmail.com" class="hover:text-blue-600 dark:hover:text-white">azmedia.com.vn@gmail.com</a></li>
            <li>Facebook: <a href="https://fb.com/congtyazmedia" target="_blank" rel="noopener noreferrer" class="hover:text-blue-600 dark:hover:text-white">fb.com/congtyazmedia</a></li>
          </ul>
        </div>
      </div>

      <div class="mx-auto mt-12 flex max-w-7xl flex-col gap-4 border-t border-slate-200 px-4 pt-8 text-sm font-medium text-slate-500 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8 dark:border-white/10 dark:text-slate-500">
        <p>© 2026 Đăng Ký Tích Xanh - Dịch vụ đăng ký tích xanh uy tín.</p>
        <div class="flex flex-wrap gap-5">
          <span>Giới thiệu</span>
          <span>Điều khoản</span>
          <span>Bảo mật</span>
          <span>DMCA</span>
        </div>
      </div>
    </footer>
  </div>
</template>
