<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231105173149 extends AbstractMigration
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
        $this->addSql('CREATE TABLE chat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id_id INTEGER DEFAULT NULL, conversacion_id_id INTEGER DEFAULT NULL, mensaje VARCHAR(255) DEFAULT NULL, fecha_creacion DATETIME DEFAULT NULL, CONSTRAINT FK_659DF2AA629AF449 FOREIGN KEY (usuario_id_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_659DF2AAAEE027EA FOREIGN KEY (conversacion_id_id) REFERENCES conversacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_659DF2AA629AF449 ON chat (usuario_id_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AAAEE027EA ON chat (conversacion_id_id)');
        $this->addSql('CREATE TABLE codigo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, persona_id INTEGER DEFAULT NULL, c_codigo VARCHAR(5) DEFAULT NULL, CONSTRAINT FK_20332D99F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_20332D99F5F88DB9 ON codigo (persona_id)');
        $this->addSql('CREATE TABLE conversacion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ultimo_mensaje_id_id INTEGER DEFAULT NULL, CONSTRAINT FK_474049CF96988784 FOREIGN KEY (ultimo_mensaje_id_id) REFERENCES chat (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_474049CF96988784 ON conversacion (ultimo_mensaje_id_id)');
        $this->addSql('CREATE TABLE participante (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id_id INTEGER DEFAULT NULL, conversacion_id_id INTEGER DEFAULT NULL, mensaje_leido DATETIME DEFAULT NULL, CONSTRAINT FK_85BDC5C3629AF449 FOREIGN KEY (usuario_id_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_85BDC5C3AEE027EA FOREIGN KEY (conversacion_id_id) REFERENCES conversacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_85BDC5C3629AF449 ON participante (usuario_id_id)');
        $this->addSql('CREATE INDEX IDX_85BDC5C3AEE027EA ON participante (conversacion_id_id)');
        $this->addSql('CREATE TABLE tarjeta (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, persona_id INTEGER DEFAULT NULL, numero_tarjeta VARCHAR(255) NOT NULL, fecha_vencimiento VARCHAR(255) NOT NULL, cvv VARCHAR(255) NOT NULL, CONSTRAINT FK_AE90B786F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_AE90B786F5F88DB9 ON tarjeta (persona_id)');
        $this->addSql('ALTER TABLE historialservicios ADD COLUMN hs_direccion VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE calificacion');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE codigo');
        $this->addSql('DROP TABLE conversacion');
        $this->addSql('DROP TABLE participante');
        $this->addSql('DROP TABLE tarjeta');
        $this->addSql('CREATE TEMPORARY TABLE __temp__historialservicios AS SELECT id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_estadopago, hs_importe FROM historialservicios');
        $this->addSql('DROP TABLE historialservicios');
        $this->addSql('CREATE TABLE historialservicios (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, idservicio_id INTEGER NOT NULL, idcliente_id INTEGER NOT NULL, idproveedor_id INTEGER NOT NULL, hs_fecha DATETIME NOT NULL, hs_estado BOOLEAN NOT NULL, hs_estadopago BOOLEAN DEFAULT NULL, hs_importe DOUBLE PRECISION DEFAULT NULL, CONSTRAINT FK_36B05FE8B70BD348 FOREIGN KEY (idservicio_id) REFERENCES servicio (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_36B05FE813DA536B FOREIGN KEY (idcliente_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_36B05FE88D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO historialservicios (id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_estadopago, hs_importe) SELECT id, idservicio_id, idcliente_id, idproveedor_id, hs_fecha, hs_estado, hs_estadopago, hs_importe FROM __temp__historialservicios');
        $this->addSql('DROP TABLE __temp__historialservicios');
        $this->addSql('CREATE INDEX IDX_36B05FE8B70BD348 ON historialservicios (idservicio_id)');
        $this->addSql('CREATE INDEX IDX_36B05FE813DA536B ON historialservicios (idcliente_id)');
        $this->addSql('CREATE INDEX IDX_36B05FE88D9F227A ON historialservicios (idproveedor_id)');
    }
}
