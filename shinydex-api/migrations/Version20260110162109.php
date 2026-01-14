<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260110162109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF219883967');
        $this->addSql('DROP INDEX IDX_79B16FF219883967 ON hunt_session');
        $this->addSql('ALTER TABLE hunt_session CHANGE method_id hunt_method_id INT NOT NULL');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF2F1E24924 FOREIGN KEY (hunt_method_id) REFERENCES hunt_method (id)');
        $this->addSql('CREATE INDEX IDX_79B16FF2F1E24924 ON hunt_session (hunt_method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF2F1E24924');
        $this->addSql('DROP INDEX IDX_79B16FF2F1E24924 ON hunt_session');
        $this->addSql('ALTER TABLE hunt_session CHANGE hunt_method_id method_id INT NOT NULL');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF219883967 FOREIGN KEY (method_id) REFERENCES hunt_method (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_79B16FF219883967 ON hunt_session (method_id)');
    }
}
