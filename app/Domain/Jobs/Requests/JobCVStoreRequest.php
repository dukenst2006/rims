<?php

namespace Rims\Domain\Jobs\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobCVStoreRequest extends FormRequest
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
            'file' => 'required|file|mimes:pdf|max:5000'
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
            'file' => 'CV'
        ];
    }

    public function messages()
    {
        return [
            'file.max' => 'The :attribute may not be greater than 5 megabytes (MB).'
        ];
    }
}
