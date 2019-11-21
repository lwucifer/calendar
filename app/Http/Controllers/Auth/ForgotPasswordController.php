<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Validation\Concerns\ValidatesAttributes;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
    use ValidatesAttributes;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $request->validate(
            ['userId' => 'required']
        );

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        if ($request->has('userId') &&$request->has('email')) {

            $userId = $request->input('userId');
            $email = $request->input('email');
            $test = User::where([['username','=',$userId] , ['email','=' ,$email]])->count();

            if ($test > 0){
                $response = $this->broker()->sendResetLink(
                    $request->only('email')
                );
                return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
            }

                return back()->with('fail', trans('auth.failed'));
            //return $this->sendResetLinkFailed($request);

        }
        return back()->with('fail', trans('auth.failed'));
    }


    protected function validateEmail(Request $request)
    {

        $request->validate(['email' => 'required|email'],[
            'email.required' => trans('auth.failed'),
        ]);
    }

//    protected function validateUserId(Request $request){
//
//        $request->validate(['userId' => 'required|userId|min:3']);
//
//    }


    /**
     * Validate that an attribute is a valid e-mail address.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return back()->with('status', trans($response));

    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
            ->withErrors(['email' => trans($response)]);
    }

    protected function sendResetLinkFailed(Request $request)
    {
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['fail' => trans('auth.failed')]);
    }



    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    public function __construct()
    {
        $this->middleware('guest');
    }
}
