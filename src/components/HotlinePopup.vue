<template>
  <div
    v-if="showPopup"
    class="fixed inset-0 bg-black/70 flex items-center justify-center z-[9999] p-4"
  >
    <div
      class="bg-[#07132B] text-white rounded-2xl w-full max-w-lg border border-blue-500 overflow-hidden"
    >
      <!-- Header -->
      <div class="flex items-center justify-between p-5 border-b border-slate-700">
        <h2 class="text-2xl font-bold">
          Đăng Ký Tư Vấn Nhanh
        </h2>

        <button
          @click="closePopup"
          class="text-gray-400 hover:text-white text-2xl"
        >
          ×
        </button>
      </div>

      <div class="p-6">
        <!-- Form -->
        <div class="space-y-3">
          <input
            type="text"
            placeholder="Họ và tên"
            class="w-full p-3 rounded-lg bg-slate-800 border border-slate-700"
          />

          <input
            type="text"
            placeholder="Số điện thoại"
            class="w-full p-3 rounded-lg bg-slate-800 border border-slate-700"
          />
        </div>

        <!-- Button -->
        <div class="grid grid-cols-2 gap-3 mt-5">
          <button
            class="bg-blue-600 hover:bg-blue-700 py-3 rounded-lg font-semibold"
          >
            Gửi ngay
          </button>

          <a
            href="tel:0968825068"
            class="bg-green-600 hover:bg-green-700 py-3 rounded-lg text-center font-semibold"
          >
            Gọi ngay
          </a>
        </div>

        <!-- Company Info -->
        <div class="mt-6 border-t border-slate-700 pt-5">
          <h3 class="font-bold text-lg mb-4">
            THÔNG TIN CÔNG TY
          </h3>

          <div class="space-y-3 text-sm text-slate-300">
            <p>
              <strong>Mã số doanh nghiệp:</strong>
              0314247738 – 002
            </p>

            <p>
              <strong>Địa chỉ ĐKKD:</strong>
              127 Lê Trọng Tấn, Phường An Khê, Đà Nẵng
            </p>

            <p>
              <strong>Văn phòng giao dịch:</strong>
              84 Nguyễn Hữu Dật, Hòa Cường Bắc, Hải Châu, Đà Nẵng
            </p>

            <p>
              <strong>Hotline:</strong>
              0968.825.068
            </p>

            <p>
              <strong>Email:</strong>
              azmedia.com.vn@gmail.com
            </p>

            <p>
              <strong>Facebook:</strong>
              fb.com/congtyazmedia
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";

const showPopup = ref(false);

onMounted(() => {
  const opened = sessionStorage.getItem("hotline_popup");

  if (!opened) {
    setTimeout(() => {
      showPopup.value = true;
      sessionStorage.setItem("hotline_popup", "1");
    }, 1000);
  }
});

const closePopup = () => {
  showPopup.value = false;
};
</script>