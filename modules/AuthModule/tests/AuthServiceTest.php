<?php

namespace Modules\AuthModule\Tests;

use PHPUnit\Framework\TestCase;
use Modules\AuthModule\Service\AuthService;
use Modules\AuthModule\Repository\UserRepository;

class AuthServiceTest extends TestCase
{
    private $authService;
    private $userRepository;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->authService = new AuthService($this->userRepository);
    }

    public function testLoginWithValidCredentials()
    {
        $email = 'test@example.com';
        $password = 'password123';
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = [
            'id' => 1,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user'
        ];

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($user);

        $result = $this->authService->login($email, $password);

        $this->assertTrue($result);
    }

    public function testLoginWithInvalidCredentials()
    {
        $email = 'test@example.com';
        $password = 'wrongpassword';

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $result = $this->authService->login($email, $password);

        $this->assertFalse($result);
    }

    public function testRegisterNewUser()
    {
        $userData = [
            'email' => 'newuser@example.com',
            'password' => 'newpassword123',
            'nom' => 'Doe',
            'prenom' => 'John',
            'role' => 'etudiant'
        ];

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with($userData['email'])
            ->willReturn(null);

        $this->userRepository->expects($this->once())
            ->method('create')
            ->with($this->callback(function($data) use ($userData) {
                return $data['email'] === $userData['email'] &&
                       password_verify($userData['password'], $data['password']);
            }))
            ->willReturn(true);

        $result = $this->authService->register($userData);

        $this->assertTrue($result);
    }

    public function testRegisterWithExistingEmail()
    {
        $userData = [
            'email' => 'existing@example.com',
            'password' => 'password123',
            'nom' => 'Smith',
            'prenom' => 'Jane',
            'role' => 'etudiant'
        ];

        $this->userRepository->expects($this->once())
            ->method('findByEmail')
            ->with($userData['email'])
            ->willReturn(['id' => 1, 'email' => $userData['email']]);

        $result = $this->authService->register($userData);

        $this->assertFalse($result);
    }
}