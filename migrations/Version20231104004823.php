<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231104004823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cuenta_niubiz (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cd_idproveedor_id INTEGER NOT NULL, cn_codigocomercio VARCHAR(30) NOT NULL, cn_usuario VARCHAR(50) NOT NULL, cn_password VARCHAR(255) NOT NULL, moneda VARCHAR(20) DEFAULT NULL, liquidacion VARCHAR(20) DEFAULT NULL, CONSTRAINT FK_3F1BDFD782D54025 FOREIGN KEY (cd_idproveedor_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_3F1BDFD782D54025 ON cuenta_niubiz (cd_idproveedor_id)');
        $this->addSql('CREATE TABLE metcobro_proveedor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, idmetcobro_id INTEGER NOT NULL, idproveedor_id INTEGER NOT NULL, mcp_numerocuenta VARCHAR(50) NOT NULL, mcp_estado BOOLEAN NOT NULL, CONSTRAINT FK_AB492B8AB3763A4C FOREIGN KEY (idmetcobro_id) REFERENCES metodocobro (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_AB492B8A8D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AB492B8AB3763A4C ON metcobro_proveedor (idmetcobro_id)');
        $this->addSql('CREATE INDEX IDX_AB492B8A8D9F227A ON metcobro_proveedor (idproveedor_id)');
        $this->addSql('CREATE TABLE metodocobro (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mc_descripcion VARCHAR(30) NOT NULL)');
        $this->addSql('ALTER TABLE historialservicios ADD COLUMN hs_estadopago BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE historialservicios ADD COLUMN hs_importe DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE persona_servicio ADD COLUMN costoservicio DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cuenta_niubiz');
        $this->addSql('DROP TABLE metcobro_proveedor');
        $this->addSql('DROP TABLE metodocobro');
        $this->addSql('CREATE TEMPORARY TABLE __temp__historialservicios AS SELECT id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_direccion FROM historialservicios');
        $this->addSql('DROP TABLE historialservicios');
        $this->addSql('CREATE TABLE historialservicios (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, idservicio_id INTEGER NOT NULL, idcliente_id INTEGER NOT NULL, idproveedor_id INTEGER NOT NULL, hs_fecha DATETIME NOT NULL, hs_estado BOOLEAN NOT NULL, hs_direccion VARCHAR(255) NOT NULL, CONSTRAINT FK_36B05FE8B70BD348 FOREIGN KEY (idservicio_id) REFERENCES servicio (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_36B05FE813DA536B FOREIGN KEY (idcliente_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_36B05FE88D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO historialservicios (id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_direccion) SELECT id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_direccion FROM __temp__historialservicios');
        $this->addSql('DROP TABLE __temp__historialservicios');
        $this->addSql('CREATE INDEX IDX_36B05FE8B70BD348 ON historialservicios (idservicio_id)');
        $this->addSql('CREATE INDEX IDX_36B05FE813DA536B ON historialservicios (idcliente_id)');
        $this->addSql('CREATE INDEX IDX_36B05FE88D9F227A ON historialservicios (idproveedor_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__persona_servicio AS SELECT id, id_persona_id, id_servicio_id FROM persona_servicio');
        $this->addSql('DROP TABLE persona_servicio');
        $this->addSql('CREATE TABLE persona_servicio (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, id_persona_id INTEGER NOT NULL, id_servicio_id INTEGER NOT NULL, CONSTRAINT FK_2B55E66B50720D6E FOREIGN KEY (id_persona_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2B55E66B69D86E10 FOREIGN KEY (id_servicio_id) REFERENCES servicio (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO persona_servicio (id, id_persona_id, id_servicio_id) SELECT id, id_persona_id, id_servicio_id FROM __temp__persona_servicio');
        $this->addSql('DROP TABLE __temp__persona_servicio');
        $this->addSql('CREATE INDEX IDX_2B55E66B50720D6E ON persona_servicio (id_persona_id)');
        $this->addSql('CREATE INDEX IDX_2B55E66B69D86E10 ON persona_servicio (id_servicio_id)');
    }
}
