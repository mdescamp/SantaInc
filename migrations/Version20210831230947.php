<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210831230947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE factory (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factory_user (factory_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F2FA1586C7AF27D2 (factory_id), INDEX IDX_F2FA1586A76ED395 (user_id), PRIMARY KEY(factory_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift (id INT AUTO_INCREMENT NOT NULL, factory_id INT NOT NULL, receiver_id INT DEFAULT NULL, code_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description VARCHAR(5000) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A47C990DD17F50A6 (uuid), INDEX IDX_A47C990DC7AF27D2 (factory_id), INDEX IDX_A47C990DCD53EDB6 (receiver_id), INDEX IDX_A47C990D27DAFE17 (code_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gift_code (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receiver (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3DB88C96D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factory_user ADD CONSTRAINT FK_F2FA1586C7AF27D2 FOREIGN KEY (factory_id) REFERENCES factory (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE factory_user ADD CONSTRAINT FK_F2FA1586A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990DC7AF27D2 FOREIGN KEY (factory_id) REFERENCES factory (id)');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990DCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES receiver (id)');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D27DAFE17 FOREIGN KEY (code_id) REFERENCES gift_code (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factory_user DROP FOREIGN KEY FK_F2FA1586C7AF27D2');
        $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990DC7AF27D2');
        $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D27DAFE17');
        $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990DCD53EDB6');
        $this->addSql('ALTER TABLE factory_user DROP FOREIGN KEY FK_F2FA1586A76ED395');
        $this->addSql('DROP TABLE factory');
        $this->addSql('DROP TABLE factory_user');
        $this->addSql('DROP TABLE gift');
        $this->addSql('DROP TABLE gift_code');
        $this->addSql('DROP TABLE receiver');
        $this->addSql('DROP TABLE user');
    }
}
