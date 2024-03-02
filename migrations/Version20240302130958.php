<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302130958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cheque (id INT AUTO_INCREMENT NOT NULL, num BIGINT NOT NULL, numcompte BIGINT NOT NULL, montant DOUBLE PRECISION NOT NULL, signature VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_desac_ce (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, compteep_id INT DEFAULT NULL, raison VARCHAR(255) DEFAULT NULL, INDEX IDX_BACBE6E919EB6921 (client_id), INDEX IDX_BACBE6E960D2C719 (compteep_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typecredit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, taux DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typetaux (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, taux DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_desac_ce ADD CONSTRAINT FK_BACBE6E919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE demande_desac_ce ADD CONSTRAINT FK_BACBE6E960D2C719 FOREIGN KEY (compteep_id) REFERENCES compteep (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_desac_ce DROP FOREIGN KEY FK_BACBE6E919EB6921');
        $this->addSql('ALTER TABLE demande_desac_ce DROP FOREIGN KEY FK_BACBE6E960D2C719');
        $this->addSql('DROP TABLE cheque');
        $this->addSql('DROP TABLE demande_desac_ce');
        $this->addSql('DROP TABLE typecredit');
        $this->addSql('DROP TABLE typetaux');
    }
}
