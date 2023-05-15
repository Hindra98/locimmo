<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230313173421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE proprietaires DROP FOREIGN KEY FK_UTILISATEURS');
        $this->addSql('DROP TABLE proprietaires');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE proprietaires (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT NOT NULL, tel INT NOT NULL, INDEX FK_UTILISATEURS (id_utilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE proprietaires ADD CONSTRAINT FK_UTILISATEURS FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
