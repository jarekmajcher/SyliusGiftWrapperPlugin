<?php

declare(strict_types=1);

namespace JarekMajcher\SyliusGiftWrapperPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251205144714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE majcher_gift_wrapper_gift_wrap (id INT AUTO_INCREMENT NOT NULL, gift_wrap_method_id INT NOT NULL, order_id INT NOT NULL, unit_price INT NOT NULL, dedication LONGTEXT DEFAULT NULL, instruction LONGTEXT DEFAULT NULL, INDEX IDX_877C50104EA46240 (gift_wrap_method_id), INDEX IDX_877C50108D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE majcher_gift_wrapper_gift_wrap_method (id INT AUTO_INCREMENT NOT NULL, tax_category_id INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, UNIQUE INDEX UNIQ_8096CA9677153098 (code), INDEX IDX_8096CA969DF894ED (tax_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE majcher_gift_wrapper_gift_wrap_method_channels (gift_wrap_method_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_1D9590434EA46240 (gift_wrap_method_id), INDEX IDX_1D95904372F5A1AA (channel_id), PRIMARY KEY(gift_wrap_method_id, channel_id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE majcher_gift_wrapper_gift_wrap_method_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_80D05C782C2AC5D3 (translatable_id), UNIQUE INDEX majcher_gift_wrapper_gift_wrap_method_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap ADD CONSTRAINT FK_877C50104EA46240 FOREIGN KEY (gift_wrap_method_id) REFERENCES majcher_gift_wrapper_gift_wrap_method (id)');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap ADD CONSTRAINT FK_877C50108D9F6D38 FOREIGN KEY (order_id) REFERENCES sylius_order (id)');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method ADD CONSTRAINT FK_8096CA969DF894ED FOREIGN KEY (tax_category_id) REFERENCES sylius_tax_category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method_channels ADD CONSTRAINT FK_1D9590434EA46240 FOREIGN KEY (gift_wrap_method_id) REFERENCES majcher_gift_wrapper_gift_wrap_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method_channels ADD CONSTRAINT FK_1D95904372F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method_translation ADD CONSTRAINT FK_80D05C782C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES majcher_gift_wrapper_gift_wrap_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_adjustment ADD gift_wrap_id INT DEFAULT NULL AFTER shipment_id');
        $this->addSql('ALTER TABLE sylius_adjustment ADD CONSTRAINT FK_ACA6E0F2C87E4CFD FOREIGN KEY (gift_wrap_id) REFERENCES majcher_gift_wrapper_gift_wrap (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_ACA6E0F2C87E4CFD ON sylius_adjustment (gift_wrap_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_adjustment DROP FOREIGN KEY FK_ACA6E0F2C87E4CFD');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap DROP FOREIGN KEY FK_877C50104EA46240');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap DROP FOREIGN KEY FK_877C50108D9F6D38');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method DROP FOREIGN KEY FK_8096CA969DF894ED');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method_channels DROP FOREIGN KEY FK_1D9590434EA46240');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method_channels DROP FOREIGN KEY FK_1D95904372F5A1AA');
        $this->addSql('ALTER TABLE majcher_gift_wrapper_gift_wrap_method_translation DROP FOREIGN KEY FK_80D05C782C2AC5D3');
        $this->addSql('DROP TABLE majcher_gift_wrapper_gift_wrap');
        $this->addSql('DROP TABLE majcher_gift_wrapper_gift_wrap_method');
        $this->addSql('DROP TABLE majcher_gift_wrapper_gift_wrap_method_channels');
        $this->addSql('DROP TABLE majcher_gift_wrapper_gift_wrap_method_translation');
        $this->addSql('DROP INDEX IDX_ACA6E0F2C87E4CFD ON sylius_adjustment');
        $this->addSql('ALTER TABLE sylius_adjustment DROP gift_wrap_id');
    }
}
