<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20220802153103 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->addSql(<<<'EOF'
            INSERT INTO neos_party_domain_model_person
                SELECT persistence_object_identifier, NULL, NULL
                FROM kaystrobach_contact_domain_model_user

        EOF
        );
        $this->addSql(<<<'EOF'
            UPDATE neos_party_domain_model_person as p
              LEFT JOIN kaystrobach_contact_domain_model_user as u
                ON p.persistence_object_identifier = u.persistence_object_identifier
              LEFT JOIN kaystrobach_contact_domain_model_contact as c
                ON u.contact = c.persistence_object_identifier
            SET
                p.name = c.name

        EOF
        );
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');

        $this->throwIrreversibleMigrationException();
    }
}