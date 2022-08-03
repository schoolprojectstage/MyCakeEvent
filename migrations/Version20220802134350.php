<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802134350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8179D0C0E4');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8179D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE baker ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE baker ADD CONSTRAINT FK_4449E865A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4449E865A76ED395 ON baker (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8179D0C0E4');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8179D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE baker DROP FOREIGN KEY FK_4449E865A76ED395');
        $this->addSql('DROP INDEX UNIQ_4449E865A76ED395 ON baker');
        $this->addSql('ALTER TABLE baker DROP user_id');
    }
}
