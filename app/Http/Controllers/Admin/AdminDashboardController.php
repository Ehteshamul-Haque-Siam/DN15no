<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'    => Registration::count(),
            'pending'  => Registration::where('approval_status', 'pending')->count(),
            'paid'     => Registration::where('payment_status', 'paid')->count(),
            'verified' => Registration::where('payment_status', 'verified')->count(),
            'approved' => Registration::where('approval_status', 'approved')->count(),
            'rejected' => Registration::where('approval_status', 'rejected')->count(),
            'revenue'  => Registration::where('payment_status', 'verified')->sum('membership_fee'),
        ];

        $recent = Registration::latest()->take(5)->get();

        $pendingVerification = Registration::where('payment_status', 'pending')
            ->whereNotNull('mfs_trn')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent', 'pendingVerification'));
    }
}