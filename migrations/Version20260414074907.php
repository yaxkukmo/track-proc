<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260414074907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE metric (id INT AUTO_INCREMENT NOT NULL, pid INT NOT NULL, cpu DOUBLE PRECISION NOT NULL, mem DOUBLE PRECISION NOT NULL, vsz INT NOT NULL, rss INT NOT NULL, collected_at DATETIME NOT NULL, process_id INT NOT NULL, INDEX IDX_87D62EE37EC2F574 (process_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE process (id INT AUTO_INCREMENT NOT NULL, user VARCHAR(16) NOT NULL, command VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE metric ADD CONSTRAINT FK_87D62EE37EC2F574 FOREIGN KEY (process_id) REFERENCES process (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metric DROP FOREIGN KEY FK_87D62EE37EC2F574');
        $this->addSql('DROP TABLE metric');
        $this->addSql('DROP TABLE process');
    }
}
