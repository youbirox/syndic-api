<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260127151525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apartment ADD CONSTRAINT FK_4D7E68544D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE apartment ADD CONSTRAINT FK_4D7E68548012C5B0 FOREIGN KEY (resident_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D48B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE complaint ADD CONSTRAINT FK_5F2732B58B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399508B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D8B225FBD FOREIGN KEY (residence_id) REFERENCES residence (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE residence DROP INDEX UNIQ_3275823F0654A02, ADD INDEX IDX_3275823F0654A02 (syndic_id)');
        $this->addSql('ALTER TABLE residence CHANGE syndic_id syndic_id INT NOT NULL');
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
        $this->addSql('ALTER TABLE residence DROP INDEX IDX_3275823F0654A02, ADD UNIQUE INDEX UNIQ_3275823F0654A02 (syndic_id)');
        $this->addSql('ALTER TABLE residence DROP FOREIGN KEY FK_3275823F0654A02');
        $this->addSql('ALTER TABLE residence CHANGE syndic_id syndic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498B225FBD');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6494D2A7E12');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649176DFE85');
    }
}
