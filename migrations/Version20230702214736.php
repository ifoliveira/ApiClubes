<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230702214736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE convocatoria (id INT AUTO_INCREMENT NOT NULL, evento_id INT DEFAULT NULL, jugador_id INT DEFAULT NULL, asistencia TINYINT(1) DEFAULT NULL, justificado TINYINT(1) DEFAULT NULL, titular TINYINT(1) DEFAULT NULL, minutos INT DEFAULT NULL, INDEX IDX_6D77302187A5F842 (evento_id), INDEX IDX_6D773021B8A54D43 (jugador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eventos (id INT AUTO_INCREMENT NOT NULL, equipo_id INT DEFAULT NULL, fecha DATE NOT NULL, hora_ini TIME NOT NULL, hora_fin TIME NOT NULL, lugar VARCHAR(255) DEFAULT NULL, tipo VARCHAR(255) DEFAULT NULL, INDEX IDX_6B23BD8F23BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE convocatoria ADD CONSTRAINT FK_6D77302187A5F842 FOREIGN KEY (evento_id) REFERENCES eventos (id)');
        $this->addSql('ALTER TABLE convocatoria ADD CONSTRAINT FK_6D773021B8A54D43 FOREIGN KEY (jugador_id) REFERENCES jugador (id)');
        $this->addSql('ALTER TABLE eventos ADD CONSTRAINT FK_6B23BD8F23BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE convocatoria DROP FOREIGN KEY FK_6D77302187A5F842');
        $this->addSql('ALTER TABLE convocatoria DROP FOREIGN KEY FK_6D773021B8A54D43');
        $this->addSql('ALTER TABLE eventos DROP FOREIGN KEY FK_6B23BD8F23BFBED');
        $this->addSql('DROP TABLE convocatoria');
        $this->addSql('DROP TABLE eventos');
    }
}
