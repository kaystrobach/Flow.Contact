<?php

declare(strict_types=1);

namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240112094732 extends AbstractMigration
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

        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_contact DROP FOREIGN KEY FK_62ADCBD8D4E6F81');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_institution DROP FOREIGN KEY FK_4CC1CA20D4E6F81');
        $this->addSql('ALTER TABLE kaystrobach_contact_domain_model_user DROP FOREIGN KEY FK_A32C8B074C62E638');
        $this->addSql('DROP TABLE kaystrobach_contact_domain_model_address');
        $this->addSql('DROP TABLE kaystrobach_contact_domain_model_contact');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySqlPlatform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\MySqlPlatform'."
        );

        $this->throwIrreversibleMigrationException();
    }
}
