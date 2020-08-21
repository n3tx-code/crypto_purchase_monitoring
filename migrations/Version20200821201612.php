<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821201612 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE crypto (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvement (id INT AUTO_INCREMENT NOT NULL, crypto_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, cashback TINYINT(1) NOT NULL, brave TINYINT(1) NOT NULL, earn TINYINT(1) NOT NULL, date_made DATE NOT NULL, INDEX IDX_5B51FC3EE9571A63 (crypto_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mouvement ADD CONSTRAINT FK_5B51FC3EE9571A63 FOREIGN KEY (crypto_id) REFERENCES crypto (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mouvement DROP FOREIGN KEY FK_5B51FC3EE9571A63');
        $this->addSql('DROP TABLE crypto');
        $this->addSql('DROP TABLE mouvement');
    }
}
