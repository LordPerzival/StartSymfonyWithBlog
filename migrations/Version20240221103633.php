<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221103633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F3DA5256D');
        $this->addSql('DROP INDEX UNIQ_C53D045F3DA5256D ON image');
        $this->addSql('ALTER TABLE image DROP image_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F3DA5256D FOREIGN KEY (image_id) REFERENCES article (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C53D045F3DA5256D ON image (image_id)');
    }
}
