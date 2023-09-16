<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622161122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo ADD division_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipo ADD CONSTRAINT FK_C49C530B41859289 FOREIGN KEY (division_id) REFERENCES divisiones (id)');
        $this->addSql('CREATE INDEX IDX_C49C530B41859289 ON equipo (division_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo DROP FOREIGN KEY FK_C49C530B41859289');
        $this->addSql('DROP INDEX IDX_C49C530B41859289 ON equipo');
        $this->addSql('ALTER TABLE equipo DROP division_id');
    }
}
