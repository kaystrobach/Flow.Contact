<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108181837 extends AbstractMigration
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

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_institution ADD address_combinedaddress LONGTEXT NOT NULL, ADD address_street VARCHAR(255) NOT NULL, ADD address_housenumber VARCHAR(255) NOT NULL, ADD address_addressaddon VARCHAR(255) NOT NULL, ADD address_roomnumber VARCHAR(255) NOT NULL, ADD address_zipcode VARCHAR(255) NOT NULL, ADD address_city VARCHAR(255) NOT NULL, ADD address_country VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1027Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1027Platform'."
        );

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_institution DROP address_combinedaddress, DROP address_street, DROP address_housenumber, DROP address_addressaddon, DROP address_roomnumber, DROP address_zipcode, DROP address_city, DROP address_country');
    }
}
