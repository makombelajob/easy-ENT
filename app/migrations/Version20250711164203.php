<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250711164203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classes (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, level INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contacts (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(50) NOT NULL, message LONGTEXT NOT NULL, send_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, subjects_id INT NOT NULL, classes_id INT NOT NULL, name VARCHAR(50) NOT NULL, coefficient DOUBLE PRECISION NOT NULL, day VARCHAR(10) NOT NULL, start_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', room VARCHAR(10) NOT NULL, INDEX IDX_A9A55A4C94AF957A (subjects_id), INDEX IDX_A9A55A4C9E225B24 (classes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(50) NOT NULL, file_type VARCHAR(10) NOT NULL, upload_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources_courses (ressources_id INT NOT NULL, courses_id INT NOT NULL, INDEX IDX_F799735C3C361826 (ressources_id), INDEX IDX_F799735CF9295384 (courses_id), PRIMARY KEY(ressources_id, courses_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE results (id INT AUTO_INCREMENT NOT NULL, courses_id INT NOT NULL, users_id INT NOT NULL, note INT NOT NULL, anual_note INT DEFAULT NULL, mensual_note INT DEFAULT NULL, remark VARCHAR(255) NOT NULL, INDEX IDX_9FA3E414F9295384 (courses_id), INDEX IDX_9FA3E41467B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school_fees (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, name VARCHAR(100) NOT NULL, amount INT NOT NULL, send_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E1BB1E8167B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subjects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(50) NOT NULL, register_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_connection_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', reset_token VARCHAR(32) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_courses (users_id INT NOT NULL, courses_id INT NOT NULL, INDEX IDX_59A52E8667B3B43D (users_id), INDEX IDX_59A52E86F9295384 (courses_id), PRIMARY KEY(users_id, courses_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_classes (users_id INT NOT NULL, classes_id INT NOT NULL, INDEX IDX_F2ED0A0F67B3B43D (users_id), INDEX IDX_F2ED0A0F9E225B24 (classes_id), PRIMARY KEY(users_id, classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_users (users_source INT NOT NULL, users_target INT NOT NULL, INDEX IDX_F3F401A0506DF1E3 (users_source), INDEX IDX_F3F401A04988A16C (users_target), PRIMARY KEY(users_source, users_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C94AF957A FOREIGN KEY (subjects_id) REFERENCES subjects (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C9E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE ressources_courses ADD CONSTRAINT FK_F799735C3C361826 FOREIGN KEY (ressources_id) REFERENCES ressources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ressources_courses ADD CONSTRAINT FK_F799735CF9295384 FOREIGN KEY (courses_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT FK_9FA3E414F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT FK_9FA3E41467B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE school_fees ADD CONSTRAINT FK_E1BB1E8167B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users_courses ADD CONSTRAINT FK_59A52E8667B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_courses ADD CONSTRAINT FK_59A52E86F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_classes ADD CONSTRAINT FK_F2ED0A0F67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_classes ADD CONSTRAINT FK_F2ED0A0F9E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_users ADD CONSTRAINT FK_F3F401A0506DF1E3 FOREIGN KEY (users_source) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_users ADD CONSTRAINT FK_F3F401A04988A16C FOREIGN KEY (users_target) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C94AF957A');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C9E225B24');
        $this->addSql('ALTER TABLE ressources_courses DROP FOREIGN KEY FK_F799735C3C361826');
        $this->addSql('ALTER TABLE ressources_courses DROP FOREIGN KEY FK_F799735CF9295384');
        $this->addSql('ALTER TABLE results DROP FOREIGN KEY FK_9FA3E414F9295384');
        $this->addSql('ALTER TABLE results DROP FOREIGN KEY FK_9FA3E41467B3B43D');
        $this->addSql('ALTER TABLE school_fees DROP FOREIGN KEY FK_E1BB1E8167B3B43D');
        $this->addSql('ALTER TABLE users_courses DROP FOREIGN KEY FK_59A52E8667B3B43D');
        $this->addSql('ALTER TABLE users_courses DROP FOREIGN KEY FK_59A52E86F9295384');
        $this->addSql('ALTER TABLE users_classes DROP FOREIGN KEY FK_F2ED0A0F67B3B43D');
        $this->addSql('ALTER TABLE users_classes DROP FOREIGN KEY FK_F2ED0A0F9E225B24');
        $this->addSql('ALTER TABLE users_users DROP FOREIGN KEY FK_F3F401A0506DF1E3');
        $this->addSql('ALTER TABLE users_users DROP FOREIGN KEY FK_F3F401A04988A16C');
        $this->addSql('DROP TABLE classes');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE ressources');
        $this->addSql('DROP TABLE ressources_courses');
        $this->addSql('DROP TABLE results');
        $this->addSql('DROP TABLE school_fees');
        $this->addSql('DROP TABLE subjects');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_courses');
        $this->addSql('DROP TABLE users_classes');
        $this->addSql('DROP TABLE users_users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
