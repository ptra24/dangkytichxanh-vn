<script setup>
import { ref } from 'vue'
// 1. Import các component do Hưng làm
import DarkModeToggle from './components/DarkModeToggle.vue'
import Footer from './components/Footer.vue'

// 2. Định nghĩa các component giả định của các bạn khác (Mock Component)
// Khi nào các bạn giao file, Hưng chỉ cần thay thế các dòng div này bằng file thật
</script>

<template>
  <div class="min-h-screen bg-white dark:bg-slate-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    
    <header class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur border-b border-gray-200 dark:border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <div class="text-xl font-black text-blue-600 dark:text-blue-400">DANGKYTICHXANH.VN</div>
        <div class="flex items-center gap-4">
          <nav class="hidden md:flex gap-6 text-sm font-medium">
            <a href="#services" class="hover:text-blue-500">Dịch vụ</a>
            <a href="#why-us" class="hover:text-blue-500">Tại sao chọn chúng tôi</a>
            <a href="#contact" class="hover:text-blue-500">Liên hệ</a>
          </nav>
          <DarkModeToggle />
        </div>
      </div>
    </header>

    <section class="py-20 text-center max-w-4xl mx-auto px-4">
      <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight mb-6 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
        Khẳng Định Uy Tín Với Tích Xanh Chính Chủ
      </h1>
      <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
        Hệ thống hỗ trợ lên tích xanh an toàn, tăng tương tác và bảo mật tuyệt đối cho tài khoản của bạn.
      </p>
      <div class="flex justify-center gap-4">
        <a href="#contact" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition">Đăng Ký Ngay</a>
        <button class="px-6 py-3 bg-gray-100 dark:bg-gray-800 font-semibold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition">Xem Bảng Giá</button>
      </div>
    </section>

    <section id="services" class="py-16 bg-gray-50 dark:bg-slate-900/50">
      <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-12">Dịch Vụ Của Chúng Tôi</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div v-for="item in ['Facebook Fanpage', 'Facebook Cá Nhân', 'Instagram', 'TikTok']" :key="item" class="p-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center mx-auto mb-4 font-bold">✓</div>
            <h3 class="font-bold text-lg mb-2">{{ item }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">Hỗ trợ lên tích xanh trọn gói, bảo hành vĩnh viễn.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="contact" class="py-20 max-w-md mx-auto px-4">
      <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-xl border border-gray-150 dark:border-gray-800">
        <h2 class="text-2xl font-bold text-center mb-6">Tư Vấn Miễn Phí</h2>
        <form @submit.prevent class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Họ và tên</label>
            <input type="text" placeholder="Nguyễn Văn A" class="w-full px-4 py-2.5 rounded-xl border dark:border-gray-750 bg-transparent focus:ring-2 focus:ring-blue-500 outline-none" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Số điện thoại</label>
            <input type="tel" placeholder="0968xxxxxx" class="w-full px-4 py-2.5 rounded-xl border dark:border-gray-750 bg-transparent focus:ring-2 focus:ring-blue-500 outline-none" />
          </div>
          <button class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow-lg shadow-blue-500/20">Gửi Yêu Cầu</button>
        </form>
      </div>
    </section>

    <Footer />

  </div>
</template>