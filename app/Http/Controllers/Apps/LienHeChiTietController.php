<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\LienHe;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LienHeChiTietController extends Controller
{
    public function index()
    {
        return view('frontend.pages.lien-he');
    }


    public function send(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|regex:/^[\w\.-]+@[\w\.-]+\.\w{2,}$/',
            'phone'   => 'required|regex:/^0[0-9]{8,10}$/',
            'subject' => 'nullable|string|max:255',
            'message' => 'required',
            'g-recaptcha-response' => 'required'
        ]);

        // Kiểm tra reCAPTCHA
        $recaptcha = $request->input('g-recaptcha-response');
        $secret    = env('RECAPTCHA_SECRET');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $secret,
            'response' => $recaptcha,
            'remoteip' => $request->ip(),
        ]);
        $responseBody = $response->json();
        if (!$responseBody['success']) {
            return back()->withErrors(['captcha' => 'Xác thực reCAPTCHA thất bại!'])->withInput();
        }

        // Xử lý upload ảnh (nếu có)
        $anhMinhHoa = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file->isValid()) {
                $anhMinhHoa = $file->store('lienhe', 'public');
            }
        }

        // Gửi email bản sao cho user. Chỉ khi gửi THÀNH CÔNG mới lưu DB
        $userEmail = $request->input('email');
        $userName  = $request->input('name');
        $subject   = "Bản sao liên hệ của bạn tới CineTick";
        $mailData = [
            'name'    => $userName,
            'email'   => $userEmail,
            'phone'   => $request->input('phone'),
            'subject' => $request->input('subject'),
            'message_body' => $request->input('message'),
        ];
        Log::info("userEmail: $userEmail, userName: $userName");
        Log::info('userEmail: ' . print_r($userEmail, true));
        Log::info('userName: ' . print_r($userName, true));
        
        try {
            
            Mail::send('emails.lienhe_ban_sao', $mailData, function ($message) use ($userEmail, $userName, $subject) {
                $message->to([$userEmail => $userName])
                    ->subject($subject);
            });
            
        } catch (\Exception $e) {
            
            Log::error('Mail error: ' . $e->getMessage());
            return back()->with('email_error', 'Chúng tôi không thể xác định được email bạn vừa nhập. Có vẻ Email bạn nhập chưa đúng? Vui lòng kiểm tra lại!')->withInput();
            
        }

        // Nếu tới đây nghĩa là gửi mail OK, mới lưu vào CSDL
        LienHe::create([
            'HoTenNguoiLienHe' => $request->input('name'),
            'Email'            => $userEmail,
            'SDT'              => $request->input('phone'),
            'TieuDe'           => $request->input('subject'),
            'NoiDung'          => $request->input('message'),
            'AnhMinhHoa'       => $anhMinhHoa,
            'TrangThai'        => 0,
        ]);

        return back()->with('success', 'Gửi liên hệ thành công!');
    }
}