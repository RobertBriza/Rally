<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231014174657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, type VARCHAR(16) NOT NULL, UNIQUE INDEX unique_firstname_lastname (first_name, last_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_members (team_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_BAD9A3C8296CD8AE (team_id), INDEX IDX_BAD9A3C87597D3FE (member_id), PRIMARY KEY(team_id, member_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE team_members ADD CONSTRAINT FK_BAD9A3C8296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_members ADD CONSTRAINT FK_BAD9A3C87597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team_members DROP FOREIGN KEY FK_BAD9A3C8296CD8AE');
        $this->addSql('ALTER TABLE team_members DROP FOREIGN KEY FK_BAD9A3C87597D3FE');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_members');
    }
}
