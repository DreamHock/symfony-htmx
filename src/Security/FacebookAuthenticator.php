<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class FacebookAuthenticator extends AbstractOAuthAuthenticator
{
    protected string $serviceName = 'facebook';

    protected function getUserFromRessourceOwner(ResourceOwnerInterface $resourceOwner, UserRepository $userRepository): ?User
    {
        if (!$resourceOwner instanceof FacebookUser) {
            throw new \RuntimeException(message: "expecting facebook user");
        }

        if(true !== ($resourceOwner->toArray()['email_verified'] ?? null)) {
            throw new AuthenticationException(message: "email not verified");
        }

        return $userRepository->findOneBy([
            'googleId' => $resourceOwner->getId(),
            'email' => $resourceOwner->getEmail()
        ]);
    }

}
