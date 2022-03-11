<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220311123248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portrait ADD instance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE portrait ADD CONSTRAINT FK_954034FB3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id)');
        $this->addSql('CREATE INDEX IDX_954034FB3A51721D ON portrait (instance_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE portrait DROP FOREIGN KEY FK_954034FB3A51721D');
        $this->addSql('DROP INDEX IDX_954034FB3A51721D ON portrait');
        $this->addSql('ALTER TABLE portrait DROP instance_id');
    }
}
