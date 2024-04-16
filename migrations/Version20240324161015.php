<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324161015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE imageclub DROP FOREIGN KEY FK_AD6FF1DB3DA5256D');
        $this->addSql('ALTER TABLE imageclub DROP FOREIGN KEY FK_AD6FF1DB61190A32');
        $this->addSql('DROP TABLE imageclub');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE imageclub (club_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_AD6FF1DB61190A32 (club_id), INDEX IDX_AD6FF1DB3DA5256D (image_id), PRIMARY KEY(club_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE imageclub ADD CONSTRAINT FK_AD6FF1DB3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imageclub ADD CONSTRAINT FK_AD6FF1DB61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
    }
}
