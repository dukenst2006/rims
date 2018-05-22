<?php

namespace Rims\Domain\Account\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserEducationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'education_id' => [
                'required',
                Rule::exists('education', 'id')->where('usable', true)
            ],
            'name' => 'required',
            'course' => 'required|max:255',
            'speciality' => 'nullable|max:255',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date|after:started_at'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'education_id' => 'education'
        ];
    }
}
