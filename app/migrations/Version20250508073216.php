<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508073216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649E7927C74 ON user (email)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649F85E0677 ON user (username)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649412FF106 ON user (is_driver)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D6493C1785B2 ON user (is_passenger)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649E7927C74 ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649F85E0677 ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649412FF106 ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D6493C1785B2 ON user
        SQL);
    }
}
