<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::orderBy('display_order')->get();
        return view('admin.banks.index', compact('banks'));
    }

    public function create()
    {
        if (Bank::count() >= 2) {
            return redirect()->route('admin.banks.index')
                ->withErrors(['error' => 'সর্বোচ্চ ২টি ব্যাংক অ্যাকাউন্ট যোগ করা যাবে।']);
        }
        return view('admin.banks.create');
    }

    public function store(Request $request)
    {
        if (Bank::count() >= 2) {
            return back()->withErrors(['error' => 'সর্বোচ্চ ২টি ব্যাংক অ্যাকাউন্ট যোগ করা যাবে।']);
        }

        $data = $request->validate([
            'bank_name'      => 'required|string|max:150',
            'account_name'   => 'required|string|max:150',
            'account_number' => 'required|string|max:100',
            'branch'         => 'nullable|string|max:150',
            'routing_number' => 'nullable|string|max:50',
            'swift_code'     => 'nullable|string|max:50',
            'instructions'   => 'nullable|string|max:500',
            'is_active'      => 'boolean',
        ]);

        $data['is_active']     = $request->has('is_active') ? true : false;
        $data['display_order'] = Bank::count() + 1;

        Bank::create($data);

        return redirect()->route('admin.banks.index')->with('success', 'ব্যাংক যোগ করা হয়েছে।');
    }

    public function edit(Bank $bank)
    {
        return view('admin.banks.edit', compact('bank'));
    }

    public function update(Request $request, Bank $bank)
    {
        $data = $request->validate([
            'bank_name'      => 'required|string|max:150',
            'account_name'   => 'required|string|max:150',
            'account_number' => 'required|string|max:100',
            'branch'         => 'nullable|string|max:150',
            'routing_number' => 'nullable|string|max:50',
            'swift_code'     => 'nullable|string|max:50',
            'instructions'   => 'nullable|string|max:500',
            'is_active'      => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active') ? true : false;

        $bank->update($data);

        return redirect()->route('admin.banks.index')->with('success', 'আপডেট হয়েছে।');
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();
        return redirect()->route('admin.banks.index')->with('success', 'ব্যাংক মুছে ফেলা হয়েছে।');
    }
}