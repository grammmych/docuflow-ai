<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261109072000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // 1. Додаємо nullable UUID
        $this->connection->executeStatement(
            'ALTER TABLE documents ADD uuid UUID DEFAULT NULL'
        );

        // 2. Заповнюємо UUID для наявних документів
        $documents = $this->connection->fetchAllAssociative(
            'SELECT id FROM documents'
        );

        foreach ($documents as $document) {
            $this->connection->executeStatement(
                'UPDATE documents SET uuid = :uuid WHERE id = :id',
                [
                    'uuid' => Uuid::v7()->toRfc4122(),
                    'id' => $document['id'],
                ]
            );
        }

        // 3. Забороняємо NULL
        $this->addSql('ALTER TABLE documents ALTER COLUMN uuid SET NOT NULL');

        // 4. Створюємо UNIQUE INDEX
        $this->addSql(
            'CREATE UNIQUE INDEX UNIQ_A2B07288D17F50A6 ON documents (uuid)'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_A2B07288D17F50A6');
        $this->addSql('ALTER TABLE documents DROP uuid');
    }
}
