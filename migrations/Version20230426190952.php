<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426190952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaires ADD CONSTRAINT FK_D9BEC0C4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_D9BEC0C4FB88E14F ON commentaires (utilisateur_id)');
        $this->addSql('ALTER TABLE favoris ADD utilisateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_8933C432FB88E14F ON favoris (utilisateur_id)');
        $this->addSql('ALTER TABLE messages ADD utilisateur_send_id INT DEFAULT NULL, ADD utilisateur_receive_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E966998C9E9 FOREIGN KEY (utilisateur_send_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE messages ADD CONSTRAINT FK_DB021E967E01AFDA FOREIGN KEY (utilisateur_receive_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_DB021E966998C9E9 ON messages (utilisateur_send_id)');
        $this->addSql('CREATE INDEX IDX_DB021E967E01AFDA ON messages (utilisateur_receive_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaires DROP FOREIGN KEY FK_D9BEC0C4FB88E14F');
        $this->addSql('DROP INDEX IDX_D9BEC0C4FB88E14F ON commentaires');
        $this->addSql('ALTER TABLE commentaires DROP utilisateur_id');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432FB88E14F');
        $this->addSql('DROP INDEX IDX_8933C432FB88E14F ON favoris');
        $this->addSql('ALTER TABLE favoris DROP utilisateur_id');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E966998C9E9');
        $this->addSql('ALTER TABLE messages DROP FOREIGN KEY FK_DB021E967E01AFDA');
        $this->addSql('DROP INDEX IDX_DB021E966998C9E9 ON messages');
        $this->addSql('DROP INDEX IDX_DB021E967E01AFDA ON messages');
        $this->addSql('ALTER TABLE messages DROP utilisateur_send_id, DROP utilisateur_receive_id');
    }
}
