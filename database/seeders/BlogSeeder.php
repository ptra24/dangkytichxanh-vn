<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Cách đăng ký Tích Xanh Facebook nhanh nhất năm 2026',
                'slug' => 'cach-dang-ky-tich-xanh-facebook-nhanh-nhat-nam-2026',
                'excerpt' => 'Hướng dẫn từng bước cách đăng ký tích xanh cho trang cá nhân và Fanpage Facebook cực kỳ đơn giản và nhanh chóng trong năm 2026.',
                'content' => 'Việc sở hữu dấu tích xanh trên Facebook không chỉ giúp tài khoản của bạn nổi bật hơn mà còn tăng độ tin cậy đối với khách hàng và đối tác. Từ năm 2024 đến năm 2026, Meta đã đơn giản hóa quy trình này thông qua chương trình Meta Verified. Để đăng ký tích xanh thành công, bạn cần chuẩn bị đầy đủ giấy tờ tùy thân như CCCD hoặc Hộ chiếu, đảm bảo ảnh đại diện rõ mặt và trùng khớp với thông tin giấy tờ. Sau khi xác thực danh tính và thanh toán gói dịch vụ hàng tháng, tài khoản của bạn sẽ nhận được tích xanh chính chủ chỉ trong vòng 15 đến 30 phút.',
                'image_url' => 'https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Những điều cần chuẩn bị khi làm Tích Xanh TikTok',
                'slug' => 'nhung-dieu-can-chuan-bi-khi-lam-tich-xanh-tiktok',
                'excerpt' => 'Các điều kiện và hồ sơ bạn cần chuẩn bị để quy trình đăng ký Tích Xanh trên TikTok diễn ra suôn sẻ, không lo bị từ chối.',
                'content' => 'Khác với Facebook, TikTok áp dụng những tiêu chuẩn khắt khe hơn đối với việc cấp tích xanh. Bạn cần phải là một nhà sáng tạo nội dung hoạt động tích cực, có lượng người theo dõi ổn định và tuân thủ tuyệt đối các nguyên tắc cộng đồng của TikTok. Hồ sơ chuẩn bị bao gồm giấy tờ đăng ký doanh nghiệp (đối với thương hiệu) hoặc các giấy tờ chứng minh độ nhận diện truyền thông (như bài viết trên các trang báo lớn, giải thưởng cá nhân). Đảm bảo tài khoản của bạn được liên kết với số điện thoại và email chính chủ trước khi gửi yêu cầu xét duyệt.',
                'image_url' => 'https://images.unsplash.com/photo-1598257006458-087169a1f08d?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Sự khác biệt giữa Tích Xanh Meta Verified và Tích Xanh truyền thống',
                'slug' => 'su-khac-biet-giua-tich-xanh-meta-verified-va-tich-xanh-truyen-thong',
                'excerpt' => 'So sánh toàn diện giữa hai loại tích xanh: Meta Verified (mua dịch vụ hàng tháng) và Tích xanh cũ của người nổi tiếng / doanh nghiệp lớn.',
                'content' => 'Nhiều người dùng vẫn đang nhầm lẫn giữa dấu tích xanh Meta Verified và tích xanh truyền thống. Tích xanh truyền thống được Meta cấp hoàn toàn miễn phí cho các cá nhân nổi tiếng, chính trị gia, hoặc doanh nghiệp lớn có sức ảnh hưởng sâu rộng trong xã hội và được báo chí nhắc đến nhiều. Trong khi đó, Meta Verified là gói đăng ký dịch vụ trả phí hàng tháng dành cho tất cả mọi người dùng phổ thông đáp ứng đủ điều kiện cơ bản về danh tính. Mặc dù hình thức hiển thị dấu tích xanh là hoàn toàn giống nhau, Meta Verified yêu cầu gia hạn phí định kỳ để duy trì quyền lợi bảo vệ tài khoản nâng cao.',
                'image_url' => 'https://images.unsplash.com/photo-1616469829581-73993eb86b02?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
