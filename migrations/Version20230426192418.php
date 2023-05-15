<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426192418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnements (id INT AUTO_INCREMENT NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expire_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE administrateur (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE proprietaires ADD abonnement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proprietaires ADD CONSTRAINT FK_74D75B73F1D74413 FOREIGN KEY (abonnement_id) REFERENCES abonnements (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_74D75B73F1D74413 ON proprietaires (abonnement_id)');
        $this->addSql('ALTER TABLE utilisateur ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3642B8210 FOREIGN KEY (admin_id) REFERENCES administrateur (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3642B8210 ON utilisateur (admin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proprietaires DROP FOREIGN KEY FK_74D75B73F1D74413');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3642B8210');
        $this->addSql('DROP TABLE abonnements');
        $this->addSql('DROP TABLE administrateur');
        $this->addSql('DROP INDEX UNIQ_74D75B73F1D74413 ON proprietaires');
        $this->addSql('ALTER TABLE proprietaires DROP abonnement_id');
        $this->addSql('DROP INDEX UNIQ_1D1C63B3642B8210 ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP admin_id');
    }
}
