<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200301233630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE developers (id INT AUTO_INCREMENT NOT NULL, developer VARCHAR(255) NOT NULL, time INT NOT NULL, difficulty INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_planning (id INT AUTO_INCREMENT NOT NULL, developer_id INT NOT NULL, task_id INT NOT NULL, time INT NOT NULL, INDEX IDX_945955BA64DD9267 (developer_id), UNIQUE INDEX UNIQ_945955BA8DB60186 (task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, group_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, time INT NOT NULL, difficulty INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('
        REPLACE INTO developers (id,developer,time,difficulty) VALUES(1,"Developer 1",1,1);
        REPLACE INTO developers (id,developer,time,difficulty) VALUES(2,"Developer 2",1,2);
        REPLACE INTO developers (id,developer,time,difficulty) VALUES(3,"Developer 3",1,3);
        REPLACE INTO developers (id,developer,time,difficulty) VALUES(4,"Developer 4",1,4);
        REPLACE INTO developers (id,developer,time,difficulty) VALUES(5,"Developer 5",1,5);
        ');
        $this->addSql('ALTER TABLE task_planning ADD CONSTRAINT FK_945955BA64DD9267 FOREIGN KEY (developer_id) REFERENCES developers (id)');
        $this->addSql('ALTER TABLE task_planning ADD CONSTRAINT FK_945955BA8DB60186 FOREIGN KEY (task_id) REFERENCES tasks (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task_planning DROP FOREIGN KEY FK_945955BA64DD9267');
        $this->addSql('ALTER TABLE task_planning DROP FOREIGN KEY FK_945955BA8DB60186');
        $this->addSql('DROP TABLE developers');
        $this->addSql('DROP TABLE task_planning');
        $this->addSql('DROP TABLE tasks');
    }
}
