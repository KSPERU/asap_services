<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029033749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historialservicios (id INT AUTO_INCREMENT NOT NULL, idservicio_id INT NOT NULL, idcliente_id INT NOT NULL, idproveedor_id INT NOT NULL, hs_fecha DATETIME NOT NULL, hs_estado TINYINT(1) NOT NULL, INDEX IDX_36B05FE8B70BD348 (idservicio_id), INDEX IDX_36B05FE813DA536B (idcliente_id), INDEX IDX_36B05FE88D9F227A (idproveedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, p_nombre VARCHAR(64) NOT NULL, p_apellido VARCHAR(64) NOT NULL, p_contacto VARCHAR(9) NOT NULL, p_direccion VARCHAR(255) NOT NULL, p_foto LONGTEXT DEFAULT NULL, p_cv LONGTEXT DEFAULT NULL, p_antpen LONGTEXT DEFAULT NULL, p_cert LONGTEXT DEFAULT NULL, p_biografia LONGTEXT DEFAULT NULL, p_experiencia VARCHAR(30) DEFAULT NULL, p_distrito VARCHAR(64) DEFAULT NULL, p_habilidades LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_51E5B69BDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona_servicio (id INT AUTO_INCREMENT NOT NULL, id_persona_id INT NOT NULL, id_servicio_id INT NOT NULL, INDEX IDX_2B55E66B50720D6E (id_persona_id), INDEX IDX_2B55E66B69D86E10 (id_servicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicio (id INT AUTO_INCREMENT NOT NULL, sv_nombre VARCHAR(30) NOT NULL, svimagen LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE historialservicios ADD CONSTRAINT FK_36B05FE8B70BD348 FOREIGN KEY (idservicio_id) REFERENCES servicio (id)');
        $this->addSql('ALTER TABLE historialservicios ADD CONSTRAINT FK_36B05FE813DA536B FOREIGN KEY (idcliente_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE historialservicios ADD CONSTRAINT FK_36B05FE88D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE persona_servicio ADD CONSTRAINT FK_2B55E66B50720D6E FOREIGN KEY (id_persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE persona_servicio ADD CONSTRAINT FK_2B55E66B69D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicio (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historialservicios DROP FOREIGN KEY FK_36B05FE8B70BD348');
        $this->addSql('ALTER TABLE historialservicios DROP FOREIGN KEY FK_36B05FE813DA536B');
        $this->addSql('ALTER TABLE historialservicios DROP FOREIGN KEY FK_36B05FE88D9F227A');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BDB38439E');
        $this->addSql('ALTER TABLE persona_servicio DROP FOREIGN KEY FK_2B55E66B50720D6E');
        $this->addSql('ALTER TABLE persona_servicio DROP FOREIGN KEY FK_2B55E66B69D86E10');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE historialservicios');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE persona_servicio');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE servicio');
        $this->addSql('DROP TABLE usuario');
    }
}
