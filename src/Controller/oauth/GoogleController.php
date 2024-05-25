<?php

// namespace App\Controller;

// use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Routing\Attribute\Route;

// class GoogleController extends AbstractController
// {
//     #[Route(path: "/connect/google", name: "connect_google_start")]
//     public function connectAction(ClientRegistry $clientRegistry)
//     {
//         // will redirect to Google
//         return $clientRegistry
//             ->getClient('google_main')
//             ->redirect([
//                 'public_profile', 'email' // the scopes you want to access
//             ], []);
//     }
// }
