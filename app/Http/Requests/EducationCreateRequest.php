<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EducationCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
          'start_date' => ['string', 'max:255'],
          'finish_date' => ['string', 'max:255'],
          'study_name' => ['string', 'max:255'],
          'study_title' => ['string', 'max:255'],
          'college' => ['string', 'max:255'],
          'description' => ['string', 'max:500']
        ];
    }
}
