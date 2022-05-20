<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518142656 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador CHANGE placajes_total placajes_total VARCHAR(255) DEFAULT NULL, CHANGE tarjeta_amarilla tarjeta_amarilla VARCHAR(255) NOT NULL, CHANGE tarjeta_roja tarjeta_roja VARCHAR(255) NOT NULL, CHANGE ensayo ensayo VARCHAR(255) NOT NULL, CHANGE minutos_jugados minutos_jugados VARCHAR(255) NOT NULL, CHANGE placajes_done placajes_done VARCHAR(255) DEFAULT NULL, CHANGE touch_total touch_total VARCHAR(255) DEFAULT NULL, CHANGE touch_done touch_done VARCHAR(255) DEFAULT NULL, CHANGE melee_total melee_total VARCHAR(255) DEFAULT NULL, CHANGE melee_done melee_done VARCHAR(255) DEFAULT NULL, CHANGE chute_palos_total chute_palos_total VARCHAR(255) DEFAULT NULL, CHANGE chute_palos_done chute_palos_done VARCHAR(255) DEFAULT NULL, CHANGE golpes_castigo_total golpes_castigo_total VARCHAR(255) DEFAULT NULL, CHANGE golpes_castigo_done golpes_castigo_done VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador CHANGE tarjeta_amarilla tarjeta_amarilla INT NOT NULL, CHANGE tarjeta_roja tarjeta_roja INT NOT NULL, CHANGE ensayo ensayo INT NOT NULL, CHANGE minutos_jugados minutos_jugados INT NOT NULL, CHANGE placajes_total placajes_total INT DEFAULT NULL, CHANGE placajes_done placajes_done INT DEFAULT NULL, CHANGE touch_total touch_total INT DEFAULT NULL, CHANGE touch_done touch_done INT DEFAULT NULL, CHANGE melee_total melee_total INT DEFAULT NULL, CHANGE melee_done melee_done INT DEFAULT NULL, CHANGE chute_palos_total chute_palos_total INT DEFAULT NULL, CHANGE chute_palos_done chute_palos_done INT DEFAULT NULL, CHANGE golpes_castigo_total golpes_castigo_total INT DEFAULT NULL, CHANGE golpes_castigo_done golpes_castigo_done INT DEFAULT NULL');
    }
}
