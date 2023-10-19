<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019201715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE persona (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id INTEGER DEFAULT NULL, p_nombre VARCHAR(64) NOT NULL, p_apellido VARCHAR(64) NOT NULL, p_contacto VARCHAR(9) NOT NULL, p_direccion VARCHAR(255) NOT NULL, p_foto CLOB DEFAULT NULL, p_cv CLOB DEFAULT NULL, p_antpen CLOB DEFAULT NULL, p_cert CLOB DEFAULT NULL, p_biografia CLOB DEFAULT NULL, p_experiencia VARCHAR(30) DEFAULT NULL, p_distrito VARCHAR(64) DEFAULT NULL, p_habilidades CLOB DEFAULT NULL, CONSTRAINT FK_51E5B69BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51E5B69BDB38439E ON persona (usuario_id)');
        $this->addSql('CREATE TABLE persona_servicio (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_persona_id INTEGER NOT NULL, id_servicio_id INTEGER NOT NULL, CONSTRAINT FK_2B55E66B50720D6E FOREIGN KEY (id_persona_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2B55E66B69D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicio (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2B55E66B50720D6E ON persona_servicio (id_persona_id)');
        $this->addSql('CREATE INDEX IDX_2B55E66B69D86E10 ON persona_servicio (id_servicio_id)');
        $this->addSql('CREATE TABLE servicio (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sv_nombre VARCHAR(30) NOT NULL)');
        $this->addSql('CREATE TABLE usuario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DE7927C74 ON usuario (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE persona_servicio');
        $this->addSql('DROP TABLE servicio');
        $this->addSql('DROP TABLE usuario');
    }
}
