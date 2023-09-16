<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623184339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador ADD equipo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jugador ADD CONSTRAINT FK_527D6F1823BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
        $this->addSql('CREATE INDEX IDX_527D6F1823BFBED ON jugador (equipo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador DROP FOREIGN KEY FK_527D6F1823BFBED');
        $this->addSql('DROP INDEX IDX_527D6F1823BFBED ON jugador');
        $this->addSql('ALTER TABLE jugador DROP equipo_id');
    }
}
