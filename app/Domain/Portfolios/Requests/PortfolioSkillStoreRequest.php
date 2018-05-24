<?php

namespace Rims\Domain\Portfolios\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PortfolioSkillStoreRequest extends FormRequest
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
            'skill_id' => [
                'required',
                Rule::exists('skills','id')->where('usable', true)
            ],
            'level_id' => [
                'required',
                Rule::exists('levels','id')->where('usable', true)
            ]
        ];
    }
}
