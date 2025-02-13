<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211202039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE id_produit');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DAABEFE2C');
        $this->addSql('DROP INDEX IDX_6EEAA67DAABEFE2C ON commande');
        $this->addSql('ALTER TABLE commande CHANGE id_produit_id produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DF347EFB ON commande (produit_id)');
        $this->addSql('ALTER TABLE commentaire CHANGE post_id post_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matching CHANGE feedback feedback VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE matching_id matching_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation CHANGE evenement_id evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE id_produit (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF347EFB');
        $this->addSql('DROP INDEX IDX_6EEAA67DF347EFB ON commande');
        $this->addSql('ALTER TABLE commande CHANGE produit_id id_produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DAABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DAABEFE2C ON commande (id_produit_id)');
        $this->addSql('ALTER TABLE commentaire CHANGE post_id post_id INT NOT NULL');
        $this->addSql('ALTER TABLE matching CHANGE feedback feedback VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE message CHANGE matching_id matching_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE reservation CHANGE evenement_id evenement_id INT NOT NULL');
    }
}
