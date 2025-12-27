<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251227122221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1060Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1060Platform'."
        );

        $this->addSql('ALTER TABLE fourviewture_kis_crm_domain_model_customer ADD postaladdress_personname LONGTEXT NOT NULL, ADD invoiceaddress_personname LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_institution ADD address_personname LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user ADD address_personname LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship ADD address_personname LONGTEXT NOT NULL, ADD postal_address_personname LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1060Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1060Platform'."
        );

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_institution DROP address_personname');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user DROP address_personname');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship DROP address_personname, DROP postal_address_personname');
        $this->addSql('ALTER TABLE fourviewture_kis_crm_domain_model_customer DROP postaladdress_personname, DROP invoiceaddress_personname');
    }
}
