<?php

namespace App\Traits;

/**
 *  This trait to run the authorize after a valid validation
 */
trait AuthorizesAfterValidation
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
     *  Set the logic after the validation
     * 
     * @param $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!$validator->failed() && !$this->authorizeValidated()) {
                $this->failedAuthorization();
            }
        });
    }

    /**
     *  Define the abstract method to run the logic.
     * 
     * @return void
     */
    abstract public function authorizeValidated();
}
