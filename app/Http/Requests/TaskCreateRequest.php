<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class TaskCreateRequest extends FormRequest
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
            'name' => 'required|min:4',
            'priority' => 'required|numeric|min:1'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Task name is required here.",
            'name.min' => "Task name should contain at least 4 characters.",
            'priority.required' => "Priority is required here.",
            'priority.numeric' => "Only numbers allowed here.",
            'priority.min' => "Zero or negative values are not allowed.",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(redirect()->back()->withErrors($errors)->withInput());
    }
}
