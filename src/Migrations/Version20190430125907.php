<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430125907 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE entrees (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, entry_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME NOT NULL, observation VARCHAR(255) NOT NULL, INDEX IDX_24E24AA14584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entrees ADD CONSTRAINT FK_24E24AA14584665A FOREIGN KEY (product_id) REFERENCES produits (id)');
        $this->addSql('DROP TABLE emtrees');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE emtrees (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, updated_at DATETIME NOT NULL, observation VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, entry_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_A276380F4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE emtrees ADD CONSTRAINT FK_A276380F4584665A FOREIGN KEY (product_id) REFERENCES produits (id)');
        $this->addSql('DROP TABLE entrees');
    }
}
