<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'name'               => ['required', 'max:50'],
            'national_id'        => ['required', 'digits:11', 'numeric', 'unique:employees,national_id'],
            'video_introduction' => ['required', 'string'],
            'personal_pics'      => ['required', 'string'],
        ];
    }
}
