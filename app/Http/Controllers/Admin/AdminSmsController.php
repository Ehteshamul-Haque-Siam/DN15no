<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Http\Request;

class AdminSmsController extends Controller
{
    public function index()
    {
        $logs = SmsLog::latest()->paginate(30);
        return view('admin.sms.index', compact('logs'));
    }

    public function resend(SmsLog $log, SmsService $sms)
    {
        $sms->send($log->mobile, $log->message, $log->type, $log->registration_id);
        return back()->with('success', 'SMS resent.');
    }

    public function sendTest(Request $request, SmsService $sms)
    {
        $request->validate([
            'mobile'  => 'required|regex:/^01[3-9]\d{8}$/',
            'message' => 'required|string|max:500',
        ]);

        $sms->send($request->mobile, $request->message, 'otp');

        return back()->with('success', 'Test SMS sent. Check the log below.');
    }
}