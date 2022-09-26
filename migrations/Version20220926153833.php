<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220926153833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE odoo_business ALTER host TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE odoo_business ALTER db TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE odoo_business ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE odoo_business ALTER api_key TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE odoo_contact ADD tag VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE odoo_contact ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE odoo_contact ALTER phone TYPE VARCHAR(255)');
        $this->addSql('ALTER INDEX idx_4b365660930a2641 RENAME TO IDX_3F201908ED6D751A');
        $this->addSql('DROP INDEX idx_4b365660930a2642');
        $this->addSql('ALTER TABLE odoo_sent_contact ALTER message TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE odoo_sent_contact ALTER message DROP DEFAULT');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3EC31CCBC83C1D67 ON odoo_sent_contact (odoo_contact_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE odoo_business ALTER host TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE odoo_business ALTER db TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE odoo_business ALTER name TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE odoo_business ALTER api_key TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE odoo_contact DROP tag');
        $this->addSql('ALTER TABLE odoo_contact ALTER name TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE odoo_contact ALTER phone TYPE VARCHAR(50)');
        $this->addSql('ALTER INDEX idx_3f201908ed6d751a RENAME TO idx_4b365660930a2641');
        $this->addSql('DROP INDEX UNIQ_3EC31CCBC83C1D67');
        $this->addSql('ALTER TABLE odoo_sent_contact ALTER message TYPE TEXT');
        $this->addSql('ALTER TABLE odoo_sent_contact ALTER message DROP DEFAULT');
        $this->addSql('CREATE INDEX idx_4b365660930a2642 ON odoo_sent_contact (odoo_contact_id)');
    }
}
