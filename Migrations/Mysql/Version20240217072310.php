<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217072310 extends AbstractMigration
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

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship ADD primaryelectronicaddress VARCHAR(40) DEFAULT NULL');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship ADD CONSTRAINT FK_DF519EB8A7CECF13 FOREIGN KEY (primaryelectronicaddress) REFERENCES neos_party_domain_model_electronicaddress (persistence_object_identifier)');
        $this->addSql('CREATE INDEX IDX_DF519EB8A7CECF13 ON kaystrobach_contact_domain_model_userinstitutionrelationship (primaryelectronicaddress)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MariaDb1027Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MariaDb1027Platform'."
        );

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship DROP FOREIGN KEY FK_DF519EB8A7CECF13');
        $this->addSql('DROP INDEX IDX_DF519EB8A7CECF13 ON kaystrobach_contact_domain_model_userinstitutionrelationship');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_userinstitutionrelationship DROP primaryelectronicaddress');
    }
}
