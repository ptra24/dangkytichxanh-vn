<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Mail\NewRegistrationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Store a newly created contact/registration in storage and send notification email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // 1. Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'service' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu gửi lên không hợp lệ.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // 2. Save registration data to SQLite database
        $contact = Contact::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'service' => $request->service ?? 'Tư vấn tích xanh',
            'message' => $request->message,
            'status' => 'pending',
        ]);

        // 3. Send Notification Email via SMTP (Gmail App Password)
        $mailSent = false;
        $mailError = null;

        try {
            $adminEmail = config('mail.admin_receiver', 'admin@dangkytichxanh.vn');
            Mail::to($adminEmail)->send(new NewRegistrationMail($contact));
            $mailSent = true;
        } catch (\Throwable $e) {
            $mailError = $e->getMessage();
            Log::error('SMTP/Gmail App Password Mail Sending Failed: ' . $mailError, [
                'exception' => $e,
                'contact' => $contact->toArray()
            ]);
        }

        // 4. Return standard JSON response
        if (!$mailSent) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công! Tuy nhiên, hệ thống email thông báo đang gặp sự cố. Chúng tôi sẽ liên hệ lại với bạn sớm.',
                'data' => $contact,
                'mail_error' => config('app.debug') ? $mailError : 'Mail delivery failed. Logged internally.'
            ], 201);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký tư vấn tích xanh thành công! Chúng tôi sẽ liên hệ với bạn trong giây lát.',
            'data' => $contact,
        ], 201);
    }
}
