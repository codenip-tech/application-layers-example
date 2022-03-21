<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220316224345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `post` table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE post (id CHAR(36) NOT NULL, author VARCHAR(50) NOT NULL, title VARCHAR(50) NOT NULL, content VARCHAR(255) NOT NULL, created_on DATETIME NOT NULL, INDEX IDX_post_title (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE post');
    }
}
