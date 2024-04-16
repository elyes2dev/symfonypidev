<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408035127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_event (id INT AUTO_INCREMENT NOT NULL, event_id_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, updated_date DATETIME NOT NULL, INDEX IDX_FC0A11873E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_event ADD CONSTRAINT FK_FC0A11873E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_event DROP FOREIGN KEY FK_FC0A11873E5F2F7B');
        $this->addSql('DROP TABLE task_event');
    }
}
