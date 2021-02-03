<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203100439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD prix DOUBLE PRECISION NOT NULL, ADD reference INT NOT NULL');
        // Suppression de la clef étrangère entre detail_commande et inventaire
        //$this->addSql('ALTER TABLE `detail_commande` DROP INDEX `IDX_98344FA6CE430A85`');
        $this->addSql('ALTER TABLE bdd_commerce.detail_commande DROP FOREIGN KEY FK_98344FA6CE430A85');
        $this->addSql('ALTER TABLE `detail_commande` DROP `inventaire_id`');
        // Suppression de la clef étrangère entre inventaire et article
        //$this->addSql('ALTER TABLE `inventaire` DROP INDEX `IDX_338920E07294869C`');
        $this->addSql('ALTER TABLE bdd_commerce.inventaire DROP FOREIGN KEY FK_338920E07294869C');
        // Suppression de la table inventaire
        $this->addSql('DROP TABLE `inventaire`');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP prix, DROP reference');
    }
}
