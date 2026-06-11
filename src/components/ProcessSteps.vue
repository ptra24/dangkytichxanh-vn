<template>
  <section id="quy-trinh" class="process-section">
    <!-- Heading -->
    <div class="process-heading">
      <h2 class="process-title">Quy trình 4 bước</h2>
      <p class="process-subtitle">Đơn giản, minh bạch, hoàn thành trong 15 phút</p>
    </div>

    <!-- Steps list -->
    <div class="process-steps">
      <div
        v-for="(step, index) in steps"
        :key="index"
        class="process-step"
      >
        <!-- Left: circle + connector -->
        <div class="step-indicator">
          <div class="step-circle">{{ step.number }}</div>
          <div v-if="index < steps.length - 1" class="step-line"></div>
        </div>

        <!-- Right: content -->
        <div class="step-content">
          <h3 class="step-title">{{ step.title }}</h3>
          <p class="step-desc">{{ step.desc }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const steps = [
  {
    number: 1,
    title: 'Liên hệ tư vấn',
    desc: 'Gọi hoặc nhắn Zalo để được tư vấn miễn phí trong 5 phút',
  },
  {
    number: 2,
    title: 'Cung cấp thông tin',
    desc: 'Gửi link Fanpage + ảnh CMND/CCCD qua Zalo',
  },
  {
    number: 3,
    title: 'Chúng tôi xử lý',
    desc: 'Đội ngũ kỹ thuật hoàn tất trong 15–60 phút',
  },
  {
    number: 4,
    title: 'Nhận tích xanh & thanh toán',
    desc: 'Xem tích xanh live trên Fanpage, thanh toán khi hài lòng',
  },
];
</script>

<style scoped>
/* ── Section ─────────────────────────────────── */
.process-section {
  background-color: #f1f5f9;
  padding: 72px 24px 80px;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.dark .process-section {
  background-color: #0d1829;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}

/* ── Heading ─────────────────────────────────── */
.process-heading {
  text-align: center;
  margin-bottom: 52px;
}

.process-title {
  font-size: 2.25rem;
  font-weight: 800;
  background: linear-gradient(135deg, #ff7d00 0%, #fd7e14 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  letter-spacing: -0.02em;
  margin: 0 0 10px;
  line-height: 1.2;
}

.dark .process-title {
  color: #ffffff;
}

.process-subtitle {
  font-size: 0.875rem;
  color: #64748b;
  margin: 0;
  font-weight: 400;
}

/* ── Steps container ─────────────────────────── */
.process-steps {
  max-width: 580px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
}

/* ── Single step ─────────────────────────────── */
.process-step {
  display: flex;
  align-items: flex-start;
  gap: 20px;
}

/* ── Left column: circle + line ──────────────── */
.step-indicator {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex-shrink: 0;
}

.step-circle {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ff7d00 0%, #ff8e38 100%);
  color: #ffffff;
  font-size: 0.9375rem;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 18px rgba(255, 125, 0, 0.45);
  flex-shrink: 0;
  position: relative;
  z-index: 1;
}

.step-line {
  width: 2px;
  flex: 1;
  min-height: 40px;
  background: linear-gradient(to bottom, rgba(255, 125, 0, 0.6) 0%, rgba(255, 125, 0, 0.15) 100%);
  margin: 4px 0;
}

/* ── Right column: text ──────────────────────── */
.step-content {
  padding-top: 9px;
  padding-bottom: 36px;
}

.process-step:last-child .step-content {
  padding-bottom: 0;
}

.step-title {
  font-size: 0.9375rem;
  font-weight: 700;
  color: #1e293b;
  margin: 0 0 5px;
  letter-spacing: -0.01em;
}

.dark .step-title {
  color: #e2e8f0;
}

.step-desc {
  font-size: 0.8125rem;
  color: #64748b;
  margin: 0;
  line-height: 1.5;
  font-weight: 400;
}

/* ── Responsive ──────────────────────────────── */
@media (max-width: 540px) {
  .process-title {
    font-size: 1.5rem;
  }
  .process-section {
    padding: 56px 20px 64px;
  }
}
</style>
