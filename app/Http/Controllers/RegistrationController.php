<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Registration;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('registration.index');
    }

    public function store(RegistrationRequest $request, SmsService $sms)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {
            // Profile photo
            if ($request->hasFile('avatar')) {
                $validated['photo'] = $request->file('avatar')->store('photos', 'public');
            }
            unset($validated['avatar']);

            // Bank slip
            if ($request->hasFile('bank_slip')) {
                $validated['bank_slip'] = $request->file('bank_slip')->store('bank-slips', 'public');
            }

            // Cash receipt
            if ($request->hasFile('cash_receipt')) {
                $validated['cash_receipt'] = $request->file('cash_receipt')->store('cash-receipts', 'public');
            }

            // Clear other-method fields
            $method = $validated['payment_method'];

            if ($method !== 'bkash') {
                $validated['mfs_no']  = null;
                $validated['mfs_trn'] = null;
            }

            if ($method !== 'bank') {
                $validated['bank_id']        = null;
                $validated['bank_reference'] = null;
                $validated['bank_slip']      = null;
                $validated['paid_to_bank']   = null;
            }

            if ($method !== 'cash') {
                $validated['cash_receipt_no'] = null;
                $validated['cash_receipt']    = null;
            }

            $validated['payment_status']  = 'pending';
            $validated['approval_status'] = 'pending';

            $registration = Registration::create($validated);

            $sms->sendTemplate(
                $registration->contact_no,
                'welcome',
                [
                    'name'           => $registration->name_bn,
                    'reg_id'         => $registration->registration_id,
                    'amount'         => number_format($registration->membership_fee, 0),
                    'payment_number' => '01761983617',
                    'mobile'         => $registration->contact_no,
                ],
                'registration',
                $registration->id,
                "ধন্যবাদ {$registration->name_bn}! Reg ID: {$registration->registration_id}। ১০২০৳ পাঠান: 01761983617"
            );

            DB::commit();

            return redirect()->route('register.success', $registration->tracking_token);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function success($token)
    {
        $registration = Registration::where('tracking_token', $token)->firstOrFail();
        return view('registration.success', compact('registration'));
    }

    public function status($token)
    {
        $registration = Registration::where('tracking_token', $token)->firstOrFail();
        return view('registration.status', compact('registration'));
    }

    public function trackForm()
    {
        return view('registration.track');
    }

    public function track(Request $request)
    {
        $request->validate(['q' => 'required|string|max:100']);
        $q = $request->input('q');

        $registration = Registration::where('registration_id', $q)
            ->orWhere('contact_no', $q)
            ->first();

        if (!$registration) {
            return back()->withErrors(['q' => 'কোনো রেকর্ড পাওয়া যায়নি।']);
        }

        return view('registration.status', compact('registration'));
    }
}