<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

	public function reset(Request $request)
	{
		$valid = Validator::make($request->all(), $this->rules());

		if($valid->fails()){
			return response()->json([
				'status' => false,
				'message' => view('Frontend::components.alert', ['type' => 'danger', 'message' => $valid->errors()->first()])->render()
			]);
		}

		$response = $this->broker()->reset(
			$this->credentials($request), function ($user, $password) {
				$this->resetPassword($user, $password);
			}
		);

		$response = Password::PASSWORD_RESET
			? $this->sendResetResponse($request, $response)
			: $this->sendResetFailedResponse($request, $response);

		return response()->json([
			'status' => true,
			'message' => view('Frontend::components.alert', ['type' => 'success', 'message' => $response->getData()->message])->render(),
			'redirect' => url('login')
		]);
	}
}
