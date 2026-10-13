<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ScreeningRequest extends FormRequest
{
    public function authorize(): bool { return (bool) $this->user()?->isAdmin(); }

    // Catatan: field `bmi` sengaja tidak ada; BMI selalu dihitung server.
    public function rules(): array
    {
        return [
            'student_id' => ['required', Rule::exists('students', 'id')->where('is_active', true)],
            'screened_at' => ['required', 'date', 'before_or_equal:today'],
            'height_cm' => ['required', 'numeric', 'between:80,250'],
            'weight_kg' => ['required', 'numeric', 'between:15,300'],
            'waist_cm' => ['nullable', 'numeric', 'between:30,200'],
            'systolic' => ['nullable', 'integer', 'between:40,300', 'required_with:diastolic'],
            'diastolic' => ['nullable', 'integer', 'between:20,200', 'required_with:systolic', 'lt:systolic'],
        ];
    }
}