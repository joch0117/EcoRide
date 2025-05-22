/*création de la base de données*/

CREATE DATABASE IF NOT EXISTS ecoride CHARACTER SET utf8mb4_unicode_ci;
USE ecoride;
/*table user */
create TAble user(
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(180) NOT NULL UNIQUE,
    roles JSON NOT NULL,
    password VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL,
    surname VARCHAR(50) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    date_birth DATE,
    photo_url VARCHAR(255),
    is_passenger TINYINT(1) DEFAULT 0,
    is_driver TINYINT(1) DEFAULT 0,
    credit INT DEFAULT 0,
    is_suspended TINYINT(1) DEFAULT 0
)

/*Table preference*/
CREATE TABLE preference (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    label VARCHAR(50) NOT NULL,
    value TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

/* Table vehicle */
CREATE TABLE vehicle (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    color VARCHAR(30),
    plate VARCHAR(15) NOT NULL UNIQUE,
    energy_type VARCHAR(20),
    first_registration DATE,
    user_id INT NOT NULL,
    seats_total INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

/* Table trip*/
CREATE TABLE trip (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    departure_city VARCHAR(50) NOT NULL,
    arrival_city VARCHAR(50) NOT NULL,
    departure_datetime DATETIME NOT NULL,
    arrival_datetime DATETIME NOT NULL,
    duration INT,
    price INT,
    seats_available INT,
    is_ecological TINYINT(1) DEFAULT 0,
    status VARCHAR(20),
    FOREIGN KEY (driver_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicle(id) ON DELETE CASCADE
);

/* Table booking */
CREATE TABLE booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    trip_id INT NOT NULL,
    seats INT NOT NULL,
    state VARCHAR(20) NOT NULL,
    created_at DATETIME,
    feedback_status VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (trip_id) REFERENCES trip(id) ON DELETE CASCADE
);

/* Table review*/
CREATE TABLE review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    writer_id INT NOT NULL,
    driver_id INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT,
    status VARCHAR(20),
    created_at DATETIME,
    FOREIGN KEY (trip_id) REFERENCES trip(id) ON DELETE CASCADE,
    FOREIGN KEY (writer_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (driver_id) REFERENCES user(id) ON DELETE CASCADE
);

/* Table credit_transaction*/
CREATE TABLE credit_transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    trip_id INT,
    type VARCHAR(20) NOT NULL,
    amount INT NOT NULL,
    description VARCHAR(255),
    created_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
    FOREIGN KEY (trip_id) REFERENCES trip(id) ON DELETE CASCADE
);

/*Table incident_report */
CREATE TABLE incident_report (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    reporter_id INT NOT NULL,
    description TEXT,
    incident_status VARCHAR(20),
    created_at DATETIME,
    FOREIGN KEY (trip_id) REFERENCES trip(id) ON DELETE CASCADE,
    FOREIGN KEY (reporter_id) REFERENCES user(id) ON DELETE CASCADE
);

/*script reset

SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE incident_report;
TRUNCATE TABLE credit_transaction;
TRUNCATE TABLE review;
TRUNCATE TABLE booking;
TRUNCATE TABLE trip;
TRUNCATE TABLE vehicle;
TRUNCATE TABLE preference;
TRUNCATE TABLE user;
SET FOREIGN_KEY_CHECKS=1;  */