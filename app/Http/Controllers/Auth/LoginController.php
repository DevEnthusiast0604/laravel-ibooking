<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout( Request $request ) {
    	Auth::logout();
	    return redirect('/');
    }

    public function login( Request $request ) {
	   $valid = Validator::make($request->all(), [
		    'email' => ['required', 'string', 'email'],
		    'password' => ['required'],
	    ]);

	    if($valid->fails()){
		    return response()->json([
	    		'status' => false,
			    'message' => view('Frontend::components.alert', ['type' => 'danger', 'message' => $valid->errors()->first()])->render(),
		    ]);
	    }

	    $isfr = $request->post('isfr', '');
	    if ( Auth::attempt( [
		    'email'    => $request->input('email'),
		    'password' => $request->input('password')
	    ], true ) ) {
	        $respond = [
                'status'   => true,
                'message'  => view( 'Frontend::components.alert', [
                    'type'    => 'success',
                    'message' => __( 'Login successfully' )
                ] )->render()
            ];
	        if($isfr){
                $respond['reload'] = true;
            }else{
                $respond['redirect'] = dashboard_url( '' );
            }
		    return response()->json($respond);
	    } else {
		    return response()->json( [
			    'status'  => false,
			    'message' => view( 'Frontend::components.alert', [ 'type'    => 'danger', 'message' => __( 'Login failed. Please check your credentials again.' )
			    ] )->render(),
		    ] );
	    }
    }

	public function authenticate(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard');
        }
        return false;
    }
}
