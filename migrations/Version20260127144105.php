<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127144105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apartment (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(255) NOT NULL, building_id INT DEFAULT NULL, resident_id INT DEFAULT NULL, INDEX IDX_4D7E68544D2A7E12 (building_id), UNIQUE INDEX UNIQ_4D7E68548012C5B0 (resident_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, residence_id INT DEFAULT NULL, INDEX IDX_E16F61D48B225FBD (residence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE complaint (id INT AUTO_INCREMENT NOT NULL, message LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, residence_id INT DEFAULT NULL, INDEX IDX_5F2732B5A76ED395 (user_id), INDEX IDX_5F2732B58B225FBD (residence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, residence_id INT DEFAULT NULL, INDEX IDX_1DD399508B225FBD (residence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, amount DOUBLE PRECISION DEFAULT NULL, date DATETIME DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, residence_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_6D28840D8B225FBD (residence_id), INDEX IDX_6D28840DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE residence (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, subdomain VARCHAR(255) NOT NULL, syndic_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_3275823F0654A02 (syndic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, residence_id INT DEFAULT NULL, building_id INT DEFAULT NULL, apartment_id INT DEFAULT NULL, INDEX IDX_8D93D6498B225FBD (residence_id), INDEX IDX_8D93D6494D2A7E12 (building_id), UNIQUE INDEX UNIQ_8D93D649176DFE85 (apartment_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE apartment ADD CONSTRAINT FK_4D7E68544D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE apartment ADD CONSTRAINT FK_4D7E68548012C5B0 FOREIGN KEY (resident_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D48B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B58B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399508B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D8B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE residence ADD CONSTRAINT FK_3275823F0654A02 FOREIGN KEY (syndic_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649176DFE85 FOREIGN KEY (apartment_id) REFERENCES apartment (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apartment DROP FOREIGN KEY FK_4D7E68544D2A7E12');
        $this->addSql('ALTER TABLE apartment DROP FOREIGN KEY FK_4D7E68548012C5B0');
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D48B225FBD');
        $this->addSql('ALTER TABLE complaint DROP FOREIGN KEY FK_5F2732B5A76ED395');
        $this->addSql('ALTER TABLE complaint DROP FOREIGN KEY FK_5F2732B58B225FBD');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399508B225FBD');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D8B225FBD');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DA76ED395');
        $this->addSql('ALTER TABLE residence DROP FOREIGN KEY FK_3275823F0654A02');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498B225FBD');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494D2A7E12');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649176DFE85');
        $this->addSql('DROP TABLE apartment');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE complaint');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE residence');
        $this->addSql('DROP TABLE user');
    }
}
