<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Alexandrump\IdToUuidDoctrine3\IdToUuidMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705230855 extends IdToUuidMigration
{
    public function postUp(Schema $schema): void
    {
        $this->migrate('staff');
        $this->migrate('equipo');
        $this->migrate('jugador');
    }
}

