<?php

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Security\Voter\ProjectVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AllowDynamicProperties]
final class ProjectController extends AbstractController
{
    public function __construct() {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[Route('/api/project', name: 'app_project')]
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }

    #[Route('/api/projects', name: 'api_project-list_index', methods: ['GET'])]
    public function apiProjectList(ProjectRepository $projectRepository): Response
    {

        if ($this->isGranted('ROLE_ADMIN')) {
            $project = $projectRepository->findAll();
        } else {
            $project = $projectRepository->findBy([
                'owner' => $this->getUser(),
            ]);
        }

        if (!$project){
            return $this->json([
                'message' => 'Aucun projet trouvé',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = [];

        foreach ($project as $project1) {
            $data[] = [
                'id' => $project1->getId(),
                'name' => $project1->getName(),
                'description' => $project1->getDescription(),
                'status' => $project1->getStatus(),
                'createdAt' => $project1->getCreatedAt()?->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json([
            'message' => 'List all projects',
            'projects' => $data,
        ]);
    }

    #[Route('/api/projects/{id}', name: 'api_project-list_show-id', methods: ['GET'])]
    public function apiProjectListId(ProjectRepository $projectRepository, int $id): Response
    {
        $project = $projectRepository->find($id);

        if (!$project){
            return $this->json([
                'message' => 'Aucun projet trouvé',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $project);

        return $this->json([
            'message' => 'List all projects',
            'projects' => [
                'id' => $project->getId(),
                'name' => $project->getName(),
                'description' => $project->getDescription(),
                'status' => $project->getStatus(),
                'createdAt' => $project->getCreatedAt()?->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    #[Route('/api/projects/{id}', name: 'api_project-list_delete', methods: ['DELETE'])]
    public function apiProjectListDelete(EntityManagerInterface $entityManager, ProjectRepository $projectRepository, int $id): Response
    {

        $project = $projectRepository->find($id);

        if(!$project){
            return $this->json([
                "message" => "Il n'y a aucun projet trouvé"
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted(ProjectVoter::DELETE, $project);

        $entityManager->remove($project);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/projects', name: 'api_project_create', methods: ['POST'])]
    public function apiProjectCreate(EntityManagerInterface $entityManager, Request $request): Response
    {

        $data = json_decode($request->getContent(), true);
        if(!is_array($data)) {
            return $this->json([
                'message' => 'data is not an array',
            ], Response::HTTP_BAD_REQUEST);
        }

        if (empty($data['name'])) {
            return $this->json([
                'message' => 'Project name cannot be empty',
            ], Response::HTTP_BAD_REQUEST);
        }

        if(empty($data['description'])){
            return $this->json([
                'message' => 'Project description cannot be empty',
            ], Response::HTTP_BAD_REQUEST);
        }

        $allowedStatus = ['todo', 'doing', 'done'];

        if(!isset($data['status'])) {
            return $this->json([
                'message' => 'Project is not todo'
            ], Response::HTTP_BAD_REQUEST);
        }

        $data['status'] = strtolower($data['status']);

        if (!in_array($data['status'], $allowedStatus)) {
            return $this->json([
                'message' => 'Project is invalid'
            ], Response::HTTP_BAD_REQUEST);
        }

        if(strLen(trim($data['name'])) < 3) {
            return $this->json([
                'message' => 'Project name is too short',
            ], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getUser();
        $project = new Project();
        $project->setOwner($user);

        $project->setName($data['name']);
        $project->setDescription($data['description']);
        $project->setStatus($data['status']);
        $project->setCreatedAt(new \DateTimeImmutable('now'));

        $entityManager->persist($project);
        $entityManager->flush();

        return $this->json([
            'message' => 'Project created',
            'project' => [
            'id' => $project->getId(),
            'name' => $project->getName(),
            'description' => $project->getDescription(),
            'status' => $project->getStatus(),
            'createdAt' => $project->getCreatedAt()?->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    #[Route('/api/projects/{id}', name: 'api_project-list_update', methods: ['PATCH'])]
    public function apiProjectListPatch(EntityManagerInterface $entityManager, Request $request, int $id): Response
    {
        $project = $entityManager->getrepository(Project::class)->find($id);
        $data = json_decode($request->getContent(), true);

        if (!$project){
            return $this->json([
                'message' => 'Aucun projet trouvé',
            ], Response::HTTP_NOT_FOUND);
        }

        $this->denyAccessUnlessGranted(ProjectVoter::EDIT, $project);

        if(!is_array($data)) {
            return $this->json([
                'message' => 'data is not an array',
            ], Response::HTTP_BAD_REQUEST);
        }

        if(isset($data['name'])) {
            if (strLen(trim($data['name'])) < 3) {
                return $this->json([
                    'message' => 'Project name cannot be empty',
                ], Response::HTTP_BAD_REQUEST);
            }

            $project->setName($data['name']);
        }

        if (array_key_exists('description', $data)) {
            if ($data['description'] !== null && strLen(trim($data['description'])) < 3) {
                return $this->json([
                    'message' => 'Project description cannot be empty',
                ], Response::HTTP_BAD_REQUEST);
            }

            $project->setDescription($data['description']);
        }

        if (isset($data['status'])) {
                $allowedStatus = ["todo", "doing", "done"];
                $status = strtolower($data['status']);

            if(!in_array($status, $allowedStatus, true)) {

                return $this->json([
                    'message' => 'Status is invalid'
                ], Response::HTTP_BAD_REQUEST);
            }
            $project->setStatus($status);
        }

      $entityManager->flush();

        return $this->json([
            'message' => 'La liste mis à jour',
            'project' => [
                'name' => $project->getName(),
                'status' => $project->getStatus(),
                'description' => $project->getDescription(),
            ]
        ]);
    }

}
