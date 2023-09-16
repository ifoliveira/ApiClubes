<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623184012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador ADD club_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jugador ADD CONSTRAINT FK_527D6F1861190A32 FOREIGN KEY (club_id) REFERENCES clubes (id)');
        $this->addSql('CREATE INDEX IDX_527D6F1861190A32 ON jugador (club_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador DROP FOREIGN KEY FK_527D6F1861190A32');
        $this->addSql('DROP INDEX IDX_527D6F1861190A32 ON jugador');
        $this->addSql('ALTER TABLE jugador DROP club_id');
    }
}
