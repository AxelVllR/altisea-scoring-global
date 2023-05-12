<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509200719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wod ADD competition_id INT NOT NULL');
        $this->addSql('ALTER TABLE wod ADD CONSTRAINT FK_64575EE7B39D312 FOREIGN KEY (competition_id) REFERENCES competition (id)');
        $this->addSql('CREATE INDEX IDX_64575EE7B39D312 ON wod (competition_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wod DROP FOREIGN KEY FK_64575EE7B39D312');
        $this->addSql('DROP INDEX IDX_64575EE7B39D312 ON wod');
        $this->addSql('ALTER TABLE wod DROP competition_id');
    }
}
