<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118123405 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE money_transfer (id INT AUTO_INCREMENT NOT NULL, source_wallet_id INT NOT NULL, destination_wallet_id INT NOT NULL, amount INT NOT NULL, currency VARCHAR(3) NOT NULL, commission INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_A15E50EE19BBB33D (source_wallet_id), INDEX IDX_A15E50EE20A59009 (destination_wallet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wallet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, amount INT NOT NULL, currency VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE money_transfer ADD CONSTRAINT FK_A15E50EE19BBB33D FOREIGN KEY (source_wallet_id) REFERENCES wallet (id)');
        $this->addSql('ALTER TABLE money_transfer ADD CONSTRAINT FK_A15E50EE20A59009 FOREIGN KEY (destination_wallet_id) REFERENCES wallet (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE money_transfer DROP FOREIGN KEY FK_A15E50EE19BBB33D');
        $this->addSql('ALTER TABLE money_transfer DROP FOREIGN KEY FK_A15E50EE20A59009');
        $this->addSql('DROP TABLE money_transfer');
        $this->addSql('DROP TABLE wallet');
    }
}
