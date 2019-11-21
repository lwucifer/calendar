<?php
namespace App\Http\Requests;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
class PasswordExpiredRequest extends FormRequest
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
            'current_password' => 'required',
            'password' => 'required|different:current_password|confirmed|min:8|regex:' . User::REGEX,
        ];
    }
    public function messages()
    {
        return [
            'password.regex' => Lang::get('passwords.change_password_regex'),
            'password.min' => Lang::get('passwords.change_password_regex'),
            'password.different' => Lang::get('passwords.change_password_different'),
        ];
    }
}
