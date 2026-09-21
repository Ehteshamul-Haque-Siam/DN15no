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
        // Normalize phone numbers and TRN before validation
        $this->merge([
            'contact_no' => preg_replace('/\D/', '', (string) $this->contact_no),
            'mfs_no'     => preg_replace('/\D/', '', (string) $this->mfs_no),
            'mfs_trn'    => strtoupper(trim((string) $this->mfs_trn)),
        ]);
    }

    public function rules(): array
    {
        return [
            'name_en' => [
                'required',
                'string',
                'max:255',
                new UniqueResident($this->name_en ?? '', $this->father_en ?? ''),
            ],
            'name_bn'     => 'required|string|max:255',
            'nickname'    => 'required|string|max:100',
            'father_en'   => 'required|string|max:255',
            'father_bn'   => 'required|string|max:255',
            'mother_en'   => 'required|string|max:255',
            'mother_bn'   => 'required|string|max:255',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'flat'        => 'required|string|max:100',
            'from_year'   => 'required|digits:4',
            'to_year'     => 'required|digits:4|gte:from_year',
            'present_add' => 'nullable|string|max:500',
            'occupation'  => 'nullable|string|max:150',
            'email'       => 'nullable|email|max:255',

            // ===== Duplicate prevention =====
            'contact_no' => [
                'required',
                'regex:/^01[3-9]\d{8}$/',
                Rule::unique('registrations', 'contact_no')->whereNull('deleted_at'),
            ],
            'mfs_no' => 'required|regex:/^01[3-9]\d{8}$/',
            'mfs_trn' => [
                'required',
                'string',
                'max:50',
                Rule::unique('registrations', 'mfs_trn')->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'contact_no.regex'  => 'সঠিক মোবাইল নাম্বার দিন (01XXXXXXXXX)।',
            'contact_no.unique' => 'এই মোবাইল নাম্বার দিয়ে ইতিমধ্যে রেজিস্ট্রেশন করা হয়েছে। প্রতিটি নাম্বার একবারই ব্যবহার করা যাবে।',
            'mfs_no.regex'      => 'সঠিক বিকাশ নাম্বার দিন (01XXXXXXXXX)।',
            'mfs_trn.unique'    => 'এই Transaction ID আগেই ব্যবহৃত হয়েছে। প্রতিটি পেমেন্ট একবারই জমা দেওয়া যাবে।',
            'to_year.gte'       => 'শেষ বছর শুরুর বছরের চেয়ে ছোট হতে পারবে না।',
            'avatar.max'        => 'ছবির সাইজ সর্বোচ্চ ৪ MB হতে পারবে।',
        ];
    }
}