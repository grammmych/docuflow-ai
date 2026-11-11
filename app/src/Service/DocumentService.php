<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DocumentRepository;
use App\Entity\User;

final class DocumentService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DocumentRepository $documentRepository,
    ) {}

    public function create(string $filename, User $owner): Document
    {
        $document = new Document();
        $document->setFilename($filename);
        $document->setOwner($owner);

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

    /**
     * @return Document[]
     */
    public function findByOwner(User $owner): array
    {
        return $this->documentRepository->findBy(['owner' => $owner]);
    }

}
