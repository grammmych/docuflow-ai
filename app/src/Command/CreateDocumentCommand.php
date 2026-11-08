<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Document;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-document',
    description: 'Create a test document in the database',
)]
class CreateDocumentCommand
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function __invoke(SymfonyStyle $io): int
    {
        $document = new Document();

        $document->setFilename('contract.pdf');
        $document->setStatus('uploaded');
        $document->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));

        $this->entityManager->persist($document);
        $this->entityManager->flush();

        $io->success(sprintf(
            'Document created with ID: %d',
            $document->getId()
        ));

        return Command::SUCCESS;
    }
}
