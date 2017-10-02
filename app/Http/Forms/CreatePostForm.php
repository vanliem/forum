<?php

namespace App\Http\Forms;

use App\Reply;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SpamFree;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\ThrottleException;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new Reply);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => [
                'required',
                new SpamFree
            ]
        ];
    }

    protected function failedAuthorization()
    {
        throw new ThrottleException(
            'You are replying too frequently, Please take a break!'
        );
    }
}
