<script setup>
const stats = [
  {
    value: '2.368',
    label: 'Trang đã cấp tích xanh',
    iconColor: 'text-blue-500'
  },
  {
    value: "15'",
    label: 'Thời gian trung bình',
    iconColor: 'text-cyan-500'
  },
  {
    value: '99%',
    label: 'Tỉ lệ thành công',
    iconColor: 'text-emerald-500'
  },
  {
    value: '1.5tr',
    label: 'Trọn gói',
    iconColor: 'text-amber-500'
  }
];
</script>

<template>
  <div class="border-y border-slate-200 bg-slate-50 py-8 backdrop-blur-sm dark:border-white/5 dark:bg-[#090f1d]/70">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-2 gap-y-8 md:grid-cols-4 md:gap-y-0 text-center">
        <div 
          v-for="(stat, idx) in stats" 
          :key="idx" 
          class="flex flex-col items-center justify-center relative px-4"
          :class="{ 'md:border-r md:border-slate-200 dark:md:border-white/5': idx < 3 }"
        >
          <!-- Accent dot -->
          <div class="h-1.5 w-1.5 rounded-full bg-blue-600 mb-2 opacity-50"></div>
          
          <!-- Large Value -->
          <span class="font-sans text-3xl font-extrabold tracking-tight text-slate-950 md:text-4xl block leading-none dark:text-white">
            {{ stat.value }}
          </span>
          
          <!-- Label -->
          <span class="text-xs sm:text-sm text-slate-600 font-medium tracking-wide mt-2 block dark:text-slate-400">
            {{ stat.label }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
