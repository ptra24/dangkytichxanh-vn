<template>
  <section class="testimonials-section">
    <!-- Heading -->
    <div class="testimonials-heading">
      <h2 class="testimonials-title">Khách hàng nói gì?</h2>
    </div>

    <!-- Cards row -->
    <div class="testimonials-grid">
      <div
        v-for="(review, index) in reviews"
        :key="index"
        class="review-card"
      >
        <!-- Stars -->
        <div class="review-stars">
          <span v-for="s in 5" :key="s" class="star">★</span>
        </div>

        <!-- Quote -->
        <p class="review-quote">"{{ review.quote }}"</p>

        <!-- Author -->
        <div class="review-author">
          <div class="author-avatar" :style="{ background: review.color }">
            {{ review.initials }}
          </div>
          <div class="author-info">
            <span class="author-name">{{ review.name }}</span>
            <span class="author-role">{{ review.role }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
const reviews = [
  {
    quote: 'Sau khi có tích xanh, inbox hỏi mua tăng gấp đôi. Khách không còn hỏi "có phải shop thật không?" nữa.',
    name: 'Nguyễn Văn Minh',
    role: 'Chủ shop thời trang',
    initials: 'NV',
    color: 'linear-gradient(135deg, #ff7d00, #ff8e38)',
  },
  {
    quote: 'Thủ tục cực nhanh, đúng 20 phút là có tích xanh. Quan trọng là không phải trả tiền trước.',
    name: 'Trần Thị Lan',
    role: 'Chủ spa làm đẹp',
    initials: 'TT',
    color: 'linear-gradient(135deg, #e87f0c, #dd8d13)',
  },
  {
    quote: 'Nhờ có tích xanh mà nhãn hàng chịu booking giá cao hơn trước. ROI quá hời.',
    name: 'Phạm Hồng Quân',
    role: 'KOL 60K followers',
    initials: 'PH',
    color: 'linear-gradient(135deg, #ff8e38, #5c0096)',
  },
];
</script>

<style scoped>
/* ── Section ─────────────────────────────────── */
.testimonials-section {
  background-color: #f8fafc;
  padding: 72px 24px 80px;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.dark .testimonials-section {
  background-color: #060b13;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}

/* ── Heading ─────────────────────────────────── */
.testimonials-heading {
  text-align: center;
  margin-bottom: 44px;
}

.testimonials-title {
  font-size: 2.25rem;
  font-weight: 800;
  background: linear-gradient(135deg, #ff7d00 0%, #fd7e14 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  text-fill-color: transparent;
  letter-spacing: -0.02em;
  margin: 0;
  line-height: 1.2;
}

/* ── Grid ────────────────────────────────────── */
.testimonials-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  max-width: 1100px;
  margin: 0 auto;
}

/* ── Card ────────────────────────────────────── */
.review-card {
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 26px 24px 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  transition: border-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

.dark .review-card {
  background-color: #131f35;
  border: 1px solid #1e2d47;
  box-shadow: none;
}

.review-card:hover {
  border-color: #cbd5e1;
  transform: translateY(-3px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}

.dark .review-card:hover {
  border-color: #2d4a7a;
  box-shadow: none;
}

/* ── Stars ───────────────────────────────────── */
.review-stars {
  display: flex;
  gap: 2px;
}

.star {
  color: #ff7d00;
  font-size: 1rem;
  line-height: 1;
}

/* ── Quote ───────────────────────────────────── */
.review-quote {
  font-size: 0.875rem;
  color: #334155;
  line-height: 1.65;
  margin: 0;
  font-weight: 400;
  flex: 1;
}

.dark .review-quote {
  color: #cbd5e1;
}

/* ── Author ──────────────────────────────────── */
.review-author {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 4px;
}

.author-avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 800;
  color: #ffffff;
  flex-shrink: 0;
  letter-spacing: 0.02em;
}

.author-info {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.author-name {
  font-size: 0.8125rem;
  font-weight: 700;
  color: #0f172a;
  line-height: 1;
}

.dark .author-name {
  color: #e2e8f0;
}

.author-role {
  font-size: 0.75rem;
  color: #64748b;
  font-weight: 400;
  line-height: 1;
}

.dark .author-role {
  color: #64748b;
}

/* ── Responsive ──────────────────────────────── */
@media (max-width: 900px) {
  .testimonials-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 560px) {
  .testimonials-grid {
    grid-template-columns: 1fr;
  }
  .testimonials-section {
    padding: 56px 20px 64px;
  }
  .testimonials-title {
    font-size: 1.5rem;
  }
}
</style>
