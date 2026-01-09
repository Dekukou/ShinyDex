<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260108230511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ability (id INT AUTO_INCREMENT NOT NULL, api_name VARCHAR(150) NOT NULL, name_fr VARCHAR(150) NOT NULL, name_en VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_35CFEE3C7FD408F5 (api_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attack (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, api_name VARCHAR(150) NOT NULL, name VARCHAR(150) NOT NULL, power INT DEFAULT NULL, accuracy INT DEFAULT NULL, pp INT DEFAULT NULL, damage_class VARCHAR(20) NOT NULL, priority INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_47C02D3BC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ball (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, sprite_path VARCHAR(255) DEFAULT NULL, is_usable TINYINT(1) DEFAULT 1 NOT NULL, is_legend_arceus TINYINT(1) DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_7432485B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE egg_group (id INT AUTO_INCREMENT NOT NULL, api_name VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_32F802767FD408F5 (api_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evolution_trigger (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_668FE19B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, version_group_id INT NOT NULL, generation_id INT NOT NULL, name VARCHAR(150) NOT NULL, INDEX IDX_232B318C92AE854F (version_group_id), INDEX IDX_232B318C553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE generation (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_D3266C3B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hunt_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, UNIQUE INDEX UNIQ_174D4F8C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hunt_session (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, pokemon_id INT NOT NULL, game_id INT NOT NULL, hunt_method_id INT NOT NULL, encounters INT NOT NULL, started_at DATETIME NOT NULL, ended_at DATETIME DEFAULT NULL, INDEX IDX_79B16FF2A76ED395 (user_id), INDEX IDX_79B16FF22FE71C3E (pokemon_id), INDEX IDX_79B16FF2E48FD905 (game_id), INDEX IDX_79B16FF2F1E24924 (hunt_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, api_name VARCHAR(100) NOT NULL, name_en VARCHAR(150) NOT NULL, name_fr VARCHAR(150) NOT NULL, UNIQUE INDEX UNIQ_1F1B251E7FD408F5 (api_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE machine (id INT AUTO_INCREMENT NOT NULL, version_group_id INT NOT NULL, attack_id INT NOT NULL, name VARCHAR(50) NOT NULL, INDEX IDX_1505DF8492AE854F (version_group_id), INDEX IDX_1505DF84F5315759 (attack_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, species_id INT NOT NULL, family_id INT DEFAULT NULL, generation_id INT DEFAULT NULL, region_form_id INT DEFAULT NULL, form_key VARCHAR(150) NOT NULL, name_fr VARCHAR(150) DEFAULT NULL, name_en VARCHAR(150) DEFAULT NULL, is_default TINYINT(1) NOT NULL, hp INT DEFAULT NULL, attack INT DEFAULT NULL, defense INT DEFAULT NULL, special_attack INT DEFAULT NULL, special_defense INT DEFAULT NULL, speed INT DEFAULT NULL, UNIQUE INDEX UNIQ_62DC90F3CFAF3820 (form_key), INDEX IDX_62DC90F3B2A1D860 (species_id), INDEX IDX_62DC90F3C35E566A (family_id), INDEX IDX_62DC90F3553A6EC4 (generation_id), INDEX IDX_62DC90F344E4EAFA (region_form_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_egg_group (pokemon_id INT NOT NULL, egg_group_id INT NOT NULL, INDEX IDX_311E8502FE71C3E (pokemon_id), INDEX IDX_311E850B76DC94C (egg_group_id), PRIMARY KEY(pokemon_id, egg_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_ability (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, ability_id INT NOT NULL, is_hidden TINYINT(1) NOT NULL, slot INT NOT NULL, INDEX IDX_59A592AD2FE71C3E (pokemon_id), INDEX IDX_59A592AD8016D8B2 (ability_id), UNIQUE INDEX uniq_pokemon_ability (pokemon_id, ability_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_capture_history (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, user_id INT NOT NULL, game_id INT NOT NULL, ball_id INT NOT NULL, hunt_method_id INT NOT NULL, is_shiny TINYINT(1) NOT NULL, gender VARCHAR(10) DEFAULT NULL, form_key VARCHAR(50) DEFAULT NULL, level INT DEFAULT NULL, is_alpha TINYINT(1) DEFAULT NULL, captured_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', notes LONGTEXT DEFAULT NULL, INDEX IDX_68581DCD2FE71C3E (pokemon_id), INDEX IDX_68581DCDA76ED395 (user_id), INDEX IDX_68581DCDE48FD905 (game_id), INDEX IDX_68581DCDF6DF9098 (ball_id), INDEX IDX_68581DCDF1E24924 (hunt_method_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_evolution (id INT AUTO_INCREMENT NOT NULL, from_pokemon_id INT NOT NULL, to_pokemon_id INT NOT NULL, trigger_id INT NOT NULL, required_item_id INT DEFAULT NULL, min_level INT DEFAULT NULL, trade_required TINYINT(1) DEFAULT NULL, extra_condition VARCHAR(255) DEFAULT NULL, INDEX IDX_73E5C2B5443DA39 (from_pokemon_id), INDEX IDX_73E5C2B58D4FE43 (to_pokemon_id), INDEX IDX_73E5C2B55FDDDCD6 (trigger_id), INDEX IDX_73E5C2B5338E759E (required_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_family (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, chain_id INT NOT NULL, UNIQUE INDEX UNIQ_C90F5D0F966C2F62 (chain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_move (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, move_id INT NOT NULL, version_group_id INT NOT NULL, learn_method VARCHAR(50) NOT NULL, level INT DEFAULT NULL, INDEX IDX_D397493B2FE71C3E (pokemon_id), INDEX IDX_D397493B6DC541A8 (move_id), INDEX IDX_D397493B92AE854F (version_group_id), UNIQUE INDEX uniq_pokemon_move (pokemon_id, move_id, version_group_id, learn_method, level), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_species (id INT AUTO_INCREMENT NOT NULL, generation_id INT NOT NULL, pokedex_number INT NOT NULL, name_en VARCHAR(255) NOT NULL, name_fr VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, description_fr LONGTEXT DEFAULT NULL, is_legendary TINYINT(1) NOT NULL, is_mythical TINYINT(1) NOT NULL, has_gender_difference TINYINT(1) NOT NULL, capture_rate INT DEFAULT NULL, base_happiness INT DEFAULT NULL, UNIQUE INDEX UNIQ_C9658B832996FAF8 (pokedex_number), INDEX IDX_C9658B83553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_sprite (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, front_default VARCHAR(255) NOT NULL, front_female VARCHAR(255) DEFAULT NULL, front_shiny VARCHAR(255) DEFAULT NULL, front_shiny_female VARCHAR(255) DEFAULT NULL, form_key VARCHAR(50) DEFAULT NULL, source VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_59F4F3CA2FE71C3E (pokemon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_type (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT NOT NULL, type_id INT NOT NULL, slot INT NOT NULL, INDEX IDX_B077296A2FE71C3E (pokemon_id), INDEX IDX_B077296AC54C8C93 (type_id), UNIQUE INDEX uniq_pokemon_type (pokemon_id, type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region_form (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8B2EEA785E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, api_name VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8CDE57297FD408F5 (api_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE version_group (id INT AUTO_INCREMENT NOT NULL, generation_id INT NOT NULL, api_name VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_6A1C90C07FD408F5 (api_name), INDEX IDX_6A1C90C0553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attack ADD CONSTRAINT FK_47C02D3BC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C92AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF2A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF22FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF2E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE hunt_session ADD CONSTRAINT FK_79B16FF2F1E24924 FOREIGN KEY (hunt_method_id) REFERENCES hunt_method (id)');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF8492AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF84F5315759 FOREIGN KEY (attack_id) REFERENCES attack (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3B2A1D860 FOREIGN KEY (species_id) REFERENCES pokemon_species (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3C35E566A FOREIGN KEY (family_id) REFERENCES pokemon_family (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F344E4EAFA FOREIGN KEY (region_form_id) REFERENCES region_form (id)');
        $this->addSql('ALTER TABLE pokemon_egg_group ADD CONSTRAINT FK_311E8502FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_egg_group ADD CONSTRAINT FK_311E850B76DC94C FOREIGN KEY (egg_group_id) REFERENCES egg_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_ability ADD CONSTRAINT FK_59A592AD2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_ability ADD CONSTRAINT FK_59A592AD8016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCD2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCDA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCDE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCDF6DF9098 FOREIGN KEY (ball_id) REFERENCES ball (id)');
        $this->addSql('ALTER TABLE pokemon_capture_history ADD CONSTRAINT FK_68581DCDF1E24924 FOREIGN KEY (hunt_method_id) REFERENCES hunt_method (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B5443DA39 FOREIGN KEY (from_pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B58D4FE43 FOREIGN KEY (to_pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B55FDDDCD6 FOREIGN KEY (trigger_id) REFERENCES evolution_trigger (id)');
        $this->addSql('ALTER TABLE pokemon_evolution ADD CONSTRAINT FK_73E5C2B5338E759E FOREIGN KEY (required_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B6DC541A8 FOREIGN KEY (move_id) REFERENCES attack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_move ADD CONSTRAINT FK_D397493B92AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_species ADD CONSTRAINT FK_C9658B83553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('ALTER TABLE pokemon_sprite ADD CONSTRAINT FK_59F4F3CA2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296A2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE version_group ADD CONSTRAINT FK_6A1C90C0553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attack DROP FOREIGN KEY FK_47C02D3BC54C8C93');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C92AE854F');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C553A6EC4');
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF2A76ED395');
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF22FE71C3E');
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF2E48FD905');
        $this->addSql('ALTER TABLE hunt_session DROP FOREIGN KEY FK_79B16FF2F1E24924');
        $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF8492AE854F');
        $this->addSql('ALTER TABLE machine DROP FOREIGN KEY FK_1505DF84F5315759');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3B2A1D860');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3C35E566A');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3553A6EC4');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F344E4EAFA');
        $this->addSql('ALTER TABLE pokemon_egg_group DROP FOREIGN KEY FK_311E8502FE71C3E');
        $this->addSql('ALTER TABLE pokemon_egg_group DROP FOREIGN KEY FK_311E850B76DC94C');
        $this->addSql('ALTER TABLE pokemon_ability DROP FOREIGN KEY FK_59A592AD2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_ability DROP FOREIGN KEY FK_59A592AD8016D8B2');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCD2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCDA76ED395');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCDE48FD905');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCDF6DF9098');
        $this->addSql('ALTER TABLE pokemon_capture_history DROP FOREIGN KEY FK_68581DCDF1E24924');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B5443DA39');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B58D4FE43');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B55FDDDCD6');
        $this->addSql('ALTER TABLE pokemon_evolution DROP FOREIGN KEY FK_73E5C2B5338E759E');
        $this->addSql('ALTER TABLE pokemon_move DROP FOREIGN KEY FK_D397493B2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_move DROP FOREIGN KEY FK_D397493B6DC541A8');
        $this->addSql('ALTER TABLE pokemon_move DROP FOREIGN KEY FK_D397493B92AE854F');
        $this->addSql('ALTER TABLE pokemon_species DROP FOREIGN KEY FK_C9658B83553A6EC4');
        $this->addSql('ALTER TABLE pokemon_sprite DROP FOREIGN KEY FK_59F4F3CA2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_type DROP FOREIGN KEY FK_B077296A2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_type DROP FOREIGN KEY FK_B077296AC54C8C93');
        $this->addSql('ALTER TABLE version_group DROP FOREIGN KEY FK_6A1C90C0553A6EC4');
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
        $this->addSql('DROP TABLE pokemon_egg_group');
        $this->addSql('DROP TABLE pokemon_ability');
        $this->addSql('DROP TABLE pokemon_capture_history');
        $this->addSql('DROP TABLE pokemon_evolution');
        $this->addSql('DROP TABLE pokemon_family');
        $this->addSql('DROP TABLE pokemon_move');
        $this->addSql('DROP TABLE pokemon_species');
        $this->addSql('DROP TABLE pokemon_sprite');
        $this->addSql('DROP TABLE pokemon_type');
        $this->addSql('DROP TABLE region_form');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE version_group');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
