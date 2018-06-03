<?php

namespace Rims\Domain\Jobs\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequirementStoreRequest extends FormRequest
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
            'title' => 'required|max:160',
            'details' => 'required|max:1000',
        ];
    }
}
