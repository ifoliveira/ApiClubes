<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250614214826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_final ADD torneo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partido_final ADD CONSTRAINT FK_512B60FAA0139802 FOREIGN KEY (torneo_id) REFERENCES torneos (id)');
        $this->addSql('CREATE INDEX IDX_512B60FAA0139802 ON partido_final (torneo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_final DROP FOREIGN KEY FK_512B60FAA0139802');
        $this->addSql('DROP INDEX IDX_512B60FAA0139802 ON partido_final');
        $this->addSql('ALTER TABLE partido_final DROP torneo_id');
    }
}
