<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211220082749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prog_meca (id INT AUTO_INCREMENT NOT NULL, typemachine VARCHAR(255) NOT NULL, client VARCHAR(255) NOT NULL, numprog VARCHAR(255) NOT NULL, datecreat DATETIME NOT NULL, ind VARCHAR(255) DEFAULT NULL, plan VARCHAR(255) DEFAULT NULL, step VARCHAR(255) DEFAULT NULL, compteur INT NOT NULL, refpiece VARCHAR(255) NOT NULL, desigpiece VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE prog_meca');
    }
}
