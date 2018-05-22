<?php

namespace Rims\Domain\Account\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvatarStoreRequest extends FormRequest
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
            'image' => 'required|image|dimensions:min_width=240,min_height=180'
        ];
    }
}
