<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210201153521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, photo1 LONGTEXT NOT NULL, photo2 LONGTEXT DEFAULT NULL, photo3 LONGTEXT DEFAULT NULL, marque VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, reference INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE inventaire (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, taille VARCHAR(10) NOT NULL, couleur VARCHAR(255) NOT NULL, stock INT NOT NULL, prix DOUBLE PRECISION NOT NULL, en_vente TINYINT(1) NOT NULL, INDEX IDX_338920E07294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE inventaire ADD CONSTRAINT FK_338920E07294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('INSERT INTO article (`designation`, `description`, `photo1`, `marque`, `type`, `genre`, `reference`) VALUES ("Robe alpha", "Une jolie petite robe", "url_photo_robe", "Ralph Lauren", "Robe", "Femme", "00001");');
        $this->addSql('INSERT INTO article (`designation`, `description`, `photo1`, `marque`, `type`, `genre`, `reference`) VALUES ("T-shirt beta", "Un joli T-shirt", "url_photo_t-shirt", "Lacoste", "T-Shirt", "Homme", "00002");');
        $this->addSql('INSERT INTO article (`designation`, `description`, `photo1`, `marque`, `type`, `genre`, `reference`) VALUES ("Pantalon gamma", "Un beau pantalon", "url_photo_pantalon", "Tommy Hilfiger", "Pantalon", "Femme", "00003");');
        $this->addSql('INSERT INTO article (`designation`, `description`, `photo1`, `marque`, `type`, `genre`, `reference`) VALUES ("Robe beta", "Une jolie petite robe", "url_photo_robe", "Jennifer", "Robe", "Femme", "00004");');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventaire DROP FOREIGN KEY FK_338920E07294869C');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE inventaire');
    }
}
