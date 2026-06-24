<template>
  <section id="loi-ich" class="why-us-section">
    <!-- Subtle dot-grid background overlay -->
    <div class="why-us-bg-dots"></div>

    <div class="why-us-inner">
      <!-- Heading -->
      <div class="why-us-heading">
        <h2 class="why-us-title">Tích Xanh Đem Lại Gì Cho Fanpage &amp; Business Của Bạn?</h2>
        <p class="why-us-subtitle">Tín hiệu Uy Tín vượt trội trên Meta, tăng tiếp cận tự nhiên và doanh số ngay lập tức</p>
      </div>

      <!-- 4 cards row -->
      <div class="why-us-cards">

        <!-- Card 1: Xác minh chính chủ -->
        <div class="why-card">
          <div class="why-card-dot-grid"></div>
          <div class="why-card-icon-wrap">
            <div class="why-card-icon-box">
              <!-- Checkmark shield icon -->
              <svg class="why-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L3 6v6c0 5.25 3.75 10.15 9 11.25C17.25 22.15 21 17.25 21 12V6l-9-4z"/>
                <polyline points="9 12 11 14 15 10"/>
              </svg>
            </div>
          </div>
          <h3 class="why-card-title">Xác minh chính chủ</h3>
          <p class="why-card-desc">Khách hàng của bạn tin tưởng, không nghi ngờ giả mạo</p>
        </div>

        <!-- Card 2: Ưu tiên hiển thị -->
        <div class="why-card">
          <div class="why-card-dot-grid"></div>
          <div class="why-card-icon-wrap">
            <div class="why-card-icon-box">
              <!-- Trending up icon -->
              <svg class="why-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                <polyline points="17 6 23 6 23 12"/>
              </svg>
            </div>
          </div>
          <h3 class="why-card-title">Ưu tiên hiển thị</h3>
          <p class="why-card-desc">Lượt tiếp cận tự nhiên tăng 20-40%, hiển thị top 1 khi khách tìm kiếm</p>
        </div>

        <!-- Card 3: Tăng doanh thu -->
        <div class="why-card">
          <div class="why-card-dot-grid"></div>
          <div class="why-card-icon-wrap">
            <div class="why-card-icon-box">
              <!-- Dollar circle icon -->
              <svg class="why-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="16"/>
                <line x1="9.5" y1="10.5" x2="14.5" y2="10.5"/>
                <path d="M9.5 10.5a2.5 2.5 0 0 1 5 0"/>
                <path d="M9.5 13.5a2.5 2.5 0 0 0 5 0"/>
              </svg>
            </div>
          </div>
          <h3 class="why-card-title">Tăng doanh thu</h3>
          <p class="why-card-desc">Tỉ lệ chốt đơn trên page tích xanh tăng trung bình 28%</p>
        </div>

        <!-- Card 4: Bảo vệ thương hiệu -->
        <div class="why-card">
          <div class="why-card-dot-grid"></div>
          <div class="why-card-icon-wrap">
            <div class="why-card-icon-box">
              <!-- Shield check icon -->
              <svg class="why-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L3 6v6c0 5.25 3.75 10.15 9 11.25C17.25 22.15 21 17.25 21 12V6l-9-4z"/>
                <path d="M9 12l2 2 4-4"/>
              </svg>
            </div>
          </div>
          <h3 class="why-card-title">Bảo vệ thương hiệu</h3>
          <p class="why-card-desc">Facebook tự động gỡ các trang mạo danh bạn</p>
        </div>

      </div>
    </div>
  </section>
</template>

<script setup>
</script>

<style scoped>
/* ── Section wrapper ──────────────────────────── */
.why-us-section {
  position: relative;
  background-color: #f8fafc;
  padding: 80px 24px;
  overflow: hidden;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  transition: background-color 0.3s ease;
}

.dark .why-us-section {
  background-color: #0b0f1e;
}

/* Dot-grid background on the section */
.why-us-bg-dots {
  pointer-events: none;
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle, rgba(15, 23, 42, 0.07) 1px, transparent 1px);
  background-size: 28px 28px;
  opacity: 0.5;
}

.dark .why-us-bg-dots {
  background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
}

/* ── Inner container ──────────────────────────── */
.why-us-inner {
  position: relative;
  z-index: 1;
  max-width: 1200px;
  margin: 0 auto;
}

/* ── Heading block ───────────────────────────── */
.why-us-heading {
  text-align: center;
  margin-bottom: 56px;
  max-width: 680px;
  margin-left: auto;
  margin-right: auto;
}

.why-us-title {
  font-size: 2rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.25;
  letter-spacing: -0.02em;
  margin: 0 0 14px 0;
  transition: color 0.3s ease;
}

.dark .why-us-title {
  color: #ffffff;
}

.why-us-subtitle {
  font-size: 0.9rem;
  color: #475569;
  line-height: 1.6;
  margin: 0;
  font-weight: 400;
  transition: color 0.3s ease;
}

.dark .why-us-subtitle {
  color: #8b9ab5;
}

/* ── Cards row ────────────────────────────────── */
.why-us-cards {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

/* ── Single card ─────────────────────────────── */
.why-card {
  position: relative;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 28px 22px 28px;
  text-align: center;
  transition: border-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease, background-color 0.3s ease;
  overflow: hidden;
}

.dark .why-card {
  background: #131929;
  border: 1px solid #1e2d47;
}

.why-card:hover {
  border-color: #c2410c;
  transform: translateY(-3px);
  box-shadow: 0 12px 32px rgba(249, 115, 22, 0.18);
}

/* Dot-grid — sweep từ dưới lên khi hover */
.why-card-dot-grid {
  position: absolute;
  inset: 0;
  border-radius: 16px;
  background-image: radial-gradient(circle, rgba(15,23,42,0.1) 1px, transparent 1px);
  background-size: 18px 18px;
  /* Ẩn hoàn toàn: clip từ dưới lên (bottom inset = 100%) */
  clip-path: inset(0 0 100% 0 round 16px);
  /* Mờ dần phần trên để trông tự nhiên hơn */
  -webkit-mask-image: linear-gradient(to top, black 50%, transparent 100%);
  mask-image: linear-gradient(to top, black 50%, transparent 100%);
  transition: clip-path 0.5s ease;
  pointer-events: none;
  z-index: 0;
}

.dark .why-card-dot-grid {
  background-image: radial-gradient(circle, rgba(255,255,255,0.12) 1px, transparent 1px);
}

.why-card:hover .why-card-dot-grid {
  /* Mở ra hoàn toàn: sweep từ dưới lên trên */
  clip-path: inset(0 0 0% 0 round 16px);
}

/* ── Icon wrapper ────────────────────── */
.why-card-icon-wrap {
  position: relative;
  width: 72px;
  height: 72px;
  margin: 0 auto 22px;
  z-index: 1;
}

/* Centered icon box (plain square) */
.why-card-icon-box {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 14px;
  background-color: #f8fafc;
  border: 1px solid #e2e8f0;
  transition: background-color 0.3s ease, border-color 0.3s ease;
}

.dark .why-card-icon-box {
  background-color: #192236;
  border: 1px solid #1e2d47;
}

.why-card-icon {
  width: 30px;
  height: 30px;
  color: #f97316;
  flex-shrink: 0;
  transition: transform 0.3s ease;
}

.why-card:hover .why-card-icon {
  transform: rotate(-15deg);
}

/* ── Card text ───────────────────────────────── */
.why-card-title {
  font-size: 0.875rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 10px;
  letter-spacing: -0.01em;
  position: relative;
  z-index: 1;
  transition: color 0.3s ease;
}

.dark .why-card-title {
  color: #e8edf5;
}

.why-card-desc {
  font-size: 0.8rem;
  color: #475569;
  line-height: 1.55;
  margin: 0;
  font-weight: 400;
  position: relative;
  z-index: 1;
  transition: color 0.3s ease;
}

.dark .why-card-desc {
  color: #6b7fa3;
}

/* ── Responsive ──────────────────────────────── */
@media (max-width: 900px) {
  .why-us-cards {
    grid-template-columns: repeat(2, 1fr);
  }
  .why-us-title {
    font-size: 1.6rem;
  }
}

@media (max-width: 540px) {
  .why-us-cards {
    grid-template-columns: 1fr;
  }
  .why-us-section {
    padding: 56px 16px;
  }
}
</style>
