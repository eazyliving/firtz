# firtz


*firtz podcast publisher*  
*Version 2.1*

## Über

Du hast offensichtlich den Drang, entweder diesen Kram hier auszuprobieren, oder einen Podcast aus der Taufe zu heben.
Was auch immer Dich dazu bewogen hat, firtz auszuprobieren: Gute Idee :)

Um einen Podcast zu veröffentlichen, reichen im Grunde zwei Dinge: Audiodateien im Web und irgendwo eine XML-Datei, die den Feed beschreibt. Alles andere ist Beiwerk, das mehr oder minder nötig und arbeitsintensiv ist.

Warum aber an vielen Stellen so viel Arbeit machen, so viel Ballast herumschleppen? Wieso ein Wordpress, wenn man alle zwei Monate mal was veröffentlicht? Wieso eine SQL-Datenbank für fünf Artikel in einem Jahr? Wieso sich den Kopf zerbrechen über die Installation diverser Plugins, wenn es ein paar KByte XML tun?

Es gibt aber noch einen zweiten Aspekt. Während ich täglich in der [Hörsuppe](http://hoersuppe.de) über deutschsprachige Podcasts blogge und podcaste, sehe ich mich immer wieder mit Podcasts konfrontiert, die viel Mühe und Arbeit in das gesprochene Wort investieren, dann aber an den technischen Hürden der Veröffentlichung, dem Publishing scheitern.

Das resultiert in kaputten Feeds, Feeds, in denen wichtige Metadaten über die Audiodateien hinaus fehlen und es dem Hörer zwar nicht unmöglich, aber unbequem machen, den Feed zu verfolgen.

Darüber hinaus erleichtert es mir mit der Hörsuppe die Arbeit enorm, wenn die Metadaten stimmen :)

Nun ist Reden Silber, Handeln hingegen Gold. Der firtz soll - wo möglich - dabei helfen, Podcasts den Druck zu nehmen, sich um technische Details zu kümmern.

Deshalb also firtz. Die 0.1 war das Ergebnis von knapp einskommafünf Tagen "Arbeit", die ich eigentlich nur gemacht habe, um mal wieder irgendwas zusammen zu schrauben, das halbwegs funktioniert.

Wie funktioniert das aber nun? Über Konfigurationsdateien. Eine für den Feed mit allgemeinen Daten und einer Datei für jede Episode mit den Details eben jener.

Wer es ganz einfach haben möchte, verbündet sich mit auphonic und überlässt alle Daten, die dort anfallen dem firtz, der dann daraus einen oder mehrere Feeds erzeugt. Einfacher wird's nur noch, wenn Auphonic selbst sowas anbietet ;)


## Voraussetzungen

Aber fangen wir vorne an. Was brauchst Du?

Du brauchst irgendwo Platz im Web. Das ist dann doch noch nötig. Dieser Platz im Web muss im Grunde nur eine Bedingung erfüllen: **php ab Version 5.4.0** muss ausgeführt werden können.

Datenbanken und anderen Kram braucht es nicht, allerdings muss der ausführende Nutzer des Webservers in der Lage, Verzeichnisse zu erzeugen und Dateien in diese hineinzuschreiben.

Sollte der firtz in einem Unterverzeichnis der genutzten Domain betrieben werden, musst Du noch die .htaccess korrigieren und die
RewriteBase von

`RewriteBase /`

auf

`RewriteBase /UNTERORDNER`

ändern.

Es gibt übrigens hier und dort Probleme mit dem Schreiben in das temporäre Verzeichnis. Je nach Webhoster kann es nötig und hilfreich sein, den Handler für php auf (fast-)cgi zu stellen. In der .htaccess geht das z.B. mit dieser Zeile:  

`AddHandler php5-cgi .php`

Das sollte allerdings wirklich nur passieren, wenn der firtz diesbezüglich Fehlermeldungen auswirft.

## Los geht's

Du besorgst Dir das [firtz-Archiv](https://github.com/eazyliving/firtz/), entpackst es in den Ordner, in dem Du das auf dem Webserver haben willst und bewegst Dich darauf hin in den Unterordner `feeds/`.

In diesem findest Du ein Demo-Verzeichnis. Der Name dieses Ordners, ist der zukünftige Name Deines Feeds.

Im Demo-Ordner sind zwei Dateien: `feed.cfg` und `001.epi`. Das nicht syntaktisch zu nennende Prinzip dieser Dateien ist identisch:

`#: ist ein Kommentar nicht den ":" vergessen!`

Attribute werden wie folgt notiert:

`attribute:`

`value`

Achtet bitte darauf, dass die Attribut-Zeile alleine steht. Leerzeilen werden ignoriert, es sei denn, es geht um eine Textfeld wie summary.

An's Ende der Konfiguration kannst Du in einer neuen Zeile ein

`---end---`

setzen, alles dahinter wird ignoriert. Du kannst da Notizen oder Müll unterbringen ;) Ich werde das hier im Moment noch nicht weiter ausbreiten. Schau in die Dateien und spiel damit rum.

**Wichtig ist, dass alle Konfigurationsdateien UTF8 sind, denn sonst gibt's Müsli im Feed!**

Solltest Du [Auphonic](http://auphonic.com/) nutzen, bist Du ganz fein raus. Aktiviere in den Ausgangsdateien dort die Production-Description, sorge dafür, dass firtz sie im Dateisystem findet und schon erstellen sich die Folgen fast von selbst anhand der von Auphonic weitergereichten Metadaten. Weitere Informationen dazu finden sich in der weiterführenden Dokumentation.

Nehmen wir an, Du hast das soweit fertig. Wie erreichst du den Feed? Angenommen der URL zu Deinem Webserver wäre `http://tollerneuerpodcast.de/`. Der Feed heißt ja noch demo, also wäre der URL zum RSS2-Feed:

`http://tollerneuerpodcast.de/demo`

Wenn Du mehrere Audioformate nutzt, kannst Du auch z.B.
`http://tollerneuerpodcast.de/demo/mp3` nutzen.

Eine Webseite gibt's als Bonus. Die kannst Du für diesen Feed so erreichen: `http://tollerneuerpodcast.de/demo/show`

Für jede Episode gibt's dann einen eigenen [Podlove Webplayer](https://github.com/gerritvanaaken/podlove-web-player) und [flattr](http://flattr.com)-Buttons.

Einzelne Episoden sind auch verlinkbar, indem an den Pfad der Slug der Episode (praktisch der Dateiname ohne Endung) angehangen wird: `http://tollerneuerpodcst.de/demo/show/001`.

## Kontakt

- [@firtz](https://twitter.com/the_firtz) (project)
- [@eazy](https://twitter.com/eazyliving)
- [@mccouman](https://twitter.com/mccouman)


## Links

- [fatfree framework](https://github.com/bcosca/fatfree)
- [QuorX II](https://github.com/Firtz-Designs/QuorX-II)
- [Podlove Webplayer](https://github.com/gerritvanaaken/podlove-web-player)
- [Ein guter Artikel von Hirnbloggade zur firtz Installation](http://www.hirnbloggade.de/2013/04/26/der-firtz-und-ich/)
