<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114205504 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE famille_fournisseur (famille_id INT NOT NULL, fournisseur_id INT NOT NULL, INDEX IDX_69745997A77B84 (famille_id), INDEX IDX_697459670C757F (fournisseur_id), PRIMARY KEY(famille_id, fournisseur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nuance_fournisseur (nuance_id INT NOT NULL, fournisseur_id INT NOT NULL, INDEX IDX_C1150BF7C24DEE99 (nuance_id), INDEX IDX_C1150BF7670C757F (fournisseur_id), PRIMARY KEY(nuance_id, fournisseur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE famille_fournisseur ADD CONSTRAINT FK_69745997A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE famille_fournisseur ADD CONSTRAINT FK_697459670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nuance_fournisseur ADD CONSTRAINT FK_C1150BF7C24DEE99 FOREIGN KEY (nuance_id) REFERENCES nuance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nuance_fournisseur ADD CONSTRAINT FK_C1150BF7670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE famille_fournisseur');
        $this->addSql('DROP TABLE nuance_fournisseur');
    }
}
