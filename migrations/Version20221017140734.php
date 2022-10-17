<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017140734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(70) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE people_hobby (people_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_F20789773147C936 (people_id), INDEX IDX_F2078977322B2123 (hobby_id), PRIMARY KEY(people_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, rs VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE people_hobby ADD CONSTRAINT FK_F20789773147C936 FOREIGN KEY (people_id) REFERENCES people (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE people_hobby ADD CONSTRAINT FK_F2078977322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE people ADD profile_id INT DEFAULT NULL, ADD job_id INT DEFAULT NULL, DROP job');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A26CCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE people ADD CONSTRAINT FK_28166A26BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_28166A26CCFA12B8 ON people (profile_id)');
        $this->addSql('CREATE INDEX IDX_28166A26BE04EA9 ON people (job_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A26BE04EA9');
        $this->addSql('ALTER TABLE people DROP FOREIGN KEY FK_28166A26CCFA12B8');
        $this->addSql('ALTER TABLE people_hobby DROP FOREIGN KEY FK_F20789773147C936');
        $this->addSql('ALTER TABLE people_hobby DROP FOREIGN KEY FK_F2078977322B2123');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE people_hobby');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP INDEX UNIQ_28166A26CCFA12B8 ON people');
        $this->addSql('DROP INDEX IDX_28166A26BE04EA9 ON people');
        $this->addSql('ALTER TABLE people ADD job VARCHAR(50) DEFAULT NULL, DROP profile_id, DROP job_id');
    }
}
