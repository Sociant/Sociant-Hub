<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200521085615 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cron_job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(191) NOT NULL, command VARCHAR(1024) NOT NULL, schedule VARCHAR(191) NOT NULL, description VARCHAR(191) NOT NULL, enabled TINYINT(1) NOT NULL, UNIQUE INDEX un_name (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cron_report (id INT AUTO_INCREMENT NOT NULL, job_id INT DEFAULT NULL, run_at DATETIME NOT NULL, run_time DOUBLE PRECISION NOT NULL, exit_code INT NOT NULL, output LONGTEXT NOT NULL, INDEX IDX_B6C6A7F5BE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cron_report ADD CONSTRAINT FK_B6C6A7F5BE04EA9 FOREIGN KEY (job_id) REFERENCES cron_job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE automated_update CHANGE next_update next_update DATETIME DEFAULT NULL, CHANGE last_update last_update DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE twitter_user CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE twitter_user_id twitter_user_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE user_action CHANGE twitter_user_id twitter_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_analytics CHANGE most_statuses_id most_statuses_id INT DEFAULT NULL, CHANGE most_followers_id most_followers_id INT DEFAULT NULL, CHANGE oldest_account_id oldest_account_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cron_report DROP FOREIGN KEY FK_B6C6A7F5BE04EA9');
        $this->addSql('DROP TABLE cron_job');
        $this->addSql('DROP TABLE cron_report');
        $this->addSql('ALTER TABLE automated_update CHANGE next_update next_update DATETIME DEFAULT \'NULL\', CHANGE last_update last_update DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE twitter_user CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE twitter_user_id twitter_user_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user_action CHANGE twitter_user_id twitter_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_analytics CHANGE most_statuses_id most_statuses_id INT DEFAULT NULL, CHANGE most_followers_id most_followers_id INT DEFAULT NULL, CHANGE oldest_account_id oldest_account_id INT DEFAULT NULL');
    }
}
