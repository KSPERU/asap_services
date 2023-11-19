<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119002809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promocion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, personacodigo_id INTEGER DEFAULT NULL, codigo VARCHAR(5) DEFAULT NULL, usado BOOLEAN DEFAULT NULL, CONSTRAINT FK_CD312F7C56A2CB7 FOREIGN KEY (personacodigo_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_CD312F7C56A2CB7 ON promocion (personacodigo_id)');
        $this->addSql('ALTER TABLE usuario ADD COLUMN google_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD COLUMN facebook_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE promocion');
        $this->addSql('CREATE TEMPORARY TABLE __temp__usuario AS SELECT id, email, roles, password, is_verified FROM usuario');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('CREATE TABLE usuario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO usuario (id, email, roles, password, is_verified) SELECT id, email, roles, password, is_verified FROM __temp__usuario');
        $this->addSql('DROP TABLE __temp__usuario');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DE7927C74 ON usuario (email)');
    }
}
