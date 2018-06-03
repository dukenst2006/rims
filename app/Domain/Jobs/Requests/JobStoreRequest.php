<?php

namespace Rims\Domain\Jobs\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Rims\Domain\Jobs\Rule\SalaryComparisonRule;

class JobStoreRequest extends FormRequest
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
            'title' => 'required|max:255',
            'overview_short' => 'required|max:300',
            'overview' => 'required',
            'applicants' => 'required|numeric',
            'area_id' => [
                'required',
                Rule::exists('areas', 'id')->where('usable', true)
            ],
            'on_location' => 'required|boolean',
            'type' => [
                'required',
                Rule::in('full-time', 'part-time'),
            ],
            'salary_min' => [
                'required',
                'numeric',
                'min:0',
                new SalaryComparisonRule($this->input('salary_max')),
            ],
            'salary_max' => [
                'required',
                'numeric',
                'min:0',
                new SalaryComparisonRule($this->input('salary_min'), '>='),
            ],
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
            'area_id' => 'job location',
            'type' => 'job type',
            'on_location' => 'job site',
            'salary_min' => 'minimum salary',
            'salary_max' => 'maximum salary',
        ];
    }
}
