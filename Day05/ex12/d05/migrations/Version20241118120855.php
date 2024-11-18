<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118120855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email ADD informations_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE email ADD CONSTRAINT FK_E7927C7490587D82 FOREIGN KEY (informations_id) REFERENCES informations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E7927C7490587D82 ON email (informations_id)');
        $this->addSql('ALTER TABLE informations ADD email_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE informations ADD CONSTRAINT FK_6F966489E209DFD8 FOREIGN KEY (email_id_id) REFERENCES email (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F966489E209DFD8 ON informations (email_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email DROP FOREIGN KEY FK_E7927C7490587D82');
        $this->addSql('DROP INDEX UNIQ_E7927C7490587D82 ON email');
        $this->addSql('ALTER TABLE email DROP informations_id');
        $this->addSql('ALTER TABLE informations DROP FOREIGN KEY FK_6F966489E209DFD8');
        $this->addSql('DROP INDEX UNIQ_6F966489E209DFD8 ON informations');
        $this->addSql('ALTER TABLE informations DROP email_id_id');
    }
}
