<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518141916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador ADD placajes_done INT DEFAULT NULL, ADD touch_total INT DEFAULT NULL, ADD touch_done INT DEFAULT NULL, ADD melee_total INT DEFAULT NULL, ADD melee_done INT DEFAULT NULL, ADD chute_palos_total INT DEFAULT NULL, ADD chute_palos_done INT DEFAULT NULL, ADD golpes_castigo_total INT DEFAULT NULL, ADD golpes_castigo_done INT DEFAULT NULL, DROP placajes, DROP melee, DROP chute_palos, DROP golpes_castigo, CHANGE touch placajes_total INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador ADD placajes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD touch INT DEFAULT NULL, ADD melee LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD chute_palos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD golpes_castigo LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', DROP placajes_total, DROP placajes_done, DROP touch_total, DROP touch_done, DROP melee_total, DROP melee_done, DROP chute_palos_total, DROP chute_palos_done, DROP golpes_castigo_total, DROP golpes_castigo_done');
    }
}
