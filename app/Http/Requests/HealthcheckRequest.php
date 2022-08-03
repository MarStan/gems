<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HealthcheckRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
        ];
    }
}
