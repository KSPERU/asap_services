<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120005250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tarjeta AS SELECT id, persona_id, numero_tarjeta, fecha_vencimiento, cvv FROM tarjeta');
        $this->addSql('DROP TABLE tarjeta');
        $this->addSql('CREATE TABLE tarjeta (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, persona_id INTEGER DEFAULT NULL, numero_tarjeta VARCHAR(255) NOT NULL, fecha_vencimiento VARCHAR(255) NOT NULL, cvv VARCHAR(255) NOT NULL, CONSTRAINT FK_AE90B786F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tarjeta (id, persona_id, numero_tarjeta, fecha_vencimiento, cvv) SELECT id, persona_id, numero_tarjeta, fecha_vencimiento, cvv FROM __temp__tarjeta');
        $this->addSql('DROP TABLE __temp__tarjeta');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AE90B786F5F88DB9 ON tarjeta (persona_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tarjeta AS SELECT id, persona_id, numero_tarjeta, fecha_vencimiento, cvv FROM tarjeta');
        $this->addSql('DROP TABLE tarjeta');
        $this->addSql('CREATE TABLE tarjeta (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, persona_id INTEGER DEFAULT NULL, numero_tarjeta VARCHAR(255) NOT NULL, fecha_vencimiento VARCHAR(255) NOT NULL, cvv VARCHAR(255) NOT NULL, CONSTRAINT FK_AE90B786F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tarjeta (id, persona_id, numero_tarjeta, fecha_vencimiento, cvv) SELECT id, persona_id, numero_tarjeta, fecha_vencimiento, cvv FROM __temp__tarjeta');
        $this->addSql('DROP TABLE __temp__tarjeta');
        $this->addSql('CREATE INDEX IDX_AE90B786F5F88DB9 ON tarjeta (persona_id)');
    }
}
