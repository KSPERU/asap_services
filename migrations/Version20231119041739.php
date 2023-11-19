<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119041739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorito (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, persona_id INTEGER DEFAULT NULL, servicio_id INTEGER DEFAULT NULL, favorito BOOLEAN DEFAULT NULL, CONSTRAINT FK_881067C7F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_881067C771CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_881067C7F5F88DB9 ON favorito (persona_id)');
        $this->addSql('CREATE INDEX IDX_881067C771CAA3E7 ON favorito (servicio_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__persona AS SELECT id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_biografia, p_experiencia, p_distrito, p_habilidades FROM persona');
        $this->addSql('DROP TABLE persona');
        $this->addSql('CREATE TABLE persona (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id INTEGER DEFAULT NULL, promocion_id INTEGER DEFAULT NULL, p_nombre VARCHAR(64) NOT NULL, p_apellido VARCHAR(64) NOT NULL, p_contacto VARCHAR(9) NOT NULL, p_direccion VARCHAR(255) NOT NULL, p_foto CLOB DEFAULT NULL, p_cv CLOB DEFAULT NULL, p_antpen CLOB DEFAULT NULL, p_cert CLOB DEFAULT NULL, p_biografia CLOB DEFAULT NULL, p_experiencia VARCHAR(30) DEFAULT NULL, p_distrito VARCHAR(64) DEFAULT NULL, p_habilidades CLOB DEFAULT NULL, CONSTRAINT FK_51E5B69BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_51E5B69BB1E453D4 FOREIGN KEY (promocion_id) REFERENCES promocion (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO persona (id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_biografia, p_experiencia, p_distrito, p_habilidades) SELECT id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_biografia, p_experiencia, p_distrito, p_habilidades FROM __temp__persona');
        $this->addSql('DROP TABLE __temp__persona');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51E5B69BDB38439E ON persona (usuario_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51E5B69BB1E453D4 ON persona (promocion_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__promocion AS SELECT id, codigo, usado FROM promocion');
        $this->addSql('DROP TABLE promocion');
        $this->addSql('CREATE TABLE promocion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, codigo VARCHAR(5) DEFAULT NULL, usado BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO promocion (id, codigo, usado) SELECT id, codigo, usado FROM __temp__promocion');
        $this->addSql('DROP TABLE __temp__promocion');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reset_password_request AS SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM reset_password_request');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reset_password_request (id, user_id, selector, hashed_token, requested_at, expires_at) SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM __temp__reset_password_request');
        $this->addSql('DROP TABLE __temp__reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__usuario AS SELECT id, email, roles, password, is_verified, google_id, facebook_id FROM usuario');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('CREATE TABLE usuario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, google_id VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO usuario (id, email, roles, password, is_verified, google_id, facebook_id) SELECT id, email, roles, password, is_verified, google_id, facebook_id FROM __temp__usuario');
        $this->addSql('DROP TABLE __temp__usuario');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DE7927C74 ON usuario (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE favorito');
        $this->addSql('CREATE TEMPORARY TABLE __temp__persona AS SELECT id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_biografia, p_experiencia, p_distrito, p_habilidades FROM persona');
        $this->addSql('DROP TABLE persona');
        $this->addSql('CREATE TABLE persona (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id INTEGER DEFAULT NULL, p_nombre VARCHAR(64) NOT NULL, p_apellido VARCHAR(64) NOT NULL, p_contacto VARCHAR(9) NOT NULL, p_direccion VARCHAR(255) NOT NULL, p_foto CLOB DEFAULT NULL, p_cv CLOB DEFAULT NULL, p_antpen CLOB DEFAULT NULL, p_cert CLOB DEFAULT NULL, p_biografia CLOB DEFAULT NULL, p_experiencia VARCHAR(30) DEFAULT NULL, p_distrito VARCHAR(64) DEFAULT NULL, p_habilidades CLOB DEFAULT NULL, CONSTRAINT FK_51E5B69BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO persona (id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_biografia, p_experiencia, p_distrito, p_habilidades) SELECT id, usuario_id, p_nombre, p_apellido, p_contacto, p_direccion, p_foto, p_cv, p_antpen, p_cert, p_biografia, p_experiencia, p_distrito, p_habilidades FROM __temp__persona');
        $this->addSql('DROP TABLE __temp__persona');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51E5B69BDB38439E ON persona (usuario_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__promocion AS SELECT id, codigo, usado FROM promocion');
        $this->addSql('DROP TABLE promocion');
        $this->addSql('CREATE TABLE promocion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, personacodigo_id INTEGER DEFAULT NULL, codigo VARCHAR(5) DEFAULT NULL, usado BOOLEAN DEFAULT NULL, CONSTRAINT FK_CD312F7C56A2CB7 FOREIGN KEY (personacodigo_id) REFERENCES persona (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO promocion (id, codigo, usado) SELECT id, codigo, usado FROM __temp__promocion');
        $this->addSql('DROP TABLE __temp__promocion');
        $this->addSql('CREATE INDEX IDX_CD312F7C56A2CB7 ON promocion (personacodigo_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__reset_password_request AS SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM reset_password_request');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('CREATE TABLE reset_password_request (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , expires_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO reset_password_request (id, user_id, selector, hashed_token, requested_at, expires_at) SELECT id, user_id, selector, hashed_token, requested_at, expires_at FROM __temp__reset_password_request');
        $this->addSql('DROP TABLE __temp__reset_password_request');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__usuario AS SELECT id, email, roles, password, is_verified, google_id, facebook_id FROM usuario');
        $this->addSql('DROP TABLE usuario');
        $this->addSql('CREATE TABLE usuario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, google_id VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO usuario (id, email, roles, password, is_verified, google_id, facebook_id) SELECT id, email, roles, password, is_verified, google_id, facebook_id FROM __temp__usuario');
        $this->addSql('DROP TABLE __temp__usuario');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DE7927C74 ON usuario (email)');
    }
}
