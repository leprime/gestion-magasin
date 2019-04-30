<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190428162124 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE entry (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, observation VARCHAR(255) NOT NULL, INDEX IDX_2B219D704584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sorties (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, quantity INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, observation LONGTEXT NOT NULL, INDEX IDX_488163E8F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entry ADD CONSTRAINT FK_2B219D704584665A FOREIGN KEY (product_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE sorties ADD CONSTRAINT FK_488163E8F347EFB FOREIGN KEY (produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE produits ADD mark VARCHAR(255) NOT NULL, ADD code VARCHAR(255) NOT NULL, ADD service VARCHAR(255) NOT NULL, ADD refe_order VARCHAR(255) NOT NULL, ADD image_url VARCHAR(255) NOT NULL, ADD is_exit_permit TINYINT(1) NOT NULL, ADD is_lendable TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE entry');
        $this->addSql('DROP TABLE sorties');
        $this->addSql('ALTER TABLE produits DROP mark, DROP code, DROP service, DROP refe_order, DROP image_url, DROP is_exit_permit, DROP is_lendable');
    }
}
