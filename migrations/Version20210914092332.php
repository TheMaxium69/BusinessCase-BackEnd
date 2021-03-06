<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914092332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, marque_id INT NOT NULL, model_id INT NOT NULL, carburant_id INT NOT NULL, garage_id INT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, year INT NOT NULL, kilometrage INT NOT NULL, price INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F65593E54827B9B2 (marque_id), INDEX IDX_F65593E57975B7E7 (model_id), INDEX IDX_F65593E532DAAD24 (carburant_id), INDEX IDX_F65593E5C4FFF555 (garage_id), INDEX IDX_F65593E5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carburant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garage (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, phone_number INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_9F26610BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, marque_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_D79572D94827B9B2 (marque_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number INT NOT NULL, siret_number DOUBLE PRECISION NOT NULL, password VARCHAR(255) NOT NULL, roles JSON DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E54827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E57975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E532DAAD24 FOREIGN KEY (carburant_id) REFERENCES carburant (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5C4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE garage ADD CONSTRAINT FK_9F26610BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D94827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E532DAAD24');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5C4FFF555');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E54827B9B2');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D94827B9B2');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E57975B7E7');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5A76ED395');
        $this->addSql('ALTER TABLE garage DROP FOREIGN KEY FK_9F26610BA76ED395');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE carburant');
        $this->addSql('DROP TABLE garage');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE user');
    }
}
