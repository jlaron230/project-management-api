<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProjectControllerTest extends WebTestCase
{

//    public function testUnauthenticatedUserIsRedirectedToLogin(): void
//    {
//        $client = static::createClient();
//
//        // Protected project routes require authentication.
//        $client->request('GET', '/api/projects');
//
//        $this->assertResponseRedirects('/login');
//    }
//
//    public function testUserCanCreateOwnProject(): void
//    {
//        $client = static::createClient();
//        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
//
//        // User A owns the project.
//        $user = new User();
//        $user->setEmail('user13@test.com');
//        $user->setPassword('password123@');
//        $user->setRoles(['ROLE_USER']);
//
//        $entityManager->persist($user);
//
//        $client->loginUser($user);
//
//        // User B will attempt to modify User A's project.
//        $userTestB = new User();
//        $userTestB->setEmail('user12@test.com');
//        $userTestB->setPassword('password123@');
//        $userTestB->setRoles(['ROLE_USER']);
//
//        $entityManager->persist($userTestB);
//        $entityManager->flush();
//
//        // Authenticate as User B before trying to edit User A's project.
//        $client->loginUser($userTestB);
//
//        $project = new Project();
//        $project->setOwner($user);
//        $project->setName('Test Project');
//        $project->setDescription('Test Project Description');
//        $project->setStatus('done');
//        $project->setCreatedAt(new \DateTimeImmutable('now'));
//        $entityManager->persist($project);
//        $entityManager->flush();
//
//        $client->jsonRequest('PATCH', '/api/projects/'.$project->getId(), [
//            'name' => 'Test Project Description',
//        ]);
//
//        // ProjectVoter must deny access because User B is not the owner.
//        $this->assertResponseStatusCodeSame(403);
//    }
//
//    public function testAnonymousUserCannotAccessProjects()
//    {
//        $client = static::createClient();
//
//        $client->request('GET', '/api/projects');
//
//        $this->assertResponseRedirects('/login');
//    }
//
//    public function testMeReturnsUnauthenticatedForAnonymousUser(): void
//    {
//        $client = static::createClient();
//        $client->request('GET', '/api/projects');
//
//        $this->assertResponseIsSuccessful();
//
//        $data = json_decode($client->getResponse()->getContent(), true);
//
//        $this->assertFalse($data['The user, is not authenticated']);
//    }
//
//    public function testAdminCanDeleteAnotherUsersProject(): void
//    {
//        $client = static::createClient();
//        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
//
//        //Create the owner
//        $user = new User();
//        $user->setEmail('user101@test.com');
//        $user->setPassword('password123@');
//        $user->setRoles(['ROLE_USER']);
//        $entityManager->persist($user);
//
//        //Create Admin user
//        $admin = new User();
//        $admin->setEmail('admin2@test.com');
//        $admin->setPassword('password123@');
//        $admin->setRoles(['ROLE_ADMIN']);
//        $entityManager->persist($admin);
//
//        //Create a new project from the owner
//        $project = new Project();
//        $project->setOwner($user);
//        $project->setName('Test Project');
//        $project->setDescription('Test Project Description');
//        $project->setStatus('done');
//        $project->setCreatedAt(new \DateTimeImmutable('now'));
//        $entityManager->persist($project);
//        $entityManager->flush();
//
//        //Login admin and attribute the project at the variable projectId
//        $client->loginUser($admin);
//        $projectId = $project->getId();
//
//        $client->request('DELETE', '/api/projects/'.$projectId);
//
//        //assert passed with the status code
//        $this->assertResponseStatusCodeSame(204);
//    }

//    public function testUserCannotDeleteAnotherUsersProject(): void
//    {
//        $client = static::createClient();
//        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
//
//        //Create the owner
//        $userA = new User();
//        $userA->setEmail('user208@test.com');
//        $userA->setPassword('password123@');
//        $userA->setRoles(['ROLE_USER']);
//        $entityManager->persist($userA);
//
//        $userB = new User();
//        $userB->setEmail('user207@test.com');
//        $userB->setPassword('password123@');
//        $userB->setRoles(['ROLE_USER']);
//        $entityManager->persist($userB);
//
//        $project = new Project();
//        $project->setName('Project Test');
//        $project->setDescription('Project Test');
//        $project->setStatus('done');
//        $project->setCreatedAt(new \DateTimeImmutable('now'));
//        $project->setOwner($userA);
//        $entityManager->persist($project);
//
//        $entityManager->flush();
//
//        $client->loginUser($userB);
//        $projectId = $project->getId();
//
//        $client->request('DELETE', '/api/projects/' . $projectId);
//        $this->assertResponseStatusCodeSame(403);
//        // même principe
//        // DELETE /api/projects/{id}
//        // attendu : 403
//    }

    public function testUserCannotEditAnotherUsersProject(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get(EntityManagerInterface::class);

        $userA = new User();
        $userA->setEmail('user216@test.com');
        $userA->setPassword('password123@');
        $userA->setRoles(['ROLE_USER']);
        $entityManager->persist($userA);

        $userB = new User();
        $userB->setEmail('user215@test.com');
        $userB->setPassword('password123@');
        $userB->setRoles(['ROLE_USER']);
        $entityManager->persist($userB);

        $project = new Project();
        $project->setName('Test Project');
        $project->setOwner($userA);
        $project->setDescription('Test Project Description');
        $project->setStatus('done');
        $project->setCreatedAt(new \DateTimeImmutable('now'));
        $entityManager->persist($project);
        $entityManager->flush();

        $client->loginUser($userB);

        $client->jsonRequest('PATCH', '/api/projects/' . $project->getId(), [
            'name' => 'Test Project Descriptioni',
        ]);
        $this->assertResponseStatusCodeSame(403);
    }

//    public function testAdminCanEditAnotherUsersProject(): void
//    {
//        $client = static::createClient();
//        $entityManager = static::getContainer()->get(EntityManagerInterface::class);
//
//        //Create a new user and admin
//        $user = new User();
//        $user->setEmail('2user8@test.com');
//        $user->setPassword('password123@');
//        $user->setRoles(['ROLE_USER']);
//        $entityManager->persist($user);
//
//        $admin = new User();
//        $admin->setEmail('2admin8@test.com');
//        $admin->setPassword('password123@');
//        $admin->setRoles(['ROLE_ADMIN']);
//        $entityManager->persist($admin);
//
//        //Create a project with the user as owner
//        $project = new Project();
//        $project->setOwner($user);
//        $project->setName('Test Project');
//        $project->setDescription('Test Project Description');
//        $project->setStatus('done');
//        $project->setCreatedAt(new \DateTimeImmutable('now'));
//        $entityManager->persist($project);
//
//        $entityManager->flush();
//        $client->loginUser($admin);
//
//        $projectId = $project->getId();
//
//        //Patch the data
//        $client->jsonRequest('PATCH', '/api/projects/'.$projectId, [
//            'name' => 'Test Project Description',
//            'description' => 'Test Project Description Description',
//            'status' => 'done',
//        ]);
//
//        $this->assertResponseStatusCodeSame(200);
//
//        //Test the assertion in json
//        $response = json_decode($client->getResponse()->getContent(), true);
//        $this->assertSame(
//            'Test Project Description',
//            $response['project']['name']
//        );
//
//        $this->assertSame(
//            'Test Project Description Description',
//            $response['project']['description']
//        );
//
//        $this->assertSame(
//            'done',
//            $response['project']['status']
//        );
//    }
}
