<?php

$page_lang = 'de';

// insert these pages only for default language
if ($config['language'] == $page_lang)
{
	if ($config['is_update'] == false)
	{
		insert_page($config['root_page'], '', "file:wacko_logo.png\n**Willkommen zu Deiner ((WackoWiki:Doc/Deutsch/WackoWiki WackoWiki)) Installation!**\n\nKlicke nach der ((Anmeldung)) unten auf den Punkt \"Editieren\" um zu beginnen.\n\nDie Dokumentation ist unter WackoWiki:Doc/Deutsch zu finden.\n\nN�tzliche Seiten: ((WackoWiki:Doc/Deutsch/Formatierung Formatierung)), ((Suche)).\n\n", $page_lang, 'Admins', true, false, null, 0);
		insert_page($config['users_page'].'/'.$config['admin_name'], $config['admin_name'], "::@::\n\n", $page_lang, $config['admin_name'], true, false, null, 0);
	}
	else
	{
		// ...
	}

	insert_page('Category', 'Kategorie', '{{category}}', $page_lang, 'Admins', false, false);
	insert_page('Permalink', 'Permalink', '{{permalinkproxy}}', $page_lang, 'Admins', false, false);
	insert_page('Groups', 'Gruppen', '{{groups}}', $page_lang, 'Admins', false, false);
	insert_page('Users', 'Benutzer', '{{users}}', $page_lang, 'Admins', false, false);
}

insert_page('LetzteAenderungen', 'Letzte �nderungen', '{{changes}}', $page_lang, 'Admins', false, true, '�nderungen');
insert_page('LetzteKommentare', 'Letzte Kommentare', '{{commented}}', $page_lang, 'Admins', false, true, 'Kommentare');
insert_page('SeitenIndex', 'Seiten Index', '{{pageindex}}', $page_lang, 'Admins', false, true, 'Index');

insert_page('Registrierung', 'Registrierung', '{{registration}}', $page_lang, 'Admins', false, false);

insert_page('Passwort', 'Passwort', '{{changepassword}}', $page_lang, 'Admins', false, false);
insert_page('Suche', 'Suche', '{{search}}\n\n\n  * ++4 Buchstaben Minimum f�r die Suche im Text der Seiten. Das ist eine ~MySQL Beschr�nkung.++\n  * ++3 Buchstaben Minimum f�r die Suche in Seitennamen.++\n  * ++Bei der Suche im Text wird die Volltextsuche Funktion von ~MySQL verwendet. Es wird nur nach ganzen Worten gesucht.++\n\n', $page_lang, 'Admins', false, false);
insert_page('Anmeldung', 'Anmeldung', '{{login}}', $page_lang, 'Admins', false, false);
insert_page('Einstellungen', 'Einstellungen', '{{usersettings}}', $page_lang, 'Admins', false, false);

?>