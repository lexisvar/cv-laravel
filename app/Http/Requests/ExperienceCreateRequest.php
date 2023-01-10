<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
          'start_date' => ['date'],
          'finish_date' => ['date', 'nullable'],
          'occupation' => ['string', 'max:255'],
          'company' => ['string', 'max:255'],
          'description' => ['string']
        ];
    }
}
