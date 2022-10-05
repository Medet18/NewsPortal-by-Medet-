<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Subadmin;
use Illuminate\Support\Facades\Hash;

class PasswordSubadmin implements Rule
{
    /**
     * Create a new rule instance.
     *s
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $user = Subadmin::where('email', request()->email)->first();
        if(Hash::check($value, $user->password)) {
            return true;
        }
//        else{
//            return false;
//        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('word.incorrect_password');
    }
}
