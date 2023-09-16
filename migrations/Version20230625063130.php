<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230625063130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE staff (id INT AUTO_INCREMENT NOT NULL, club_id INT DEFAULT NULL, nombre VARCHAR(255) NOT NULL, apellidos VARCHAR(255) DEFAULT NULL, tipo VARCHAR(255) DEFAULT NULL, INDEX IDX_426EF39261190A32 (club_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE staff_equipo (staff_id INT NOT NULL, equipo_id INT NOT NULL, INDEX IDX_C1E1E1B0D4D57CD (staff_id), INDEX IDX_C1E1E1B023BFBED (equipo_id), PRIMARY KEY(staff_id, equipo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF39261190A32 FOREIGN KEY (club_id) REFERENCES clubes (id)');
        $this->addSql('ALTER TABLE staff_equipo ADD CONSTRAINT FK_C1E1E1B0D4D57CD FOREIGN KEY (staff_id) REFERENCES staff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE staff_equipo ADD CONSTRAINT FK_C1E1E1B023BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF39261190A32');
        $this->addSql('ALTER TABLE staff_equipo DROP FOREIGN KEY FK_C1E1E1B0D4D57CD');
        $this->addSql('ALTER TABLE staff_equipo DROP FOREIGN KEY FK_C1E1E1B023BFBED');
        $this->addSql('DROP TABLE staff');
        $this->addSql('DROP TABLE staff_equipo');
    }
}
