<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261109091040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B072887E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_A2B072887E3C61F9 ON documents (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP CONSTRAINT FK_A2B072887E3C61F9');
        $this->addSql('DROP INDEX IDX_A2B072887E3C61F9');
        $this->addSql('ALTER TABLE documents DROP owner_id');
    }
}
