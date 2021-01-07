<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107162005 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE header_vote DROP FOREIGN KEY FK_F794FFE972DCDAFC');
        $this->addSql('DROP INDEX UNIQ_F794FFE972DCDAFC ON header_vote');
        $this->addSql('ALTER TABLE header_vote CHANGE vote_id vote_header_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header_vote ADD CONSTRAINT FK_F794FFE96CB5D81D FOREIGN KEY (vote_header_id) REFERENCES vote (id)');
        $this->addSql('CREATE INDEX IDX_F794FFE96CB5D81D ON header_vote (vote_header_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE header_vote DROP FOREIGN KEY FK_F794FFE96CB5D81D');
        $this->addSql('DROP INDEX IDX_F794FFE96CB5D81D ON header_vote');
        $this->addSql('ALTER TABLE header_vote CHANGE vote_header_id vote_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE header_vote ADD CONSTRAINT FK_F794FFE972DCDAFC FOREIGN KEY (vote_id) REFERENCES vote (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F794FFE972DCDAFC ON header_vote (vote_id)');
    }
}
