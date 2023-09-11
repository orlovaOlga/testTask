<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230911183426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "users" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users
        (
            id          INT AUTO_INCREMENT NOT NULL,
            name        VARCHAR(50)        NOT NULL,
            age         TINYINT            NOT NULL,
            country     VARCHAR(30)        NOT NULL,
            email       VARCHAR(50)        NOT NULL,
            profile_pic VARCHAR(150)       NOT NULL,
            INDEX users_age_index (age),
            INDEX users_country_index (country),
            PRIMARY KEY (id)
        ) DEFAULT CHARACTER SET utf8mb4
          COLLATE `utf8mb4_unicode_ci`
          ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
