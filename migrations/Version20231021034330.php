<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021034330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__persona AS SELECT id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_servicio, p_foto, p_cv, p_antpen, p_cert FROM persona');
        $this->addSql('DROP TABLE persona');
        $this->addSql('CREATE TABLE persona (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id INTEGER DEFAULT NULL, p_nombre VARCHAR(64) NOT NULL, p_apellido VARCHAR(64) NOT NULL, p_contacto VARCHAR(9) NOT NULL, p_direccion VARCHAR(255) NOT NULL, p_distrito VARCHAR(64) DEFAULT NULL, p_foto CLOB DEFAULT NULL, p_cv CLOB DEFAULT NULL, p_antpen CLOB DEFAULT NULL, p_cert CLOB DEFAULT NULL, p_biografia CLOB DEFAULT NULL, p_experiencia VARCHAR(30) DEFAULT NULL, p_habilidades CLOB DEFAULT NULL, CONSTRAINT FK_51E5B69BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO persona (id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_distrito, p_foto, p_cv, p_antpen, p_cert) SELECT id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_servicio, p_foto, p_cv, p_antpen, p_cert FROM __temp__persona');
        $this->addSql('DROP TABLE __temp__persona');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51E5B69BDB38439E ON persona (usuario_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reset_password_request AS SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM reset_password_request');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reset_password_request (id, user_id, selector, hashed_token, requested_at, expires_at) SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM __temp__reset_password_request');
        $this->addSql('DROP TABLE __temp__reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__persona AS SELECT id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_distrito FROM persona');
        $this->addSql('DROP TABLE persona');
        $this->addSql('CREATE TABLE persona (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id INTEGER DEFAULT NULL, p_nombre VARCHAR(64) NOT NULL, p_apellido VARCHAR(64) NOT NULL, p_contacto VARCHAR(9) NOT NULL, p_direccion VARCHAR(255) NOT NULL, p_foto CLOB DEFAULT NULL, p_cv CLOB DEFAULT NULL, p_antpen CLOB DEFAULT NULL, p_cert CLOB DEFAULT NULL, p_servicio VARCHAR(64) DEFAULT NULL, p_correo VARCHAR(64) NOT NULL, CONSTRAINT FK_51E5B69BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO persona (id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_servicio) SELECT id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_distrito FROM __temp__persona');
        $this->addSql('DROP TABLE __temp__persona');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51E5B69BDB38439E ON persona (usuario_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reset_password_request AS SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM reset_password_request');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reset_password_request (id, user_id, selector, hashed_token, requested_at, expires_at) SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM __temp__reset_password_request');
        $this->addSql('DROP TABLE __temp__reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
    }
}
