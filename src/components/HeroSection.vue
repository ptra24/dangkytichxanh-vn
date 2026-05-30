<script setup>
import { computed } from 'vue';
import { Phone } from '@lucide/vue';

const props = defineProps({
  service: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['open-register']);

// Determine checkmark color based on service
const checkIconColor = computed(() => {
  if (props.service.id === 'whatsapp') return 'text-emerald-400';
  if (props.service.id === 'tiktok') return 'text-cyan-400';
  if (props.service.id === 'instagram') return 'text-pink-500';
  return 'text-blue-500';
});

const checkBgColor = computed(() => {
  if (props.service.id === 'whatsapp') return 'bg-emerald-500/15';
  if (props.service.id === 'tiktok') return 'bg-cyan-500/15';
  if (props.service.id === 'instagram') return 'bg-pink-500/15';
  return 'bg-blue-500/15';
});
</script>

<template>
  <section class="relative overflow-hidden py-16 lg:py-24">
    <!-- Premium background glowing gradients -->
    <div class="absolute inset-0 -z-10 bg-[#060b13]">
      <div 
        class="absolute -top-40 left-1/4 h-[500px] w-[500px] rounded-full blur-[120px] opacity-25 mix-blend-screen transition-colors duration-500"
        :class="{
          'bg-blue-600': service.id.startsWith('facebook'),
          'bg-purple-600': service.id === 'instagram',
          'bg-cyan-600': service.id === 'tiktok',
          'bg-emerald-600': service.id === 'whatsapp'
        }"
      ></div>
      <div class="absolute right-10 bottom-10 h-[350px] w-[350px] rounded-full bg-slate-900 blur-[80px] opacity-50"></div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-8">
        
        <!-- Left Content -->
        <div class="lg:col-span-7 space-y-6 text-left">
          
          <!-- Badge -->
          <div class="inline-flex items-center rounded-full border border-blue-500/30 bg-blue-500/5 px-4 py-1.5 transition-all duration-300">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-400">
              {{ service.badgeLabel }}
            </span>
          </div>

          <!-- Main Dynamic Heading -->
          <h1 class="font-sans text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl leading-[1.15]">
            <span class="block">{{ service.title }}</span>
          </h1>

          <!-- Description -->
          <p class="text-base text-slate-300 sm:text-lg max-w-2xl leading-relaxed">
            {{ service.subtitle }}
          </p>

          <!-- Divider -->
          <div class="h-px w-full bg-gradient-to-r from-white/10 to-transparent"></div>

          <!-- Checklist Grid -->
          <div class="grid grid-cols-1 gap-x-6 gap-y-3.5 sm:grid-cols-2">
            <div 
              v-for="(feature, idx) in service.features" 
              :key="idx" 
              class="flex items-start gap-3 group"
            >
              <div 
                class="flex h-5.5 w-5.5 shrink-0 items-center justify-center rounded-full mt-0.5 transition-all duration-300"
                :class="checkBgColor"
              >
                <!-- Checked Circle Icon -->
                <svg 
                  class="h-3.5 w-3.5" 
                  :class="checkIconColor" 
                  fill="none" 
                  viewBox="0 0 24 24" 
                  stroke="currentColor" 
                  stroke-width="3.5"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <span class="text-sm font-medium text-slate-300 group-hover:text-white transition-colors duration-200">
                {{ feature }}
              </span>
            </div>
          </div>

          <!-- Divider -->
          <div class="h-px w-full bg-gradient-to-r from-white/10 to-transparent"></div>

          <!-- Action Buttons -->
          <div class="flex flex-wrap gap-4 pt-2">
            <button 
              @click="emit('open-register')" 
              class="relative inline-flex items-center justify-center rounded-xl bg-blue-600 px-8 py-4 text-base font-extrabold text-white shadow-lg shadow-blue-500/25 hover:bg-blue-500 hover:shadow-blue-500/40 transition-all duration-300 transform active:scale-98 group overflow-hidden"
            >
              <span class="relative z-10">Đăng Ký Ngay</span>
              <div class="absolute inset-0 -translate-x-full group-hover:translate-x-0 bg-gradient-to-r from-blue-500 to-indigo-600 transition-transform duration-300 -z-10"></div>
            </button>

            <a 
              href="tel:0921169999" 
              class="inline-flex items-center justify-center gap-2.5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 px-7 py-4 text-base font-bold text-white transition-all duration-300 transform active:scale-98"
            >
              <Phone class="h-5 w-5 text-slate-400 fill-slate-400/10" />
              092.116.9999
            </a>
          </div>

        </div>

        <!-- Right Mockup Preview Card -->
        <div class="lg:col-span-5 flex justify-center lg:justify-end">
          <div 
            class="relative w-full max-w-[380px] rounded-2xl p-6 border transition-all duration-500 ease-out bg-[#0f172a]/60 backdrop-blur-xl"
            :class="service.mockup.glowClass"
          >
            <!-- Glowing overlay effect behind the card -->
            <div class="absolute inset-0 -z-10 rounded-2xl opacity-10 blur-xl bg-gradient-to-tr from-blue-600 to-indigo-500"></div>

            <!-- Header Info -->
            <div class="flex flex-col items-center text-center pb-6">
              
              <!-- Avatar with verification badge -->
              <div class="relative mb-4">
                <div 
                  class="flex h-20 w-20 items-center justify-center rounded-full font-sans text-2xl font-black tracking-wider text-white shadow-inner transition-transform duration-500"
                  :class="{
                    'bg-blue-600': service.id.startsWith('facebook'),
                    'bg-gradient-to-tr from-purple-600 via-pink-500 to-yellow-500': service.id === 'instagram',
                    'bg-slate-950 border border-slate-800': service.id === 'tiktok',
                    'bg-emerald-600': service.id === 'whatsapp'
                  }"
                >
                  {{ service.mockup.avatarLetters }}
                </div>
                
                <!-- Verification Check Badge at the bottom right corner of avatar -->
                <div class="absolute bottom-0 right-0 rounded-full border-2 border-[#090f1d] bg-white p-0.5">
                  <div class="flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-white">
                    <!-- Standard Verification Icon SVG -->
                    <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Username & Badge -->
              <div class="flex items-center gap-1.5">
                <h3 class="font-sans text-lg font-bold text-white tracking-tight">
                  {{ service.mockup.username }}
                </h3>
                <!-- Custom small verified badge -->
                <div class="inline-flex h-4.5 w-4.5 items-center justify-center rounded-full bg-blue-600">
                  <svg class="h-2.5 w-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
              </div>

              <!-- Subtitle / Meta Verified state -->
              <p class="text-xs text-slate-400 mt-1">
                {{ service.mockup.verifiedType }}
              </p>

            </div>

            <!-- Divider -->
            <div class="h-px w-full bg-white/5"></div>

            <!-- Platform Stats Grid -->
            <div class="grid grid-cols-3 gap-2 py-5 text-center">
              <div 
                v-for="(stat, idx) in service.mockup.stats" 
                :key="idx"
                class="flex flex-col items-center justify-center px-1"
                :class="{ 'border-r border-white/5': idx < 2 }"
              >
                <span class="font-sans text-lg font-black tracking-tight text-white block">
                  {{ stat.value }}
                </span>
                <span class="text-[10px] text-slate-400 font-medium uppercase tracking-wider mt-1 block">
                  {{ stat.label }}
                </span>
              </div>
            </div>

            <!-- Decorative mini line -->
            <div class="flex justify-center">
              <div class="h-1 w-12 rounded-full bg-white/10"></div>
            </div>
            
          </div>
        </div>

      </div>
    </div>
  </section>
</template>
