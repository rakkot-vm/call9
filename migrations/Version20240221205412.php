<?php

declare(strict_types=1);

// phpcs:ignoreFile
namespace DoctrineMigrations;

use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221205412 extends AbstractMigration
{
    /**
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getDescription(): string
    {
        return 'TODO: Describe reason for this migration';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof AbstractMySQLPlatform,
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE initiate_time_rule (id INT AUTO_INCREMENT NOT NULL, provider_id INT NOT NULL, until_time INT NOT NULL, start_today TINYINT(1) NOT NULL, INDEX IDX_905A1337A53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scheduled_command (id INT AUTO_INCREMENT NOT NULL, version INT DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT NULL, name VARCHAR(150) NOT NULL, command VARCHAR(200) NOT NULL, arguments LONGTEXT DEFAULT NULL, cron_expression VARCHAR(200) DEFAULT NULL, last_execution DATETIME DEFAULT NULL, last_return_code INT DEFAULT NULL, log_file VARCHAR(150) DEFAULT NULL, priority INT NOT NULL, execute_immediately TINYINT(1) NOT NULL, disabled TINYINT(1) NOT NULL, locked TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_EA0DBC905E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE time_rule (id INT AUTO_INCREMENT NOT NULL, provider_id INT NOT NULL, time INT NOT NULL, country VARCHAR(3) NOT NULL, INDEX IDX_EB65A5E1A53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE initiate_time_rule ADD CONSTRAINT FK_905A1337A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE time_rule ADD CONSTRAINT FK_EB65A5E1A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');

        $this->fillDefaultParams($schema);
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * {@inheritdoc}
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof AbstractMySQLPlatform,
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE initiate_time_rule DROP FOREIGN KEY FK_905A1337A53A8AA');
        $this->addSql('ALTER TABLE time_rule DROP FOREIGN KEY FK_EB65A5E1A53A8AA');
        $this->addSql('DROP TABLE initiate_time_rule');
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE scheduled_command');
        $this->addSql('DROP TABLE time_rule');
    }

    private function fillDefaultParams(Schema $schema): void
    {
        $this->addSql("INSERT INTO provider (id, name) VALUES (1, 'Royal Mail' )");

        $this->addSql("INSERT INTO initiate_time_rule (id, provider_id, until_time, start_today) VALUES (1, 1, 16, true )");
        $this->addSql("INSERT INTO initiate_time_rule (id, provider_id, until_time, start_today) VALUES (2, 1, 24, false )");

        $this->addSql("INSERT INTO time_rule (id, provider_id, time, country) VALUES (1, 1, 1, 'GB')");
        $this->addSql("INSERT INTO time_rule (id, provider_id, time, country) VALUES (2, 1, 3, 'EU')");
        $this->addSql("INSERT INTO time_rule (id, provider_id, time, country) VALUES (3, 1, 8, '')");
    }
}
