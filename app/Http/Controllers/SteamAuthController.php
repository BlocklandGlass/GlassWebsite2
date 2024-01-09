<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Ilzrv\LaravelSteamAuth\SteamAuth;
use Ilzrv\LaravelSteamAuth\SteamData;

class SteamAuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steamAuth;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/my-account';

    /**
     * SteamAuthController constructor.
     */
    public function __construct(SteamAuth $steamAuth)
    {
        $this->steamAuth = $steamAuth;
    }

    /**
     * Get user data and login.
     */
    public function login(): RedirectResponse
    {
        if (! $this->steamAuth->validate()) {
            return $this->steamAuth->redirect();
        }

        $data = $this->steamAuth->getUserData();

        if (is_null($data)) {
            return $this->steamAuth->redirect();
        }

        $user = $this->firstOrCreate($data);

        if (! $user->wasRecentlyCreated) {
            $user->name = $data->getPersonaName();
            $user->avatar_url = $data->getAvatarFull();

            $user->save();
        }

        Auth::login(
            $user,
            true
        );

        Log::debug('Steam OpenID Authentication SUCCESS: "'.$user->steam_id.'" ('.$user->name.')');

        return redirect($this->redirectTo);
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect($this->redirectTo);
    }

    /**
     * Get the first user by SteamID or create a new one if not exists.
     */
    protected function firstOrCreate(SteamData $data): User
    {
        return User::firstOrCreate([
            'steam_id' => $data->getSteamId(),
        ], [
            'name' => $data->getPersonaName(),
            'avatar_url' => $data->getAvatarFull(),
        ]);
    }
}
