<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114145635 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre ADD nuance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appel_offre ADD CONSTRAINT FK_BC56FD47C24DEE99 FOREIGN KEY (nuance_id) REFERENCES nuance (id)');
        $this->addSql('CREATE INDEX IDX_BC56FD47C24DEE99 ON appel_offre (nuance_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appel_offre DROP FOREIGN KEY FK_BC56FD47C24DEE99');
        $this->addSql('DROP INDEX IDX_BC56FD47C24DEE99 ON appel_offre');
        $this->addSql('ALTER TABLE appel_offre DROP nuance_id');
    }
}
