<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230621194524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE divisiones (id INT AUTO_INCREMENT NOT NULL, temporada VARCHAR(255) DEFAULT NULL, cod_temporada_astfut VARCHAR(255) DEFAULT NULL, categoria VARCHAR(255) DEFAULT NULL, cod_categoria_astfut VARCHAR(255) DEFAULT NULL, division VARCHAR(255) DEFAULT NULL, cod_division_astfut VARCHAR(255) DEFAULT NULL, grupo VARCHAR(255) DEFAULT NULL, cod_grupo_astfut VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX equipo_UN ON equipo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE divisiones');
        $this->addSql('CREATE UNIQUE INDEX equipo_UN ON equipo (nombre, fecha_baja, categoria)');
    }
}
