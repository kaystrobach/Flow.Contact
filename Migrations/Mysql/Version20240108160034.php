<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240108160034 extends AbstractMigration
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

        $this->addSql('
            UPDATE kaystrobach_contact_domain_model_user AS u
            LEFT JOIN `kaystrobach_contact_domain_model_contact` AS c
                ON u.`contact` = c.`persistence_object_identifier`
            LEFT JOIN `kaystrobach_contact_domain_model_address` AS a
                ON c.`address` = a.`persistence_object_identifier`
            SET
              u.`address_street` = a.`street`,
              u.`address_housenumber` = a.`housenumber`,
              u.`address_addressaddon` = a.`addressaddon`,
              u.`address_roomnumber` = a.`roomnumber`,
              u.`address_zipcode` = a.`zipcode`,
              u.`address_city` = a.`city`,
              u.`address_country` = a.`country`
        ');
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
