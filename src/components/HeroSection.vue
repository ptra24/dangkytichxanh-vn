<script setup>
import { Phone } from '@lucide/vue';

const props = defineProps({
  service: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['open-register']);
</script>

<template>
  <section class="relative overflow-hidden bg-white py-20 lg:py-28 dark:bg-[#060b13]">
    <!-- Premium background glowing gradients -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-white via-slate-50 to-blue-50/60 dark:bg-[#060b13] dark:bg-none">
      <div 
        class="absolute -top-40 left-1/4 h-[500px] w-[500px] rounded-full blur-[120px] opacity-10 transition-colors duration-500 dark:opacity-0"
        :class="{
          'bg-blue-600': service.id.startsWith('facebook'),
          'bg-purple-600': service.id === 'instagram',
          'bg-cyan-600': service.id === 'tiktok',
          'bg-emerald-600': service.id === 'whatsapp'
        }"
      ></div>
      <div class="absolute right-10 bottom-10 h-[350px] w-[350px] rounded-full bg-blue-100 blur-[80px] opacity-70 dark:opacity-0"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-8">
        
        <!-- Left Content -->
        <div class="lg:col-span-7 space-y-6 text-left">
          
          <!-- Badge -->
          <div class="inline-flex items-center rounded-full border border-blue-500/30 bg-blue-500/5 px-5 py-2 transition-all duration-300 dark:bg-blue-500/10">
            <span class="text-sm font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400">
              {{ service.badgeLabel }}
            </span>
          </div>

          <!-- Main Dynamic Heading -->
          <h1 class="font-sans text-5xl font-extrabold tracking-tight text-slate-950 sm:text-6xl lg:text-[74px] leading-[1.08] dark:text-white">
            <span class="block">{{ service.title }}</span>
          </h1>

          <!-- Description -->
          <p class="max-w-3xl text-xl font-medium leading-relaxed text-slate-600 dark:text-slate-300 lg:text-2xl">
            {{ service.subtitle }}
          </p>

          <!-- Divider -->
          <div class="h-px w-full bg-gradient-to-r from-slate-200 to-transparent dark:from-white/10"></div>

          <!-- Checklist Grid -->
          <div class="grid grid-cols-1 gap-x-6 gap-y-3.5 sm:grid-cols-2">
            <div 
              v-for="(feature, idx) in service.features" 
              :key="idx" 
              class="flex items-start gap-3 group"
            >
              <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white transition-all duration-300">
                <svg 
                  class="h-4 w-4 text-white" 
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke="currentColor" 
                  stroke-width="3.5"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-base font-bold leading-snug text-slate-800 group-hover:text-blue-700 transition-colors duration-200 dark:text-white">
                {{ feature }}
              </span>
            </div>
          </div>

          <!-- Divider -->
          <div class="h-px w-full bg-gradient-to-r from-slate-200 to-transparent dark:from-white/10"></div>

          <!-- Action Buttons -->
          <div class="flex flex-wrap gap-4 pt-2">
            <button 
              @click="emit('open-register')" 
              class="relative inline-flex items-center justify-center rounded-xl bg-blue-600 px-9 py-4 text-lg font-extrabold text-white shadow-lg shadow-blue-500/25 hover:bg-blue-500 hover:shadow-blue-500/40 transition-all duration-300 transform active:scale-95 group overflow-hidden"
            >
              <span class="relative z-10">Đăng Ký Ngay</span>
              <div class="absolute inset-0 -translate-x-full group-hover:translate-x-0 bg-gradient-to-r from-blue-500 to-indigo-600 transition-transform duration-300"></div>
            </button>

            <a 
              href="tel:0968825068" 
              class="inline-flex items-center justify-center gap-2.5 rounded-xl border border-slate-200 bg-white px-8 py-4 text-lg font-bold text-slate-900 shadow-sm hover:border-blue-200 hover:bg-blue-50 transition-all duration-300 transform active:scale-95 dark:border-white/10 dark:bg-[#111b2f] dark:text-white dark:hover:bg-[#16233b] dark:hover:border-white/20"
            >
              <Phone class="h-5 w-5 text-blue-600 dark:text-slate-400" />
              0968.825.068
            </a>
          </div>

        </div>

        <!-- Right Mockup Preview Card -->
        <div class="lg:col-span-5 flex justify-center lg:justify-end">
          <div 
            class="relative w-full max-w-[480px] rounded-2xl border bg-white p-9 shadow-xl shadow-slate-900/10 transition-all duration-500 ease-out dark:border-white/10 dark:bg-[#111b2f] dark:shadow-none"
            :class="service.mockup?.glowClass ?? ''"
          >
            <!-- Glowing overlay effect behind the card -->
            <div class="absolute inset-0 -z-10 rounded-2xl opacity-10 blur-xl bg-gradient-to-tr from-blue-600 to-indigo-500"></div>

            <!-- Header Info -->
            <div class="flex flex-col items-center text-center pb-6">
              
              <!-- Avatar with verification badge -->
              <div class="relative mb-4">
                <div 
                  class="flex h-24 w-24 items-center justify-center rounded-full font-sans text-4xl font-black tracking-wider text-white shadow-inner transition-transform duration-500"
                  :class="{
                    'bg-blue-600': service.id.startsWith('facebook'),
                    'bg-gradient-to-tr from-purple-600 via-pink-500 to-yellow-500': service.id === 'instagram',
                    'bg-slate-950 border border-slate-800': service.id === 'tiktok',
                    'bg-emerald-600': service.id === 'whatsapp'
                  }"
                >
                  {{ service.mockup.avatarLetters }}
                </div>
                
                <!-- Verification Check Badge -->
                <div class="absolute bottom-0 right-0 rounded-full border-2 border-white bg-white p-0.5 dark:border-[#090f1d]">
                  <div class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white">
                    <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Username & Badge -->
              <div class="flex items-center gap-1.5">
                <h3 class="font-sans text-xl font-extrabold text-slate-950 tracking-tight dark:text-white">
                  {{ service.mockup.username }}
                </h3>
                <!-- Verified badge — fixed size h-5 w-5 (h-4.5 không hợp lệ) -->
                <div class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-blue-600">
                  <svg class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>

              <!-- Subtitle / Meta Verified state -->
              <p class="mt-1 text-base text-slate-500 dark:text-slate-400">
                {{ service.mockup.verifiedType }}
              </p>

            </div>

            <!-- Divider -->
            <div class="h-px w-full bg-slate-200 dark:bg-white/5"></div>

            <!-- Platform Stats Grid -->
            <div class="grid grid-cols-3 gap-2 py-5 text-center">
              <div 
                v-for="(stat, idx) in service.mockup.stats" 
                :key="idx"
                class="flex flex-col items-center justify-center px-1"
                :class="{ 'border-r border-slate-200 dark:border-white/5': idx < 2 }"
              >
                <span class="block font-sans text-3xl font-black tracking-tight text-blue-600 dark:text-blue-400">
                  {{ stat.value }}
                </span>
                <span class="mt-2 block text-sm font-medium text-slate-500 dark:text-slate-400">
                  {{ stat.label }}
                </span>
              </div>
            </div>

            <!-- Decorative mini line -->
            <div class="flex justify-center">
              <div class="h-1 w-12 rounded-full bg-slate-200 dark:bg-white/10"></div>
            </div>
            
          </div>
        </div>

      </div>
    </div>
  </section>
</template>