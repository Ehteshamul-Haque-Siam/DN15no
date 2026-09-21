<?php

namespace App\Rules;

use App\Models\Registration;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueResident implements ValidationRule
{
    public function __construct(
        private string $nameEn,
        private string $fatherEn
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = Registration::where('name_en', 'like', trim($this->nameEn))
            ->where('father_en', 'like', trim($this->fatherEn))
            ->exists();

        if ($exists) {
            $fail('এই নাম ও পিতার নাম দিয়ে ইতিমধ্যে রেজিস্ট্রেশন করা হয়েছে। সহায়তার জন্য কল করুন: 01721308219');
        }
    }
}