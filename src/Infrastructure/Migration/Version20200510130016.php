<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510130016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE genres (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A8EBE5165E237E06 ON genres (name)');
        $this->addSql('CREATE TABLE games (id UUID NOT NULL, distributor_id UUID NOT NULL, source_id VARCHAR(50) NOT NULL, name VARCHAR(255) NOT NULL, rating INT NOT NULL, release_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description VARCHAR(1000) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FF232B312D863A58 ON games (distributor_id)');
        $this->addSql('COMMENT ON COLUMN games.release_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE games_genres (game_id UUID NOT NULL, genre_id UUID NOT NULL, PRIMARY KEY(game_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_6AC30AA3E48FD905 ON games_genres (game_id)');
        $this->addSql('CREATE INDEX IDX_6AC30AA34296D31F ON games_genres (genre_id)');
        $this->addSql('CREATE TABLE images (id UUID NOT NULL, game_id UUID NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(255) CHECK(type IN (\'preview\', \'screenshot\')) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E01FBE6AE48FD905 ON images (game_id)');
        $this->addSql('COMMENT ON COLUMN images.type IS \'(DC2Type:image_type)\'');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, email VARCHAR(254) DEFAULT NULL, password_hash VARCHAR(100) DEFAULT NULL, confirm_token VARCHAR(100) DEFAULT NULL, status VARCHAR(255) CHECK(status IN (\'wait\', \'active\')) NOT NULL, role VARCHAR(255) CHECK(role IN (\'ROLE_USER\', \'ROLE_ADMIN\')) NOT NULL, reset_token_token VARCHAR(100) DEFAULT NULL, reset_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('COMMENT ON COLUMN users.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.status IS \'(DC2Type:status_type)\'');
        $this->addSql('COMMENT ON COLUMN users.role IS \'(DC2Type:role_type)\'');
        $this->addSql('COMMENT ON COLUMN users.reset_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE networks (id UUID NOT NULL, user_id UUID NOT NULL, name VARCHAR(255) CHECK(name IN (\'google\', \'facebook\')) NOT NULL, identity VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D9B9B42BA76ED395 ON networks (user_id)');
        $this->addSql('COMMENT ON COLUMN networks.name IS \'(DC2Type:network_type)\'');
        $this->addSql('CREATE TABLE distributors (id UUID NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B312D863A58 FOREIGN KEY (distributor_id) REFERENCES distributors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games_genres ADD CONSTRAINT FK_6AC30AA3E48FD905 FOREIGN KEY (game_id) REFERENCES games (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE games_genres ADD CONSTRAINT FK_6AC30AA34296D31F FOREIGN KEY (genre_id) REFERENCES genres (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AE48FD905 FOREIGN KEY (game_id) REFERENCES games (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE networks ADD CONSTRAINT FK_D9B9B42BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE games_genres DROP CONSTRAINT FK_6AC30AA34296D31F');
        $this->addSql('ALTER TABLE games_genres DROP CONSTRAINT FK_6AC30AA3E48FD905');
        $this->addSql('ALTER TABLE images DROP CONSTRAINT FK_E01FBE6AE48FD905');
        $this->addSql('ALTER TABLE networks DROP CONSTRAINT FK_D9B9B42BA76ED395');
        $this->addSql('ALTER TABLE games DROP CONSTRAINT FK_FF232B312D863A58');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE games_genres');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE networks');
        $this->addSql('DROP TABLE distributors');
    }
}
