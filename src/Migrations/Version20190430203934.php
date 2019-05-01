<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430203934 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, file VARCHAR(255) NOT NULL, alt VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produits ADD cover_id INT DEFAULT NULL, DROP image_url');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C922726E9 FOREIGN KEY (cover_id) REFERENCES images (id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8C922726E9 ON produits (cover_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C922726E9');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP INDEX IDX_BE2DDF8C922726E9 ON produits');
        $this->addSql('ALTER TABLE produits ADD image_url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP cover_id');
    }
}
