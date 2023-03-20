<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320082041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE about_us (id INT AUTO_INCREMENT NOT NULL, rules LONGTEXT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE action (id INT AUTO_INCREMENT NOT NULL, about_us_id INT DEFAULT NULL, action LONGTEXT NOT NULL, INDEX IDX_47CC8C927CE2CF2D (about_us_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE home (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE limited_offer (id INT NOT NULL, display_start_date DATE NOT NULL, display_end_date DATE NOT NULL, order_number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `member` (id INT AUTO_INCREMENT NOT NULL, about_us_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, fisrt_name VARCHAR(255) NOT NULL, picture VARCHAR(255) NOT NULL, INDEX IDX_70E4FA787CE2CF2D (about_us_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, nb_places INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, description LONGTEXT NOT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer_picture (id INT AUTO_INCREMENT NOT NULL, offer_id INT DEFAULT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_B7D3898053C674EE (offer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, picture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permanant_offer (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C927CE2CF2D FOREIGN KEY (about_us_id) REFERENCES about_us (id)');
        $this->addSql('ALTER TABLE limited_offer ADD CONSTRAINT FK_C0E519CABF396750 FOREIGN KEY (id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA787CE2CF2D FOREIGN KEY (about_us_id) REFERENCES about_us (id)');
        $this->addSql('ALTER TABLE offer_picture ADD CONSTRAINT FK_B7D3898053C674EE FOREIGN KEY (offer_id) REFERENCES offer (id)');
        $this->addSql('ALTER TABLE permanant_offer ADD CONSTRAINT FK_6FA2F84ABF396750 FOREIGN KEY (id) REFERENCES offer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C927CE2CF2D');
        $this->addSql('ALTER TABLE limited_offer DROP FOREIGN KEY FK_C0E519CABF396750');
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA787CE2CF2D');
        $this->addSql('ALTER TABLE offer_picture DROP FOREIGN KEY FK_B7D3898053C674EE');
        $this->addSql('ALTER TABLE permanant_offer DROP FOREIGN KEY FK_6FA2F84ABF396750');
        $this->addSql('DROP TABLE about_us');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE home');
        $this->addSql('DROP TABLE limited_offer');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE offer_picture');
        $this->addSql('DROP TABLE partner');
        $this->addSql('DROP TABLE permanant_offer');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
