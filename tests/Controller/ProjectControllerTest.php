<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProjectControllerTest extends WebTestCase
{

    public function testVisitingWhileLoogedIn(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/projects');

        $this->assertResponseRedirects('/login');
    }

    public function testUserCanCreateOwnProject(): void
    {
        $client = static::createClient();
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $user = new User();
        $user->setEmail('user6@test.com');
        $user->setPassword('password123@');
        $user->setRoles(['ROLE_USER']);

        $entityManager->persist($user);

        $client->loginUser($user);

        $userTestB = new User();
        $userTestB->setEmail('user7@test.com');
        $userTestB->setPassword('password123@');
        $userTestB->setRoles(['ROLE_USER']);

        $entityManager->persist($userTestB);
        $entityManager->flush();

        $client->loginUser($userTestB);

        $project = new Project();
        $project->setOwner($user);
        $project->setName('Test Project');
        $project->setDescription('Test Project Description');
        $project->setStatus('done');
        $project->setCreatedAt(new \DateTimeImmutable('now'));
        $entityManager->persist($project);
        $entityManager->flush();

        $client->jsonRequest('PATCH', '/api/projects/'.$project->getId(), [
            'name' => 'Test Project Description',
        ]);


        $this->assertResponseStatusCodeSame(403);

    }
}
