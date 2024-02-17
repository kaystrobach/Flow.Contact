<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217063104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySqlPlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySqlPlatform'."
        );

        $this->addSql('CREATE TABLE kaystrobach_contact_domain_model_userinstitutionrelationship (persistence_object_identifier VARCHAR(40) NOT NULL, institution VARCHAR(40) DEFAULT NULL, user VARCHAR(40) DEFAULT NULL, position VARCHAR(255) NOT NULL, notes LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_by VARCHAR(255) NOT NULL, term_startat DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', term_stopat DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', address_combinedaddress LONGTEXT NOT NULL, address_street VARCHAR(255) NOT NULL, address_housenumber VARCHAR(255) NOT NULL, address_addressaddon VARCHAR(255) NOT NULL, address_roomnumber VARCHAR(255) NOT NULL, address_zipcode VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, address_country VARCHAR(255) NOT NULL, phone_work_landline VARCHAR(255) NOT NULL, phone_work_mobile VARCHAR(255) NOT NULL, phone_private_landline VARCHAR(255) NOT NULL, phone_private_mobile VARCHAR(255) NOT NULL, INDEX IDX_DF519EB83A9F98E5 (institution), INDEX IDX_DF519EB88D93D649 (user), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship ADD CONSTRAINT FK_DF519EB83A9F98E5 FOREIGN KEY (institution) REFERENCES kaystrobach_contact_domain_model_institution (persistence_object_identifier)');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship ADD CONSTRAINT FK_DF519EB88D93D649 FOREIGN KEY (user) REFERENCES kaystrobach_contact_domain_model_user (persistence_object_identifier)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySqlPlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySqlPlatform'."
        );

        $this->addSql('DROP TABLE kaystrobach_contact_domain_model_userinstitutionrelationship');
        $this->addSql('DROP TABLE kaystrobach_contact_securitycenter_domain_model_activityl_21769');
    }
}
