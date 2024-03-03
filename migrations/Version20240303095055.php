<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303095055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit ADD typecredit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE2D394D65 FOREIGN KEY (typecredit_id) REFERENCES typecredit (id)');
        $this->addSql('CREATE INDEX IDX_1CC16EFE2D394D65 ON credit (typecredit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFE2D394D65');
        $this->addSql('DROP INDEX IDX_1CC16EFE2D394D65 ON credit');
        $this->addSql('ALTER TABLE credit DROP typecredit_id');
    }
}
