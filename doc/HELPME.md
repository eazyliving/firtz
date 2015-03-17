#firtz - eine Entscheidungshilfe

Du möchtest einen Podcast veröffentlichen? Dann bist Du hier schon einmal nicht falsch.

Wer sich der Aufgabe gegenübersieht, einen Podcast unter die Menschen zu bringen, steht - noch bevor nur das erste gesprochene Wort über die Leitung gegangen ist - vor der Frage, wie man diesen Menschen den Podcast präsentieren möchte.

Möglichkeiten gibt es viele, und eine eigene Webseite mit allen nötigen Informationen rund um den Podcast und natürlich mit allen Episoden ist nur eine davon. Genauso gut kann man Dienstleister wie Soundcloud in Anspruch nehmen und dort vordefinierte Baukästen und Content-Management-Systeme nutzen.

Viele Podcasts setzen auf eine selbst gehostete Seite. Der Grund dafür ist aber nicht zwingend, dass so alle Kontrolle beim Veröffentlichen in der Hand der Podcastenden bleibt. Ich glaube schlicht, dass viele Podcasts von Menschen gemacht werden, die sowieso den Hang dazu haben, alles selbst zu machen.

Wie auch immer, hier und heute soll es um das Eigenhosting gehen. Dafür stellt sich zunächst die Frage, was man denn so benötigt. 

Die Frage wird oft schnell beantwortet: Eine Webseite. Was zwar richtig ist, aber nicht alles. Denn nicht vergessen darf man den Feed, in dem die aktuellen Folgen der Hörerschaft, die über neue Folgen informiert werden möchte bekannt macht.

Der Feed, nichts anderes als eine XML-Datei, bringt alle nötigen Informationen mit, die sich rund um den Podcast und die einzelnen Episoden ansammeln. Es ist nicht sofort ersichtlich, aber im Feed stehen oft mehr Informationen als auf der Webseite selbst. 

Nun kann man auf vielen Wegen zu Webseite und Feed kommen: Vielleicht existiert bereits eine Seite, in die nur die Episoden per Hand (also im HTML) eingefügt werden, der Feed wird per Hand geschrieben. Das ist grausam und man möchte es seinem ärgsten Feind nicht wünschen.

Oder man nimmt eines der bekannten CMS und hofft, dass es die Veröffentlichung von Audiodaten unterstützt. Gerne genommen wird, weil man hat ja Erfahrung damit, Wordpress. Für WP gibt einen kleinen Wald an Plugins, die das Veröffentlichen von Podcasts ermöglichen und mit dem [Podlove Publisher](http://podlove.org) gibt es seit ein paar Jahren ein aktiv entwickeltes Plugin, das praktisch keine Wünsche offen lässt.

Wer ohnehin Wordpress im Einsatz hat und dieses auch für den Podcast nutzen möchte, der kann jetzt einpacken und rüber zu den [Kolleginnen und Kollegen vom PP](http://podlove.org) gehen. Viel Vergnügen, Ihr werdet es nicht bereuen, da bin ich sicher.

Immer noch hier? Keine Lust auf Wordpress? Sicherheitslücken, Datenbanken, Wartungsstress?

Ok, vielleicht kommen wir ins Geschäft. Ich hab da nämlich den firtz. Der firtz ist ein kleiner, flotter Podcast Publisher, der ohne CMS, ohne Datenbank, ohne Authentifizierung und all den sonst üblichen Kram daher kommt. Der firtz ernährt sich ausschließlich von einer zentralen Konfigurationsdatei (Klartext) und ebensolchen Konfigurationsdateien für jede Episode. 

Aus diesen Dateien erzeugt der firtz automatisch eine Webseite und natürlich Feeds. Um der Wahrheit die Ehre zu geben, stand ursprünglich der Feed im Vordergrund, die Webseite war ein automatisch erzeugtes Bonbon, auf das ich wenig Wert legte. Das hat sich geändert, glücklicherweise ;)

Was brauchts Du für den firtz? Platz in diesem Internet (z.B. bei [uberspace](http://uberspace.de)), php und den firtz selbst. Fertig. Aus. Ende.

Alle sonstigen Dateien, die der firtz zum Erstellen von Seite und Feed benötigt, werden mitgeliefert und sind im Standard gut brauchbar.

Wer jetzt noch auphonic nutzt und ein klein wenig Ahnung hat (kennst Du den Unterschied zwischen einem Pfad im Dateisystem und einer URL? Glückwunsch!), für den habe ich eine noch bessere Nachricht: Ab sofort kannst Du automatisch aus auphonic heraus veröffentlichen. Wirf Deine Daten auf auphonic, lass die für Dich arbeiten und kurz danach erscheint, wenn alles korrekt konfiguriert ist, auf Deiner Podcastseite.

So viel zur Entscheidungshilfe. Wenn Du ein klein wenig experimentierfreudig bist, mit der Konsole halbwegs Frieden geschlossen hast oder zumindest einen grafischen (S)FTP-Client bedienen kannst, dann willkommen: hier bist Du richtig!