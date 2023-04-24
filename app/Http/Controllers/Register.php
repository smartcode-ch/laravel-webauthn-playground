<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use lbuchs\WebAuthn\WebAuthn;
use lbuchs\WebAuthn\WebAuthnException;

class Register
{

    public function __construct(private readonly WebAuthn $webAuthn)
    {
    }

    public function register()
    {
        return Inertia::render('Register');
    }

    public function create(Request $request) : JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'name' => ['required', 'string'],
        ]);

        $user = User::firstOrNew([
            'email' => $request->input('email'),
        ], [
            'name' => $request->input('name'),
            'webauthn_id' => Str::random(36)
        ]);

        $createArgs = $this->webAuthn->getCreateArgs(
            userId: $user->webauthn_id,
            userName: $user->email,
            userDisplayName: $user->name,
            requireUserVerification: 'discouraged',
        );

        $request->session()->put('webauthn_registration', [
            'challenge' => $this->webAuthn->getChallenge(),
            'user' => $user,
        ]);

        return \response()->json($createArgs);
    }

    /**
     * @throws WebAuthnException
     * @throws Exception
     */
    public function process(Request $request) : RedirectResponse
    {
        $postData = json_decode($request->getContent());

        $credentials = $this->webAuthn->processCreate(
            clientDataJSON: base64_decode($postData->clientDataJSON),
            attestationObject: base64_decode($postData->attestationObject),
            challenge: $request->session()->get('webauthn_registration')['challenge'],
            requireUserVerification: 'discouraged'
        );

        /** @var User $user */
        $user = DB::transaction(function () use ($request, $credentials)
        {

            /** @var User $user */
            $user = $request->session()->get('webauthn_registration')['user'];
            $user->save();

            $user->credentials()->create([
                'credential_id' => $credentials->credentialId,
                'credential_public_key' => $credentials->credentialPublicKey,
            ]);

            return $user;
        });

        $request->session()->forget('webauthn_registration');

        return \redirect('/login')->with('user' , $user);
    }
}
