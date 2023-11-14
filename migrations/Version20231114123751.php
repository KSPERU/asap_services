<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114123751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calificacion (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, calificacion INT DEFAULT NULL, opinion LONGTEXT DEFAULT NULL, INDEX IDX_8A3AF218F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, usuario_id_id INT DEFAULT NULL, conversacion_id_id INT DEFAULT NULL, mensaje VARCHAR(255) DEFAULT NULL, fecha_creacion DATETIME DEFAULT NULL, INDEX IDX_659DF2AA629AF449 (usuario_id_id), INDEX IDX_659DF2AAAEE027EA (conversacion_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codigo (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, c_codigo VARCHAR(5) DEFAULT NULL, UNIQUE INDEX UNIQ_20332D99F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversacion (id INT AUTO_INCREMENT NOT NULL, ultimo_mensaje_id_id INT DEFAULT NULL, INDEX IDX_474049CF96988784 (ultimo_mensaje_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuenta_niubiz (id INT AUTO_INCREMENT NOT NULL, cd_idproveedor_id INT NOT NULL, cn_codigocomercio VARCHAR(30) NOT NULL, cn_usuario VARCHAR(50) NOT NULL, cn_password VARCHAR(255) NOT NULL, moneda VARCHAR(20) DEFAULT NULL, liquidacion VARCHAR(20) DEFAULT NULL, INDEX IDX_3F1BDFD782D54025 (cd_idproveedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ganancia_proveedor (id INT AUTO_INCREMENT NOT NULL, idproveedor_id INT NOT NULL, gp_total DOUBLE PRECISION NOT NULL, gp_fechaoperacion DATETIME NOT NULL, gp_estadotransferencia TINYINT(1) NOT NULL, gp_metodocobro VARCHAR(30) DEFAULT NULL, INDEX IDX_1FB3A6EE8D9F227A (idproveedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historialservicios (id INT AUTO_INCREMENT NOT NULL, idservicio_id INT NOT NULL, idcliente_id INT NOT NULL, idproveedor_id INT NOT NULL, hs_fecha DATETIME NOT NULL, hs_estado TINYINT(1) NOT NULL, hs_estadopago TINYINT(1) DEFAULT NULL, hs_importe DOUBLE PRECISION DEFAULT NULL, hs_direccion VARCHAR(255) NOT NULL, hs_estadocobro TINYINT(1) NOT NULL, INDEX IDX_36B05FE8B70BD348 (idservicio_id), INDEX IDX_36B05FE813DA536B (idcliente_id), INDEX IDX_36B05FE88D9F227A (idproveedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metcobro_proveedor (id INT AUTO_INCREMENT NOT NULL, idmetcobro_id INT NOT NULL, idproveedor_id INT NOT NULL, mcp_numerocuenta VARCHAR(50) NOT NULL, mcp_estado TINYINT(1) NOT NULL, INDEX IDX_AB492B8AB3763A4C (idmetcobro_id), INDEX IDX_AB492B8A8D9F227A (idproveedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metodocobro (id INT AUTO_INCREMENT NOT NULL, mc_descripcion VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participante (id INT AUTO_INCREMENT NOT NULL, usuario_id_id INT DEFAULT NULL, conversacion_id_id INT DEFAULT NULL, mensaje_leido DATETIME DEFAULT NULL, INDEX IDX_85BDC5C3629AF449 (usuario_id_id), INDEX IDX_85BDC5C3AEE027EA (conversacion_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, p_nombre VARCHAR(64) NOT NULL, p_apellido VARCHAR(64) NOT NULL, p_contacto VARCHAR(9) NOT NULL, p_direccion VARCHAR(255) NOT NULL, p_foto LONGTEXT DEFAULT NULL, p_cv LONGTEXT DEFAULT NULL, p_antpen LONGTEXT DEFAULT NULL, p_cert LONGTEXT DEFAULT NULL, p_biografia LONGTEXT DEFAULT NULL, p_experiencia VARCHAR(30) DEFAULT NULL, p_distrito VARCHAR(64) DEFAULT NULL, p_habilidades LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_51E5B69BDB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona_servicio (id INT AUTO_INCREMENT NOT NULL, id_persona_id INT NOT NULL, id_servicio_id INT NOT NULL, costoservicio DOUBLE PRECISION DEFAULT NULL, INDEX IDX_2B55E66B50720D6E (id_persona_id), INDEX IDX_2B55E66B69D86E10 (id_servicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicio (id INT AUTO_INCREMENT NOT NULL, sv_nombre VARCHAR(30) NOT NULL, svimagen LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tarjeta (id INT AUTO_INCREMENT NOT NULL, persona_id INT DEFAULT NULL, numero_tarjeta VARCHAR(255) NOT NULL, fecha_vencimiento VARCHAR(255) NOT NULL, cvv VARCHAR(255) NOT NULL, INDEX IDX_AE90B786F5F88DB9 (persona_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2265B05DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE calificacion ADD CONSTRAINT FK_8A3AF218F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA629AF449 FOREIGN KEY (usuario_id_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAAEE027EA FOREIGN KEY (conversacion_id_id) REFERENCES conversacion (id)');
        $this->addSql('ALTER TABLE codigo ADD CONSTRAINT FK_20332D99F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE conversacion ADD CONSTRAINT FK_474049CF96988784 FOREIGN KEY (ultimo_mensaje_id_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE cuenta_niubiz ADD CONSTRAINT FK_3F1BDFD782D54025 FOREIGN KEY (cd_idproveedor_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE ganancia_proveedor ADD CONSTRAINT FK_1FB3A6EE8D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE historialservicios ADD CONSTRAINT FK_36B05FE8B70BD348 FOREIGN KEY (idservicio_id) REFERENCES servicio (id)');
        $this->addSql('ALTER TABLE historialservicios ADD CONSTRAINT FK_36B05FE813DA536B FOREIGN KEY (idcliente_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE historialservicios ADD CONSTRAINT FK_36B05FE88D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE metcobro_proveedor ADD CONSTRAINT FK_AB492B8AB3763A4C FOREIGN KEY (idmetcobro_id) REFERENCES metodocobro (id)');
        $this->addSql('ALTER TABLE metcobro_proveedor ADD CONSTRAINT FK_AB492B8A8D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE participante ADD CONSTRAINT FK_85BDC5C3629AF449 FOREIGN KEY (usuario_id_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE participante ADD CONSTRAINT FK_85BDC5C3AEE027EA FOREIGN KEY (conversacion_id_id) REFERENCES conversacion (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BDB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE persona_servicio ADD CONSTRAINT FK_2B55E66B50720D6E FOREIGN KEY (id_persona_id) REFERENCES persona (id)');
        $this->addSql('ALTER TABLE persona_servicio ADD CONSTRAINT FK_2B55E66B69D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicio (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE tarjeta ADD CONSTRAINT FK_AE90B786F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calificacion DROP FOREIGN KEY FK_8A3AF218F5F88DB9');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA629AF449');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AAAEE027EA');
        $this->addSql('ALTER TABLE codigo DROP FOREIGN KEY FK_20332D99F5F88DB9');
        $this->addSql('ALTER TABLE conversacion DROP FOREIGN KEY FK_474049CF96988784');
        $this->addSql('ALTER TABLE cuenta_niubiz DROP FOREIGN KEY FK_3F1BDFD782D54025');
        $this->addSql('ALTER TABLE ganancia_proveedor DROP FOREIGN KEY FK_1FB3A6EE8D9F227A');
        $this->addSql('ALTER TABLE historialservicios DROP FOREIGN KEY FK_36B05FE8B70BD348');
        $this->addSql('ALTER TABLE historialservicios DROP FOREIGN KEY FK_36B05FE813DA536B');
        $this->addSql('ALTER TABLE historialservicios DROP FOREIGN KEY FK_36B05FE88D9F227A');
        $this->addSql('ALTER TABLE metcobro_proveedor DROP FOREIGN KEY FK_AB492B8AB3763A4C');
        $this->addSql('ALTER TABLE metcobro_proveedor DROP FOREIGN KEY FK_AB492B8A8D9F227A');
        $this->addSql('ALTER TABLE participante DROP FOREIGN KEY FK_85BDC5C3629AF449');
        $this->addSql('ALTER TABLE participante DROP FOREIGN KEY FK_85BDC5C3AEE027EA');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BDB38439E');
        $this->addSql('ALTER TABLE persona_servicio DROP FOREIGN KEY FK_2B55E66B50720D6E');
        $this->addSql('ALTER TABLE persona_servicio DROP FOREIGN KEY FK_2B55E66B69D86E10');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE tarjeta DROP FOREIGN KEY FK_AE90B786F5F88DB9');
        $this->addSql('DROP TABLE calificacion');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE codigo');
        $this->addSql('DROP TABLE conversacion');
        $this->addSql('DROP TABLE cuenta_niubiz');
        $this->addSql('DROP TABLE ganancia_proveedor');
        $this->addSql('DROP TABLE historialservicios');
        $this->addSql('DROP TABLE metcobro_proveedor');
        $this->addSql('DROP TABLE metodocobro');
        $this->addSql('DROP TABLE participante');
        $this->addSql('DROP TABLE persona');
        $this->addSql('DROP TABLE persona_servicio');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE servicio');
        $this->addSql('DROP TABLE tarjeta');
        $this->addSql('DROP TABLE usuario');
    }
}
