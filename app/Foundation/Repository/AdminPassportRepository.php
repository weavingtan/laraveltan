<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/9/2017
 * Time: 4:00 PM
 */

namespace App\Foundation\Repository;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Laravel\Passport\Bridge\User;
use Laravel\Passport\Bridge\UserRepository;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use RuntimeException;

class AdminPassportRepository extends UserRepository
{

    public function getUserEntityByUserCredentials( $username, $password, $grantType, ClientEntityInterface $clientEntity )
    {

        $guard = App::make(Request::class)->get('guard') ?: 'api';

        $provider = config("auth.guards.{$guard}.provider");

        if (is_null($model = config('auth.providers.' . $provider . '.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        if (method_exists($model, 'findForPassport')) {

            $user = (new $model)->findForPassport($username);

        }
        else {
            $user = (new $model)->where('email', $username)->first();
        }
        if (!$user) {
            return;
        }
        elseif (method_exists($user, 'validateForPassportPasswordGrant')) {
            if (!$user->validateForPassportPasswordGrant($password)) {
                return;
            }
        }

        elseif (!$this->hasher->check($password, $user->getAuthPassword())) {
            return;
        }

        return new User($user->getAuthIdentifier());
    }
}