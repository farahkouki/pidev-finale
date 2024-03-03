<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240211174646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenements_equipements (evenement_id INT NOT NULL, equipement_id INT NOT NULL, INDEX IDX_E456F4CDFD02F13 (evenement_id), INDEX IDX_E456F4CD806F0F5C (equipement_id), PRIMARY KEY(evenement_id, equipement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenements_equipements ADD CONSTRAINT FK_E456F4CDFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE evenements_equipements ADD CONSTRAINT FK_E456F4CD806F0F5C FOREIGN KEY (equipement_id) REFERENCES equipement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenements_equipements DROP FOREIGN KEY FK_E456F4CDFD02F13');
        $this->addSql('ALTER TABLE evenements_equipements DROP FOREIGN KEY FK_E456F4CD806F0F5C');
        $this->addSql('DROP TABLE evenements_equipements');
    }
}
