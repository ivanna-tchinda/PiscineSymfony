<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250111194708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD last_update_user_id INT DEFAULT NULL, DROP last_update_user');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D8ED8C3E8 FOREIGN KEY (last_update_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D8ED8C3E8 ON post (last_update_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D8ED8C3E8');
        $this->addSql('DROP INDEX IDX_5A8A6C8D8ED8C3E8 ON post');
        $this->addSql('ALTER TABLE post ADD last_update_user VARCHAR(255) NOT NULL, DROP last_update_user_id');
    }
}
