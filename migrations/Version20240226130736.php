<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240226130736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipement DROP FOREIGN KEY FK_B8B4C6F32D6BA2D9');
        $this->addSql('DROP INDEX IDX_B8B4C6F32D6BA2D9 ON equipement');
        $this->addSql('ALTER TABLE equipement DROP reclamation_id');
        $this->addSql('ALTER TABLE reclamation ADD equipement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404806F0F5C FOREIGN KEY (equipement_id) REFERENCES equipement (id)');
        $this->addSql('CREATE INDEX IDX_CE606404806F0F5C ON reclamation (equipement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipement ADD reclamation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipement ADD CONSTRAINT FK_B8B4C6F32D6BA2D9 FOREIGN KEY (reclamation_id) REFERENCES reclamation (id)');
        $this->addSql('CREATE INDEX IDX_B8B4C6F32D6BA2D9 ON equipement (reclamation_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404806F0F5C');
        $this->addSql('DROP INDEX IDX_CE606404806F0F5C ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP equipement_id');
    }
}
