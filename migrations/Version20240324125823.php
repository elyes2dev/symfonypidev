<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324125823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, pricetotal DOUBLE PRECISION NOT NULL, idUser VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE composed (cart_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_6CA7B3891AD5CDBF (cart_id), INDEX IDX_6CA7B3894584665A (product_id), PRIMARY KEY(cart_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE claim (id INT AUTO_INCREMENT NOT NULL, date VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, satisfaction VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, response VARCHAR(255) NOT NULL, closuredate VARCHAR(255) NOT NULL, idUser VARCHAR(255) NOT NULL, idClub VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, governorate VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, starttime VARCHAR(255) NOT NULL, endtime VARCHAR(255) NOT NULL, stadiumnbr INT NOT NULL, description VARCHAR(255) NOT NULL, longitude DOUBLE PRECISION NOT NULL, latitude DOUBLE PRECISION NOT NULL, idUser VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imageclub (club_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_AD6FF1DB61190A32 (club_id), INDEX IDX_AD6FF1DB3DA5256D (image_id), PRIMARY KEY(club_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, datedeb VARCHAR(255) NOT NULL, datefin VARCHAR(255) NOT NULL, starttime VARCHAR(255) NOT NULL, endtime VARCHAR(255) NOT NULL, nbrparticipants INT NOT NULL, price DOUBLE PRECISION NOT NULL, idClub VARCHAR(255) NOT NULL, idImage VARCHAR(255) NOT NULL, idPlanner VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paymentevent (event_id INT NOT NULL, payment_id INT NOT NULL, INDEX IDX_4708EDC871F7E88B (event_id), INDEX IDX_4708EDC84C3A3BB (payment_id), PRIMARY KEY(event_id, payment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, idProduct VARCHAR(255) NOT NULL, idClub VARCHAR(255) NOT NULL, idEvent VARCHAR(255) NOT NULL, refStadium VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, iduser_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, INDEX IDX_BF5476CA786A81FB (iduser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, text VARCHAR(255) NOT NULL, nbrclub INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, idCart VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paymentorder (order_id INT NOT NULL, payment_id INT NOT NULL, INDEX IDX_898F74F78D9F6D38 (order_id), INDEX IDX_898F74F74C3A3BB (payment_id), PRIMARY KEY(order_id, payment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, idUser VARCHAR(255) NOT NULL, idEvent VARCHAR(255) NOT NULL, idOder VARCHAR(255) NOT NULL, idReservation VARCHAR(255) NOT NULL, idSubscription VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, quantitystock INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imageproduct (product_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_8564BDE64584665A (product_id), INDEX IDX_8564BDE63DA5256D (image_id), PRIMARY KEY(product_id, image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, refstadium VARCHAR(255) DEFAULT NULL, date VARCHAR(255) NOT NULL, starttime VARCHAR(255) NOT NULL, endtime VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, idPlayer VARCHAR(255) NOT NULL, INDEX IDX_42C849556A2E8B35 (refstadium), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paymentreservation (reservation_id INT NOT NULL, payment_id INT NOT NULL, INDEX IDX_147538F8B83297E7 (reservation_id), INDEX IDX_147538F84C3A3BB (payment_id), PRIMARY KEY(reservation_id, payment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stadium (reference VARCHAR(255) NOT NULL, idclub_id INT DEFAULT NULL, height DOUBLE PRECISION NOT NULL, width DOUBLE PRECISION NOT NULL, price INT NOT NULL, rate DOUBLE PRECISION NOT NULL, maintenance INT NOT NULL, INDEX IDX_E604044FBE1D585C (idclub_id), PRIMARY KEY(reference)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE imagestadium (refStadium VARCHAR(255) NOT NULL, idImage INT NOT NULL, INDEX IDX_B02ABD04A593B2A9 (refStadium), INDEX IDX_B02ABD04D2F94742 (idImage), PRIMARY KEY(refStadium, idImage)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, iduser_id INT DEFAULT NULL, startdate VARCHAR(255) NOT NULL, enddate VARCHAR(255) NOT NULL, idOffer VARCHAR(255) NOT NULL, INDEX IDX_A3C664D3786A81FB (iduser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paymentsubscription (subscription_id INT NOT NULL, payment_id INT NOT NULL, INDEX IDX_BF706F79A1887DC (subscription_id), INDEX IDX_BF706F74C3A3BB (payment_id), PRIMARY KEY(subscription_id, payment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phonenumber INT NOT NULL, birthdate VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, creationdate VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, idEvent VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE liked (idUser INT NOT NULL, refStadium VARCHAR(255) NOT NULL, INDEX IDX_CA19CBBAFE6E88D7 (idUser), INDEX IDX_CA19CBBAA593B2A9 (refStadium), PRIMARY KEY(idUser, refStadium)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (idPlayer INT NOT NULL, idEvent INT NOT NULL, INDEX IDX_AB55E24FFB08D2FF (idPlayer), INDEX IDX_AB55E24F2C6A49BA (idEvent), PRIMARY KEY(idPlayer, idEvent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE useractivitylog (logid INT AUTO_INCREMENT NOT NULL, userid_id INT DEFAULT NULL, logintimestamp VARCHAR(255) NOT NULL, loginsuccess TINYINT(1) NOT NULL, INDEX IDX_AC92FBF758E0A285 (userid_id), PRIMARY KEY(logid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE composed ADD CONSTRAINT FK_6CA7B3891AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE composed ADD CONSTRAINT FK_6CA7B3894584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imageclub ADD CONSTRAINT FK_AD6FF1DB61190A32 FOREIGN KEY (club_id) REFERENCES club (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imageclub ADD CONSTRAINT FK_AD6FF1DB3DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paymentevent ADD CONSTRAINT FK_4708EDC871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paymentevent ADD CONSTRAINT FK_4708EDC84C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paymentorder ADD CONSTRAINT FK_898F74F78D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paymentorder ADD CONSTRAINT FK_898F74F74C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imageproduct ADD CONSTRAINT FK_8564BDE64584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE imageproduct ADD CONSTRAINT FK_8564BDE63DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556A2E8B35 FOREIGN KEY (refstadium) REFERENCES stadium (reference)');
        $this->addSql('ALTER TABLE paymentreservation ADD CONSTRAINT FK_147538F8B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paymentreservation ADD CONSTRAINT FK_147538F84C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE stadium ADD CONSTRAINT FK_E604044FBE1D585C FOREIGN KEY (idclub_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE imagestadium ADD CONSTRAINT FK_B02ABD04A593B2A9 FOREIGN KEY (refStadium) REFERENCES stadium (reference)');
        $this->addSql('ALTER TABLE imagestadium ADD CONSTRAINT FK_B02ABD04D2F94742 FOREIGN KEY (idImage) REFERENCES image (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3786A81FB FOREIGN KEY (iduser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE paymentsubscription ADD CONSTRAINT FK_BF706F79A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paymentsubscription ADD CONSTRAINT FK_BF706F74C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE liked ADD CONSTRAINT FK_CA19CBBAFE6E88D7 FOREIGN KEY (idUser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE liked ADD CONSTRAINT FK_CA19CBBAA593B2A9 FOREIGN KEY (refStadium) REFERENCES stadium (reference)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FFB08D2FF FOREIGN KEY (idPlayer) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F2C6A49BA FOREIGN KEY (idEvent) REFERENCES event (id)');
        $this->addSql('ALTER TABLE useractivitylog ADD CONSTRAINT FK_AC92FBF758E0A285 FOREIGN KEY (userid_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE composed DROP FOREIGN KEY FK_6CA7B3891AD5CDBF');
        $this->addSql('ALTER TABLE composed DROP FOREIGN KEY FK_6CA7B3894584665A');
        $this->addSql('ALTER TABLE imageclub DROP FOREIGN KEY FK_AD6FF1DB61190A32');
        $this->addSql('ALTER TABLE imageclub DROP FOREIGN KEY FK_AD6FF1DB3DA5256D');
        $this->addSql('ALTER TABLE paymentevent DROP FOREIGN KEY FK_4708EDC871F7E88B');
        $this->addSql('ALTER TABLE paymentevent DROP FOREIGN KEY FK_4708EDC84C3A3BB');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA786A81FB');
        $this->addSql('ALTER TABLE paymentorder DROP FOREIGN KEY FK_898F74F78D9F6D38');
        $this->addSql('ALTER TABLE paymentorder DROP FOREIGN KEY FK_898F74F74C3A3BB');
        $this->addSql('ALTER TABLE imageproduct DROP FOREIGN KEY FK_8564BDE64584665A');
        $this->addSql('ALTER TABLE imageproduct DROP FOREIGN KEY FK_8564BDE63DA5256D');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556A2E8B35');
        $this->addSql('ALTER TABLE paymentreservation DROP FOREIGN KEY FK_147538F8B83297E7');
        $this->addSql('ALTER TABLE paymentreservation DROP FOREIGN KEY FK_147538F84C3A3BB');
        $this->addSql('ALTER TABLE stadium DROP FOREIGN KEY FK_E604044FBE1D585C');
        $this->addSql('ALTER TABLE imagestadium DROP FOREIGN KEY FK_B02ABD04A593B2A9');
        $this->addSql('ALTER TABLE imagestadium DROP FOREIGN KEY FK_B02ABD04D2F94742');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3786A81FB');
        $this->addSql('ALTER TABLE paymentsubscription DROP FOREIGN KEY FK_BF706F79A1887DC');
        $this->addSql('ALTER TABLE paymentsubscription DROP FOREIGN KEY FK_BF706F74C3A3BB');
        $this->addSql('ALTER TABLE liked DROP FOREIGN KEY FK_CA19CBBAFE6E88D7');
        $this->addSql('ALTER TABLE liked DROP FOREIGN KEY FK_CA19CBBAA593B2A9');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FFB08D2FF');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F2C6A49BA');
        $this->addSql('ALTER TABLE useractivitylog DROP FOREIGN KEY FK_AC92FBF758E0A285');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE composed');
        $this->addSql('DROP TABLE claim');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE imageclub');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE paymentevent');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE offer');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE paymentorder');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE imageproduct');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE paymentreservation');
        $this->addSql('DROP TABLE stadium');
        $this->addSql('DROP TABLE imagestadium');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE paymentsubscription');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE liked');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE useractivitylog');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
