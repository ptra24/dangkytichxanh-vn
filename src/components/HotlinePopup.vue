<script setup>
import { ref, watch } from 'vue';
import { X, Zap, Phone, Check, Loader2 } from '@lucide/vue';
import { services } from '../data/services';

const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true
  },
  defaultServiceIndex: {
    type: Number,
    default: 0
  }
});

const emit = defineEmits(['close', 'submitted']);

// ── Form state ─────────────────────────────────────
const name = ref('');
const phone = ref('');
const serviceIndex = ref('');
const errors = ref({});
const isLoading = ref(false);
const isSubmitted = ref(false);

// Reset form mỗi lần mở modal
watch(() => props.isOpen, (val) => {
  if (val) {
    name.value = '';
    phone.value = '';
    serviceIndex.value = props.defaultServiceIndex !== '' ? props.defaultServiceIndex : '';
    errors.value = {};
    isLoading.value = false;
    isSubmitted.value = false;
  }
});

// ── Validation ─────────────────────────────────────
const validate = () => {
  const e = {};
  if (!name.value.trim()) e.name = 'Vui lòng nhập họ tên';
  if (!phone.value.trim()) {
    e.phone = 'Vui lòng nhập số điện thoại';
  } else if (!/^\d{9,11}$/.test(phone.value.replace(/\s+/g, ''))) {
    e.phone = 'Số điện thoại không hợp lệ (9–11 chữ số)';
  }
  if (serviceIndex.value === '') e.service = 'Vui lòng chọn dịch vụ';
  errors.value = e;
  return Object.keys(e).length === 0;
};

// ── Submit ─────────────────────────────────────────
const handleSubmit = async () => {
  if (!validate()) return;
  isLoading.value = true;
  try {
    const selectedService = services[serviceIndex.value];
    const payload = {
      name: name.value,
      phone: phone.value,
      service: selectedService ? selectedService.name : ''
    };
    const res = await fetch('/api/contact', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify(payload)
    });
    const result = await res.json();
    if (res.ok && result.success) {
      isSubmitted.value = true;
      emit('submitted', payload);
    } else {
      alert(result.message || 'Có lỗi xảy ra, vui lòng thử lại.');
    }
  } catch (err) {
    console.error(err);
    alert('Không thể kết nối máy chủ. Vui lòng thử lại sau.');
  } finally {
    isLoading.value = false;
  }
};

const close = () => emit('close');
</script>

<template>
  <Teleport to="body">
    <Transition name="backdrop">
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4
               bg-black/60 backdrop-blur-sm"
        @click.self="close"
      >
        <Transition name="modal">
          <div
            v-if="isOpen"
            class="relative w-full max-w-[480px] overflow-hidden rounded-2xl shadow-2xl
                   bg-white dark:bg-[#0f172a]
                   border border-slate-200 dark:border-white/10"
          >

            <!-- ── Header xanh ────────────────────── -->
            <div class="relative bg-blue-600 px-6 pt-6 pb-7">
              <!-- Close btn -->
              <button
                @click="close"
                class="absolute top-4 right-4 flex h-8 w-8 items-center justify-center
                       rounded-full bg-white/15 text-white hover:bg-white/25
                       transition-colors"
              >
                <X class="h-4 w-4" />
              </button>

              <!-- Icon + label -->
              <div class="flex items-center gap-3 mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15">
                  <Zap class="h-5 w-5 text-white fill-white/30" />
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-white/70">
                  Đăng ký nhanh
                </span>
              </div>

              <h2 class="text-2xl font-black leading-snug text-white">
                Tư vấn miễn phí —<br>hoàn thành trong 15 phút
              </h2>
            </div>

            <!-- ── Body ──────────────────────────── -->
            <div class="px-6 py-5">

              <!-- ── Success state ─────────────────── -->
              <div v-if="isSubmitted" class="py-6 text-center space-y-4">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10">
                  <Check class="h-8 w-8 text-emerald-500 stroke-[3]" />
                </div>
                <div>
                  <p class="text-lg font-black text-slate-900 dark:text-white">Đã nhận thông tin!</p>
                  <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Chuyên viên sẽ gọi lại cho
                    <strong class="text-slate-800 dark:text-white">{{ name }}</strong>
                    qua số <strong class="text-slate-800 dark:text-white">{{ phone }}</strong>
                    trong 15 phút.
                  </p>
                </div>
                <button
                  @click="close"
                  class="rounded-xl bg-slate-100 dark:bg-slate-800 px-6 py-2.5
                         text-sm font-bold text-slate-700 dark:text-slate-300
                         hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors"
                >
                  Đóng
                </button>
              </div>

              <!-- ── Form state ────────────────────── -->
              <template v-else>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-5 leading-relaxed">
                  Để lại thông tin, chúng tôi gọi lại ngay trong 15 phút —
                  không spam, không ép mua.
                </p>

                <form @submit.prevent="handleSubmit" class="space-y-4" novalidate>

                  <!-- Họ tên -->
                  <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">
                      Họ tên <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="name"
                      type="text"
                      placeholder="Nguyễn Văn A"
                      class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                             placeholder-slate-400
                             bg-white text-slate-900 border-slate-300
                             focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
                             dark:bg-slate-900/60 dark:text-white dark:border-white/10
                             dark:focus:border-blue-500"
                      :class="errors.name ? 'border-red-400 focus:border-red-400 focus:ring-red-400/20' : ''"
                    />
                    <p v-if="errors.name" class="mt-1 text-xs text-red-500 font-medium">{{ errors.name }}</p>
                  </div>

                  <!-- Số điện thoại -->
                  <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">
                      Số điện thoại <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="phone"
                      type="tel"
                      placeholder="0912345678"
                      class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                             placeholder-slate-400
                             bg-white text-slate-900 border-slate-300
                             focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
                             dark:bg-slate-900/60 dark:text-white dark:border-white/10
                             dark:focus:border-blue-500"
                      :class="errors.phone ? 'border-red-400 focus:border-red-400 focus:ring-red-400/20' : ''"
                    />
                    <p v-if="errors.phone" class="mt-1 text-xs text-red-500 font-medium">{{ errors.phone }}</p>
                  </div>

                  <!-- Dịch vụ -->
                  <div>
                    <label class="mb-1.5 block text-sm font-bold text-slate-700 dark:text-slate-300">
                      Dịch vụ quan tâm
                    </label>
                    <select
                      v-model="serviceIndex"
                      class="w-full rounded-xl border px-4 py-3 text-sm outline-none transition-all
                             bg-white text-slate-900 border-slate-300
                             focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20
                             dark:bg-slate-900/60 dark:text-white dark:border-white/10
                             dark:focus:border-blue-500"
                      :class="errors.service ? 'border-red-400' : ''"
                    >
                      <option value="" disabled>-- Chọn dịch vụ --</option>
                      <option
                        v-for="(svc, idx) in services"
                        :key="svc.id"
                        :value="idx"
                      >{{ svc.name }}</option>
                    </select>
                    <p v-if="errors.service" class="mt-1 text-xs text-red-500 font-medium">{{ errors.service }}</p>
                  </div>

                  <!-- Divider -->
                  <div class="relative flex items-center gap-3 py-1">
                    <div class="h-px flex-1 bg-slate-200 dark:bg-white/10"></div>
                  </div>

                  <!-- Submit -->
                  <button
                    type="submit"
                    :disabled="isLoading"
                    class="flex w-full items-center justify-center gap-2 rounded-xl
                           bg-blue-600 hover:bg-blue-500 active:scale-95
                           px-4 py-3.5 text-sm font-extrabold text-white
                           shadow-lg shadow-blue-600/25
                           transition-all duration-200
                           disabled:opacity-60 disabled:cursor-not-allowed"
                  >
                    <Loader2 v-if="isLoading" class="h-4 w-4 animate-spin" />
                    <Zap v-else class="h-4 w-4 fill-white/20" />
                    {{ isLoading ? 'Đang gửi...' : 'Gửi ngay — gọi lại trong 15 phút' }}
                  </button>

                  <!-- Hoặc gọi thẳng -->
                  <div class="flex items-center gap-3">
                    <div class="h-px flex-1 bg-slate-200 dark:bg-white/10"></div>
                    <span class="text-xs text-slate-400">hoặc</span>
                    <div class="h-px flex-1 bg-slate-200 dark:bg-white/10"></div>
                  </div>

                  <a
                    href="tel:0968825068"
                    class="flex w-full items-center justify-center gap-2
                           rounded-xl border border-slate-200 dark:border-white/10
                           bg-transparent hover:bg-slate-50 dark:hover:bg-white/5
                           px-4 py-3 text-sm font-bold
                           text-blue-600 dark:text-blue-400
                           transition-colors"
                  >
                    <Phone class="h-4 w-4" />
                    Gọi ngay 0968.825.068
                  </a>

                </form>
              </template>

            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
/* Backdrop fade */
.backdrop-enter-active,
.backdrop-leave-active {
  transition: opacity 0.2s ease;
}
.backdrop-enter-from,
.backdrop-leave-to {
  opacity: 0;
}

/* Modal slide + scale */
.modal-enter-active {
  transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.modal-leave-active {
  transition: all 0.18s ease-in;
}
.modal-enter-from {
  opacity: 0;
  transform: scale(0.92) translateY(12px);
}
.modal-leave-to {
  opacity: 0;
  transform: scale(0.95) translateY(8px);
}
</style>

