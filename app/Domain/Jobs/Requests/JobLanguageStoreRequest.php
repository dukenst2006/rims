<?php

namespace Rims\Domain\Jobs\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobLanguageStoreRequest extends FormRequest
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
            'language_id' => [
                'required',
                Rule::exists('languages', 'id')->where('usable', true)
            ],
            'level_id' => [
                'required',
                Rule::exists('levels', 'id')->where('usable', true)
            ],
            'details' => 'nullable|max:300'
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
            'language_id' => 'language',
            'level_id' => 'level'
        ];
    }
}
