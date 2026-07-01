<?php

namespace App\Controller;

use App\Entity\Application;
use App\Enum\ApplicationStatus;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/applications')]
final class ApplicationController extends AbstractController
{
    #[Route('', name: 'application_list', methods: ['GET'])]
    public function list(Request $request, ApplicationRepository $repository): JsonResponse
    {
        $status = $request->query->get('status');

        if ($status !== null) {
            $applications = $repository->findBy(['status' => $status]);
        } else {
            $applications = $repository->findAll();
        }

        return $this->json($applications, context: ['groups' => 'application:read']);
    }

    #[Route('/{id}', name: 'application_show', methods: ['GET'])]
    public function show(Application $application): JsonResponse
    {
        return $this->json($application, context: ['groups' => 'application:read']);
    }

    #[Route('', name: 'application_create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ): JsonResponse {
        try {
            $application = $serializer->deserialize(
                $request->getContent(),
                Application::class,
                'json'
            );
        } catch (\Throwable $e) {
            return $this->json(
                ['error' => 'Invalid JSON payload'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $errors = $validator->validate($application);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $application->setCreatedAt(new \DateTimeImmutable());

        $em->persist($application);
        $em->flush();

        return $this->json(
            $application,
            Response::HTTP_CREATED,
            [],
            ['groups' => 'application:read']
        );
    }

    #[Route('/{id}', name: 'application_update', methods: ['PUT'])]
    public function update(
        Request $request,
        Application $application,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        EntityManagerInterface $em
    ): JsonResponse {
    try {
            $serializer->deserialize(
                $request->getContent(),
                Application::class,
                'json',
                ['object_to_populate' => $application]
            );
        } catch (\Throwable $e) {
            return $this->json(
                ['error' => 'Invalid JSON payload'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $errors = $validator->validate($application);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $em->flush();

        return $this->json(
            $application,
            Response::HTTP_OK,
            [],
            ['groups' => 'application:read']
        );
    }

    #[Route('/{id}', name: 'application_delete', methods: ['DELETE'])]
    public function delete(Application $application, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($application);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}/status', name: 'application_update_status', methods: ['PATCH'])]
    public function updateStatus(
        Application $application,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return $this->json(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
        }

        $newStatusValue = $data['status'] ?? null;
        if ($newStatusValue === null) {
            return $this->json(['error' => 'Field "status" is required'], Response::HTTP_BAD_REQUEST);
        }

        $newStatus = ApplicationStatus::tryFrom($newStatusValue);
        if ($newStatus === null) {
            return $this->json(
                ['error' => sprintf('Invalid status "%s"', $newStatusValue)],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $currentStatus = $application->getStatus();
        if (!$currentStatus->canTransitionTo($newStatus)) {
            return $this->json(
                ['error' => sprintf(
                    'Cannot change status from "%s" to "%s"',
                    $currentStatus->value,
                    $newStatus->value
                )],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $application->setStatus($newStatus);
        $em->flush();

        return $this->json(
            $application,
            Response::HTTP_OK,
            [],
            ['groups' => 'application:read']
        );
    }
}
