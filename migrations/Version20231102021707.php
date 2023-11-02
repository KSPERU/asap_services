<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102021707 extends AbstractMigration
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
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cuenta_niubiz');
    }
}
