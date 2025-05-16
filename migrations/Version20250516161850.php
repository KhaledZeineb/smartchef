<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250516161850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout du champ full_name avec une valeur par défaut vide pour éviter les erreurs SQLite';
    }

    public function up(Schema $schema): void
    {
        // Ajoute une colonne avec une valeur par défaut pour éviter les erreurs SQLite
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD COLUMN full_name VARCHAR(100) NOT NULL DEFAULT ''
        SQL);
    }

    public function down(Schema $schema): void
    {
        // Supprime la colonne full_name
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password FROM user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                email VARCHAR(180) NOT NULL,
                roles CLOB NOT NULL, --(DC2Type:json)
                password VARCHAR(255) NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO user (id, email, roles, password) SELECT id, email, roles, password FROM __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__user
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)
        SQL);
    }
}
