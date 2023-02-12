<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230211233949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE insurance ADD vehicle_owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE insurance ADD CONSTRAINT FK_640EAF4CA32E6E6D FOREIGN KEY (vehicle_owner_id) REFERENCES vehicle (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_640EAF4CA32E6E6D ON insurance (vehicle_owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE insurance DROP FOREIGN KEY FK_640EAF4CA32E6E6D');
        $this->addSql('DROP INDEX UNIQ_640EAF4CA32E6E6D ON insurance');
        $this->addSql('ALTER TABLE insurance DROP vehicle_owner_id');
    }
}
