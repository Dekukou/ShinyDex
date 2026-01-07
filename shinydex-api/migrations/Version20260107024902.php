<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260107024902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ability (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attack (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, power INT DEFAULT NULL, pp INT NOT NULL, damage_class VARCHAR(255) NOT NULL, INDEX IDX_47C02D3BC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ball (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, catch_rate_bonus DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE egg_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evolution_trigger (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, generation_id INT NOT NULL, name VARCHAR(255) NOT NULL, version_group VARCHAR(255) NOT NULL, INDEX IDX_232B318C553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE generation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hunt_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hunt_session (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, pokemon_id INT NOT NULL, game_id INT NOT NULL, hunt_method_id INT NOT NULL, encounters INT NOT NULL, success TINYINT(1) NOT NULL, INDEX IDX_79B16FF2A76ED395 (user_id), INDEX IDX_79B16FF22FE71C3E (pokemon_id), INDEX IDX_79B16FF2E48FD905 (game_id), INDEX IDX_79B16FF2F1E24924 (hunt_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE machine (id INT AUTO_INCREMENT NOT NULL, attack_id INT NOT NULL, generation_id INT NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_1505DF84F5315759 (attack_id), INDEX IDX_1505DF84553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, species_id INT NOT NULL, region_form_id INT DEFAULT NULL, is_default_form TINYINT(1) NOT NULL, box_order INT NOT NULL, INDEX IDX_62DC90F3B2A1D860 (species_id), INDEX IDX_62DC90F344E4EAFA (region_form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_ability (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, ability_id INT NOT NULL, is_hidden TINYINT(1) NOT NULL, INDEX IDX_59A592AD2FE71C3E (pokemon_id), INDEX IDX_59A592AD8016D8B2 (ability_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_attack_level (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, attack_id INT NOT NULL, game_id INT NOT NULL, level INT NOT NULL, INDEX IDX_52132CE2FE71C3E (pokemon_id), INDEX IDX_52132CEF5315759 (attack_id), INDEX IDX_52132CEE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_attack_machine (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, machine_id INT NOT NULL, INDEX IDX_7B9624062FE71C3E (pokemon_id), INDEX IDX_7B962406F6B75B26 (machine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_capture_history (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, pokemon_id INT NOT NULL, game_id INT NOT NULL, ball_id INT NOT NULL, hunt_method_id INT NOT NULL, is_shiny TINYINT(1) NOT NULL, encounter_count INT DEFAULT NULL, INDEX IDX_68581DCDA76ED395 (user_id), INDEX IDX_68581DCD2FE71C3E (pokemon_id), INDEX IDX_68581DCDE48FD905 (game_id), INDEX IDX_68581DCDF6DF9098 (ball_id), INDEX IDX_68581DCDF1E24924 (hunt_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_egg_group (id INT AUTO_INCREMENT NOT NULL, species_id INT NOT NULL, egg_group_id INT NOT NULL, INDEX IDX_311E850B2A1D860 (species_id), INDEX IDX_311E850B76DC94C (egg_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_evolution (id INT AUTO_INCREMENT NOT NULL, from_species_id INT NOT NULL, to_species_id INT NOT NULL, evolution_trigger_id INT NOT NULL, item_id INT DEFAULT NULL, required_region_form_id INT DEFAULT NULL, min_level INT DEFAULT NULL, INDEX IDX_73E5C2B599051E67 (from_species_id), INDEX IDX_73E5C2B595923A1D (to_species_id), INDEX IDX_73E5C2B5AD65CE5B (evolution_trigger_id), INDEX IDX_73E5C2B5126F525E (item_id), INDEX IDX_73E5C2B59F6A66CB (required_region_form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_family (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_species (id INT AUTO_INCREMENT NOT NULL, generation_id INT NOT NULL, pokedex_number INT NOT NULL, name_en VARCHAR(255) NOT NULL, name_fr VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C9658B832996FAF8 (pokedex_number), INDEX IDX_C9658B83553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_sprite (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, variant VARCHAR(255) NOT NULL, is_shiny TINYINT(1) NOT NULL, url VARCHAR(255) NOT NULL, is_official_artwork TINYINT(1) NOT NULL, INDEX IDX_59F4F3CA2FE71C3E (pokemon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_type (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, type_id INT NOT NULL, slot INT NOT NULL, INDEX IDX_B077296A2FE71C3E (pokemon_id), INDEX IDX_B077296AC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region_form (id INT AUTO_INCREMENT NOT NULL, generation_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_8B2EEA78553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attack ADD CONSTRAINT FK_47C02D3BC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF22FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF2E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF2F1E24924 FOREIGN KEY (hunt_method_id) REFERENCES hunt_method (id)');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF84F5315759 FOREIGN KEY (attack_id) REFERENCES attack (id)');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF84553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3B2A1D860 FOREIGN KEY (species_id) REFERENCES pokemon_species (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F344E4EAFA FOREIGN KEY (region_form_id) REFERENCES region_form (id)');
        $this->addSql('ALTER TABLE pokemon_ability ADD CONSTRAINT FK_59A592AD2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_ability ADD CONSTRAINT FK_59A592AD8016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id)');
        $this->addSql('ALTER TABLE pokemon_attack_level ADD CONSTRAINT FK_52132CE2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_attack_level ADD CONSTRAINT FK_52132CEF5315759 FOREIGN KEY (attack_id) REFERENCES attack (id)');
        $this->addSql('ALTER TABLE pokemon_attack_level ADD CONSTRAINT FK_52132CEE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE pokemon_attack_machine ADD CONSTRAINT FK_7B9624062FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_attack_machine ADD CONSTRAINT FK_7B962406F6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCD2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCDE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCDF6DF9098 FOREIGN KEY (ball_id) REFERENCES ball (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCDF1E24924 FOREIGN KEY (hunt_method_id) REFERENCES hunt_method (id)');
        $this->addSql('ALTER TABLE pokemon_egg_group ADD CONSTRAINT FK_311E850B2A1D860 FOREIGN KEY (species_id) REFERENCES pokemon_species (id)');
        $this->addSql('ALTER TABLE pokemon_egg_group ADD CONSTRAINT FK_311E850B76DC94C FOREIGN KEY (egg_group_id) REFERENCES egg_group (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B599051E67 FOREIGN KEY (from_species_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B595923A1D FOREIGN KEY (to_species_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B5AD65CE5B FOREIGN KEY (evolution_trigger_id) REFERENCES evolution_trigger (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B5126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B59F6A66CB FOREIGN KEY (required_region_form_id) REFERENCES region_form (id)');
        $this->addSql('ALTER TABLE pokemon_species ADD CONSTRAINT FK_C9658B83553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('ALTER TABLE pokemon_sprite ADD CONSTRAINT FK_59F4F3CA2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296A2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE region_form ADD CONSTRAINT FK_8B2EEA78553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attack DROP FOREIGN KEY FK_47C02D3BC54C8C93');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C553A6EC4');
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF2A76ED395');
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF22FE71C3E');
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF2E48FD905');
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF2F1E24924');
        $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF84F5315759');
        $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF84553A6EC4');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3B2A1D860');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F344E4EAFA');
        $this->addSql('ALTER TABLE pokemon_ability DROP FOREIGN KEY FK_59A592AD2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_ability DROP FOREIGN KEY FK_59A592AD8016D8B2');
        $this->addSql('ALTER TABLE pokemon_attack_level DROP FOREIGN KEY FK_52132CE2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_attack_level DROP FOREIGN KEY FK_52132CEF5315759');
        $this->addSql('ALTER TABLE pokemon_attack_level DROP FOREIGN KEY FK_52132CEE48FD905');
        $this->addSql('ALTER TABLE pokemon_attack_machine DROP FOREIGN KEY FK_7B9624062FE71C3E');
        $this->addSql('ALTER TABLE pokemon_attack_machine DROP FOREIGN KEY FK_7B962406F6B75B26');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCDA76ED395');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCD2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCDE48FD905');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCDF6DF9098');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCDF1E24924');
        $this->addSql('ALTER TABLE pokemon_egg_group DROP FOREIGN KEY FK_311E850B2A1D860');
        $this->addSql('ALTER TABLE pokemon_egg_group DROP FOREIGN KEY FK_311E850B76DC94C');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B599051E67');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B595923A1D');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B5AD65CE5B');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B5126F525E');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B59F6A66CB');
        $this->addSql('ALTER TABLE pokemon_species DROP FOREIGN KEY FK_C9658B83553A6EC4');
        $this->addSql('ALTER TABLE pokemon_sprite DROP FOREIGN KEY FK_59F4F3CA2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_type DROP FOREIGN KEY FK_B077296A2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_type DROP FOREIGN KEY FK_B077296AC54C8C93');
        $this->addSql('ALTER TABLE region_form DROP FOREIGN KEY FK_8B2EEA78553A6EC4');
        $this->addSql('DROP TABLE ability');
        $this->addSql('DROP TABLE attack');
        $this->addSql('DROP TABLE ball');
        $this->addSql('DROP TABLE egg_group');
        $this->addSql('DROP TABLE evolution_trigger');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE generation');
        $this->addSql('DROP TABLE hunt_method');
        $this->addSql('DROP TABLE hunt_session');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE machine');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_ability');
        $this->addSql('DROP TABLE pokemon_attack_level');
        $this->addSql('DROP TABLE pokemon_attack_machine');
        $this->addSql('DROP TABLE pokemon_capture_history');
        $this->addSql('DROP TABLE pokemon_egg_group');
        $this->addSql('DROP TABLE pokemon_evolution');
        $this->addSql('DROP TABLE pokemon_family');
        $this->addSql('DROP TABLE pokemon_species');
        $this->addSql('DROP TABLE pokemon_sprite');
        $this->addSql('DROP TABLE pokemon_type');
        $this->addSql('DROP TABLE region_form');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
