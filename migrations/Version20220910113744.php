<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220910113744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "odoo_business_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE odoo_business (id INT NOT NULL, host VARCHAR(50) NOT NULL, db VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, api_key VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE SEQUENCE "odoo_contact_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE odoo_contact (id INT NOT NULL, odoo_business_id INT NOT NULL, name VARCHAR(50) NOT NULL, phone VARCHAR(50) NOT NULL, odoo_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE SEQUENCE "odoo_sent_contact_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE odoo_sent_contact (id INT NOT NULL, odoo_contact_id INT NOT NULL, message TEXT NOT NULL, PRIMARY KEY(id))');

        $this->addSql('ALTER TABLE odoo_contact ADD CONSTRAINT FK_4B365660930A2641 FOREIGN KEY (odoo_business_id) REFERENCES odoo_business (id)');
        $this->addSql('CREATE INDEX IDX_4B365660930A2641 ON odoo_contact (odoo_business_id)');

        $this->addSql('ALTER TABLE odoo_sent_contact ADD CONSTRAINT FK_4B365660930A2642 FOREIGN KEY (odoo_contact_id) REFERENCES odoo_contact (id)');
        $this->addSql('CREATE INDEX IDX_4B365660930A2642 ON odoo_sent_contact (odoo_contact_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE odoo_business;');
        $this->addSql('DROP TABLE odoo_contact;');
        $this->addSql('DROP TABLE odoo_sent_contact;');
    }
}
