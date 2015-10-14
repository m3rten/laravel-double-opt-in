<?php

namespace M3rten\DoubleOptIn;

use App\User;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Mail;

trait RegisterAndActivateUsers
{
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }

        # create user with active = 0
        $user = $this->create($request->all());
        $this->message(trans('doubleoptin::activation.created', ['name' => $user->name]), 'success');

        $this->createToken($user);
        $this->sendActivationMail($user);

        return redirect($this->loginPath);
    }

    /**
     * Send activation link for submitted E-Mail-Address
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postActivation(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255',
        ]);

        $user = User::where('email', $request->get('email'))
            ->where('active', 0)
            ->first();

        if (!$user) {
            $this->message(trans('doubleoptin::activation.not_found'), 'danger');
            return back()->withInput();
        }

        $this->createToken($user);
        $this->sendActivationMail($user);

        return back();
        #return redirect($this->loginPath);
    }

    /**
     * Show a form to request a new activation token.
     *
     * @return \Illuminate\Http\Response
     */
    public function editActivation()
    {
        return view('doubleoptin::activate');
    }

    /**
     * Verify the users token and activate
     *
     * @param $token
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verify($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (is_null($token) || !$user) {
            $this->message(trans('doubleoptin::activation.failed', ['activation_link' => route('activation.edit')]), 'danger');
            return redirect('auth/login');
        }

        $user->active = 1;
        $user->activation_token = "";
        $user->save();

        $this->message(trans('doubleoptin::activation.activated'), 'success');
        return redirect('auth/login');
    }

    /**
     * Get the needed authorization credentials from the request.
     * Add constraint "active =1", ensures that only activated accounts can login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(Request $request)
    {
        $credentials = $request->only($this->loginUsername(), 'password');
        $credentials['active'] = 1;
        return $credentials;
    }

    protected function sendActivationMail(User $user)
    {
        # send email with activation token to user
        Mail::queue('doubleoptin::emails.activate', compact('user'), function ($message) use ($user) {
            $message->to($user->email, $user->name)
                    ->subject(trans('doubleoptin::email.activate_subject'));
        });
        $this->message(trans('doubleoptin::activation.sent', ['email' => $user->email]), 'success');
    }

    protected function createToken(User $user)
    {
        $user->activation_token = $this->generateToken($user->email);
        $user->save();
    }

    /**
     * Create a new token for the user.
     *
     * @return string
     */
    protected function generateToken($hashKey)
    {
        return hash_hmac('sha256', Str::random(40), $hashKey);
    }

    /**
     * Set a flash message and bootstrap color
     *
     * @param $message
     * @param $type
     */
    private function message($message, $type)
    {
        \Session::flash('message', $message);
        \Session::flash('message-alert', $type);
    }
}
