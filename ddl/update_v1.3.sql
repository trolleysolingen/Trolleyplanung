--
-- Tabellenstruktur für Tabelle `todotypes`
--

CREATE TABLE IF NOT EXISTS `todotypes` (
`id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `todotypes`
--

INSERT INTO `todotypes` (`id`, `name`) VALUES
(1, 'Fehler'),
(2, 'Funktion');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `todotypes`
--
ALTER TABLE `todotypes`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `todotypes`
--
ALTER TABLE `todotypes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;

--
-- Tabellenstruktur für Tabelle `todos`
--

CREATE TABLE IF NOT EXISTS `todos` (
`id` int(11) NOT NULL,
  `todotype_id` int(11) DEFAULT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `worker_id` int(11) DEFAULT NULL,
  `shortdesc` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `creationdate` date NOT NULL,
  `startdate` date DEFAULT NULL,
  `finishdate` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `todos`
--

INSERT INTO `todos` (`id`, `todotype_id`, `reporter_id`, `worker_id`, `shortdesc`, `description`, `creationdate`, `startdate`, `finishdate`) VALUES
(1, 2, 3, NULL, 'Eigenes Profil', 'Jeder VerkÃ¼ndiger bekommt ein eigenes Profil. Dort kann er Handynummer und Email Adresse Ã¤ndern. Falls gefordert kann auch noch das Passwort geÃ¤ndert werden. Dies wÃ¼rde dann in einem separaten Todo erledigt werden, da dies deutlich mehr Aufwand ist.', '2015-01-06', NULL, NULL),
(2, 2, 3, NULL, 'Hilfeseite', 'Es soll eine Hilfeseite eingerichtet werden. Dort wird es eine Hilfe fÃ¼r VerkÃ¼ndiger geben (alles was bisher in den blauen KÃ¤stchen ist) und zusÃ¤tzlich fÃ¼r Admins eine Hilfe fÃ¼r alle Einstellungen und Seiten, die sie bedienen kÃ¶nnen. ', '2015-01-06', NULL, NULL),
(3, 2, 3, NULL, 'Eigene Schichten', 'Alle eigene Schichten, in die man gebucht ist, sollen zentral angezeigt werden.', '2015-01-06', NULL, NULL),
(4, 2, 3, NULL, 'Freie Schichten Mail', 'VERSAMMLUNGSINDIVIDUELL\r\n\r\nJede Woche Samstags wird eine Mail mit den freien Schichten der nÃ¤chsten Woche an alle VerkÃ¼ndiger versendet. So kÃ¶nnen sich die VerkÃ¼ndiger noch gezielt fÃ¼r den Trolley verabreden und weniger Schichten bleiben leer.', '2015-01-06', NULL, NULL),
(5, 2, 3, NULL, 'Bericht', 'VERSAMMLUNGSINDIVIDUELL\r\n\r\nDer Bericht Ã¼ber die Abgabe am Trolley wird online erfolgen. So bekommen die VerkÃ¼ndiger, nachdem sie eine Schicht absolviert haben eine Mail mit einem Link, oder beim nÃ¤chsten Login kommen sie auf eine Maske, in der sie eingeben was sie abgegeben und erreicht haben. ', '2015-01-06', NULL, NULL),
(6, 2, 3, NULL, 'Dashboard', 'Nach dem Einloggen bekommt der User eine neue Ansicht zu sehen, bevor er zur Schichttabelle gehen kann. Auf diesem Dashboard sieht er verschiedenste Informationen, die ihm nÃ¼tzlich sein kÃ¶nnten. Diese werden spÃ¤ter noch definiert.', '2015-01-06', NULL, NULL),
(7, 2, 3, NULL, 'VerkÃ¼ndiger pro Schicht', 'VERSAMMLUNGSINDIVIDUELL\r\n\r\nEs lÃ¤sst sich die Zahl der VerkÃ¼ndiger definieren, die sich fÃ¼r eine Schicht eintragen kÃ¶nnen. Dies kann z.B. bei mehr als 2 Trolleys Sinn machen oder wenn man mehreren die MÃ¶glichkeit geben mÃ¶chte sich einzutragen.', '2015-01-06', NULL, NULL),
(8, 2, 3, NULL, 'Erster Admin', 'Bei der Anlage der Versammlung auch der erste Versammlungsadmin angelegt werden kÃ¶nnen.', '2015-01-06', NULL, NULL),
(9, 2, 3, NULL, 'Kalender Export', 'Es kann ein personalisierter Kalender auf dem Handy abonniert werden, der einem alle seine Schichten im Kalender anzeigt.', '2015-01-06', NULL, NULL),
(10, 2, 3, NULL, 'Einrichtungsassistent', 'Wenn eine neue Versammlung angelegt wurde und der Admin sich das erste Mal einloggt, wird er durch einen Einrichtungsassistenten geleitet, der ihm alle Funktionen vorstellt und erklÃ¤rt, bevor er loslegen kann.', '2015-01-06', NULL, NULL),
(11, 2, 3, NULL, 'Mulitlanguage', 'Eine mehrsprachige Version der Trolleyverwaltung einplanen und realisieren.', '2015-01-06', NULL, NULL),
(12, 2, 105, 2, 'Keine GÃ¤ste', 'VERSAMMLUNGSINDIVIDUELL\r\n\r\nMan kann einstellen ob man GÃ¤ste zulassen will. Falls nicht, kÃ¶nnen bei der Partnerwahl NUR Partner ausgewÃ¤hlt werden, die auch in der Trolleyverwaltung eingetragen wurden.', '2015-01-06', NULL, NULL),
(13, 2, 82, NULL, 'Benachrichtigung fÃ¼r Partner', 'VERSAMMLUNGSINDIVIDUELL\r\n\r\nDer Partner soll eine Mail bekommen, wenn er als Partner eingetragen oder gelÃ¶scht wurde.', '2015-01-06', NULL, NULL),
(14, 2, 82, NULL, 'Admin Schichtverwaltung', 'Der Admin soll die MÃ¶glichkeit haben VerkÃ¼ndiger aus Schichten herauszulÃ¶schen.', '2015-01-06', NULL, NULL),
(15, 2, 82, NULL, 'Versammlungsangabe', 'Zu jedem VerkÃ¼ndiger sollte es die MÃ¶glichkeit geben eine spezielle Versammlung anzugeben. Diese sind dann einer Congregation Group zugeordnet.', '2015-01-06', NULL, NULL),
(16, 2, 82, 3, 'SchlÃ¼sselverwaltung', 'VERSAMMLUNGSINDIVIDUELL\r\n\r\nZu jedem VerkÃ¼ndiger soll angegeben werden kÃ¶nnen ob er einen SaalschlÃ¼ssel besitzt. Diese Angabe wird dann in der Schichtliste angezeigt.', '2015-01-06', NULL, NULL),
(17, 2, 82, NULL, 'Schichtdruckansicht', 'Es soll eine Druckansicht geben auf der man alle Schichten sieht und auch welche VerkÃ¼ndiger mit welchen Handynummern dort eingetragen worden sind.', '2015-01-06', NULL, NULL),
(18, 2, 3, NULL, 'Killswitch', 'Entweder einzelne Versammlungen oder die gesamte Trolleyverwaltung sollte deaktiviert werden, sodass sich kein User mehr einloggen kann. (Falls Fehler behoben werden mÃ¼ssen)', '2015-01-06', NULL, NULL),
(19, 2, 3, NULL, 'Einzelne Nachrichten', 'Nachrichten sollten auch an einzelne VerkÃ¼ndiger verschickt werden kÃ¶nnen.', '2015-01-06', NULL, NULL),
(20, 2, 3, NULL, 'Statistiken', 'Es sollen verschiedene Statistiken aufgestellt und ausgewertet werden kÃ¶nnen.', '2015-01-06', NULL, NULL),
(21, 1, 3, NULL, 'Safari Password speichern', 'Auf dem iPhone und iPad im Safari muss man immer wieder die Logindaten eingeben.', '2015-01-06', NULL, NULL),
(22, 1, 3, 3, 'iPhone Autocomplete', 'Auf meinem iPhone kann ich weder im Safari, noch im Chrome beim tippen eines Partners die Liste der VerkÃ¼ndiger sehen.', '2015-01-08', NULL, NULL);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `todos`
--
ALTER TABLE `todos`
 ADD PRIMARY KEY (`id`), ADD KEY `fk_todos_todotype_idx` (`todotype_id`), ADD KEY `fk_todos_reporter_idx` (`reporter_id`), ADD KEY `fk_todos_worker_idx` (`worker_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `todos`
--
ALTER TABLE `todos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `todos`
--
ALTER TABLE `todos`
ADD CONSTRAINT `fk_todos_reporters` FOREIGN KEY (`reporter_id`) REFERENCES `publishers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_todos_todotypes` FOREIGN KEY (`todotype_id`) REFERENCES `todotypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_todos_workers` FOREIGN KEY (`worker_id`) REFERENCES `publishers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
