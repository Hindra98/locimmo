<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426214137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E81E969C5');
        $this->addSql('DROP INDEX IDX_32EB52E81E969C5 ON administrateur');
        $this->addSql('ALTER TABLE administrateur CHANGE utilisateurs_id utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E8FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_32EB52E8FB88E14F ON administrateur (utilisateur_id)');
        $this->addSql('ALTER TABLE proprietaires DROP FOREIGN KEY FK_74D75B731E969C5');
        $this->addSql('DROP INDEX IDX_74D75B731E969C5 ON proprietaires');
        $this->addSql('ALTER TABLE proprietaires CHANGE utilisateurs_id utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proprietaires ADD CONSTRAINT FK_74D75B73FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_74D75B73FB88E14F ON proprietaires (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E8FB88E14F');
        $this->addSql('DROP INDEX IDX_32EB52E8FB88E14F ON administrateur');
        $this->addSql('ALTER TABLE administrateur CHANGE utilisateur_id utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E81E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_32EB52E81E969C5 ON administrateur (utilisateurs_id)');
        $this->addSql('ALTER TABLE proprietaires DROP FOREIGN KEY FK_74D75B73FB88E14F');
        $this->addSql('DROP INDEX IDX_74D75B73FB88E14F ON proprietaires');
        $this->addSql('ALTER TABLE proprietaires CHANGE utilisateur_id utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE proprietaires ADD CONSTRAINT FK_74D75B731E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_74D75B731E969C5 ON proprietaires (utilisateurs_id)');
    }
}
