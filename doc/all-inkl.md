# Konfigurationshilfe für Kunden von all-inkl.com

* [PHP 5.3](#php-5.3)
* [Pfade](#pfade)

## PHP 5.3

Der Firtz braucht PHP 5.3. All-inkl unterstützt das grundsätzlich. Je nachdem, wann Dein Webspace eingerichtet wurde, kann es aber sein, dass er für eine niederigere Version konfiguriert ist.
Falls das bei Dir so ist, füge in Deine `.htaccess` folgendes ein:

`AddHandler php53-cgi .php`

## Pfade

Der Firtz braucht an manchen Stellen absolute Pfade des Dateisystems, in dem Dein Webspace liegt. Hierzu musst Du dem Wurzelverzeichnis Deines Webspaces folgendes voranstellen:

`/www/htdocs/<Dein_Benutzername>/`

**Beispiel:**
Dein Benutzername ist w00da42, der Ordner auf den Du verweisen willst liegt direkt in Deinem Hauptverzeichnis und heißt `files`.

Dann wäre der Pfad: `/www/htdocs/w00da42/files`

