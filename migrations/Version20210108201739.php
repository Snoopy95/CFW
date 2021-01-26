<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210108201739 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appel_offre (id INT AUTO_INCREMENT NOT NULL, famille_id INT NOT NULL, client VARCHAR(255) NOT NULL, matiere VARCHAR(255) NOT NULL, debit VARCHAR(255) NOT NULL, epaisseur INT NOT NULL, quantite INT NOT NULL, naf INT NOT NULL, statut VARCHAR(255) NOT NULL, datecreat DATETIME NOT NULL, INDEX IDX_BC56FD4797A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, nomfamille VARCHAR(255) NOT NULL, codefamille VARCHAR(255) NOT NULL, statutfamille VARCHAR(255) NOT NULL, datecreat DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD4797A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD4797A77B84');
        $this->addSql('DROP TABLE appel_offre');
        $this->addSql('DROP TABLE famille');
    }
}
