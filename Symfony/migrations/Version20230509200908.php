<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230509200908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competition ADD wod_in_progress_id INT DEFAULT NULL, ADD is_active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE competition ADD CONSTRAINT FK_B50A2CB17D7686CA FOREIGN KEY (wod_in_progress_id) REFERENCES wod (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B50A2CB17D7686CA ON competition (wod_in_progress_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competition DROP FOREIGN KEY FK_B50A2CB17D7686CA');
        $this->addSql('DROP INDEX UNIQ_B50A2CB17D7686CA ON competition');
        $this->addSql('ALTER TABLE competition DROP wod_in_progress_id, DROP is_active');
    }
}
