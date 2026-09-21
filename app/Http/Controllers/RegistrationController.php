<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Registration;
use App\Services\SmsService;
use Illuminate\Database\QueryException;
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
            // ===== Extra safety: re-check duplicates inside transaction =====
            $duplicate = Registration::where(function ($q) use ($validated) {
                    $q->where('contact_no', $validated['contact_no'])
                      ->orWhere('mfs_trn', $validated['mfs_trn']);
                })
                ->lockForUpdate()
                ->first();

            if ($duplicate) {
                DB::rollBack();
                $field = $duplicate->contact_no === $validated['contact_no']
                    ? 'contact_no' : 'mfs_trn';

                $message = $field === 'contact_no'
                    ? "এই মোবাইল নাম্বার দিয়ে ইতিমধ্যে রেজিস্ট্রেশন করা হয়েছে। Reg ID: {$duplicate->registration_id}"
                    : "এই Transaction ID আগেই ব্যবহৃত হয়েছে। Reg ID: {$duplicate->registration_id}";

                return back()->withInput()->withErrors([$field => $message]);
            }

            // ===== Handle photo upload =====
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('photos', 'public');
                $validated['photo'] = $path;
            }

            unset($validated['avatar']);
            $validated['payment_status']  = 'pending';
            $validated['approval_status'] = 'pending';

            $registration = Registration::create($validated);

            $sms->send(
                $registration->contact_no,
                "ধন্যবাদ {$registration->name_bn}! Reg ID: {$registration->registration_id}। ১০২০৳ বিকাশ করুন: 01761983617",
                'registration',
                $registration->id
            );

            DB::commit();

            return redirect()->route('register.success', $registration->tracking_token);

        } catch (QueryException $e) {
            DB::rollBack();

            // Handle DB-level unique constraint violations gracefully
            if ($e->getCode() === '23000') {
                $msg = str_contains($e->getMessage(), 'mfs_trn')
                    ? 'এই Transaction ID আগেই ব্যবহৃত হয়েছে।'
                    : 'এই মোবাইল নাম্বার দিয়ে ইতিমধ্যে রেজিস্ট্রেশন করা হয়েছে।';

                return back()->withInput()->withErrors(['contact_no' => $msg]);
            }

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
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