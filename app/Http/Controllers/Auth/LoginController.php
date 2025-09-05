<?php

namespace App\Http\Controllers\Auth;

use App\Form\UserExam;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Exam_acc;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

    //protected $redirectTo = '/home';
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

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'status' => '0'))) {
            if (auth()->user()->is_admin == 1 || auth()->user()->is_admin == 3) {
                return redirect('/admin');
            } else {
                return redirect('/user');
            }
        } else {
            $this->validate($request, [
                'login-error' => 'required',
            ]);
            // return redirect('/login')
            //     ->with('error', 'メールアドレスかパスワードが間違ってます。管理者にお問い合わせください。');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/login');
    }
}
