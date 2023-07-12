<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712084625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enseigne ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE enseigne ADD CONSTRAINT FK_37D4778EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_37D4778EA76ED395 ON enseigne (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enseigne DROP FOREIGN KEY FK_37D4778EA76ED395');
        $this->addSql('DROP INDEX IDX_37D4778EA76ED395 ON enseigne');
        $this->addSql('ALTER TABLE enseigne DROP user_id');
    }
}
