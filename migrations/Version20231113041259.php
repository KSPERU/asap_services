<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231113041259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ganancia_proveedor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, idproveedor_id INTEGER NOT NULL, gp_total DOUBLE PRECISION NOT NULL, gp_fechaoperacion DATETIME NOT NULL, gp_estadotransferencia BOOLEAN NOT NULL, gp_metodocobro VARCHAR(30) DEFAULT NULL, CONSTRAINT FK_1FB3A6EE8D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1FB3A6EE8D9F227A ON ganancia_proveedor (idproveedor_id)');
        $this->addSql('ALTER TABLE historialservicios ADD COLUMN hs_estadocobro BOOLEAN NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ganancia_proveedor');
        $this->addSql('CREATE TEMPORARY TABLE __temp__historialservicios AS SELECT id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_estadopago, hs_importe, hs_direccion FROM historialservicios');
        $this->addSql('DROP TABLE historialservicios');
        $this->addSql('CREATE TABLE historialservicios (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, idservicio_id INTEGER NOT NULL, idcliente_id INTEGER NOT NULL, idproveedor_id INTEGER NOT NULL, hs_fecha DATETIME NOT NULL, hs_estado BOOLEAN NOT NULL, hs_estadopago BOOLEAN DEFAULT NULL, hs_importe DOUBLE PRECISION DEFAULT NULL, hs_direccion VARCHAR(255) NOT NULL, CONSTRAINT FK_36B05FE8B70BD348 FOREIGN KEY (idservicio_id) REFERENCES servicio (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_36B05FE813DA536B FOREIGN KEY (idcliente_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_36B05FE88D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO historialservicios (id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_estadopago, hs_importe, hs_direccion) SELECT id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_estadopago, hs_importe, hs_direccion FROM __temp__historialservicios');
        $this->addSql('DROP TABLE __temp__historialservicios');
        $this->addSql('CREATE INDEX IDX_36B05FE8B70BD348 ON historialservicios (idservicio_id)');
        $this->addSql('CREATE INDEX IDX_36B05FE813DA536B ON historialservicios (idcliente_id)');
        $this->addSql('CREATE INDEX IDX_36B05FE88D9F227A ON historialservicios (idproveedor_id)');
    }
}
