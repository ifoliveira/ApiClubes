<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711205821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE convocatoria RENAME INDEX fk_6d773021b8a54d43_idx TO IDX_6D773021B8A54D43');
        $this->addSql('ALTER TABLE equipo RENAME INDEX fk_c49c530b61190a32_idx TO IDX_C49C530B61190A32');
        $this->addSql('ALTER TABLE eventos RENAME INDEX fk_6b23bd8f23bfbed_idx TO IDX_6B23BD8F23BFBED');
        $this->addSql('ALTER TABLE jugador RENAME INDEX fk_527d6f1861190a32_idx TO IDX_527D6F1861190A32');
        $this->addSql('ALTER TABLE jugador RENAME INDEX fk_527d6f1823bfbed_idx TO IDX_527D6F1823BFBED');
        $this->addSql('ALTER TABLE staff RENAME INDEX fk_426ef39261190a32_idx TO IDX_426EF39261190A32');
        $this->addSql('DROP INDEX `primary` ON staff_equipo');
        $this->addSql('ALTER TABLE staff_equipo ADD PRIMARY KEY (staff_id, equipo_id)');
        $this->addSql('ALTER TABLE staff_equipo RENAME INDEX fk_c1e1e1b0d4d57cd_idx TO IDX_C1E1E1B0D4D57CD');
        $this->addSql('ALTER TABLE staff_equipo RENAME INDEX fk_c1e1e1b023bfbed_idx TO IDX_C1E1E1B023BFBED');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE equipo RENAME INDEX idx_c49c530b61190a32 TO FK_C49C530B61190A32_idx');
        $this->addSql('ALTER TABLE convocatoria RENAME INDEX idx_6d773021b8a54d43 TO FK_6D773021B8A54D43_idx');
        $this->addSql('ALTER TABLE eventos RENAME INDEX idx_6b23bd8f23bfbed TO FK_6B23BD8F23BFBED_idx');
        $this->addSql('ALTER TABLE jugador RENAME INDEX idx_527d6f1861190a32 TO FK_527D6F1861190A32_idx');
        $this->addSql('ALTER TABLE jugador RENAME INDEX idx_527d6f1823bfbed TO FK_527D6F1823BFBED_idx');
        $this->addSql('ALTER TABLE staff RENAME INDEX idx_426ef39261190a32 TO FK_426EF39261190A32_idx');
        $this->addSql('DROP INDEX `PRIMARY` ON staff_equipo');
        $this->addSql('ALTER TABLE staff_equipo ADD PRIMARY KEY (equipo_id, staff_id)');
        $this->addSql('ALTER TABLE staff_equipo RENAME INDEX idx_c1e1e1b0d4d57cd TO FK_C1E1E1B0D4D57CD_idx');
        $this->addSql('ALTER TABLE staff_equipo RENAME INDEX idx_c1e1e1b023bfbed TO FK_C1E1E1B023BFBED_idx');
    }
}
