<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217063742 extends AbstractMigration
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

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user ADD primaryinstitutionrelationship VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user ADD CONSTRAINT FK_A32C8B071C682152 FOREIGN KEY (primaryinstitutionrelationship) REFERENCES kaystrobach_contact_domain_model_userinstitutionrelationship (persistence_object_identifier)');
        $this->addSql('CREATE INDEX IDX_A32C8B071C682152 ON kaystrobach_contact_domain_model_user (primaryinstitutionrelationship)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySqlPlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySqlPlatform'."
        );

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user DROP FOREIGN KEY FK_A32C8B071C682152');
        $this->addSql('DROP INDEX IDX_A32C8B071C682152 ON kaystrobach_contact_domain_model_user');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user DROP primaryinstitutionrelationship');
    }
}
