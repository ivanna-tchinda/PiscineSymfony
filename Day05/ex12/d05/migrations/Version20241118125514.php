<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118125514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE informations DROP FOREIGN KEY FK_6F966489E209DFD8');
        $this->addSql('DROP INDEX UNIQ_6F966489E209DFD8 ON informations');
        $this->addSql('ALTER TABLE informations DROP email, CHANGE email_id_id email_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE informations ADD CONSTRAINT FK_6F966489A832C1C9 FOREIGN KEY (email_id) REFERENCES email (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F966489A832C1C9 ON informations (email_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE informations DROP FOREIGN KEY FK_6F966489A832C1C9');
        $this->addSql('DROP INDEX UNIQ_6F966489A832C1C9 ON informations');
        $this->addSql('ALTER TABLE informations ADD email VARCHAR(255) NOT NULL, CHANGE email_id email_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE informations ADD CONSTRAINT FK_6F966489E209DFD8 FOREIGN KEY (email_id_id) REFERENCES email (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F966489E209DFD8 ON informations (email_id_id)');
    }
}
