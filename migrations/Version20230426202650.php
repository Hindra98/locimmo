<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426202650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE photo_biens');
        $this->addSql('ALTER TABLE abonnements ADD proprietaires_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE abonnements ADD CONSTRAINT FK_4788B767710ED0A5 FOREIGN KEY (proprietaires_id) REFERENCES proprietaires (id)');
        $this->addSql('CREATE INDEX IDX_4788B767710ED0A5 ON abonnements (proprietaires_id)');
        $this->addSql('ALTER TABLE administrateur ADD utilisateurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE administrateur ADD CONSTRAINT FK_32EB52E81E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_32EB52E81E969C5 ON administrateur (utilisateurs_id)');
        $this->addSql('ALTER TABLE appartements ADD biens_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE appartements ADD CONSTRAINT FK_8876962B7773350C FOREIGN KEY (biens_id) REFERENCES biens (id)');
        $this->addSql('CREATE INDEX IDX_8876962B7773350C ON appartements (biens_id)');
        $this->addSql('ALTER TABLE chambres ADD biens_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chambres ADD CONSTRAINT FK_36C929627773350C FOREIGN KEY (biens_id) REFERENCES biens (id)');
        $this->addSql('CREATE INDEX IDX_36C929627773350C ON chambres (biens_id)');
        $this->addSql('ALTER TABLE maisons ADD biens_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maisons ADD CONSTRAINT FK_28F4DE587773350C FOREIGN KEY (biens_id) REFERENCES biens (id)');
        $this->addSql('CREATE INDEX IDX_28F4DE587773350C ON maisons (biens_id)');
        $this->addSql('ALTER TABLE proprietaires ADD utilisateurs_id INT DEFAULT NULL, DROP id_utilisateur');
        $this->addSql('ALTER TABLE proprietaires ADD CONSTRAINT FK_74D75B731E969C5 FOREIGN KEY (utilisateurs_id) REFERENCES utilisateurs (id)');
        $this->addSql('CREATE INDEX IDX_74D75B731E969C5 ON proprietaires (utilisateurs_id)');
        $this->addSql('ALTER TABLE studios ADD biens_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE studios ADD CONSTRAINT FK_3946F2B57773350C FOREIGN KEY (biens_id) REFERENCES biens (id)');
        $this->addSql('CREATE INDEX IDX_3946F2B57773350C ON studios (biens_id)');
        $this->addSql('ALTER TABLE utilisateurs DROP nom, DROP email, DROP login, DROP password, DROP date_creation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE photo_biens (id INT AUTO_INCREMENT NOT NULL, id_bien SMALLINT NOT NULL, nom_image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE abonnements DROP FOREIGN KEY FK_4788B767710ED0A5');
        $this->addSql('DROP INDEX IDX_4788B767710ED0A5 ON abonnements');
        $this->addSql('ALTER TABLE abonnements DROP proprietaires_id');
        $this->addSql('ALTER TABLE administrateur DROP FOREIGN KEY FK_32EB52E81E969C5');
        $this->addSql('DROP INDEX IDX_32EB52E81E969C5 ON administrateur');
        $this->addSql('ALTER TABLE administrateur DROP utilisateurs_id');
        $this->addSql('ALTER TABLE appartements DROP FOREIGN KEY FK_8876962B7773350C');
        $this->addSql('DROP INDEX IDX_8876962B7773350C ON appartements');
        $this->addSql('ALTER TABLE appartements DROP biens_id');
        $this->addSql('ALTER TABLE chambres DROP FOREIGN KEY FK_36C929627773350C');
        $this->addSql('DROP INDEX IDX_36C929627773350C ON chambres');
        $this->addSql('ALTER TABLE chambres DROP biens_id');
        $this->addSql('ALTER TABLE maisons DROP FOREIGN KEY FK_28F4DE587773350C');
        $this->addSql('DROP INDEX IDX_28F4DE587773350C ON maisons');
        $this->addSql('ALTER TABLE maisons DROP biens_id');
        $this->addSql('ALTER TABLE proprietaires DROP FOREIGN KEY FK_74D75B731E969C5');
        $this->addSql('DROP INDEX IDX_74D75B731E969C5 ON proprietaires');
        $this->addSql('ALTER TABLE proprietaires ADD id_utilisateur INT NOT NULL, DROP utilisateurs_id');
        $this->addSql('ALTER TABLE studios DROP FOREIGN KEY FK_3946F2B57773350C');
        $this->addSql('DROP INDEX IDX_3946F2B57773350C ON studios');
        $this->addSql('ALTER TABLE studios DROP biens_id');
        $this->addSql('ALTER TABLE utilisateurs ADD nom VARCHAR(100) NOT NULL, ADD email VARCHAR(50) NOT NULL, ADD login VARCHAR(20) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD date_creation DATETIME NOT NULL');
    }
}
