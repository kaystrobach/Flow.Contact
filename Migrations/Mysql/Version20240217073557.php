<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217073557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1027Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1027Platform'."
        );

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user DROP FOREIGN KEY FK_A32C8B073A9F98E5');
        $this->addSql('DROP INDEX IDX_A32C8B073A9F98E5 ON kaystrobach_contact_domain_model_user');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user DROP institution, DROP phone_landline, DROP phone_mobile, DROP institutionposition');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship ADD postal_address_combinedaddress LONGTEXT NOT NULL, ADD postal_address_street VARCHAR(255) NOT NULL, ADD postal_address_housenumber VARCHAR(255) NOT NULL, ADD postal_address_addressaddon VARCHAR(255) NOT NULL, ADD postal_address_roomnumber VARCHAR(255) NOT NULL, ADD postal_address_zipcode VARCHAR(255) NOT NULL, ADD postal_address_city VARCHAR(255) NOT NULL, ADD postal_address_country VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1027Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1027Platform'."
        );

        $this->addSql('ALTER TABLE fourviewture_kis_crm_domain_mode_332c8_personrelationships_join DROP FOREIGN KEY FK_5EC80934D77894CC');
        $this->addSql('DROP TABLE fourviewture_kis_crm_domain_model_order');
        $this->addSql('DROP TABLE fourviewture_kis_crm_domain_model_quote');
        $this->addSql('DROP TABLE fourviewture_kis_crm_domain_mode_332c8_personrelationships_join');
        $this->addSql('DROP TABLE kaystrobach_contact_securitycenter_domain_model_activityl_21769');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user ADD institution VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD phone_landline VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD phone_mobile VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD institutionposition VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user ADD CONSTRAINT FK_A32C8B073A9F98E5 FOREIGN KEY (institution) REFERENCES kaystrobach_contact_domain_model_institution (persistence_object_identifier)');
        $this->addSql('CREATE INDEX IDX_A32C8B073A9F98E5 ON kaystrobach_contact_domain_model_user (institution)');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship DROP postal_address_combinedaddress, DROP postal_address_street, DROP postal_address_housenumber, DROP postal_address_addressaddon, DROP postal_address_roomnumber, DROP postal_address_zipcode, DROP postal_address_city, DROP postal_address_country');
    }
}
