<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109195647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_dislikes (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_921E37B04B89032C (post_id), INDEX IDX_921E37B0A76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_dislikes ADD CONSTRAINT FK_921E37B04B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE post_dislikes ADD CONSTRAINT FK_921E37B0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post_unlikes DROP FOREIGN KEY FK_A3C2408B4B89032C');
        $this->addSql('ALTER TABLE post_unlikes DROP FOREIGN KEY FK_A3C2408BA76ED395');
        $this->addSql('DROP TABLE post_unlikes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_unlikes (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A3C2408B4B89032C (post_id), INDEX IDX_A3C2408BA76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE post_unlikes ADD CONSTRAINT FK_A3C2408B4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE post_unlikes ADD CONSTRAINT FK_A3C2408BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE post_dislikes DROP FOREIGN KEY FK_921E37B04B89032C');
        $this->addSql('ALTER TABLE post_dislikes DROP FOREIGN KEY FK_921E37B0A76ED395');
        $this->addSql('DROP TABLE post_dislikes');
    }
}
