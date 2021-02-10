<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203051338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bloked (id INT AUTO_INCREMENT NOT NULL, id_bloquer INT NOT NULL, id_bloqued VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, bloked_id INT DEFAULT NULL, message LONGTEXT NOT NULL, id_sender INT NOT NULL, id_receved INT NOT NULL, created_at DATETIME NOT NULL, view TINYINT(1) NOT NULL, INDEX IDX_B6BD307F70834FA6 (bloked_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, parent_id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_64C19C1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F70834FA6 FOREIGN KEY (bloked_id) REFERENCES bloked (id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article CHANGE boutique_id boutique_id INT DEFAULT NULL, CHANGE price_global price_global INT DEFAULT NULL, CHANGE quantity quantity VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE sous_category sous_category VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE boutique CHANGE user_id user_id INT DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE user_condition user_condition VARCHAR(300) DEFAULT NULL');
        $this->addSql('ALTER TABLE cart CHANGE user_id user_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL, CHANGE vote_id vote_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE es_article CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header_vote CHANGE vote_header_id vote_header_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reference CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slider CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE social_network CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_condition CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE votes CHANGE user_id user_id INT DEFAULT NULL, CHANGE article_id article_id INT DEFAULT NULL, CHANGE votearticle_id votearticle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F70834FA6');
        $this->addSql('DROP TABLE bloked');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE category');
        $this->addSql('ALTER TABLE article CHANGE boutique_id boutique_id INT DEFAULT NULL, CHANGE price_global price_global INT DEFAULT NULL, CHANGE quantity quantity VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE sous_category sous_category VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE boutique CHANGE user_id user_id INT DEFAULT NULL, CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE user_condition user_condition VARCHAR(300) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cart CHANGE user_id user_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL, CHANGE vote_id vote_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE es_article CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header_vote CHANGE vote_header_id vote_header_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reference CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slider CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE social_network CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user_condition CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE votes CHANGE user_id user_id INT DEFAULT NULL, CHANGE article_id article_id INT DEFAULT NULL, CHANGE votearticle_id votearticle_id INT DEFAULT NULL');
    }
}
