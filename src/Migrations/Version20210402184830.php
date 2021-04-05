<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402184830 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, from_user_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, view LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_BF5476CA2130303A (from_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA2130303A FOREIGN KEY (from_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD notification_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649EF1A9D84 ON user (notification_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EF1A9D84');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP INDEX IDX_8D93D649EF1A9D84 ON user');
        $this->addSql('ALTER TABLE user DROP notification_id');
    }
}
