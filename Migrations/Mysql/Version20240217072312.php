<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217072312 extends AbstractMigration
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

        // @todo create
        // * a relationship for the one institution related to the person
        // * and copy all the address
        // * and phone info
        // * and and the primary electronic address

        $now = new \DateTimeImmutable('now');
        $now = $now->format('Y-m-d H:i:s');

        $stmt = $this->connection->prepare('
            SELECT * FROM kaystrobach_contact_domain_model_user
                INNER JOIN neos_party_domain_model_person
                ON kaystrobach_contact_domain_model_user.persistence_object_identifier = neos_party_domain_model_person.persistence_object_identifier

        ');
        $data = $stmt->executeQuery();

        foreach ($data->fetchAllAssociative() as $row)
        {
            $newRelationship = [
                'persistence_object_identifier' => Uuid::uuid4(),
                'institution' => $row['institution'] ?? null,
                'user' => $row['persistence_object_identifier'],
                'position' => $row['institutionposition'] ?? '',
                'notes' => '',
                'created_at' => $now,
                'created_by' => 'migration',
                'term_startat' => null,
                'term_stopat' => null,
                'address_combinedaddress' => $row['address_combinedaddress'],
                'address_street' => $row['address_street'],
                'address_housenumber' => $row['address_housenumber'],
                'address_addressaddon' => $row['address_addressaddon'],
                'address_roomnumber' => $row['address_roomnumber'],
                'address_zipcode' => $row['address_zipcode'],
                'address_city' => $row['address_city'],
                'address_country' => $row['address_country'],
                'phone_work_mobile' => $row['phone_mobile'],
                'phone_work_landline' => $row['phone_mobile'],
                'phone_private_mobile' => '',
                'phone_private_landline' => '',
                'primaryelectronicaddress' => $row['primaryelectronicaddress'] ?? null
            ];
            $this->connection->insert(
                'kaystrobach_contact_domain_model_userinstitutionrelationship',
                $newRelationship
            );

            $this->connection->update(
                'kaystrobach_contact_domain_model_user',
                [
                    'primaryinstitutionrelationship' => $newRelationship['persistence_object_identifier']
                ],
                [
                    'persistence_object_identifier' => $row['persistence_object_identifier']
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySqlPlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySqlPlatform'."
        );


    }
}
