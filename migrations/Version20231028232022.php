<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231028232022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historialservicios (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, idservicio_id INTEGER NOT NULL, idcliente_id INTEGER NOT NULL, idproveedor_id INTEGER NOT NULL, hs_fecha DATETIME NOT NULL, hs_estado BOOLEAN NOT NULL, CONSTRAINT FK_36B05FE8B70BD348 FOREIGN KEY (idservicio_id) REFERENCES servicio (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_36B05FE813DA536B FOREIGN KEY (idcliente_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_36B05FE88D9F227A FOREIGN KEY (idproveedor_id) REFERENCES persona (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_36B05FE8B70BD348 ON historialservicios (idservicio_id)');
        $this->addSql('CREATE INDEX IDX_36B05FE813DA536B ON historialservicios (idcliente_id)');
        $this->addSql('CREATE INDEX IDX_36B05FE88D9F227A ON historialservicios (idproveedor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE historialservicios');
    }
}
