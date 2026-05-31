    <script setup>
    import { ref, onMounted } from 'vue'

    const blogs = ref([])

    const testimonials = [
    {
        id: 1,
        name: 'Nguyễn Văn Minh',
        role: 'Chủ shop thời trang',
        avatar: 'NV',
        content:
        'Sau khi có tích xanh, inbox hỏi mua tăng gần gấp đôi. Khách không còn hỏi "có phải shop thật không?" nữa.'
    },
    {
        id: 2,
        name: 'Trần Thị Lan',
        role: 'Chủ spa làm đẹp',
        avatar: 'TT',
        content:
        'Thủ tục cực nhanh, đúng 20 phút là có tích xanh. Quan trọng là không phải trả tiền trước.'
    },
    {
        id: 3,
        name: 'Phạm Hồng Quân',
        role: 'KOL 50K followers',
        avatar: 'PH',
        content:
        'Nhờ có tích xanh mà nhãn hàng chịu booking giá cao hơn trước. ROI quá hời.'
    }
    ]

    onMounted(async () => {
    try {
        const res = await fetch('/api/blog')
        const result = await res.json()

        if (result.success && result.data.length > 0) {
        blogs.value = result.data.slice(0, 3)
        }
    } catch (error) {
        console.error(error)
    }
    })
    </script>

    <template>

    <!-- BLOG THẬT TỪ API -->
    <section
        v-if="blogs.length"
        class="py-24 bg-slate-950"
    >
        <div class="max-w-7xl mx-auto px-4">

        <h2
            class="text-5xl font-bold text-center mb-12 text-white"
        >
            Tin tức mới nhất
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <div
            v-for="blog in blogs"
            :key="blog.id"
            class="group
                    rounded-3xl
                    border border-white/10
                    bg-white/[0.03]
                    p-8
                    transition-all
                    duration-300
                    hover:-translate-y-2"
            >
            <h3
                class="text-2xl font-bold text-white mb-4"
            >
                {{ blog.title }}
            </h3>

            <p
                class="text-slate-300 leading-8"
            >
                {{ blog.excerpt }}
            </p>
            </div>

        </div>

        </div>
    </section>

    <!-- COVER REVIEW KHI CHƯA CÓ API -->
    <section
    v-else
    class="py-24 bg-slate-950 relative overflow-hidden"
    >
    <div
    class="pointer-events-none absolute inset-0"
    style="
        background-image:
        radial-gradient(
            600px 300px at 15% 20%,
            rgba(59,130,246,0.25),
            transparent
        ),
        radial-gradient(
            500px 300px at 90% 80%,
            rgba(14,165,233,0.2),
            transparent
        );
    "
    ></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4">

        <h2
            class="text-5xl md:text-10xl font-bold text-center text-white mb-14"
        >
            Khách hàng nói gì?
        </h2>

        <div class="grid md:grid-cols-3 gap-8">

            <div
            v-for="item in testimonials"
            :key="item.id"
            class="group
                    rounded-3xl
                    border border-white/10
                    bg-white/[0.03]
                    backdrop-blur-sm
                    p-8
                    transition-all
                    duration-300
                    hover:-translate-y-2
                    hover:border-cyan-40/30"
            >

            <!-- 5 SAO -->
            <div class="flex gap-1 mb-4">
                <span
                v-for="n in 5"
                :key="n"
                class="text-yellow-400 text-2xl"
                >
                ★
                </span>
            </div>

            <!-- NỘI DUNG REVIEW -->
            <p
                class="text-slate-300
                    text-[18px]
                    leading-8
                    min-h-[10px]"
            >
                "{{ item.content }}"
            </p>

            <!-- AVATAR + THÔNG TIN -->
            <div
                class="flex items-center gap-4 mt-8"
            >

                <div
                class="w-11
                        h-11
                        rounded-full
                        bg-blue-600
                        flex
                        items-center
                        justify-center
                        text-white
                        font-bold
                        text-xl"
                >
                {{ item.avatar }}
                </div>

                <div>

                <h4
                    class="text-white
                        text-1.5xl
                        font-bold"
                >
                    {{ item.name }}
                </h4>

                <p
                    class="text-slate-400
                        text-lg"
                >
                    {{ item.role }}
                </p>

                </div>

            </div>

            </div>

        </div>

        </div>
    </section>

    </template>