<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230321090927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens ADD nom VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE chambres DROP FOREIGN KEY FK_CHAMBRES');
        $this->addSql('DROP INDEX FK_CHAMBRES ON chambres');
        $this->addSql('ALTER TABLE proprietaires DROP FOREIGN KEY FK_UTILISATEURS');
        $this->addSql('DROP INDEX FK_UTILISATEURS ON proprietaires');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens DROP nom');
        $this->addSql('ALTER TABLE chambres ADD CONSTRAINT FK_CHAMBRES FOREIGN KEY (id_bien) REFERENCES biens (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX FK_CHAMBRES ON chambres (id_bien)');
        $this->addSql('ALTER TABLE proprietaires ADD CONSTRAINT FK_UTILISATEURS FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX FK_UTILISATEURS ON proprietaires (id_utilisateur)');
    }
}
