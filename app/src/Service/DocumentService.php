<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DocumentRepository;

final class DocumentService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DocumentRepository $documentRepository,
    ) {}

    public function create(string $filename): Document
    {
        $document = new Document();
        $document->setFilename($filename);

        $this->entityManager->persist($document);
        $this->entityManager->flush();

        return $document;
    }

    /**
     * @return Document[]
     */
    public function findAll(): array
    {
        return $this->documentRepository->findAll();
    }
}
