<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925155657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message CHANGE created_at created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD entrenador_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64992FE0A7E FOREIGN KEY (entrenador_id) REFERENCES staff (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64992FE0A7E ON user (entrenador_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64992FE0A7E');
        $this->addSql('DROP INDEX IDX_8D93D64992FE0A7E ON user');
        $this->addSql('ALTER TABLE user DROP entrenador_id');
        $this->addSql('ALTER TABLE message CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
