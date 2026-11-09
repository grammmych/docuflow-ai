<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\DocumentRepository;
use App\Service\DocumentService;

final class DocumentController extends AbstractController
{
    #[Route('/api/documents', name: 'api_documents_create', methods: ['POST'])]
    public function create(
        Request $request,
        DocumentService $documentService
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data) || !isset($data['filename']) || !is_string($data['filename']) || trim($data['filename']) === '') {
            return $this->json(
                ['error' => 'Valid filename is required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $document = $documentService->create(
            trim($data['filename'])
        );

        return $this->json(
            [
                'uuid' => $document->getUuid(),
                'filename' => $document->getFilename(),
                'status' => $document->getStatus(),
                'createdAt' => $document->getCreatedAt()?->format(\DateTimeInterface::ATOM),
            ],
            Response::HTTP_CREATED
        );
    }

    #[Route('/api/documents', name: 'api_documents_list', methods: ['GET'])]
    public function list(DocumentService $documentService): JsonResponse
    {
        $documents = $documentService->findAll();

        $result = [];

        foreach ($documents as $document) {
            $result[] = [
                'uuid' => $document->getUuid(),
                'filename' => $document->getFilename(),
                'status' => $document->getStatus(),
                'createdAt' => $document->getCreatedAt()?->format(\DateTimeInterface::ATOM),
            ];
        }

        return $this->json($result);
    }

    #[Route('/api/documents/{uuid}', name: 'api_documents_show', methods: ['GET'], format: 'json')]
    public function show(string $uuid, DocumentRepository $documentRepository): JsonResponse
    {
        $document = $documentRepository->findOneBy(['uuid' => $uuid]);

        if ($document === null) {
            return $this->json(
                ['error' => 'Document not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $result = [
            'uuid' => $document->getUuid(),
            'filename' => $document->getFilename(),
            'status' => $document->getStatus(),
            'createdAt' => $document->getCreatedAt()?->format(\DateTimeInterface::ATOM),
        ];

        return $this->json($result);
    }
}
