<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516155058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador ADD ano_nacimiento INT NOT NULL, ADD es_chutador TINYINT(1) NOT NULL, DROP fecha_nacimiento, CHANGE posicion posicion INT NOT NULL, CHANGE golpes_castigo golpes_castigo LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador ADD fecha_nacimiento DATE NOT NULL, DROP ano_nacimiento, DROP es_chutador, CHANGE posicion posicion LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE golpes_castigo golpes_castigo LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
