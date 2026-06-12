<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu Cầu Đăng Ký Tích Xanh Mới</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #0b0f19;
            color: #f3f4f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #0b0f19;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #111827;
            border-radius: 16px;
            border: 1px solid #1f2937;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -4px rgba(0, 0, 0, 0.3);
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header p {
            color: #93c5fd;
            font-size: 14px;
            margin: 8px 0 0 0;
            font-weight: 500;
        }
        .content {
            padding: 30px;
        }
        .section-title {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #3b82f6;
            font-weight: 700;
            margin-bottom: 20px;
            border-bottom: 1px solid #1f2937;
            padding-bottom: 8px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .info-table td {
            padding: 12px 0;
            border-bottom: 1px dashed #1f2937;
            font-size: 15px;
            vertical-align: top;
        }
        .info-table td.label {
            color: #9ca3af;
            width: 30%;
            font-weight: 500;
        }
        .info-table td.value {
            color: #f3f4f6;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            background-color: #1e3a8a;
            color: #60a5fa;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 9999px;
            text-transform: uppercase;
        }
        .message-box {
            background-color: #1f2937;
            border-radius: 8px;
            padding: 16px;
            font-size: 14px;
            line-height: 1.6;
            color: #d1d5db;
            border-left: 4px solid #3b82f6;
            margin-bottom: 20px;
        }
        .footer {
            background-color: #0b0f19;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #1f2937;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>⚡ Đăng Ký Tích Xanh Mới</h1>
                <p>Hệ thống AZ Tích Xanh vừa ghi nhận một yêu cầu tư vấn</p>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="section-title">Thông tin khách hàng</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Họ và tên</td>
                        <td class="value">{{ $contact->name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Số điện thoại</td>
                        <td class="value">
                            <a href="tel:{{ $contact->phone }}" style="color: #60a5fa; text-decoration: none;">
                                {{ $contact->phone }}
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Địa chỉ Email</td>
                        <td class="value">
                            @if($contact->email)
                                <a href="mailto:{{ $contact->email }}" style="color: #60a5fa; text-decoration: none;">
                                    {{ $contact->email }}
                                </a>
                            @else
                                <span style="color: #6b7280; font-style: italic;">Không cung cấp</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Loại dịch vụ</td>
                        <td class="value">
                            <span class="badge">{{ $contact->service ?? 'Chưa chọn' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Thời gian đăng ký</td>
                        <td class="value">{{ $contact->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>

                @if($contact->message)
                    <div class="section-title">Lời nhắn / Yêu cầu chi tiết</div>
                    <div class="message-box">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Email này được tự động gửi từ hệ thống quản trị <a href="https://aztichxanh.vn">aztichxanh.vn</a>.</p>
                <p>&copy; {{ date('Y') }} aztichxanh.vn. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
