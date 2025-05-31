<?php

    namespace App\Http\Requests; // Or App\Http\Requests\Auth

    use App\Models\User;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Validation\Rule;

    class ProfileUpdateRequest extends FormRequest
    {
        /**
         * Get the validation rules that apply to the request.
         *
         * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
         */
        public function rules(): array
        {
            return [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
                'github_username' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/'], // Basic GitHub username regex
                'bio' => ['nullable', 'string', 'max:1000'],
                'skills' => ['nullable', 'string', 'max:1000'], // Validates as a string
            ];
        }

        /**
         * Get the validated data from the request.
         *
         * We will let the parent method handle returning validated data.
         * The 'skills' attribute will be passed as a string (or null) to the controller.
         * The User model's mutator will handle converting this string to an array.
         *
         * @param  string|null  $key
         * @param  mixed  $default
         * @return mixed
         */
        // public function validated($key = null, $default = null)
        // {
        //     // By removing this override, we use the parent's validated() method.
        //     // It will return 'skills' as a string as per the rules() method.
        //     return parent::validated($key, $default);
        // }
    }
