<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240219111320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_desac_ce ADD compteep_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demande_desac_ce ADD CONSTRAINT FK_BACBE6E960D2C719 FOREIGN KEY (compteep_id) REFERENCES compteep (id)');
        $this->addSql('CREATE INDEX IDX_BACBE6E960D2C719 ON demande_desac_ce (compteep_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_desac_ce DROP FOREIGN KEY FK_BACBE6E960D2C719');
        $this->addSql('DROP INDEX IDX_BACBE6E960D2C719 ON demande_desac_ce');
        $this->addSql('ALTER TABLE demande_desac_ce DROP compteep_id');
    }
}
