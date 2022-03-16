<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220316223128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `user` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL, email VARCHAR(50) NOT NULL, created_on DATETIME NOT NULL, UNIQUE INDEX U_user_email (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user');
    }
}
