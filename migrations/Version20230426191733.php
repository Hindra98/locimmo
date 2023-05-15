<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426191733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens ADD chambre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biens ADD CONSTRAINT FK_1F9004DD9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambres (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1F9004DD9B177F54 ON biens (chambre_id)');
        $this->addSql('ALTER TABLE photos_biens ADD biens_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photos_biens ADD CONSTRAINT FK_C80ACC817773350C FOREIGN KEY (biens_id) REFERENCES biens (id)');
        $this->addSql('CREATE INDEX IDX_C80ACC817773350C ON photos_biens (biens_id)');
        $this->addSql('ALTER TABLE utilisateur ADD proprietaire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B376C50E4A FOREIGN KEY (proprietaire_id) REFERENCES proprietaires (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B376C50E4A ON utilisateur (proprietaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens DROP FOREIGN KEY FK_1F9004DD9B177F54');
        $this->addSql('DROP INDEX UNIQ_1F9004DD9B177F54 ON biens');
        $this->addSql('ALTER TABLE biens DROP chambre_id');
        $this->addSql('ALTER TABLE photos_biens DROP FOREIGN KEY FK_C80ACC817773350C');
        $this->addSql('DROP INDEX IDX_C80ACC817773350C ON photos_biens');
        $this->addSql('ALTER TABLE photos_biens DROP biens_id');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B376C50E4A');
        $this->addSql('DROP INDEX UNIQ_1D1C63B376C50E4A ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP proprietaire_id');
    }
}
