<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    /**
     * ==========================================
     * PUBLIC API FOR CUSTOMER (KHÁCH HÀNG)
     * ==========================================
     */

    /**
     * Lấy toàn bộ tin nhắn của một Session Khách hàng.
     */
    public function getMessagesForCustomer(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return response()->json(['success' => false, 'message' => 'Missing session_id'], 400);
        }

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Khách hàng gửi tin nhắn.
     */
    public function sendMessageFromCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_id' => 'required|string|max:255',
            'customer_name' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $customerName = $request->customer_name ?: 'Khách vãng lai';

        $message = ChatMessage::create([
            'session_id' => $request->session_id,
            'customer_name' => $customerName,
            'sender_type' => 'customer',
            'sender_name' => $customerName,
            'message' => $request->message,
            'is_read' => false
        ]);

        // Kiểm tra xem đã có tin nhắn từ nhân viên thực tế chưa
        $hasHumanReplied = ChatMessage::where('session_id', $request->session_id)
            ->where('sender_type', 'employee')
            ->where('sender_name', '!=', 'Hệ thống tự động')
            ->exists();

        // Kiểm tra xem đã gửi tin nhắn tự động chưa
        $hasAutoReplied = ChatMessage::where('session_id', $request->session_id)
            ->where('sender_name', 'Hệ thống tự động')
            ->exists();

        if (!$hasHumanReplied && !$hasAutoReplied) {
            // Tự động phản hồi để phục vụ kiểm thử và tạo dữ liệu thực tế cho Admin
            $autoReplies = [
                "Chào bạn {$customerName}, cảm ơn bạn đã liên hệ dịch vụ Đăng ký Tích xanh. Yêu cầu của bạn đang được chuyển đến nhân viên hỗ trợ trực tuyến!",
                "Xin chào! Nhân viên hỗ trợ của chúng tôi sẽ online và tư vấn trực tiếp cho bạn sau ít phút. Bạn vui lòng để lại số điện thoại/link fanpage để chúng tôi tiện liên hệ nhé.",
                "Chào bạn, AZ Media đã nhận được lời nhắn của bạn. Chúng tôi sẽ phản hồi lại ngay lập tức qua Zalo hoặc trực tiếp tại khung chat này."
            ];
            $replyText = $autoReplies[array_rand($autoReplies)];

            ChatMessage::create([
                'session_id' => $request->session_id,
                'customer_name' => $customerName,
                'sender_type' => 'employee',
                'sender_name' => 'Hệ thống tự động',
                'message' => $replyText,
                'is_read' => false // Để hiện thông báo đỏ chưa đọc phía Khách hàng
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $message
        ], 201);
    }


    /**
     * ==========================================
     * PRIVATE API FOR ADMIN & EMPLOYEES (QUẢN TRỊ VIÊN & NHÂN VIÊN)
     * ==========================================
     */

    /**
     * Lấy danh sách các phiên chat hiện có (có gom nhóm và đếm tin chưa đọc).
     */
    public function getSessionsForAdmin(Request $request)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // Lấy danh sách các session_id độc nhất cùng thời gian tin nhắn cuối cùng
        $sessions = ChatMessage::select('session_id', 'customer_name')
            ->selectRaw('MAX(created_at) as latest_message_time')
            ->groupBy('session_id', 'customer_name')
            ->orderBy('latest_message_time', 'desc')
            ->get();

        // Nạp tin nhắn cuối cùng và số lượng chưa đọc cho mỗi session
        foreach ($sessions as $session) {
            $latestMsg = ChatMessage::where('session_id', $session->session_id)
                ->orderBy('id', 'desc')
                ->first();

            $session->latest_message = $latestMsg ? $latestMsg->message : '';
            $session->unread_count = ChatMessage::where('session_id', $session->session_id)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->count();
        }

        return response()->json([
            'success' => true,
            'data' => $sessions
        ]);
    }

    /**
     * Lấy toàn bộ tin nhắn của một phiên chat từ Admin Dashboard.
     */
    public function getMessagesForAdmin(Request $request, $session_id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $messages = ChatMessage::where('session_id', $session_id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Admin/Nhân viên gửi tin nhắn phản hồi cho khách hàng.
     */
    public function sendMessageFromAdmin(Request $request, $session_id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:5000'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Lấy tên khách hàng của session này
        $latestCustomerMessage = ChatMessage::where('session_id', $session_id)
            ->where('sender_type', 'customer')
            ->first();
        $customerName = $latestCustomerMessage ? $latestCustomerMessage->customer_name : 'Khách vãng lai';

        $message = ChatMessage::create([
            'session_id' => $session_id,
            'customer_name' => $customerName,
            'sender_type' => 'employee',
            'sender_name' => $user['name'],
            'message' => $request->message,
            'is_read' => true // Tin nhắn nhân viên mặc định đã đọc
        ]);

        return response()->json([
            'success' => true,
            'data' => $message
        ], 201);
    }

    /**
     * Đánh dấu tất cả tin nhắn trong session của khách hàng gửi đã được đọc.
     */
    public function markAsRead(Request $request, $session_id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        ChatMessage::where('session_id', $session_id)
            ->where('sender_type', 'customer')
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Marked as read'
        ]);
    }
}
