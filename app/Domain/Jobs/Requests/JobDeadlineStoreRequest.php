<?php

namespace Rims\Domain\Jobs\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobDeadlineStoreRequest extends FormRequest
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
            'closed_at' => 'required|date'
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
            'closed_at' => 'close at'
        ];
    }
}
