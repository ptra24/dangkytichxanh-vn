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
            'link' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'service' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:5000',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'link.required' => 'Vui lòng nhập link tài khoản / Fanpage.',
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
            'link' => $request->link,
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

    /**
     * Display a listing of contacts for the admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $contacts = Contact::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $contacts
        ]);
    }

    /**
     * Update the specified contact status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,contacting,completed,contacted'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $contact = Contact::findOrFail($id);
        $contact->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công.',
            'data' => $contact
        ]);
    }

    /**
     * Remove the specified contact from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        $user = $this->getAuthorizedUser($request);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        if ($user['role'] !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Bạn không có quyền thực hiện hành động này.'], 403);
        }

        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thông tin đăng ký thành công.'
        ]);
    }

    /**
     * Get recent contacts with anonymized names and phone numbers for public social proof.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentContactsPublic()
    {
        $contacts = Contact::orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['name', 'phone', 'service', 'created_at']);

        $anonymized = $contacts->map(function ($contact) {
            // Anonymize Name
            $nameParts = explode(' ', trim($contact->name));
            $lastName = array_pop($nameParts);
            $anonLastName = mb_substr($lastName, 0, 1, 'UTF-8') . '***';
            $anonName = count($nameParts) > 0 
                ? implode(' ', $nameParts) . ' ' . $anonLastName 
                : $anonLastName;

            // Anonymize Phone
            $phone = trim($contact->phone);
            $anonPhone = strlen($phone) >= 7 
                ? substr($phone, 0, 4) . '***' . substr($phone, -3)
                : substr($phone, 0, 3) . '***';

            return [
                'name' => $anonName,
                'phone' => $anonPhone,
                'service' => $contact->service,
                'created_at' => $contact->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $anonymized
        ]);
    }
}
