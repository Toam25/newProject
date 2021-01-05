<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201028182917 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE votes ADD votearticle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACF7D44E044 FOREIGN KEY (votearticle_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_518B7ACF7D44E044 ON votes (votearticle_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACF7D44E044');
        $this->addSql('DROP INDEX IDX_518B7ACF7D44E044 ON votes');
        $this->addSql('ALTER TABLE votes DROP votearticle_id');
    }
}
