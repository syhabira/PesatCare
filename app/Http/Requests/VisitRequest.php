<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitRequest extends FormRequest
{
    public function authorize(): bool { return (bool) $this->user()?->isAdmin(); }

    public function rules(): array
    {
        return [
            'patient_type' => ['required', 'in:murid,guru'],
            'student_id' => ['required_if:patient_type,murid', 'nullable', Rule::exists('students', 'id')->where('is_active', true)],
            'teacher_id' => ['required_if:patient_type,guru', 'nullable', Rule::exists('teachers', 'id')->where('is_active', true)],
            'visited_at' => ['nullable', 'date', 'before_or_equal:' . now()->addMinutes(10)->toDateTimeString()],
            'temperature' => ['nullable', 'numeric', 'between:30,45'],
            'systolic' => ['nullable', 'integer', 'between:40,300', 'required_with:diastolic'],
            'diastolic' => ['nullable', 'integer', 'between:20,200', 'required_with:systolic', 'lt:systolic'],
            'pulse' => ['nullable', 'integer', 'between:20,250'],
            'complaint' => ['required', 'string', 'max:1000'],
            'anamnesis' => ['nullable', 'string', 'max:2000'],
            'action' => ['nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'disease_ids' => ['required', 'array', 'min:1'],
            'disease_ids.*' => ['integer', 'distinct', Rule::exists('diseases', 'id')->where('is_active', true)],
            'medicines' => ['nullable', 'array'],
            'medicines.*.medicine_id' => ['required', 'integer', 'distinct', Rule::exists('medicines', 'id')->where('is_active', true)],
            'medicines.*.quantity' => ['required', 'integer', 'min:1', 'max:1000'],
            'medicines.*.notes' => ['nullable', 'string', 'max:255'],
            'left_at' => ['nullable', 'date', 'after_or_equal:visited_at'],
            'follow_up_status' => ['nullable', 'string', 'max:100'],
        ];
    }
}