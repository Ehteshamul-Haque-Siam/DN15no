<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\BkashService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class AdminRegistrationController extends Controller
{
    protected function audit(string $action, Registration $reg, array $extra = []): void
    {
        Log::info('ADMIN_ACTION', [
            'admin_id'    => auth()->id(),
            'admin_email' => auth()->user()->email ?? null,
            'admin_role'  => auth()->user()->role ?? null,
            'action'      => $action,
            'reg_id'      => $reg->registration_id,
            'ip'          => request()->ip(),
            'user_agent'  => substr((string) request()->userAgent(), 0, 200),
            'extra'       => $extra,
        ]);
    }

    public function index(Request $request)
    {
        $query = Registration::query();

        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }
        if ($request->filled('status')) {
            $query->where('approval_status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('registration_id', 'like', "%$s%")
                  ->orWhere('name_en', 'like', "%$s%")
                  ->orWhere('name_bn', 'like', "%$s%")
                  ->orWhere('contact_no', 'like', "%$s%")
                  ->orWhere('mfs_trn', 'like', "%$s%");
            });
        }

        $registrations = $query->latest()->paginate(20)->withQueryString();

        return view('admin.registrations.index', compact('registrations'));
    }

    public function show(Registration $registration)
    {
        return view('admin.registrations.show', compact('registration'));
    }

    /**
     * NEW — Query bKash API for real-time transaction status.
     * Auto-verifies if the payment is Completed and amount matches.
     */
    public function queryBkash(Registration $registration, BkashService $bkash)
    {
        if (empty($registration->mfs_trn)) {
            return back()->withErrors(['bkash' => 'No TRN submitted for this registration.']);
        }

        try {
            $result = $bkash->searchTransaction($registration->mfs_trn);

            if (!$result['success']) {
                return back()->withErrors([
                    'bkash' => 'bKash lookup failed: ' . ($result['statusMessage'] ?? 'Unknown response'),
                ]);
            }

            $this->audit('query_bkash', $registration, [
                'trx_id' => $registration->mfs_trn,
                'status' => $result['transactionStatus'],
                'amount' => $result['amount'],
            ]);

            if ($result['transactionStatus'] === 'Completed') {
                $expected = (float) $registration->membership_fee;
                $actual   = (float) $result['amount'];

                if (abs($expected - $actual) < 0.01) {
                    if ($registration->payment_status !== 'verified') {
                        $registration->update([
                            'payment_status' => 'verified',
                            'paid_at'        => $result['paymentExecuteTime'] ?? now(),
                            'verified_by'    => auth()->id(),
                            'verified_at'    => now(),
                            'admin_remarks'  => 'Auto-verified via bKash API query',
                        ]);

                        return back()->with('success',
                            "✅ bKash confirmed ৳{$actual}. Payment auto-verified.");
                    }
                    return back()->with('success',
                        "bKash shows Completed ৳{$actual} (already verified).");
                }

                return back()->withErrors([
                    'bkash' => "Amount mismatch: bKash shows ৳{$actual}, expected ৳{$expected}.",
                ]);
            }

            return back()->with('success',
                "bKash status: {$result['transactionStatus']}. Not yet completed.");
        } catch (\Throwable $e) {
            return back()->withErrors(['bkash' => 'bKash error: ' . $e->getMessage()]);
        }
    }

    public function verifyPayment(Registration $registration, SmsService $sms)
    {
        if ($registration->payment_status === 'verified') {
            return back()->with('success', 'Payment already verified.');
        }

        $registration->update([
            'payment_status' => 'verified',
            'paid_at'        => $registration->paid_at ?? now(),
            'verified_by'    => auth()->id(),
            'verified_at'    => now(),
        ]);

        $this->audit('verify_payment', $registration, ['old' => 'pending']);

        $sms->send(
            $registration->contact_no,
            "প্রিয় {$registration->name_bn}, আপনার পেমেন্ট যাচাই হয়েছে। সদস্যপদ অনুমোদনের অপেক্ষায় আছেন।",
            'payment',
            $registration->id
        );

        return back()->with('success', "Payment verified for {$registration->registration_id}.");
    }

    public function rejectPayment(Request $request, Registration $registration, SmsService $sms)
    {
        $request->validate(['remarks' => 'required|string|max:255']);

        $registration->update([
            'payment_status' => 'rejected',
            'admin_remarks'  => $request->remarks,
        ]);

        $this->audit('reject_payment', $registration, ['reason' => $request->remarks]);

        $sms->send(
            $registration->contact_no,
            "দুঃখিত {$registration->name_bn}, পেমেন্ট যাচাই ব্যর্থ হয়েছে। কারণ: {$request->remarks}",
            'rejection',
            $registration->id
        );

        return back()->with('success', 'Payment rejected.');
    }

    public function approve(Registration $registration, SmsService $sms)
    {
        if ($registration->payment_status !== 'verified') {
            return back()->withErrors(['error' => 'Cannot approve before payment is verified.']);
        }

        if ($registration->approval_status === 'approved') {
            return back()->with('success', 'Already approved.');
        }

        $registration->update([
            'approval_status' => 'approved',
            'approved_at'     => now(),
            'approved_by'     => auth()->id(),
        ]);

        $this->audit('approve', $registration);

        $sms->send(
            $registration->contact_no,
            "অভিনন্দন {$registration->name_bn}! সদস্যপদ অনুমোদিত। Reg ID: {$registration->registration_id}",
            'approval',
            $registration->id
        );

        return back()->with('success', "{$registration->registration_id} approved.");
    }

    public function reject(Request $request, Registration $registration, SmsService $sms)
    {
        $request->validate(['remarks' => 'required|string|max:255']);

        $registration->update([
            'approval_status' => 'rejected',
            'admin_remarks'   => $request->remarks,
        ]);

        $this->audit('reject', $registration, ['reason' => $request->remarks]);

        $sms->send(
            $registration->contact_no,
            "দুঃখিত {$registration->name_bn}, আবেদন প্রত্যাখ্যাত। কারণ: {$request->remarks}",
            'rejection',
            $registration->id
        );

        return back()->with('success', 'Registration rejected.');
    }

    public function bulkAction(Request $request, SmsService $sms)
    {
        $request->validate([
            'ids'    => 'required|array',
            'ids.*'  => 'exists:registrations,id',
            'action' => 'required|in:approve,verify',
        ]);

        $count = 0;

        foreach (Registration::whereIn('id', $request->ids)->get() as $reg) {
            if ($request->action === 'verify' && $reg->payment_status !== 'verified') {
                $reg->update([
                    'payment_status' => 'verified',
                    'paid_at'        => $reg->paid_at ?? now(),
                    'verified_by'    => auth()->id(),
                    'verified_at'    => now(),
                ]);
                $this->audit('bulk_verify_payment', $reg);
                $sms->send($reg->contact_no,
                    "প্রিয় {$reg->name_bn}, আপনার পেমেন্ট যাচাই হয়েছে।",
                    'payment', $reg->id);
                $count++;
            } elseif (
                $request->action === 'approve'
                && $reg->payment_status === 'verified'
                && $reg->approval_status !== 'approved'
            ) {
                $reg->update([
                    'approval_status' => 'approved',
                    'approved_at'     => now(),
                    'approved_by'     => auth()->id(),
                ]);
                $this->audit('bulk_approve', $reg);
                $sms->send($reg->contact_no,
                    "অভিনন্দন {$reg->name_bn}! সদস্যপদ অনুমোদিত। Reg ID: {$reg->registration_id}",
                    'approval', $reg->id);
                $count++;
            }
        }

        return back()->with('success', "{$count} registrations processed.");
    }

    public function export()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="registrations_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Reg ID', 'Name (EN)', 'Name (BN)', 'Nickname',
                'Father', 'Mother', 'Flat', 'From', 'To',
                'Contact', 'Email', 'Occupation',
                'Fee', 'TRN', 'Payment Status', 'Approval Status', 'Created At',
            ]);

            Registration::chunk(500, function ($rows) use ($file) {
                foreach ($rows as $r) {
                    fputcsv($file, [
                        $r->registration_id, $r->name_en, $r->name_bn, $r->nickname,
                        $r->father_en, $r->mother_en, $r->flat, $r->from_year, $r->to_year,
                        $r->contact_no, $r->email, $r->occupation, $r->membership_fee,
                        $r->mfs_trn, $r->payment_status, $r->approval_status, $r->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}