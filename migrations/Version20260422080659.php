<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260422080659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metric ADD utime INT NOT NULL, ADD stime INT NOT NULL, ADD vsize INT NOT NULL, ADD num_threads INT NOT NULL, ADD shared INT NOT NULL, ADD text INT NOT NULL, ADD data INT NOT NULL, ADD priority INT NOT NULL, ADD nice INT NOT NULL, DROP pid, DROP cpu, DROP mem, DROP vsz');
        $this->addSql('ALTER TABLE process ADD pid INT NOT NULL, DROP user, CHANGE command name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE metric ADD pid INT NOT NULL, ADD cpu DOUBLE PRECISION NOT NULL, ADD mem DOUBLE PRECISION NOT NULL, ADD vsz INT NOT NULL, DROP utime, DROP stime, DROP vsize, DROP num_threads, DROP shared, DROP text, DROP data, DROP priority, DROP nice');
        $this->addSql('ALTER TABLE process ADD user VARCHAR(16) NOT NULL, DROP pid, CHANGE name command VARCHAR(255) NOT NULL');
    }
}
