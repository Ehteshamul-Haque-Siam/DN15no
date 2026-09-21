<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BkashCredential;
use Illuminate\Http\Request;

class BkashSettingController extends Controller
{
    public function index()
    {
        $credential = BkashCredential::active();
        return view('admin.bkash.index', compact('credential'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'app_key'         => 'required|string',
            'app_secret'      => 'required|string',
            'username'        => 'required|string',
            'password'        => 'required|string',
            'merchant_number' => 'nullable|string|max:20',
            'environment'     => 'required|in:sandbox,live',
        ]);

        BkashCredential::query()->update(['is_active' => false]);

        BkashCredential::create(array_merge($data, ['is_active' => true]));

        return back()->with('success', 'bKash credentials saved.');
    }

    public function testConnection()
    {
        try {
            $service = new \App\Services\BkashService();
            $token = $service->grantToken();
            return back()->with('success', 'Connection OK. Token: ' . substr($token, 0, 20) . '...');
        } catch (\Throwable $e) {
            return back()->withErrors(['connection' => $e->getMessage()]);
        }
    }
}