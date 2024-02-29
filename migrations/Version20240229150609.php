<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229150609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invest CHANGE idclient idclient INT DEFAULT NULL, CHANGE crypto crypto VARCHAR(255) DEFAULT NULL, CHANGE amount amount INT DEFAULT NULL, CHANGE action action VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invest CHANGE idclient idclient INT NOT NULL, CHANGE crypto crypto VARCHAR(255) NOT NULL, CHANGE action action VARCHAR(255) NOT NULL, CHANGE amount amount INT NOT NULL');
    }
}
