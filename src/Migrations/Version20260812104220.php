<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260812104220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE majcher_gift_wrapper_gift_wrap_method_image (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, type VARCHAR(255) DEFAULT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_5E81481F7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method_image ADD CONSTRAINT FK_5E81481F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES majcher_gift_wrapper_gift_wrap_method (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method_image DROP FOREIGN KEY FK_5E81481F7E3C61F9');
        $this->addSql('DROP TABLE majcher_gift_wrapper_gift_wrap_method_image');
    }
}
