<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118125709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email (id INT AUTO_INCREMENT NOT NULL, informations_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E7927C7490587D82 (informations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE informations (id INT AUTO_INCREMENT NOT NULL, email_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, age INT NOT NULL, UNIQUE INDEX UNIQ_6F966489A832C1C9 (email_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C7490587D82 FOREIGN KEY (informations_id) REFERENCES informations (id)');
        $this->addSql('ALTER TABLE informations ADD CONSTRAINT FK_6F966489A832C1C9 FOREIGN KEY (email_id) REFERENCES email (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C7490587D82');
        $this->addSql('ALTER TABLE informations DROP FOREIGN KEY FK_6F966489A832C1C9');
        $this->addSql('DROP TABLE email');
        $this->addSql('DROP TABLE informations');
    }
}
