<?php

namespace App\Http\Requests;

use App\Rules\UniqueResident;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'contact_no'      => preg_replace('/\D/', '', (string) $this->contact_no),
            'mfs_no'          => $this->mfs_no ? preg_replace('/\D/', '', (string) $this->mfs_no) : null,
            'mfs_trn'         => $this->mfs_trn ? strtoupper(trim((string) $this->mfs_trn)) : null,
            'bank_reference'  => $this->bank_reference ? strtoupper(trim((string) $this->bank_reference)) : null,
            'cash_receipt_no' => $this->cash_receipt_no ? strtoupper(trim((string) $this->cash_receipt_no)) : null,
            'building_no'     => trim((string) $this->building_no),
            'flat_no'         => trim((string) $this->flat_no),
            'payment_method'  => $this->input('payment_method', 'bkash'),
        ]);
    }

    public function rules(): array
    {
        $method = $this->input('payment_method', 'bkash');

        return [
            'name_en' => [
                'required', 'string', 'max:255',
                'regex:/^[A-Za-z\s\.\-]+$/',
                new UniqueResident($this->name_en ?? '', $this->father_en ?? ''),
            ],
            'name_bn' => [
                'required', 'string', 'max:255',
                'regex:/^[\p{Bengali}\s\.\-]+$/u',
            ],
            'nickname'  => 'required|string|max:100',
            'father_en' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\.\-]+$/'],
            'father_bn' => ['required', 'string', 'max:255', 'regex:/^[\p{Bengali}\s\.\-]+$/u'],
            'mother_en' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\.\-]+$/'],
            'mother_bn' => ['required', 'string', 'max:255', 'regex:/^[\p{Bengali}\s\.\-]+$/u'],

            'avatar' => [
                'nullable', 'image', 'mimes:jpeg,jpg,png,webp',
                'max:4096',
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            ],

            'building_no' => 'required|string|max:50',
            'flat_no'     => 'required|string|max:50',

            'from_year'   => 'required|digits:4|integer|min:1950|max:' . (date('Y') + 1),
            'to_year'     => 'required|digits:4|integer|min:1950|max:' . (date('Y') + 1) . '|gte:from_year',
            'present_add' => 'nullable|string|max:500',
            'occupation'  => 'nullable|string|max:150',
            'email'       => 'nullable|email|max:255',

            'contact_no' => [
                'required',
                'regex:/^01[3-9]\d{8}$/',
                Rule::unique('registrations', 'contact_no')->whereNull('deleted_at'),
            ],

            // Payment method
            'payment_method' => 'required|in:bkash,bank,cash',

            // bKash
            'mfs_no' => $method === 'bkash'
                ? 'required|regex:/^01[3-9]\d{8}$/'
                : 'nullable|regex:/^01[3-9]\d{8}$/',
            'mfs_trn' => $method === 'bkash'
                ? ['required', 'string', 'max:50', Rule::unique('registrations', 'mfs_trn')->whereNull('deleted_at')]
                : 'nullable|string|max:50',

            // Bank
            'bank_id' => $method === 'bank'
                ? 'required|exists:banks,id'
                : 'nullable',
            'bank_reference' => $method === 'bank'
                ? ['required', 'string', 'max:100', Rule::unique('registrations', 'bank_reference')->whereNull('deleted_at')]
                : 'nullable|string|max:100',
            'paid_to_bank' => $method === 'bank'
                ? 'required|string|max:100'
                : 'nullable|string|max:100',
            'bank_slip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:8192',

            // Cash
            'cash_receipt_no' => $method === 'cash'
                ? 'required|string|max:100'
                : 'nullable|string|max:100',
            'cash_receipt' => $method === 'cash'
                ? 'required|file|mimes:jpg,jpeg,png,pdf|max:8192'
                : 'nullable|file|mimes:jpg,jpeg,png,pdf|max:8192',
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.regex'     => 'English name may contain only English letters.',
            'name_bn.regex'     => 'বাংলা নামে শুধু বাংলা অক্ষর ব্যবহার করুন।',
            'contact_no.regex'  => 'সঠিক মোবাইল নাম্বার দিন (01XXXXXXXXX)।',
            'contact_no.unique' => 'এই মোবাইল নাম্বার দিয়ে ইতিমধ্যে রেজিস্ট্রেশন করা হয়েছে।',
            'mfs_no.regex'      => 'সঠিক বিকাশ নাম্বার দিন।',
            'mfs_trn.regex'     => 'Transaction ID-তে শুধু বড় হাতের অক্ষর ও সংখ্যা।',
            'mfs_trn.unique'    => 'এই Transaction ID আগেই ব্যবহৃত হয়েছে।',
            'bank_id.required'         => 'একটি ব্যাংক নির্বাচন করুন।',
            'bank_reference.required'  => 'ব্যাংক ট্রান্সফার রেফারেন্স দিন।',
            'bank_reference.unique'    => 'এই রেফারেন্স আগেই ব্যবহৃত হয়েছে।',
            'paid_to_bank.required'    => 'আপনার অ্যাকাউন্ট নাম্বার দিন।',
            'bank_slip.max'            => 'স্লিপের সাইজ সর্বোচ্চ ৮ MB।',
            'bank_slip.mimes'          => 'শুধু JPG, PNG, অথবা PDF আপলোড করুন।',
            'cash_receipt_no.required' => 'ক্যাশ রিসিট নাম্বার দিন।',
            'cash_receipt.required'    => 'ক্যাশ রিসিট / স্লিপ আপলোড করুন।',
            'cash_receipt.max'         => 'রিসিটের সাইজ সর্বোচ্চ ৮ MB।',
            'cash_receipt.mimes'       => 'শুধু JPG, PNG, অথবা PDF আপলোড করুন।',
            'to_year.gte'              => 'শেষ বছর শুরুর বছরের চেয়ে ছোট হতে পারবে না।',
            'avatar.max'               => 'ছবির সাইজ সর্বোচ্চ ৪ MB।',
            'avatar.dimensions'        => 'ছবির মাপ ১০০×১০০ থেকে ৪০০০×৪০০০ পিক্সেলের মধ্যে হতে হবে।',
            'building_no.required'     => 'বিল্ডিং নম্বর দিন।',
            'flat_no.required'         => 'ফ্ল্যাট নম্বর দিন।',
        ];
    }
}