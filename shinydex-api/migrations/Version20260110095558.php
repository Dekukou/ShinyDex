<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260110095558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF2F1E24924');
        $this->addSql('DROP INDEX IDX_79B16FF2F1E24924 ON hunt_session');
        $this->addSql('ALTER TABLE hunt_session ADD method_id INT NOT NULL, ADD counter INT NOT NULL, ADD is_shiny_found TINYINT(1) NOT NULL, DROP hunt_method_id, DROP encounters, CHANGE started_at started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE ended_at ended_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF219883967 FOREIGN KEY (method_id) REFERENCES hunt_method (id)');
        $this->addSql('CREATE INDEX IDX_79B16FF219883967 ON hunt_session (method_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF219883967');
        $this->addSql('DROP INDEX IDX_79B16FF219883967 ON hunt_session');
        $this->addSql('ALTER TABLE hunt_session ADD hunt_method_id INT NOT NULL, ADD encounters INT NOT NULL, DROP method_id, DROP counter, DROP is_shiny_found, CHANGE started_at started_at DATETIME NOT NULL, CHANGE ended_at ended_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF2F1E24924 FOREIGN KEY (hunt_method_id) REFERENCES hunt_method (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_79B16FF2F1E24924 ON hunt_session (hunt_method_id)');
    }
}
