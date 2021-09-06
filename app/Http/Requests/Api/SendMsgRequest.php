<?php


namespace App\Http\Requests\Api;


use App\Http\Requests\BaseRequest;

class SendMsgRequest extends BaseRequest
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
            'phone' => ['required', 'regex:/^1[345789]\d{9}$/'],
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => '手机号码必填',
            'phone.regex' => '手机号码格式错误',
        ];
    }


}
