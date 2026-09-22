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
            'contact_no' => preg_replace('/\D/', '', (string) $this->contact_no),
            'mfs_no'     => preg_replace('/\D/', '', (string) $this->mfs_no),
            'mfs_trn'    => strtoupper(trim((string) $this->mfs_trn)),
        ]);
    }

    public function rules(): array
    {
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
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:4096',
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            ],

            'flat'        => 'required|string|max:100',
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
            'mfs_no' => 'required|regex:/^01[3-9]\d{8}$/',
            'mfs_trn' => [
                'required', 'string', 'max:50',
                'regex:/^[A-Z0-9]+$/',
                Rule::unique('registrations', 'mfs_trn')->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name_en.regex'     => 'English name should contain only English letters, spaces, dots and hyphens.',
            'name_bn.regex'     => 'বাংলা নামে শুধু বাংলা অক্ষর, স্পেস, ডট ও হাইফেন ব্যবহার করুন।',
            'father_en.regex'   => 'Father\'s English name should contain only English letters.',
            'father_bn.regex'   => 'পিতার বাংলা নামে শুধু বাংলা অক্ষর ব্যবহার করুন।',
            'mother_en.regex'   => 'Mother\'s English name should contain only English letters.',
            'mother_bn.regex'   => 'মাতার বাংলা নামে শুধু বাংলা অক্ষর ব্যবহার করুন।',
            'contact_no.regex'  => 'সঠিক মোবাইল নাম্বার দিন (01XXXXXXXXX)।',
            'contact_no.unique' => 'এই মোবাইল নাম্বার দিয়ে ইতিমধ্যে রেজিস্ট্রেশন করা হয়েছে।',
            'mfs_no.regex'      => 'সঠিক বিকাশ নাম্বার দিন (01XXXXXXXXX)।',
            'mfs_trn.regex'     => 'Transaction ID-তে শুধু বড় হাতের অক্ষর ও সংখ্যা ব্যবহার করুন।',
            'mfs_trn.unique'    => 'এই Transaction ID আগেই ব্যবহৃত হয়েছে।',
            'to_year.gte'       => 'শেষ বছর শুরুর বছরের চেয়ে ছোট হতে পারবে না।',
            'avatar.max'        => 'ছবির সাইজ সর্বোচ্চ ৪ MB।',
            'avatar.dimensions' => 'ছবির মাপ ১০০×১০০ থেকে ৪০০০×৪০০০ পিক্সেলের মধ্যে হতে হবে।',
        ];
    }
}