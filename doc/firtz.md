# firtz podcast publisher

* [Einleitung](#Einleitung)
* [Was braucht es?](#was-braucht-es)
* [der Feed](#der-feed)
* [die Episode](#die-episode)
* [die Webseite](#die-webseite)
* [Auphonic](#auphonic)
* [firtz erweitern](#firtz-erweitern)
 * [Templates](#templates)
 * [Extensions](#extensions)

## Einleitung

Firtz ist eine einfache, relativ schnell zu nutzende Podcastpublishinglösung, die ohne viele Vorarbeiten einen fertigen, gültigen Feed erzeugt und darüber hinaus eine auf das nötigste reduzierte Webseite erstellt.

Wer möchte - und kann - ist aber auch in der Lage, alle Aspekte der Feed- und Seitengenerierung selbst zu steuern und zu erweitern.

Kurz und gut: Man kann, aber muss nicht alles damit machen. Wofür es allerdings wirklich nicht taugt: Bloggen. Wer neben dem Podcast ausgiebig bloggt, sollte bei Wordpress bleiben.

Firtz ist für Podcaster gedacht, die alle paar Wochen etwas veröffentlichen und darüber hinaus wenig Ansprüche an das ganze Drumherum stellen.

Darüber hinaus ist es sehr hilfreich, wenn nicht sogar der einfachste Weg, mit [Auphonic](http://auphonic.com) zu arbeiten. Wenn der Workflow mit Auphonic einmal aufgestellt ist und funktioniert, muss die Webseite/der Feed nie wieder angefasst werden und erstellt sich praktisch von selbst.

## Was braucht es?

Wie alle Software, die im Open-Source-Dampfgarer produziert wird, benötigt firtz einige Dinge, in die andere Menschen viel Arbeit und Zeit gesteckt haben. Firtz bringt die meisten dieser Pakete bereits mit. Dazu zählen:

* [fatfree framework](https://github.com/bcosca/fatfree)
* [bootstrap](http://twitter.github.com/bootstrap/)
* [podlove webplayer](https://github.com/gerritvanaaken/podlove-web-player)

Diese Projekte integrieren wiederum zahlreiche Module aus anderen Projekten, die ich aber nicht weiter aufzählen möchte.

Was aber musst Du mitbringen, damit das alles klappt?

**Platz in diesem Internet**

Das dürfte für die meisten noch die größte Hürde darstellen. Es ist inzwischen zwar möglich, für sehr wenig Geld geeigneten "Webspace" zu bekommen, dennoch ist es natürlich viel bequemer, seine Daten in einen vorhandenen Dienst zu schieben, der dann den Rest übernimmt. Für solche Fälle gibt es z.B. die [Soundcloud](http://soundcloud.com), aus der auch ein korrekter Feed herausfällt. Viel mehr dann aber auch nicht.  

Ich persönlich mache sowas sehr gerne bei [Uberspace](http://uberspace.de). Da hat man seinen Zugang in ein paar Minuten geklickt, hat den besten Support den man (auch für viel Geld) bekommen kann und selbst dem DAU erschließt sich das verhältnismäßig schnell. Aber jeder, wo er mag.

Nehmen wir also mal an, Du hast irgendwo Platz in diesem Internetz und weißt auch, wie dieser Platz heißt. Einigen wir uns auf die Domain **supicast.de** (mal sehen, wie lange die noch unregistriert ist...).

Natürlich musst Du Zugang zu diesem Platz haben und die Dateien vom firtz dorthin kopieren können. Darüber hinaus muss der ausführende Webserver ausreichend Rechte haben, um Verzeichnisse und Dateien erzeugen zu können. Das sollte aber eigentlich überall so sein (man beachte "eigentlich" und "überall", ich habe schon Pferde vor der Apotheke kotzen sehen...).

**Den Firtz**

Du besorgst Dir nun das [Zip-Archiv vom firtz](https://github.com/eazyliving/firtz) und entpackst es an die Stelle, an der Dein Webserver das verdauen kann.

Für Profis:
> git clone https://github.com/eazyliving/firtz.git .

Da liegen nun ein Haufen Dateien und Verzeichnisse herum. Wirklich von Interesse aber sind folgende Verzeichnisse und die Dateien und Unterverzeichnisse, die sich darin befinden:

feeds/  
templates/
	
Im Ordner *feeds* befinden sich die Ordner, die dem System sagen, welche Feeds angeboten werden sollen. Der Name dieses Ordners wird an vielen Stellen als Schlüssel (slug) wieder verwendet, wähle ihn also weise. Dazu später allerdings noch mehr.

In den Feed-Ordnern wiederum werden sich die Konfigurationsdatei und die Episoden-Dateien finden, aus denen der Feed generiert wird.

Im Ordner *templates* wiederum finden sich alle Dateien, die dazu beitragen, die generierten Feeds in irgendeiner Weise darzustellen. Ob nun RSS oder Webseite, die Ausgabe wird - größtenteils - hier festgelegt. Hier **muss** nichts getan werden, wer mag, kann die Grundeinstellungen übernehmen. Mit der Zeit wirst Du aber vermutlich an der einen oder anderen Stelle, vermutlich an der Webseite, drehen wollen.

**Die Audiodateien**

Das ist wiederum ein Kapitel, bei dem ich Dir nur schlecht helfen kann. Ich will nicht leugnen, dass das nicht eben der einfachste Part ist. Firtz hilft Dir beim Verteilen der Informationen Deines Podcasts, nicht beim Erstellen und Weiterverarbeiten der eigentlichen Audiodatei.

Es gibt aber jemanden, der dabei sehr behilflich ist: [Auphonic](http://auphonic.com). Dort gibt es nicht nur Hilfe beim Verarbeiten der Audiodatei. Auphonic übernimmt auch das Übertragen der Audiodateien an den Ort, von dem sie das Publikum später herunterladen wird und zu allem Überfluss gibt es auch eine Möglichkeit, mit Auphonic den firtz zu füttern, der dann den Rest der Arbeit übernimmt, ohne dass irgendeine Episode angelegt werden muss.

**Ich empfehle Anfängern, die sich noch im Unklaren sind, wie die nötigen Arbeitsschritte vor der Veröffentlichung aussehen, dringend den Artikel [How to Podcast for Free](https://auphonic.com/blog/2013/02/07/how-to-podcast-for-free/) durchzulesen. Wem der erste grobe Durchblick fehlt, diesen Artikel aber nicht gelesen hat, der braucht auch hier erstmal nicht weiter zu lesen.**
  
## Der Feed

Das Herzstück eines jeden Podcasts ist der Feed. Webseite: schön zu haben. iTunes: wichtig für die PR. Aber ohne Feed ist alles nichts. Ohne validen Feed ist alles blöd und da 99,9% aller Podcasthörer ihre Sendungen mit Hilfe eines Podcatchers konsumieren, ist hier für größte Sorgfalt und Stabilität zu sorgen. Also fangen wir auch genau hier an.

Wieder ein paar beispielhafte Grundannahmen. Deine Domain lautet **supicast.de**, der Podcast heißt: **supicast**.

Im Ordner *feeds* findest Du einen Ordner *demo*. Benenne den - wenn Du das schon tun möchtest - nach *supicast* um. Der Name sollte weise gewählt werden, denn er stellt ein immer wieder genutztes und in vielen URLs vorkommendes Schlüsselwort (slug) dar. Es sollte nach Veröffentlichen des Podcasts nur geändert werden, wenn es gar nicht anders mehr geht. Zum Testen kannst Du das natürlich nennen wie Du möchtest, achte aber darauf, Umlaute, Leer- und Sonderzeichen zu vermeiden. Wie gesagt: Der Name kommt immer wieder in URLs vor!

In diesem Feed-Ordner *supicast* befindet sich eine vorgefertigte Datei namens *feed.cfg*. Dies ist die einzige und zentrale Konfigurationsdatei deines Podcasts. Hier finden sich alle allgemeingültigen Informationen, die für den gesamten Podcast oder aber für jede Episode gilt.

Die Datei muss zwingend *feed.cfg* heißen, andere Dateien werden nicht erkannt. Jede Zeile enthält entweder einen Kommentar, eine Attributanweisung oder Attributinhalt.

1. Kommentarzeilen fangen mit #: an. Diese Zeilen werden ignoriert.  
2. Attributzeilen fangen mit einem Attributnamen an und enden mit einem ':' ohne Leerzeichen zwischen Attribut und Doppelpunkt.  
3. Attributinhalte folgen einer Attributanweisung und werden immer der letzten Anweisung zugeordnet.

Klingt schlimm? Ist es nicht. Hier ein Beispiel:

> \#: Dies ist eine Feedkonfiguration

>title:  
SupiCast

>description:  
Hier ein kurze Zusammenfassung dessen, was der Hörer auf die Ohren bekommen wird.

>summary:  
Es war einmal, vor langer langer Zeit, da begab es sich, dass jemand der Meinung war eine längere Beschreibung eines Podcasts zusammenschreiben zu müssen.  
  
>Das ging schief  

>\#: das erste angegebene Audioformat ist der ausgelieferte Standard

>formats:  
m4a mp3

>author:  
Jon Doe

>email:  
jondoe@supicast.de



So könnte eine minimale Feedkonfiguration aussehen. Viele weitere Optionen sind möglich und auch empfehlenswert. Hier eine Liste:

**title:**  
Der Name des Podcasts

**description:**  
Eine kurze und aussagekräftige Zusammenfassung, Subheadline.

**formats:**  
Die im Feed zu veröffentlichenden Formate. Das erste Format der durch Leerzeichen getrennten Liste ist das Hauptformat des Feeds, alle anderen werden als alternate Feeds angeboten und sind nur durch explizite Angabe des Formates in der Feed-URL erreichbar. Beispiel:

`m4a mp3 ogg opus`

Folgende Formate werden aktuell komplett unterstützt, also im Feed ein korrekter mime-type angegeben, sonst application/octet:

* mp3: audio/mpeg
* torrent: application/x-bittorrent
* mpg: video/mpeg
* m4a: audio/mp4
* m4v: video/mp4
* oga: audio/ogg
* ogg: audio/ogg
* ogv: video/ogg
* webm: audio/webm
* flac: audio/flac
* opus: audio/ogg;codecs=opus
* mka: audio/x-matroska
* mkv: video/x-matroska
* pdf: application/pdf
* epub: application/epub+zip
* png: image/png
* jpg: image/jpeg
 
**flattrid:**  
Wenn flattr genutzt wird/werden soll, hier die user-id angeben. Es werden Payment-Links im Feed und Buttons auf der Webseite für den gesamten Feed und für einzelne Episoden erzeugt. Es ist nicht möglich, mehrere IDs anzugeben.
 
**author:**  
Der Name des Autors dieses Podcasts
  
**summary:**  
Eine ausführlichere Zusammenfassung der Inhalte des Podcasts. Dieser kann sich über mehrere Zeilen erstrecken und Zeilenumbrüche beinhalten.
 
**image:**  
URL zu einem Bild, das im optimalen Fall 1400x1400 Pixel groß ist und sowohl im Feed als 
auch auf der Webseite genutzt wird.
 
**keywords:**  
Kommagetrennte Stichwörter, die den Podcast beschreiben
 
**category:**  
Die Kategorien orientieren sich an den Vorgaben Apples für iTunes. Es gibt Hauptkategorien und jeweils mehrere Unterkategorien, die aber keine weiteren Kategorien unter sich haben. Um dies an dieser Stelle einigermaßen einfach konfigurieren zu können, wird entweder eine Hauptkategorie einzeln angegeben oder eine solche mit Unterkategorie durch -> verbunden:

`Society & Culture  
Technology -> Podcasting`

Diese Konfiguration würde den Podcast sowohl in die Hauptkategorie *Society & Culture* ohne weitere Unterkategorie setzen und in die Unterkategorie *Podcasting* in der Kategorie *Technology*. Die einzelne Angabe der Hauptkategorie *Technology* ohne *Podcasting* ist in diesem Falle nicht nötig. 
Details zu den Kategorien finden sich unter http://www.apple.com/itunes/podcasts/specs.html#categories

Es ist nicht nötig (und erlaubt!) das "&" als "&amp;" zu kodieren.
  
**email:**  
Emailadresse, unter der der Autor des Podcasts erreichbar ist.
 
**language:**  
Sprache, in der der Podcast veröffentlicht, Deutsch z.B. wäre "de-DE". Info zu den Sprachcodes finden sich hier: http://www.rssboard.org/rss-language-codes
  
**itunes-block:** Blockt den iTunes-Store. Will man vermutlich nicht ;)

**explicit:**  
entweder yes oder no, wirkt sich vor allem auf iTunes aus.
 
**itunes:** Ist der Podcast bereits bei iTunes erreichbar, steht hier der komplette Link zur iTunes-Seite. (firtz: [https://itunes.apple.com/de/podcast/firtz/id604449399](https://itunes.apple.com/de/podcast/firtz/id604449399))
  
**disqus:**  
Disqus stellt externe Kommentarfunktionen zur Verfügung, ohne dass man sich um Datenbanken und Nutzerverwaltung kümmern muss. Hier den Forenname eintragen, damit auf den Webseiten des Feeds Kommentarfunktionen freigeschaltet werden. Nähere Informationen dazu finden sich unter [http://disqus.com/for-websites/](http://disqus.com/for-websites/) und [https://disqus.com/admin/signup/](https://disqus.com/admin/signup/)
 

**Details zu Auphonic und firtz**  

Seit 0.4 gibt es Auphonic Support. Dazu muss bei den outgoing services im Preset einer Produktion lediglich die Ausgabedatei "Production Description" hinzugefügt werden. Bitte das Format "json" einstellen.

In dieser Datei stehen alle nötigen Metadaten einer Folge, die dazu genutzt werden können, eine Episode aufzubauen. Aktuell ist nur die lokale Auphonic-Unterstützung implementiert. Das heißt, dass die Description-Datei auf dem Filesystem des Webservers liegen muss, auf dem firtz läuft.

Die anderen Dateien hingegen können problemlos auf einem anderen Server liegen. Bei der Gelegenheit aktiviert gleich die Ausgabe des Cover-Images, das wird von firtz nämlich auch unterstützt.

Weiterhin ist es wichtig, dass im "outgoing service" des Podcasts die "HTTP base URL on your web server" angegeben ist. Wenn Eure Dateien also per `http://dein.toller.podcast.test/<dateiname>.m4a` erreichbar sind, muss dort `http://dein.toller.podcast.test/` stehen.

Aktuell sind folgende Attribute im Zusammenhang mit Auphonic vorhanden:
 
**auphonic-mode:**  
full, exclusive, episode oder off

**full**:  
alle Episoden, die mittels Auphonic gefunden werden UND die, die mittels .epi-Datei konfiguriert wurden werden angezeigt.Dateinamensgleichheit führt dazu, dass die Daten der .epi-Datei die Auphonic-Produktion überschreiben, soweit vorhanden. Es ist damit möglich, eine Auphonic-Produktion einzubinden und Daten per .epi hinzuzufügen, bzw. zu modifizieren.

**exclusive**:  
Nur Auphonic-Produktionen werden angezeigt.

**episode**:  
nur Auphonic-Produktionen werden angezeigt, zu denen auch eine namensgleiche .epi-Datei vorhanden ist. Dateinamensgleichheit führt dazu, dass die Daten der .epi-Datei die Auphonic-Produktion überschreiben, soweit vorhanden.

**off**:  
nur .epi-Dateien werden angezeigt.

 
**auphonic-path:**
Der Pfad zu den lokal vorhandenen Auphonic-Produktionsdateien. 
 

**auphonic-glob**:  
Ein Matchpattern, mit denen die .json Produktionsdateien gefunden werden. *.json z.B. für alle.
Sind im Pfad Dateien unterschiedlicher Feeds vorhanden, sollte das Pattern (und natürlich die Dateinamen!) entsprechend gewählt werden. 

 
**auphonic-url**:
Im Remote-Modus, in dem die Produktionsdateien nicht per Filesystem zu finden sind, muss der URL
zu diesen Daten angegeben werden. Die Dateien werden dann ausschließlich über konfigurierte namensgleiche .epi-Dateien zu finden sein, die dann auch leer sein können. Eine andere Idee, diese Dateien zu identifizieren hatte ich bisher nicht. **Implementiert ist diese Funktion mit Version 0.5 noch nicht.**

Das war's wohl für's erste. Versuche alle nötigen Informationen zu liefern und so viele Attribute wie nur möglich zu füllen, die den Feed mit Metadaten versehen.


## Die Episode

Ein Feed taugt nichts, wenn nicht Inhalte darin transportiert werden. Diese Inhalte sind die Episoden Deines Podcasts. Das Hinzufügen einer Episode ist dem Anlegen eines Feeds nicht unähnlich.

Betrachten wir zunächst den vermutlich normalen Fall, in dem für jede Episode eine Datei im Feed-Ordner angelegt wird:

Der Name der Datei lautet *title-slug.epi*. Ich empfehle, den Titel-Slug eindeutig und fortlaufend zu wählen. Entweder *001.epi, 002.epi, 003.epi* usw... oder aber mit vorangestelltem Kürzel, wie zum Beispiel (*supicast* also: SC) *SC001.epi, SC002.epi, SC003.epi* usw...

Nur im auphonic-Modus ist die Bennnung der Episoden-Datei eventuell identisch zum im Auphonic gewählten Titel vorzunehmen. Dazu aber später im Auphonic-Kapitel mehr.

In der .epi-Datei finden sich die für diese Episode nötigen Informationen. Der Aufbau der Datei ist analog zu dem der [Feed-Konfiguration](#der-feed).

Die Episoden-Attribute lauten wie folgt:

**title:**  
Titel der Episode

**date:**  
Optional das Veröffentlichungsdatum der Episode in der Notation "YYYY-MM-DD HH:MM:SS". Wird das Datum weggelassen, erzeugt sich das Veröffentlichungsdatum aus dem Datum der letzten Änderung der Konfigurationsdatei.
 
**description:**  
Kurze Beschreibung der Episode, Subheadline

**article:**  
Ausführliche Beschreibung / Artikel zum Inhalt der Episode. Dieser Text wird auch im Player der Webseite angezeigt und darf HTML enthalten.

**keywords:**  
Kommagetrennte Stichwörter, die den Inhalt der Episode beschreiben

**duration:**  
Dauer der Episode in der Notation 'HH:MM:SS'. Wird diese Angabe weggelassen, wird die Dauer mit 00:00:00 angegeben, was für den Hörer allerdings unschön ist. Bitte füllt das aus, es erhöht die Akzeptanz Eure Sendungen enorm, auch wenn das nach einer Nebensache klingt!

**image:**  
optional die URL zu einem Bild nur für diese Episode. Wird dies weggelassen, wird das Bild aus der Feedkonfiguration übernommen.
 
**chapters:**  
Kapitel der Episode. Die Notation folgt dem Simple Chapter Standard von [Podlove](http://podlove.org/simple-chapters/):

`HH:MM:SS Kurze Beschreibung des Kapitels`
	
*Link und Bild für Kapitel werden aktuell noch nicht unterstützt!*
 
Die eigentlichen Audiodateien werden in der Episode analog zu den in der Feedkonfiguration unter 'formats' angegebenen Formaten konfiguriert.

Jedes im Feed konfigurierte Format muss hier mit einer URL zum Download der Folge versehen werden. Werden also m4a, mp3 und opus im Feed konfiguriert, müssen - hier beispielhaft - diese drei Dateien angegeben werden:

**mp3:**  
http://podcast.hoersuppe.de/fz001.mp3 13302075

**m4a:**  
http://podcast.hoersuppe.de/fz001.m4a 9360499

**opus:**  
http://podcast.hoersuppe.de/fz001.opus 6905080
 
Die numerische Angabe hinter der URL ist die Dateigröße in Bytes. Dies ist optional, aber auch hier bitte ich um Sorgfalt. Als Podcasthörer möchte man, wenn man z.B. unterwegs auf UTMS oder schlechteres angewiesen ist, vor dem Download wissen, ob man nicht doch bis zum nächsten WLAN wartet. Anständige Daten im Feed erleichtern das Hören und wo das Hören leicht gemacht wird, hört man auch gerne noch ein zweites Mal hin; glaubt mir, ich weiß wovon ich rede, in meinem Podcatcher stecken aktuell 213 Podcast.

Und nun? Eigentlich bist Du jetzt fertig. Wenn alle Daten stimmen, dann hast Du jetzt eine funktionierende Podcastpublishingseite zusammengebaut, die Dir Feeds ausspuckt und eine Webseite.

Eine Webseite? Ach, da war ja noch etwas!