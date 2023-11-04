<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104001758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calificacion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, persona_id INTEGER DEFAULT NULL, calificacion INTEGER DEFAULT NULL, opinion CLOB DEFAULT NULL, CONSTRAINT FK_8A3AF218F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8A3AF218F5F88DB9 ON calificacion (persona_id)');
        $this->addSql('CREATE TABLE codigo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, persona_id INTEGER DEFAULT NULL, c_codigo VARCHAR(5) DEFAULT NULL, CONSTRAINT FK_20332D99F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_20332D99F5F88DB9 ON codigo (persona_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE calificacion');
        $this->addSql('DROP TABLE codigo');
    }
}
