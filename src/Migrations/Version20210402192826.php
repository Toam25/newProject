<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210402192826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EF1A9D84');
        $this->addSql('CREATE TABLE notify (id INT AUTO_INCREMENT NOT NULL, from_user_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_217BEDC82130303A (from_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notify ADD CONSTRAINT FK_217BEDC82130303A FOREIGN KEY (from_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP INDEX IDX_8D93D649EF1A9D84 ON user');
        $this->addSql('ALTER TABLE user CHANGE notification_id notify_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D5FA27FC FOREIGN KEY (notify_id) REFERENCES notify (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D5FA27FC ON user (notify_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D5FA27FC');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, from_user_id INT DEFAULT NULL, subject VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, view LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', INDEX IDX_BF5476CA2130303A (from_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA2130303A FOREIGN KEY (from_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE notify');
        $this->addSql('DROP INDEX IDX_8D93D649D5FA27FC ON user');
        $this->addSql('ALTER TABLE user CHANGE notify_id notification_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649EF1A9D84 ON user (notification_id)');
    }
}
