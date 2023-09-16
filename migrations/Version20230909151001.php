<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230909151001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE convocatoria DROP FOREIGN KEY FK_6D77302187A5F842');
        $this->addSql('ALTER TABLE convocatoria ADD CONSTRAINT FK_6D77302187A5F842 FOREIGN KEY (evento_id) REFERENCES eventos (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE convocatoria DROP FOREIGN KEY FK_6D77302187A5F842');
        $this->addSql('ALTER TABLE convocatoria ADD CONSTRAINT FK_6D77302187A5F842 FOREIGN KEY (evento_id) REFERENCES eventos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
