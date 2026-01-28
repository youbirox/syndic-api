<?php

namespace App\Tests\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $client->request('GET', '/api/users');

        // $user = $container->get('doctrine')
        //     ->getRepository(User::class)
        //     ->findOneBy(['email' => 'admin@monsite.com']);

        $em = $container->get('doctrine')->getManager();
        $user = new User();
        $user->setEmail('test@test.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $container->get(UserPasswordHasherInterface::class)
                ->hashPassword($user, 'password')
        );

        $em->persist($user);
        $em->flush();
    
        $jwtManager = $container->get(JWTTokenManagerInterface::class);
        $token = $jwtManager->create($user);

        $client->request(
            'GET',
            '/api/users',
            [],
            [],
            [
                'HTTP_Authorization' => 'Bearer '.$token,
            ]
        );

        self::assertResponseIsSuccessful();
    }
}
