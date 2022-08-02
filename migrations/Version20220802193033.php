<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802193033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8179D0C0E4');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8179D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8179D0C0E4');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8179D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
