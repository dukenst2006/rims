<?php

namespace Rims\Domain\Jobs\Rule;

use Illuminate\Contracts\Validation\Rule;

class SalaryComparisonRule implements Rule
{
    private $operator;
    private $salary;

    /**
     * Create a new rule instance.
     *
     * @param $salary
     * @param string $operator
     */
    public function __construct($salary, $operator = '<=')
    {
        $this->operator = $operator;
        $this->salary = $salary;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        switch ($this->operator):

            case '<=':
                return $value <= $this->salary;

            case '>=':
                return $value >= $this->salary;

        endswitch;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        switch ($this->operator):

            case '<=':
                return 'The :attribute must be less than or equal to maximum salary.';

            case '>=':
                return 'The :attribute must be greater than or equal to minimum salary.';

        endswitch;
    }
}
