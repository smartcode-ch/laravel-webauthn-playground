<?php

namespace App\Http\Controllers;

use App\Models\Credential;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use lbuchs\WebAuthn\WebAuthn;
use lbuchs\WebAuthn\WebAuthnException;

class Login
{

    public function __construct(private readonly WebAuthn $webAuthn)
    {
    }

    public function login(Request $request)
    {

        return Inertia::render('Login', [
            'user' => $request->session()->get('user'),
        ]);
    }

    public function create(Request $request) : JsonResponse
    {
        $request->validate([
            'email' => ['required', Rule::exists(User::class, 'email')]
        ]);

        /** @var User $user */
        $user = User::with('credentials')->firstWhere('email', $request->input('email'));

        $getArgs = $this->webAuthn->getGetArgs($user->credentials->pluck('credential_id'));

        $request->session()->put('webauthn_login', [
            'challenge' => $this->webAuthn->getChallenge(),
            'user' => $user,
        ]);

        return response()->json($getArgs);
    }

    /**
     * @throws WebAuthnException
     */
    public function process(Request $request) : \Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    {
        $postData = json_decode($request->getContent());

        /** @var User $user */
        $user = $request->session()->get('webauthn_login')['user'];

        /** @var Credential $credential */
        $credential_id = base64_decode($postData->credentialId);
        $credential_public_key = $user->credentials
            ->firstWhere('credential_id', $credential_id)
            ?->credential_public_key;

        if ($credential_public_key === null) {
            return \redirect('/register');
        }

        // Process the get request. throws WebAuthnException if it fails
        $this->webAuthn->processGet(
            clientDataJSON: base64_decode($postData->clientDataJSON),
            authenticatorData: base64_decode($postData->authenticatorData),
            signature: base64_decode($postData->signature),
            credentialPublicKey: $credential_public_key,
            challenge: $request->session()->get('webauthn_login')['challenge'] ?? '',
        );

        // Login the user.
        Auth::login($user);

        $request->session()->forget('webauthn_login');

        return \redirect('/');
    }
}
