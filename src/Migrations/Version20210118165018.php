<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118165018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, price_promo INT NOT NULL, created_at DATETIME NOT NULL, price_global INT DEFAULT NULL, quantity VARCHAR(255) DEFAULT NULL, promo VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, word_key LONGTEXT DEFAULT NULL, description LONGTEXT NOT NULL, type VARCHAR(255) DEFAULT NULL, sous_category VARCHAR(255) DEFAULT NULL, INDEX IDX_23A0E66AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bloked (id INT AUTO_INCREMENT NOT NULL, id_bloquer INT NOT NULL, id_bloqued VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE boutique (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, contact VARCHAR(255) NOT NULL, apropos LONGTEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, user_condition VARCHAR(300) DEFAULT NULL, INDEX IDX_A1223C54A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, quantity INT NOT NULL, type VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_BA388B7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cart_article (cart_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_F9E0C6611AD5CDBF (cart_id), INDEX IDX_F9E0C6617294869C (article_id), PRIMARY KEY(cart_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, vote_id INT DEFAULT NULL, INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C72DCDAFC (vote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE es_article (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, INDEX IDX_E3A6820AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE header (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6E72A8C1AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE header_vote (id INT AUTO_INCREMENT NOT NULL, vote_header_id INT DEFAULT NULL, images VARCHAR(255) NOT NULL, INDEX IDX_F794FFE96CB5D81D (vote_header_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E01FBE6A7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_7D053A93AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, bloked_id INT DEFAULT NULL, message LONGTEXT NOT NULL, id_sender INT NOT NULL, id_receved INT NOT NULL, created_at DATETIME NOT NULL, view TINYINT(1) NOT NULL, INDEX IDX_B6BD307F70834FA6 (bloked_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reference (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, images VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_AEA34913AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE slider (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_CFC71007AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_network (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, images VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, name_link VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_EFFF5221AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, birthday DATETIME NOT NULL, genre VARCHAR(255) NOT NULL, avatar VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_condition (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, descriptions LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_BD3605A7AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, boutique_id INT DEFAULT NULL, nbr_vote INT NOT NULL, images VARCHAR(255) NOT NULL, placement INT NOT NULL, create_at DATETIME NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_5A108564AB677BE6 (boutique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, article_id INT DEFAULT NULL, votearticle_id INT DEFAULT NULL, value INT NOT NULL, comment LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_518B7ACFA76ED395 (user_id), INDEX IDX_518B7ACF7294869C (article_id), INDEX IDX_518B7ACF7D44E044 (votearticle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE boutique ADD CONSTRAINT FK_A1223C54A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cart_article ADD CONSTRAINT FK_F9E0C6611AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_article ADD CONSTRAINT FK_F9E0C6617294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user_condition (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C72DCDAFC FOREIGN KEY (vote_id) REFERENCES vote (id)');
        $this->addSql('ALTER TABLE es_article ADD CONSTRAINT FK_E3A6820AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE header ADD CONSTRAINT FK_6E72A8C1AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE header_vote ADD CONSTRAINT FK_F794FFE96CB5D81D FOREIGN KEY (vote_header_id) REFERENCES vote (id)');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F70834FA6 FOREIGN KEY (bloked_id) REFERENCES bloked (id)');
        $this->addSql('ALTER TABLE reference ADD CONSTRAINT FK_AEA34913AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE slider ADD CONSTRAINT FK_CFC71007AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE social_network ADD CONSTRAINT FK_EFFF5221AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE user_condition ADD CONSTRAINT FK_BD3605A7AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF7D44E044 FOREIGN KEY (votearticle_id) REFERENCES article (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cart_article DROP FOREIGN KEY FK_F9E0C6617294869C');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A7294869C');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF7294869C');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF7D44E044');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F70834FA6');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66AB677BE6');
        $this->addSql('ALTER TABLE es_article DROP FOREIGN KEY FK_E3A6820AB677BE6');
        $this->addSql('ALTER TABLE header DROP FOREIGN KEY FK_6E72A8C1AB677BE6');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93AB677BE6');
        $this->addSql('ALTER TABLE reference DROP FOREIGN KEY FK_AEA34913AB677BE6');
        $this->addSql('ALTER TABLE slider DROP FOREIGN KEY FK_CFC71007AB677BE6');
        $this->addSql('ALTER TABLE social_network DROP FOREIGN KEY FK_EFFF5221AB677BE6');
        $this->addSql('ALTER TABLE user_condition DROP FOREIGN KEY FK_BD3605A7AB677BE6');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564AB677BE6');
        $this->addSql('ALTER TABLE cart_article DROP FOREIGN KEY FK_F9E0C6611AD5CDBF');
        $this->addSql('ALTER TABLE boutique DROP FOREIGN KEY FK_A1223C54A76ED395');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7A76ED395');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C72DCDAFC');
        $this->addSql('ALTER TABLE header_vote DROP FOREIGN KEY FK_F794FFE96CB5D81D');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE bloked');
        $this->addSql('DROP TABLE boutique');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE cart_article');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE es_article');
        $this->addSql('DROP TABLE header');
        $this->addSql('DROP TABLE header_vote');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE reference');
        $this->addSql('DROP TABLE slider');
        $this->addSql('DROP TABLE social_network');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_condition');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP TABLE votes');
    }
}
