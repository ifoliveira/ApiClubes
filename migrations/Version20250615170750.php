<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250615170750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_final ADD fecha_inicio DATETIME DEFAULT NULL, ADD fecha_fin DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE partido_grupo ADD fecha_inicio DATETIME DEFAULT NULL, ADD fecha_fin DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partido_final DROP fecha_inicio, DROP fecha_fin');
        $this->addSql('ALTER TABLE partido_grupo DROP fecha_inicio, DROP fecha_fin');
    }
}
