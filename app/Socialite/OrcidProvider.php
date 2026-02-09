<?php

namespace App\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class OrcidProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['openid'];
    protected $scopeSeparator = ' ';

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://orcid.org/oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://orcid.org/oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://orcid.org/oauth/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['sub'],
            'name'     => $user['name'] ?? null,
            'email'    => $user['email'] ?? null,
            'orcid'    => $user['sub'], // ORCID iD
        ]);
    }
}