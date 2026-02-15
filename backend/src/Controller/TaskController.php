<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tasks', name: 'api_tasks_')]
class TaskController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(
        Request $request,
        TaskRepository $taskRepository
    ): JsonResponse {
        $status = $request->query->get('status');
        $search = $request->query->get('search');

        if ($status) {
            $tasks = $taskRepository->findByStatus($status);
        } elseif ($search) {
            $tasks = $taskRepository->searchByTitle($search);
            $tasksData = [];
            foreach ($tasks as $taskData) {
                $tasksData[] = [
                    'id' => $taskData['id'],
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'status' => $taskData['status'],
                    'createdAt' => $taskData['created_at']
                ];
            }
            return new JsonResponse($tasksData);
        } else {
            $tasks = $taskRepository->findAll();
        }

        $data = array_map(function($task) {
            $assignedUser = $task->getAssignedTo();
            return [
                'id' => $task->getId(),
                'title' => $task->getTitle(),
                'description' => $task->getDescription(),
                'status' => $task->getStatus(),
                'assignedTo' => [
                    'id' => $assignedUser->getId(),
                    'name' => $assignedUser->getName(),
                    'email' => $assignedUser->getEmail()
                ],
                'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s')
            ];
        }, $tasks);

        return new JsonResponse($data);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setTitle($data['title']);
        $task->setDescription($data['description'] ?? '');
        $task->setStatus($data['status'] ?? 'todo');

        $user = $userRepository->find($data['assignedTo']);
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_BAD_REQUEST);
        }

        $task->setAssignedTo($user);

        $em->persist($task);
        $em->flush();

        return new JsonResponse([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'assignedTo' => [
                'id' => $user->getId(),
                'name' => $user->getName()
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        TaskRepository $taskRepository,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $task = $taskRepository->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) {
            $task->setTitle($data['title']);
        }

        if (isset($data['description'])) {
            $task->setDescription($data['description']);
        }

        if (isset($data['status'])) {
            $task->setStatus($data['status']);
        }

        if (isset($data['assignedTo'])) {
            $user = $userRepository->find($data['assignedTo']);
            if ($user) {
                $task->setAssignedTo($user);
            }
        }

        $em->flush();

        return new JsonResponse([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus()
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        int $id,
        TaskRepository $taskRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $task = $taskRepository->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($task);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, TaskRepository $taskRepository): JsonResponse
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return new JsonResponse(['error' => 'Task not found'], Response::HTTP_NOT_FOUND);
        }

        $assignedUser = $task->getAssignedTo();

        return new JsonResponse([
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'status' => $task->getStatus(),
            'assignedTo' => [
                'id' => $assignedUser->getId(),
                'name' => $assignedUser->getName(),
                'email' => $assignedUser->getEmail()
            ],
            'createdAt' => $task->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }
}
