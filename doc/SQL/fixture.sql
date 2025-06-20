
/* fixture SQL base de donné railway*/

/*table utilisateur données*/

INSERT INTO `user` (id, email, roles, password, username, surname, firstname, phone, date_birth, photo_url, is_passenger, is_driver, credit, is_suspended)
VALUES  (2, 'user1@ecoride.com', '["ROLE_ADMIN"]', '$2y$13$goHdjF8613xVCr.U1MElAeFr1ek9RuMwic/5L4.P5mLkF7mgICV3O', 'user1', 'Martin', 'Alice', '0600000001', '1992-09-27', 'default.png', true, false, 23, false),
        (3, 'user2@ecoride.com', '["ROLE_USER"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user2', 'Durand', 'Bob', '0600000002', '1995-06-24', 'default.png', true, false, 26, false),
        (4, 'user3@ecoride.com', '["ROLE_USER"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user3', 'Lemoine', 'Clara', '0600000003', '1998-03-20', 'default.png', true, true, 29, false),
        (5, 'user4@ecoride.com', '["ROLE_USER"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user4', 'Dubois', 'David', '0600000004', '2000-12-14', 'default.png', true, false, 32, false),
        (6, 'user5@ecoride.com', '["ROLE_USER"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user5', 'Fontaine', 'Emma', '0600000005', '2003-09-10', 'default.png', true, false, 35, false),
        (7, 'user6@ecoride.com', '["ROLE_USER"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user6', 'Petit', 'Florian', '0600000006', '2006-06-06', 'default.png', true, true, 38, false),
        (8, 'user7@ecoride.com', '["ROLE_USER"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user7', 'Moreau', 'Gwen', '0600000007', '2009-03-02', 'default.png', true, false, 41, false),
        (9, 'user8@ecoride.com', '["ROLE_USER"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user8', 'Leroy', 'Hugo', '0600000008', '2011-11-27', 'default.png', true, false, 44, false),
        (10, 'user9@ecoride.com', '["ROLE_USER"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user9', 'Benoit', 'Iris', '0600000009', '2014-08-23', 'default.png', true, true, 47, false),
        (11, 'user10@ecoride.com', '["ROLE_EMPLOYE"]', '$2y$13$79Wf/eOinl4PwIJtjHa4Z.BgYrf7j4t1yswO0XVsY/1Z01gWTb6Fq', 'user10', 'Renard', 'Julien', '06000000010', '2017-05-19', 'default.png', true, false, 50, false);


INSERT INTO `preference` (id, user_id, label, value)
VALUES  (3, 2, 'Animal', false),
        (4, 2, 'Fumeur', false),
        (5, 3, 'Animal', false),
        (6, 3, 'Fumeur', false),
        (7, 4, 'Animal', false),
        (8, 4, 'Fumeur', false),
        (9, 5, 'Animal', false),
        (10, 5, 'Fumeur', false),
        (11, 6, 'Animal', false),
        (12, 6, 'Fumeur', false),
        (13, 7, 'Animal', false),
        (14, 7, 'Fumeur', false),
        (15, 8, 'Animal', false),
        (16, 8, 'Fumeur', false),
        (17, 9, 'Animal', false),
        (18, 9, 'Fumeur', false);


/*table vehicle*/

INSERT INTO `vehicle` (id, brand, model, color, plate, energy_type, first_registration, user_id, seats_total)
VALUES (1, 'Peugeot', '208', 'Bleu', 'AB-10CD', 'diesel', '2011-01-15', 7, 5),
(2, 'Citroën', 'C3', 'Gris', 'AB-11CD', 'electrique', '2011-02-15', 5, 5),
(3, 'Citroën', 'C3', 'Gris', 'AB-20CD', 'electrique', '2012-01-15', 2, 5),
(4, 'Tesla', 'Model 3', 'Noir', 'AB-21CD', 'hybride', '2012-02-15', 2, 5),
(5, 'Tesla', 'Model 3', 'Noir', 'AB-30CD', 'hybride', '2013-01-15', 3, 5),
(6, 'Toyota', 'Yaris', 'Blanc', 'AB-31CD', 'essence', '2013-02-15', 3, 5),
(7, 'Toyota', 'Yaris', 'Blanc', 'AB-40CD', 'essence', '2014-01-15', 4, 5),
(8, 'BMW', 'i3', 'Vert', 'AB-41CD', 'diesel', '2014-02-15', 4, 5),
(9, 'BMW', 'i3', 'Vert', 'AB-50CD', 'diesel', '2015-01-15', 5, 5),
(10, 'Hyundai', 'Ioniq', 'Jaune', 'AB-51CD', 'electrique', '2015-02-15', 5, 5),
(11, 'Hyundai', 'Ioniq', 'Jaune', 'AB-60CD', 'electrique', '2016-01-15', 6, 5),
(12, 'Fiat', '500', 'Orange', 'AB-61CD', 'hybride', '2016-02-15', 6, 5),
(13, 'Fiat', '500', 'Orange', 'AB-70CD', 'hybride', '2017-01-15', 7, 5),
(14, 'Renault', 'Clio', 'Rouge', 'AB-71CD', 'essence', '2017-02-15', 7, 5),
(15, 'Renault', 'Clio', 'Rouge', 'AB-80CD', 'essence', '2018-01-15', 8, 5),
(16, 'Peugeot', '208', 'Bleu', 'AB-81CD', 'diesel', '2018-02-15', 8, 5);


/* table trip */

INSERT INTO `trip` (id, driver_id, vehicle_id, departure_city, arrival_city, departure_datetime, arrival_datetime, duration, price, seats_available, is_ecological, status)
VALUES(1, 3, 1, 'Marseille', 'Nice', '2025-08-01 08:00:00', '2025-08-01 13:00:00', 300, 29, 1, false, 'started'),
(2, 2, 2, 'Lyon', 'Toulouse', '2025-08-02 09:00:00', '2025-08-02 11:00:00', 120, 22, 1, false, 'scheduled'),
(3, 3, 3, 'Bordeaux', 'Nantes', '2025-08-03 10:00:00', '2025-08-03 15:00:00', 300, 9, 1, true, 'finished'),
(4, 4, 4, 'Paris', 'Nantes', '2025-08-04 11:00:00', '2025-08-04 16:00:00', 300, 17, 3, false, 'started'),
(5, 5, 5, 'Lyon', 'Toulouse', '2025-08-05 12:00:00', '2025-08-05 15:00:00', 180, 29, 4, true, 'cancelled'),
(6, 6, 6, 'Lyon', 'Grenoble', '2025-08-06 13:00:00', '2025-08-06 16:00:00', 180, 11, 4, true, 'cancelled'),
(7, 7, 7, 'Paris', 'Nice', '2025-08-07 14:00:00', '2025-08-07 19:00:00', 300, 16, 1, false, 'scheduled'),
(8, 8, 8, 'Paris', 'Nantes', '2025-08-08 15:00:00', '2025-08-08 18:00:00', 180, 24, 3, false, 'cancelled'),
(9, 5, 9, 'Paris', 'Nantes', '2025-08-09 16:00:00', '2025-08-09 21:00:00', 300, 24, 3, false, 'started'),
(10, 2, 10, 'Paris', 'Toulouse', '2025-08-10 17:00:00', '2025-08-10 21:00:00', 240, 28, 2, false, 'finished');


/* table booking */

INSERT INTO `booking` (id, user_id, trip_id, seats, state, created_at, feedback_status)
VALUES
(1, 5, 1, 2, 'pending', '2025-05-11 00:00:00', 'validated'),
(2, 2, 1, 2, 'cancelled', '2025-05-11 00:00:00', 'rejected'),
(3, 3, 1, 1, 'pending', '2025-05-11 00:00:00', 'rejected'),
(4, 8, 1, 2, 'pending', '2025-05-11 00:00:00', 'validated'),
(5, 6, 1, 2, 'pending', '2025-05-11 00:00:00', 'rejected'),
(6, 5, 2, 2, 'cancelled', '2025-05-12 00:00:00', 'pending'),
(7, 4, 2, 1, 'confirmed', '2025-05-12 00:00:00', 'pending'),
(8, 5, 2, 2, 'pending', '2025-05-12 00:00:00', 'rejected'),
(9, 8, 2, 2, 'confirmed', '2025-05-12 00:00:00', 'rejected'),
(10, 3, 2, 2, 'pending', '2025-05-12 00:00:00', 'rejected'),
(11, 7, 3, 2, 'confirmed', '2025-05-13 00:00:00', 'pending'),
(12, 6, 3, 2, 'cancelled', '2025-05-13 00:00:00', 'rejected'),
(13, 6, 3, 2, 'pending', '2025-05-13 00:00:00', 'pending'),
(14, 8, 3, 2, 'confirmed', '2025-05-13 00:00:00', 'pending'),
(15, 4, 3, 2, 'cancelled', '2025-05-13 00:00:00', 'rejected'),
(16, 2, 4, 1, 'pending', '2025-05-14 00:00:00', 'validated'),
(17, 9, 4, 2, 'cancelled', '2025-05-14 00:00:00', 'validated'),
(18, 5, 4, 2, 'confirmed', '2025-05-14 00:00:00', 'validated'),
(19, 7, 4, 2, 'confirmed', '2025-05-14 00:00:00', 'validated'),
(20, 3, 4, 1, 'cancelled', '2025-05-14 00:00:00', 'rejected');


/* table review */

INSERT INTO `review` (id, trip_id, writer_id, driver_id, rating, comment, status, created_at)
VALUES
(1, 4, 2, 4, 1, 'Pas de communication.', 'rejected', '2025-06-01 00:00:00'),
(2, 8, 5, 8, 4, 'RAS, parfait.', 'pending', '2025-06-02 00:00:00'),
(3, 7, 6, 7, 1, 'Trajet annulé sans prévenir.', 'approved', '2025-06-03 00:00:00'),
(4, 2, 5, 2, 2, 'Impolitesse flagrante.', 'approved', '2025-06-04 00:00:00'),
(5, 2, 3, 2, 1, 'Trop bruyant.', 'pending', '2025-06-05 00:00:00'),
(6, 2, 4, 2, 2, 'Conduite dangereuse.', 'rejected', '2025-06-06 00:00:00'),
(7, 6, 5, 6, 4, 'Super trajet !', 'rejected', '2025-06-07 00:00:00'),
(8, 1, 3, 1, 3, 'Super trajet !', 'rejected', '2025-06-08 00:00:00'),
(9, 3, 5, 3, 1, 'Manque de ponctualité.', 'rejected', '2025-06-09 00:00:00'),
(10, 8, 2, 8, 4, 'Bonne ambiance à bord.', 'rejected', '2025-06-10 00:00:00'),
(11, 8, 5, 8, 5, 'Très bon conducteur, je recommande.', 'rejected', '2025-06-11 00:00:00'),
(12, 9, 7, 1, 4, 'Très bon conducteur, je recommande.', 'approved', '2025-06-12 00:00:00'),
(13, 3, 5, 3, 2, 'Conduite dangereuse.', 'pending', '2025-06-13 00:00:00'),
(14, 9, 4, 1, 5, 'Je referais un trajet avec lui.', 'pending', '2025-06-14 00:00:00'),
(15, 4, 3, 4, 3, 'Ponctuel et très sympa.', 'rejected', '2025-06-15 00:00:00'),
(16, 4, 2, 4, 4, 'Bonne ambiance à bord.', 'pending', '2025-06-16 00:00:00'),
(17, 1, 4, 1, 1, 'Conduite dangereuse.', 'approved', '2025-06-17 00:00:00'),
(18, 9, 3, 1, 5, 'Un peu en retard mais sympa.', 'approved', '2025-06-18 00:00:00'),
(19, 7, 5, 7, 5, 'Très bon conducteur, je recommande.', 'rejected', '2025-06-19 00:00:00'),
(20, 9, 4, 1, 1, 'Trajet annulé sans prévenir.', 'approved', '2025-06-20 00:00:00'),
(21, 5, 8, 5, 5, 'Communication facile, super covoiturage.', 'approved', '2025-06-21 00:00:00'),
(22, 9, 6, 1, 2, 'Ne respecte pas les limitations.', 'pending', '2025-06-22 00:00:00'),
(23, 5, 2, 5, 2, 'Pas de communication.', 'rejected', '2025-06-23 00:00:00'),
(24, 9, 4, 1, 5, 'Un peu en retard mais sympa.', 'pending', '2025-06-24 00:00:00'),
(25, 8, 4, 8, 2, 'Conduite dangereuse.', 'approved', '2025-06-25 00:00:00'),
(26, 2, 8, 2, 2, 'Manque de ponctualité.', 'rejected', '2025-06-26 00:00:00'),
(27, 5, 3, 5, 1, 'Trajet annulé sans prévenir.', 'pending', '2025-06-27 00:00:00'),
(28, 2, 3, 2, 1, 'Ne respecte pas les limitations.', 'rejected', '2025-06-28 00:00:00'),
(29, 5, 6, 5, 2, 'Ne respecte pas les limitations.', 'pending', '2025-06-29 00:00:00'),
(30, 7, 6, 7, 2, 'Manque de ponctualité.', 'rejected', '2025-06-30 00:00:00'),
(31, 1, 3, 1, 3, 'Un peu en retard mais sympa.', 'pending', '2025-07-01 00:00:00'),
(32, 5, 4, 5, 2, 'Voiture sale.', 'rejected', '2025-07-02 00:00:00'),
(33, 1, 5, 1, 4, 'RAS, parfait.', 'pending', '2025-07-03 00:00:00'),
(34, 1, 6, 1, 1, 'Impolitesse flagrante.', 'approved', '2025-07-04 00:00:00'),
(35, 5, 4, 5, 5, 'Trajet fluide et agréable.', 'approved', '2025-07-05 00:00:00'),
(36, 8, 3, 8, 4, 'Trajet fluide et agréable.', 'rejected', '2025-07-06 00:00:00'),
(37, 1, 5, 1, 4, 'Communication facile, super covoiturage.', 'rejected', '2025-07-07 00:00:00'),
(38, 10, 6, 2, 3, 'Ponctuel et très sympa.', 'pending', '2025-07-08 00:00:00'),
(39, 10, 5, 2, 1, 'Ne respecte pas les limitations.', 'rejected', '2025-07-09 00:00:00'),
(40, 1, 6, 1, 5, 'Communication facile, super covoiturage.', 'pending', '2025-07-10 00:00:00'),
(41, 9, 5, 1, 4, 'Conduite prudente, bonne expérience.', 'pending', '2025-07-11 00:00:00'),
(42, 8, 7, 8, 3, 'Je referais un trajet avec lui.', 'approved', '2025-07-12 00:00:00'),
(43, 2, 6, 2, 2, 'Voiture sale.', 'rejected', '2025-07-13 00:00:00'),
(44, 3, 7, 3, 4, 'Très bon conducteur, je recommande.', 'pending', '2025-07-14 00:00:00'),
(45, 4, 8, 4, 5, 'Conduite prudente, bonne expérience.', 'approved', '2025-07-15 00:00:00'),
(46, 2, 8, 2, 1, 'En retard, pas très aimable.', 'rejected', '2025-07-16 00:00:00'),
(47, 3, 4, 3, 3, 'Bonne ambiance à bord.', 'approved', '2025-07-17 00:00:00'),
(48, 1, 8, 1, 4, 'Un peu en retard mais sympa.', 'pending', '2025-07-18 00:00:00'),
(49, 8, 6, 8, 5, 'Je referais un trajet avec lui.', 'rejected', '2025-07-19 00:00:00'),
(50, 10, 8, 2, 5, 'Conduite prudente, bonne expérience.', 'approved', '2025-07-20 00:00:00');

/* crédit transaction */

INSERT INTO `credit_transaction` (id, user_id, trip_id, type, amount, description, created_at)
VALUES
(1, 7, 1, 'debit', 20, 'Paiement du trajet 1', '2025-06-26 00:00:00'),
(2, 6, 1, 'plateform_fee', 2, 'Commission plateforme', '2025-06-26 00:00:00'),
(3, 5, 1, 'credit', 18, 'Gain chauffeur sur trajet 1', '2025-06-26 00:00:00'),
(4, 6, 1, 'debit', 20, 'Paiement du trajet 1', '2025-06-26 00:00:00'),
(5, 3, 1, 'plateform_fee', 2, 'Commission plateforme', '2025-06-26 00:00:00'),
(6, 5, 1, 'credit', 18, 'Gain chauffeur sur trajet 1', '2025-06-26 00:00:00'),
(7, 4, 2, 'debit', 9, 'Paiement du trajet 2', '2025-06-27 00:00:00'),
(8, 2, 2, 'plateform_fee', 2, 'Commission plateforme', '2025-06-27 00:00:00'),
(9, 2, 2, 'credit', 7, 'Gain chauffeur sur trajet 2', '2025-06-27 00:00:00'),
(10, 5, 2, 'debit', 9, 'Paiement du trajet 2', '2025-06-27 00:00:00');


INSERT INTO `incident_report` (id, trip_id, reporter_id, description, incident_status, created_at)
VALUES
(1, 4, 3, 'Le conducteur est arrivé en retard.', 'nochecked', '2025-07-10 00:00:00'),
(2, 5, 7, 'Annulation sans prévenir.', 'nochecked', '2025-07-11 00:00:00'),
(3, 8, 4, 'Comportement inadapté durant le trajet.', 'nochecked', '2025-07-12 00:00:00'),
(4, 9, 7, 'Véhicule sale et inconfortable.', 'nochecked', '2025-07-13 00:00:00'),
(5, 6, 8, 'Non-respect du code de la route.', 'nochecked', '2025-07-14 00:00:00'),
(6, 4, 4, 'Mauvais itinéraire pris volontairement.', 'nochecked', '2025-07-15 00:00:00'),
(7, 7, 5, 'Absence de communication avant le trajet.', 'nochecked', '2025-07-16 00:00:00'),
(8, 5, 5, 'Refus d''un arrêt demandé.', 'nochecked', '2025-07-17 00:00:00'),
(9, 6, 4, 'Problème de ponctualité récurrent.', 'nochecked', '2025-07-18 00:00:00'),
(10, 5, 3, 'Conduite agressive constatée.', 'nochecked', '2025-07-19 00:00:00');

