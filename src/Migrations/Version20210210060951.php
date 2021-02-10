<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210060951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE boutique CHANGE user_id user_id INT DEFAULT NULL, CHANGE logo logo VARCHAR(255) DEFAULT NULL, CHANGE user_condition user_condition VARCHAR(300) DEFAULT NULL');
        $this->addSql('ALTER TABLE cart CHANGE user_id user_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL, CHANGE vote_id vote_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE es_article CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header_vote CHANGE vote_header_id vote_header_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reference CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slider CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE social_network CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_condition CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE votes CHANGE user_id user_id INT DEFAULT NULL, CHANGE article_id article_id INT DEFAULT NULL, CHANGE votearticle_id votearticle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE bloked_id bloked_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE article ADD referency VARCHAR(255) NOT NULL, CHANGE boutique_id boutique_id INT DEFAULT NULL, CHANGE price_global price_global INT DEFAULT NULL, CHANGE quantity quantity VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL, CHANGE sous_category sous_category VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD category VARCHAR(255) NOT NULL, ADD sous_category VARCHAR(255) NOT NULL, CHANGE boutique_id boutique_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE article DROP referency, CHANGE boutique_id boutique_id INT DEFAULT NULL, CHANGE price_global price_global INT DEFAULT NULL, CHANGE quantity quantity VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE sous_category sous_category VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE boutique CHANGE user_id user_id INT DEFAULT NULL, CHANGE logo logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE user_condition user_condition VARCHAR(300) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cart CHANGE user_id user_id INT DEFAULT NULL, CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE category CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment CHANGE user_id user_id INT DEFAULT NULL, CHANGE vote_id vote_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE es_article CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header_vote CHANGE vote_header_id vote_header_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images CHANGE article_id article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu DROP category, DROP sous_category, CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE bloked_id bloked_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reference CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slider CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE social_network CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user_condition CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote CHANGE boutique_id boutique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE votes CHANGE user_id user_id INT DEFAULT NULL, CHANGE article_id article_id INT DEFAULT NULL, CHANGE votearticle_id votearticle_id INT DEFAULT NULL');
    }
}
