<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\BkashService;
use App\Services\SmsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class AdminRegistrationController extends Controller
{
    /* =========================================================
     | Audit helper
     * ========================================================= */
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

    /* =========================================================
     | All list
     * ========================================================= */
    public function index(Request $request)
    {
        $query = Registration::query();

        if ($request->filled('payment'))  $query->where('payment_status', $request->payment);
        if ($request->filled('approval')) $query->where('approval_status', $request->approval);
        if ($request->filled('method'))   $query->where('payment_method', $request->method);
        if ($request->filled('id'))       $query->where('registration_id', 'like', '%' . $request->id . '%');
        if ($request->filled('building')) $query->where('building_no', 'like', '%' . $request->building . '%');
        if ($request->filled('flat'))     $query->where('flat_no', 'like', '%' . $request->flat . '%');
        if ($request->filled('contact'))  $query->where('contact_no', 'like', '%' . $request->contact . '%');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('registration_id', 'like', "%{$s}%")
                  ->orWhere('name_en', 'like', "%{$s}%")
                  ->orWhere('name_bn', 'like', "%{$s}%")
                  ->orWhere('nickname', 'like', "%{$s}%")
                  ->orWhere('father_en', 'like', "%{$s}%")
                  ->orWhere('father_bn', 'like', "%{$s}%")
                  ->orWhere('mother_en', 'like', "%{$s}%")
                  ->orWhere('mother_bn', 'like', "%{$s}%")
                  ->orWhere('contact_no', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('mfs_trn', 'like', "%{$s}%")
                  ->orWhere('bank_reference', 'like', "%{$s}%")
                  ->orWhere('cash_receipt_no', 'like', "%{$s}%");
            });
        }

        $sort = in_array($request->sort, ['registration_id','name_en','building_no','approved_at','created_at'], true)
            ? $request->sort : 'created_at';
        $dir  = in_array($request->dir, ['asc','desc'], true) ? $request->dir : 'desc';
        $query->orderBy($sort, $dir);

        $registrations = $query->paginate(20)->withQueryString();

        $pageTitle    = 'All Registrations';
        $pageSubtitle = 'Complete list with filters';
        $listKey      = 'all';

        return view('admin.registrations.list', compact('registrations', 'pageTitle', 'pageSubtitle', 'listKey'));
    }

    /* =========================================================
     | Pending list
     * ========================================================= */
    public function pending(Request $request)
    {
        $query = Registration::where('payment_status', 'pending');

        if ($request->filled('building')) $query->where('building_no', 'like', '%' . $request->building . '%');
        if ($request->filled('contact'))  $query->where('contact_no', 'like', '%' . $request->contact . '%');
        if ($request->filled('method'))   $query->where('payment_method', $request->method);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('registration_id', 'like', "%{$s}%")
                  ->orWhere('name_en', 'like', "%{$s}%")
                  ->orWhere('name_bn', 'like', "%{$s}%")
                  ->orWhere('contact_no', 'like', "%{$s}%");
            });
        }

        $registrations = $query->latest()->paginate(20)->withQueryString();

        $pageTitle    = 'Pending Verifications';
        $pageSubtitle = 'New submissions awaiting payment verification';
        $listKey      = 'pending';

        return view('admin.registrations.list', compact('registrations', 'pageTitle', 'pageSubtitle', 'listKey'));
    }

    /* =========================================================
     | Legacy redirects
     * ========================================================= */
    public function registered()
    {
        return redirect()->route('admin.registrations', ['approval' => 'approved']);
    }

    public function verified()
    {
        return redirect()->route('admin.registrations', ['payment' => 'verified']);
    }

    /* =========================================================
     | Show single
     * ========================================================= */
    public function show(Registration $registration)
    {
        return view('admin.registrations.show', compact('registration'));
    }

    /* =========================================================
     | Edit form
     * ========================================================= */
    public function edit(Registration $registration)
    {
        return view('admin.registrations.edit', compact('registration'));
    }

    /* =========================================================
     | Update
     * ========================================================= */
    public function update(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'name_en'       => 'required|string|max:255',
            'name_bn'       => 'required|string|max:255',
            'nickname'      => 'required|string|max:100',
            'father_en'     => 'required|string|max:255',
            'father_bn'     => 'required|string|max:255',
            'mother_en'     => 'required|string|max:255',
            'mother_bn'     => 'required|string|max:255',
            'building_no'   => 'required|string|max:50',
            'flat_no'       => 'required|string|max:50',
            'from_year'     => 'required|digits:4',
            'to_year'       => 'required|digits:4|gte:from_year',
            'present_add'   => 'nullable|string|max:500',
            'contact_no'    => 'required|regex:/^01[3-9]\d{8}$/',
            'occupation'    => 'nullable|string|max:150',
            'email'         => 'nullable|email|max:255',
            'mfs_no'        => 'nullable|regex:/^01[3-9]\d{8}$/',
            'mfs_trn'       => 'nullable|string|max:50',
            'photo'         => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4096',
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('photo')) {
                if ($registration->photo && Storage::disk('public')->exists($registration->photo)) {
                    Storage::disk('public')->delete($registration->photo);
                }
                $validated['photo'] = $request->file('photo')->store('photos', 'public');
            }

            $registration->update($validated);
            $this->audit('update_registration', $registration);
            DB::commit();

            return redirect()
                ->route('admin.registrations.show', $registration->id)
                ->with('success', 'Registration updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /* =========================================================
     | Delete
     * ========================================================= */
    public function destroy(Registration $registration)
    {
        $id = $registration->registration_id;
        $this->audit('delete_registration', $registration);
        $registration->delete();

        return redirect()
            ->route('admin.registrations')
            ->with('success', "Registration {$id} deleted.");
    }

    /* =========================================================
     | bKash query
     * ========================================================= */
    public function queryBkash(Registration $registration, BkashService $bkash)
    {
        if (empty($registration->mfs_trn)) {
            return back()->withErrors(['bkash' => 'No TRN submitted.']);
        }

        try {
            $result = $bkash->searchTransaction($registration->mfs_trn);

            if (!$result['success']) {
                return back()->withErrors([
                    'bkash' => 'bKash lookup failed: ' . ($result['statusMessage'] ?? 'Unknown'),
                ]);
            }

            $this->audit('query_bkash', $registration, [
                'trx_id' => $registration->mfs_trn,
                'status' => $result['transactionStatus'],
            ]);

            if ($result['transactionStatus'] === 'Completed') {
                $expected = (float) $registration->membership_fee;
                $actual   = (float) $result['amount'];

                if (abs($expected - $actual) < 0.01) {
                    if ($registration->payment_status !== 'verified') {
                        // Verify AND auto-approve
                        $registration->update([
                            'payment_status'  => 'verified',
                            'paid_at'         => $result['paymentExecuteTime'] ?? now(),
                            'verified_by'     => auth()->id(),
                            'verified_at'     => now(),
                            'approval_status' => 'approved',
                            'approved_at'     => now(),
                            'approved_by'     => auth()->id(),
                            'admin_remarks'   => 'Auto-verified & approved via bKash API',
                        ]);
                        return back()->with('success', "bKash confirmed ৳{$actual}. Auto-verified & approved.");
                    }
                    return back()->with('success', "bKash: Completed ৳{$actual} (already verified).");
                }
                return back()->withErrors(['bkash' => "Amount mismatch: ৳{$actual} vs ৳{$expected}."]);
            }

            return back()->with('success', "bKash status: {$result['transactionStatus']}");
        } catch (\Throwable $e) {
            return back()->withErrors(['bkash' => 'bKash error: ' . $e->getMessage()]);
        }
    }

    /* =========================================================
     | Verify Payment — AUTO-APPROVES membership in same step
     * ========================================================= */
    public function verifyPayment(Registration $registration, SmsService $sms)
    {
        if ($registration->payment_status === 'verified') {
            return back()->with('success', 'Payment already verified.');
        }

        // Verify payment AND auto-approve membership in one action
        $registration->update([
            'payment_status'  => 'verified',
            'paid_at'         => $registration->paid_at ?? now(),
            'verified_by'     => auth()->id(),
            'verified_at'     => now(),

            'approval_status' => 'approved',
            'approved_at'     => now(),
            'approved_by'     => auth()->id(),
        ]);

        $this->audit('verify_and_approve', $registration);

        $sms->sendTemplate(
            $registration->contact_no,
            'approved',
            [
                'name'   => $registration->name_bn,
                'reg_id' => $registration->registration_id,
            ],
            'approval',
            $registration->id,
            "অভিনন্দন {$registration->name_bn}! সদস্যপদ অনুমোদিত। Reg ID: {$registration->registration_id}"
        );

        return back()->with('success', "✓ Payment verified and membership approved for {$registration->registration_id}.");
    }

    /* =========================================================
     | Reject Payment
     * ========================================================= */
    public function rejectPayment(Request $request, Registration $registration, SmsService $sms)
    {
        $request->validate(['remarks' => 'required|string|max:255']);

        $registration->update([
            'payment_status' => 'rejected',
            'admin_remarks'  => $request->remarks,
        ]);

        $this->audit('reject_payment', $registration, ['reason' => $request->remarks]);

        $sms->sendTemplate(
            $registration->contact_no,
            'payment_rejected',
            [
                'name'   => $registration->name_bn,
                'reg_id' => $registration->registration_id,
                'reason' => $request->remarks,
            ],
            'rejection',
            $registration->id,
            "দুঃখিত {$registration->name_bn}, পেমেন্ট যাচাই ব্যর্থ। কারণ: {$request->remarks}"
        );

        return back()->with('success', 'Payment rejected.');
    }

    /* =========================================================
     | Approve (manual — fallback if auto-approve skipped)
     * ========================================================= */
    public function approve(Registration $registration, SmsService $sms)
    {
        if ($registration->payment_status !== 'verified') {
            return back()->withErrors(['error' => 'Payment must be verified first.']);
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

        $sms->sendTemplate(
            $registration->contact_no,
            'approved',
            [
                'name'   => $registration->name_bn,
                'reg_id' => $registration->registration_id,
            ],
            'approval',
            $registration->id,
            "অভিনন্দন {$registration->name_bn}! সদস্যপদ অনুমোদিত। Reg ID: {$registration->registration_id}"
        );

        return back()->with('success', "🎖 {$registration->registration_id} approved.");
    }

    /* =========================================================
     | Reject Application
     * ========================================================= */
    public function reject(Request $request, Registration $registration, SmsService $sms)
    {
        $request->validate(['remarks' => 'required|string|max:255']);

        $registration->update([
            'approval_status' => 'rejected',
            'admin_remarks'   => $request->remarks,
        ]);

        $this->audit('reject', $registration, ['reason' => $request->remarks]);

        $sms->sendTemplate(
            $registration->contact_no,
            'rejected',
            [
                'name'    => $registration->name_bn,
                'reg_id'  => $registration->registration_id,
                'reason'  => $request->remarks,
                'contact' => '01721308219',
            ],
            'rejection',
            $registration->id,
            "দুঃখিত {$registration->name_bn}, আবেদন প্রত্যাখ্যাত। কারণ: {$request->remarks}"
        );

        return back()->with('success', 'Registration rejected.');
    }

    /* =========================================================
     | Bulk Action — Verify also auto-approves
     * ========================================================= */
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
                // Verify AND auto-approve
                $reg->update([
                    'payment_status'  => 'verified',
                    'paid_at'         => $reg->paid_at ?? now(),
                    'verified_by'     => auth()->id(),
                    'verified_at'     => now(),
                    'approval_status' => 'approved',
                    'approved_at'     => now(),
                    'approved_by'     => auth()->id(),
                ]);
                $this->audit('bulk_verify_and_approve', $reg);
                $sms->sendTemplate($reg->contact_no, 'approved', [
                    'name'   => $reg->name_bn,
                    'reg_id' => $reg->registration_id,
                ], 'approval', $reg->id);
                $count++;
            } elseif ($request->action === 'approve' && $reg->payment_status === 'verified' && $reg->approval_status !== 'approved') {
                $reg->update([
                    'approval_status' => 'approved',
                    'approved_at'     => now(),
                    'approved_by'     => auth()->id(),
                ]);
                $this->audit('bulk_approve', $reg);
                $sms->sendTemplate($reg->contact_no, 'approved', [
                    'name'   => $reg->name_bn,
                    'reg_id' => $reg->registration_id,
                ], 'approval', $reg->id);
                $count++;
            }
        }

        return back()->with('success', "{$count} registrations processed.");
    }

    /* =========================================================
     | CSV Export
     * ========================================================= */
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
                'Father', 'Mother', 'Building No', 'Flat No',
                'From', 'To', 'Contact', 'Email', 'Occupation',
                'Fee', 'Method', 'Reference', 'Payment Status', 'Approval Status', 'Created At',
            ]);

            Registration::chunk(500, function ($rows) use ($file) {
                foreach ($rows as $r) {
                    fputcsv($file, [
                        $r->registration_id, $r->name_en, $r->name_bn, $r->nickname,
                        $r->father_en, $r->mother_en, $r->building_no, $r->flat_no,
                        $r->from_year, $r->to_year, $r->contact_no, $r->email,
                        $r->occupation, $r->membership_fee,
                        $r->payment_method_label, $r->transaction_reference,
                        $r->payment_status, $r->approval_status, $r->created_at,
                    ]);
                }
            });

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /* =========================================================
     | Print
     * ========================================================= */
    public function print(Request $request)
    {
        $query = Registration::query();

        if ($request->filled('id'))       $query->where('registration_id', 'like', '%' . $request->id . '%');
        if ($request->filled('name'))     $query->where('name_en', 'like', '%' . $request->name . '%');
        if ($request->filled('building')) $query->where('building_no', 'like', '%' . $request->building . '%');
        if ($request->filled('flat'))     $query->where('flat_no', 'like', '%' . $request->flat . '%');
        if ($request->filled('contact'))  $query->where('contact_no', 'like', '%' . $request->contact . '%');
        if ($request->filled('method'))   $query->where('payment_method', $request->method);
        if ($request->filled('payment'))  $query->where('payment_status', $request->payment);
        if ($request->filled('approval')) $query->where('approval_status', $request->approval);

        $registrations = $query->latest()->get();

        return view('admin.registrations.print', compact('registrations'));
    }

    /* =========================================================
     | Server-Side PDF
     * ========================================================= */
    public function exportPdf(Request $request)
    {
        $query = Registration::query();

        if ($request->filled('id'))       $query->where('registration_id', 'like', '%' . $request->id . '%');
        if ($request->filled('name'))     $query->where('name_en', 'like', '%' . $request->name . '%');
        if ($request->filled('building')) $query->where('building_no', 'like', '%' . $request->building . '%');
        if ($request->filled('flat'))     $query->where('flat_no', 'like', '%' . $request->flat . '%');
        if ($request->filled('contact'))  $query->where('contact_no', 'like', '%' . $request->contact . '%');
        if ($request->filled('method'))   $query->where('payment_method', $request->method);
        if ($request->filled('payment'))  $query->where('payment_status', $request->payment);
        if ($request->filled('approval')) $query->where('approval_status', $request->approval);

        $registrations = $query->latest()->get();

        $pdf = Pdf::loadView('admin.registrations.pdf', compact('registrations'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isRemoteEnabled' => true,
                'defaultFont'     => 'sans-serif',
            ]);

        return $pdf->download('registrations_' . date('Ymd_His') . '.pdf');
    }
}