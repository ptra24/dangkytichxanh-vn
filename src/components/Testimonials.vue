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
    color: 'linear-gradient(135deg, #2563eb, #1d4ed8)',
  },
  {
    quote: 'Thủ tục cực nhanh, đúng 20 phút là có tích xanh. Quan trọng là không phải trả tiền trước.',
    name: 'Trần Thị Lan',
    role: 'Chủ spa làm đẹp',
    initials: 'TT',
    color: 'linear-gradient(135deg, #0891b2, #0e7490)',
  },
  {
    quote: 'Nhờ có tích xanh mà nhãn hàng chịu booking giá cao hơn trước. ROI quá hời.',
    name: 'Phạm Hồng Quân',
    role: 'KOL 60K followers',
    initials: 'PH',
    color: 'linear-gradient(135deg, #7c3aed, #6d28d9)',
  },
];
</script>

<style scoped>
/* ── Section ─────────────────────────────────── */
.testimonials-section {
  background-color: var(--site-bg-soft);
  padding: 72px 24px 80px;
  font-family: 'Inter', 'Segoe UI', sans-serif;
  border-top: 1px solid var(--site-border);
}

/* ── Heading ─────────────────────────────────── */
.testimonials-heading {
  text-align: center;
  margin-bottom: 44px;
}

.testimonials-title {
  font-size: 1.875rem;
  font-weight: 800;
  color: var(--site-text);
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
  background-color: var(--site-card);
  border: 1px solid var(--site-border);
  border-radius: 14px;
  padding: 26px 24px 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  transition: border-color 0.25s ease, transform 0.25s ease;
}

.review-card:hover {
  border-color: #2d4a7a;
  transform: translateY(-3px);
}

/* ── Stars ───────────────────────────────────── */
.review-stars {
  display: flex;
  gap: 2px;
}

.star {
  color: #f59e0b;
  font-size: 1rem;
  line-height: 1;
}

/* ── Quote ───────────────────────────────────── */
.review-quote {
  font-size: 0.875rem;
  color: var(--site-muted);
  line-height: 1.65;
  margin: 0;
  font-weight: 400;
  flex: 1;
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
  color: var(--site-text);
  line-height: 1;
}

.author-role {
  font-size: 0.75rem;
  color: var(--site-muted);
  font-weight: 400;
  line-height: 1;
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
