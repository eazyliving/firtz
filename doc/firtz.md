# firtz podcast publisher

* [Einleitung](#Einleitung)
* [Was braucht es?](#was-braucht-es)
* [der Feed](#der-feed)
* [die Episode](#die-episode)
* [die Webseite](#die-webseite)
* [Auphonic](#auphonic)
* [firtz erweitern](#firtz-erweitern)
* [Cloning](#cloning)

## Einleitung

Firtz ist eine einfache, relativ schnell zu nutzende Podcastpublishinglösung, die ohne viele Vorarbeiten einen fertigen, gültigen Feed erzeugt und darüber hinaus eine auf das nötigste reduzierte Webseite erstellt.

Wer möchte - und kann - ist aber auch in der Lage, alle Aspekte der Feed- und Seitengenerierung selbst zu steuern und zu erweitern.

Kurz und gut: Man kann, aber muss nicht alles damit machen. Wofür es allerdings wirklich nicht taugt: Bloggen. Wer neben dem Podcast ausgiebig bloggt, sollte bei Wordpress bleiben.

Firtz ist für Podcaster gedacht, die alle paar Wochen etwas veröffentlichen und darüber hinaus wenig Ansprüche an das ganze Drumherum stellen.

Darüber hinaus ist es sehr hilfreich, wenn nicht sogar der einfachste Weg, mit [Auphonic](http://auphonic.com) zu arbeiten. Wenn der Workflow mit Auphonic einmal aufgestellt ist und funktioniert, muss die Webseite/der Feed nie wieder angefasst werden und erstellt sich praktisch von selbst.

**Was - grob überschlagen - bekommst Du mit Firtz?**

* Feeds für alle gängigen Audioformate, generiert aus einer einzigen Konfigurationszeile
* komplette flattr-Integration
* nahezu komplette Integration aller Podlove Standards und Features
 * Alternate Feeds
 * Simple Chapters
 * Podlove Webplayer
 * Deep Linking
* Integration Auphonic
* eine automagisch generierte Webseite
* einiges mehr, das mir aktuell nicht einfällt...

## Was braucht es?

Wie alle Software, die im Open-Source-Dampfgarer produziert wird, benötigt firtz einige Dinge, in die andere Menschen viel Arbeit und Zeit gesteckt haben. Firtz bringt die meisten dieser Pakete bereits mit. Dazu zählen:

* [fatfree framework](https://github.com/bcosca/fatfree)
* [bootstrap](http://twitter.github.com/bootstrap/)
* [podlove webplayer](https://github.com/gerritvanaaken/podlove-web-player)

Diese Projekte integrieren wiederum zahlreiche Module aus anderen Projekten, die ich aber nicht weiter aufzählen möchte.

**PHP**  
Das fatfree framework ist ein PHP-Framework. Deshalb ist php ab Version 5.3 nötig, damit der firtz funktioniert. Außerdem muss ggf. die Nutzung von .htaccess-Dateien erlaubt sein, wenn der Server nicht in Deiner Hand ist. Wenn die Kiste Dir gehört, rate ich die Einstellungen der .htaccess in die Konfiguration des Webservers zu bringen, das macht alles etwas schneller.

Ob nun Apache, nginx oder lighthttpd ist nicht entscheidend. Im Zweifel sind jedoch die Konfigurationen der Server etwas unerschiedlich, um die rewrite-Rules umzusetzen. Schau mal beim [fatfree framework](https://github.com/bcosca/fatfree#getting-started) vorbei, dort steht nähers.

Außerdem ist es für das Caching nötig, dass der firtz schreibend auf das Verzeichnis zugreifen kann, in dem er installiert ist. Das sollte in den meisten Fällen gegeben sein.

*Kurz und gut: php 5.3 oder höher ist nötig, damit das alles klappt und der firtz muss auf sein Verzeichnis schreiben können.*

Was aber musst Du mitbringen, damit das alles klappt?

**Ich empfehle Anfängern, die sich noch im Unklaren sind, wie die nötigen Arbeitsschritte vor der Veröffentlichung aussehen, dringend den Artikel [How to Podcast for Free](https://auphonic.com/blog/2013/02/07/how-to-podcast-for-free/) durchzulesen. Wem der erste grobe Durchblick fehlt, diesen Artikel aber nicht gelesen hat, der braucht auch hier erstmal nicht weiter zu lesen.**

**Platz in diesem Internet**

Das dürfte für die meisten noch die größte Hürde darstellen. Es ist inzwischen zwar möglich, für sehr wenig Geld geeigneten "Webspace" zu bekommen, dennoch ist es natürlich viel bequemer, seine Daten in einen vorhandenen Dienst zu schieben, der dann den Rest übernimmt. Für solche Fälle gibt es z.B. die [Soundcloud](http://soundcloud.com), aus der auch ein korrekter Feed herausfällt. Viel mehr dann aber auch nicht.  

Ich persönlich mache sowas sehr gerne bei [Uberspace](http://uberspace.de). Da hat man seinen Zugang in ein paar Minuten geklickt, hat den besten Support den man (auch für viel Geld) bekommen kann und selbst dem DAU erschließt sich das verhältnismäßig schnell. Aber jeder, wo er mag.

Nehmen wir also mal an, Du hast irgendwo Platz in diesem Internetz und weißt auch, wie dieser Platz heißt. Einigen wir uns auf die Domain **supicast.de** (mal sehen, wie lange die noch unregistriert ist...).

Natürlich musst Du Zugang zu diesem Platz haben und die Dateien vom firtz dorthin kopieren können. Darüber hinaus muss der ausführende Webserver ausreichend Rechte haben, um Verzeichnisse und Dateien erzeugen zu können. Das sollte aber eigentlich überall so sein (man beachte "eigentlich" und "überall", ich habe schon Pferde vor der Apotheke kotzen sehen...).

**Den Firtz**

Du besorgst Dir nun das [Zip-Archiv vom firtz](https://github.com/eazyliving/firtz) und entpackst es an die Stelle, an der Dein Webserver das verdauen kann.

Für Profis:
> git clone https://github.com/eazyliving/firtz.git .

Sollte der firtz in einem Unterordner laufen, musst Du ggf. in die .htaccess eingreifen und die RewriteBase von / auf /UNTERORDNER ändern.

Da liegen nun ein Haufen Dateien und Verzeichnisse herum. Wirklich von Interesse aber sind folgende Verzeichnisse und die Dateien und Unterverzeichnisse, die sich darin befinden:

feeds/  
templates/
	
Im Ordner *feeds* befinden sich die Ordner, die dem System sagen, welche Feeds angeboten werden sollen. Der Name dieses Ordners wird an vielen Stellen als Schlüssel (slug) wieder verwendet, wähle ihn also weise. Dazu später allerdings noch mehr.

In den Feed-Ordnern wiederum werden sich die Konfigurationsdatei und die Episoden-Dateien finden, aus denen der Feed generiert wird.

Feeds lassen sich übrigens deaktivieren, indem Du dem Ordnernamen ein "_" voranstellst. Alle Ordner, die mit "_" beginnen werden ignoriert.

Im Ordner *templates* wiederum finden sich alle Dateien, die dazu beitragen, die generierten Feeds in irgendeiner Weise darzustellen. Ob nun RSS oder Webseite, die Ausgabe wird - größtenteils - hier festgelegt. Hier **muss** nichts getan werden, wer mag, kann die Grundeinstellungen übernehmen. Mit der Zeit wirst Du aber vermutlich an der einen oder anderen Stelle, vermutlich an der Webseite, drehen wollen.

**Die Audiodateien**

Das ist wiederum ein Kapitel, bei dem ich Dir nur schlecht helfen kann. Ich will nicht leugnen, dass das nicht eben der einfachste Part ist. Firtz hilft Dir beim Verteilen der Informationen Deines Podcasts, nicht beim Erstellen und Weiterverarbeiten der eigentlichen Audiodatei.

Es gibt aber jemanden, der dabei sehr behilflich ist: [Auphonic](http://auphonic.com). Dort gibt es nicht nur Hilfe beim Verarbeiten der Audiodatei. Auphonic übernimmt auch das Übertragen der Audiodateien an den Ort, von dem sie das Publikum später herunterladen wird und zu allem Überfluss gibt es auch eine Möglichkeit, mit Auphonic den firtz zu füttern, der dann den Rest der Arbeit übernimmt, ohne dass irgendeine Episode angelegt werden muss.

**Noch einmal: Ich empfehle Anfängern, die sich noch im Unklaren sind, wie die nötigen Arbeitsschritte vor der Veröffentlichung aussehen, dringend den Artikel [How to Podcast for Free](https://auphonic.com/blog/2013/02/07/how-to-podcast-for-free/) durchzulesen. Wem der erste grobe Durchblick fehlt, diesen Artikel aber nicht gelesen hat, der braucht auch hier erstmal nicht weiter zu lesen.**
  
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
* mobi: application/x-mobipocket-ebook

**mediabaseurl:**  
**mediabasepath:**

Mit diesen beiden Attributen ist firtz in der Lage, Audiodateien ohne Konfiguration in den Episoden zu finden. Nehmen wir an, die .epi-Datei einer Episode heißt *sc001.epi*. Alle Audiodateien dieses Podcasts finden sich im Netz unter *http://media.supicast.de/* (**mediabaseurl**) und lokal sind die Dateien unter */home/supicast/media/* (**mediabasepath**) zu finden.

Du wirst einwenden, dass der lokale Pfad unnötig ist. Ist er auch, aber nur wenn man in Kauf nimmt, dass im Feed die Angabe der Dateigröße fehlt bzw. auf 0 Bytes steht. Außerdem wäre ein sinnvoller Check auf die Existenz der Datei nur mit permanenter Netzlast durchführbar. Und das will vermutlich niemand. Schluckt die Kröte oder tippt die URLs per Hand ;-)

**flattrid:**  
Wenn flattr genutzt wird/werden soll, hier die user-id angeben. Es werden Payment-Links im Feed und Buttons auf der Webseite für den gesamten Feed und für einzelne Episoden erzeugt. Es ist nicht möglich, mehrere IDs anzugeben.
 
**author:**  
Der Name des Autors dieses Podcasts

**licensename:**  
Name der Lizenz, unter deren Bedingungen die Weitergabe des Podcasts, deren Metadaten und Dateien gestattet ist. 

**licenseurl:**  
URL einer Seite, auf der diese Weitergabebedingungen im Detail erklärt sind.
  
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

Es ist nicht nötig (und erlaubt!) das "&" als `&amp;` zu kodieren.
  
**email:**  
Emailadresse, unter der der Autor des Podcasts erreichbar ist.
 
**language:**  
Sprache, in der der Podcast veröffentlicht, Deutsch z.B. wäre "de-DE". Info zu den Sprachcodes finden sich hier: http://www.rssboard.org/rss-language-codes
  
**itunes-block:** Blockt den iTunes-Store. Will man vermutlich nicht ;)

**explicit:**  
entweder yes oder no, wirkt sich vor allem auf iTunes aus.
 
**itunes:**  
Ist der Podcast bereits bei iTunes erreichbar, steht hier der komplette Link zur iTunes-Seite. (firtz: [https://itunes.apple.com/de/podcast/firtz/id604449399](https://itunes.apple.com/de/podcast/firtz/id604449399))
  
**disqus:**  
Disqus stellt externe Kommentarfunktionen zur Verfügung, ohne dass man sich um Datenbanken und Nutzerverwaltung kümmern muss. Hier den Forenname eintragen, damit auf den Webseiten des Feeds Kommentarfunktionen freigeschaltet werden. Nähere Informationen dazu finden sich unter [http://disqus.com/for-websites/](http://disqus.com/for-websites/) und [https://disqus.com/admin/signup/](https://disqus.com/admin/signup/)
 
**bitlove:**  
Wenn Du bei [bitlove](http://bitlove.org) Deine Feeds torrentifizierst, kannst Du hier - allerdings ausschließlich für das Webseitentemplate - Downloadlinks dafür konfigurieren. Das Format sieht wie folgt aus:

*format* *user* *feedname*

Wenn Deine Torrents also ungefähr so heißen:  
*http://bitlove.org/supicast/supicast-m4a/sc001.m4a.torrent*  
dann sieht die Konfigurationszeile so aus:

bitlove:  
m4a supicast supicast-m4a

Es können beliebig viele Formate konfiguriert werden.

Verwechselt das bitte nicht mit einem torrent-Feed. Das geht auch, funktioniert allerdings genau  so wie die normalen Formate. Du müsstest dafür in die **format:** Konfiguration "torrent" hinzufügen und in jeder Episode den vollständigen Link zu einem Torrentfile angeben. So wäre auch ein Feed ausschließlich mit Torrentlinks denkbar.


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
Ein Matchpattern, mit denen die `*.json` Produktionsdateien gefunden werden. `*.json` z.B. für alle.
Sind im Pfad Dateien unterschiedlicher Feeds vorhanden, sollte das Pattern (und natürlich die Dateinamen!) entsprechend gewählt werden. 

 
**auphonic-url**:
Im Remote-Modus, in dem die Produktionsdateien nicht per Filesystem zu finden sind, muss der URL
zu diesen Daten angegeben werden. Die Dateien werden dann ausschließlich über konfigurierte namensgleiche .epi-Dateien zu finden sein, die dann auch leer sein können. Eine andere Idee, diese Dateien zu identifizieren hatte ich bisher nicht. **Implementiert ist diese Funktion mit Version 0.5 noch nicht.**

Das war's wohl für's erste. Versuche alle nötigen Informationen zu liefern und so viele Attribute wie nur möglich zu füllen, die den Feed mit Metadaten versehen.

**redirect**:

Zu guter Letzt kann es natürlich vorkommen, dass Du den Feed umziehen musst. Anderes System, andere Domain, was auch immer. Um Deine Abonnenten nicht mit einem toten (alten) Feed alleine zu lassen, erzählst Du dem firtz, wo denn der neue Feed zu finden ist. Der führt dann ein redirect (301) aus, was im Allgemeinen auch diverse Dienste wie z.B. iTunes auf den neuen Feed führen sollte.


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

Alles beisammen? Dann schau Dir doch mal Deinen RSS-Feed an! Der Feed dieses Beispieles würde unter

http://supicast.de/supicast/

zu finden sein. Wenn Du weitere Formate anbietest (z.B. mp3, ogg oder opus - bitte keine Diskussion, dass ogg nur ein Container ist, hier ist mit ogg ogg-vorbis gemeint), wären die Feeds dieser Formate unter z.B.

http://supicast.de/supicast/mp3 oder  
http://supicast.de/supicast/opus 

zu finden. Das Schema lautet http://domain/feedname/format

Wird das Format weggelassen, wird der Feed für das erste unter **formats:** konfigurierte Audioformat ausgegeben.

Moment... Webseite? Ach, da war ja noch etwas!

## Die Webseite

Wenn Dich das Thema Webseite nur am Rande interessiert, überfliege diesen Abschnitt, nehme freudig zur Kenntnis, dass diese automatisch aus den Daten des Feeds und der Episoden erzeugt wird und lass es damit gut sein ;-)

Erstmal aber schau Dir doch die Seite an: http://supicast.de/supicast/show

**show** ist hier das Schlüsselwort, der Befehl, nicht den Feed auszugeben, sondern die Webseite anzuzeigen. Die Seite besteht aus Kopf, allen Episoden und einem Seitenfuß. Da aber oft eine Episode einzeln adressierbar sein muss oder sollte (z.B. flattr!) gibt es auch eine Möglichkeit, eine einzelne Episodenseite aufzurufen:

http://supicast.de/supicast/show/001

wenn die Episode den Slug 001 (Dateiname: *001.epi*) trägt. So kannst Du auch an beliebigen Stellen im Netz auf diese Folge verweisen.

Seitenpaginierung mit einer bestimmten Menge an Episoden existiert noch nicht, wird aber bis Version 1.0 implementiert.

#### Die Seite modifizieren

Wesentlichen Beitrag zum Aufbau und Aussehen der Seite leisten [bootstrap](http://twitter.github.com/bootstrap/) für die Gestaltung, der [podlove webplayer](https://github.com/gerritvanaaken/podlove-web-player) für den Audioplayer und die Template-Engine des [fatfree frameworks](https://github.com/bcosca/fatfree).

Wenn Du also am Aussehen der Seite schrauben willst, wirst Du eine oder mehrere dieser Komponenten zumindest in Ansätzen kennen und verstehen müssen. Schaue Dir das Seitentemplate an, ich glaube, der Aufbau erschließt sich dem halbwegs ambitionierten Amateur von selbst.

Was aber kannst Du tun, ohne gleich am Template drehen zu müssen? Es gibt ein paar Stellen, in die Du eigene Seiten und Informationen hineinwerfen kannst, nennen wir sie Hooks.

Da wären zum einen die Template-Hooks. Das sind kleine HTML-Schnippsel, die in einem bestimmten Unterverzeichnis des Templates liegen und anhand dieses Verzeichnisses dem Haupt-Template hinzugefügt werden.

Diese Verzeichnisse sind folgende:

*head/*   
Hier bringt Ihr Teile unter, die in der Webseite vor dem schließenden `</head>` hinzugefügt werden. Das kann Javascript sein oder auch zusätzliche Stylesheets.

*header/*  
Nicht zu verwechseln mit *head/*! Hier werden dem Kopf der Seite Informationen hinzugefügt. Beispielhaft zu nennen wären hier... äh... öhm.. Euch fällt bestimmt was ein, mir im Moment nicht.

*episode/*  
Die Schnippsel in diesem Verzeichnis werden hinter jede Episode integriert. Mit Hilfe der Episoden- oder Feedattribute lassen sich hier zusätzliche Informationen unterbringen.  
Stell Dir vor, Du machst einen Podcast zusammen mit einem Freund. Dessen Flattr-Konto möchtest Du auch bedenken und deshalb hinter jede Episode einen kurzen Text wie "ohne Karl-Heinz geht es nicht, hier findet Ihr seinen [link] Klingebeutel" stellen. Ein besseres Beispiel fällt mir aktuell nicht ein ;)

**Bedenke, dass diese Schnippsel für alle Folgen gelten. Mit hilfe der Attribute lässt sich das zwar etwas zuschneiden, aber individuellen Text für einzelne Episoden bringe bitte im Artikel der jeweiligen unter.**

*footer/*  
Alles was in den Fuß der Seite gelangen soll, muss hier hineingeworfen werden. 

Das wären die Erweiterungsmöglichkeiten, die Unter-Templates ins Haupt-Template integrieren.

Etwas einfacher geht es mit den  

*pages/*  
Diese finden sich im Unterordner *templates/pages* und heißen z.B.: *Impressum.html*.

Findet firtz hier eine Datei geschehen zwei Dinge. Erstens wird der Webseite im Menübalken ein Menüpunkt hinzugefügt, der den Teil des Dateinamens vor .html als Titel trägt. In diesem Falle als *Impressum*.

Klickt der Besucher Deiner Seite auf diesen Menüpunkt, gelangt er zur URL `http://supicast.de/supicast/page/Impressum`. Auf dieser wird der komplette Inhalt des Templates angezeigt, umrahmt von Kopf und Fuß der Webseite, allerdings **ohne Episoden**. Auf diese Art und Weise kannst Du also z.B. ein Impressum integrieren oder andere Infoseiten.

Um dem ganzen etwas Struktur zu geben, ist es möglich, Unterordner in pages/ anzulegen. Diese ergeben dann im Kopf der Seite ein Dropdown-Menu. Verschachtelte Ordnerstrukturen sind nicht möglich. Der Name des Ordners wird als Titel des Dropdownmenus gesetzt.

Gestaltungsänderungen sind auch möglich, ohne gleich wahnsinnig zu werden. Für den ganzen CSS-Kram wird bootstrap genutzt. Für bootstrap gibt es ein paar freie Themes, die Du auf zwei Arten in das System hineinbringen kannst:

**Austausch der Standard CSS-Datei:**  
Die findet sich im Ordner css. Eigentlich sind es zwei Dateien, einmal für den Desktop, einmal als responsive Version für mobile Geräte. Auf z.B. [Bootswatch](http://bootswatch.com/) oder auch bei [bootstrap selbst](http://twitter.github.com/bootstrap/getting-started.html#examples) gibt es freie Themes, die genutzt werden können.

Lade die entsprechenden CSS-Dateien herunter und ersetze die Standard-Dateien. Diese Dateien gelten für alle angelegten Podcasts!

**Individuelle Styles für einen Podcast:**  
Wenn Du nur einem Podcast eine bestimmte Gestaltung zukommen lassen möchtest, dann lege die CSS-Datei in den Ordner mit der Feedkonfiguration des Podcasts und nenne sie <feed-slug>.css. In unserem SupiCast-Beispiel hieße die Datei also supicast.css.

Findet firtz eine solche Datei, wird diese anstelle der Standard-Datei genutzt. Zusätzlich kannst (und solltest) Du eine responsive-Datei nutzen, die dann supicast-responsive.css heißen muss.

So viel (oder wenig) zur Webseite. Naturgemäß liegt hier das größte Potential für die Individualisierung Deines Podcasts, Feeds sehen schließlich immer gleich aus. Wenn Du weißt, was zu tun ist und Spaß an solchen Sachen hast, wird Dir die Standard-Template-Datei sicher hilfreich sein, das System zu verstehen.

Und wenn Du mal ein tolles Template gebaut hast, dann lass uns bitte daran teilhaben. Firtz ist auch für Podcaster gedacht, die von solchen Systemen keine Ahnung haben. Gerade die freuen sich über solche Themes. Wende Dich einfach an [mich](mailto:info@hoersuppe.de), wenn Du Dein Theme veröffentlichen möchtest. Ich würde das einfach dem Archiv hinzufügen und in der Dokumentation behandeln.

## Auphonic

Kommen wir zu dem Teil, der anfangs vielleicht etwas gewöhnungsbedürftig und lernintensiv ist, jedoch zu guter Letzt den größten Komfort bietet und ein maximal gutes Ergebnis in Deine Feeds kippt: [Auphonic](http://auphonic.com).

Früher[tm]hat man seine Audiodateien am Rechner erstellt, dann geschnitten, mehr oder minder notdürftig in der Qualität verbessert, in die gewünschten Audioformate konvertiert und dann irgendwo in dieses Netz geworfen.

Weil schon das Encoding in unterschiedliche Formate nicht von jeder Software erledigt werden kann und weitere Arbeit nach sich zieht (inklusive Feederstellung und -pflege), haben viele Podcaster darauf verzichtet, mehr als einen Feed mit mp3 anzubieten. Ein altes Format, bei dem heute das Verhältnis aus Qualität und Dateigröße nicht mehr zeitgemäß ist. Bessere Ergebnisse werden mit AAC erzielt und wo Bandbreite ein Thema ist, ist opus in den Startlöchern.

Ich habe für die [Hörsuppe](http://hoersuppe.de) genau so angefangen. Erst gab's nur mp3, dann erstellte ich per Hand ein m4a (aac) und nachdem ich endlich Auphonic genutzt hatte, kam dann auch irgendwann opus hinzu.

Aber Auphonic bietet nicht nur das Encoding in viele unterschiedliche Formate. Das ist nur ein kleiner Teil. Ich werde das hier nicht lang und breit erklären, dafür gibt es eine Menge Quellen, die das deutlich besser machen können als ich. Da wären zu nennen:

* [Das Auphonic Blog](https://auphonic.com/blog/)
* [Die Folge 7 "Auphonic"](http://der-lautsprecher.de/ls007/) in Tim Pritloves Podcast "der Lautsprecher" (etwas überholt, aber ein guter Anfang)
* [Folge 240 der FLOSS Weekly](http://twit.tv/show/floss-weekly/240) für diejenigen, die des Englisch mächtig sind

Kurz und gut: Auphonic bietet einen kompletten Audioworkflow von der ursprünglichen Audiodatei ausgehend bis hin zum Upload der verschiedenen Audiodateien auf den Server, von dem die Episoden heruntergeladen werden.

Zugang und Nutzung von Auphonic ist bisher kostenlos, was hoffentlich noch lange so bleibt. Dennoch ist es anständig, wenigstens ein flattr-Abo draufzuwerfen!

Nehmen wir mal an, Du hast alle für Auphonic nötigen Grundvoraussetzungen erfüllt: Eine fertige Ursprungsdatei (am besten .flac, mindestens jedoch ein 192kbit mp3), einen Server, auf dem die Dateien abgelegt werden sollen (hier am besten per sftp) und alle Metadaten in einem Preset für diesen Podcast.

### Komm zur Sache!
Nun muss firtz eigentlich nichts von Auphonic wissen. Wenn in einer episoden-Datei die URL zu den Audiodateien stecken, reicht das im Grunde.

Wenn Du aber, wie ich, zu den extrem Faulen Zeitgenossen gehörst oder einfach einen automatischen Workflow bevorzugst, dann ist das Deine Chance!

Ich sag's direkt: Wenn Du nicht ein wenig Erfahrung im Umgang mit Servern und *nix hast, wird das hier nicht so ganz einfach. Vielleicht kann Dir jemand dabei helfen? Fragen kostet nichts!

####Was bei Auphonic getan werden muss

Zusätzlich zu den üblicherweise benötigten Daten in einer Auphonicproduktion, muss eine Production-Description weggeschrieben werden, die vom firtz erreicht werden kann. Aktiviere diese Ausgabedatei dort, wo Du auch die Audioformate des Presets/der Produktion angibst. Achte darauf, dass das Format .json ist.

Wo Du schon dabei bist, aktiviere hier auch die Ausgabe des Cover-Images, wenn Du für jede Episode unterschiedliche Cover nutzt.

In der Konfiguration des "external service" (sftp...) gebe unbedingt die in Auphonic nur optional nötige BASEURL an. Diese Option lautet aktuell **HTTP base URL on your web server**.

Wenn Deine Podcasts also unter z.B. der URL http://media.supicast.de/001.m4a zu finden sind, lautet die BASEURL http://media.supicast.de/ Lässt Du diese Angabe weg, wird der automatische Auphonic-Modus nicht funktionieren (oder nur zufällig ;)).

Bis hierhin warst Du auf auphonic.com unterwegs. Wenden wir uns dem Kapitel zu, in dem es heißt:

####Dem firtz erklären, wo auphonic den Most holt

Die im Abschnitt [Feed](#der-feed) erklärten Auphonic-Attribute sind von zentraler Bedeutung. Ich erwähne hier nur noch ein paar zusätzliche Kniffe und Bedingungen, die an erster Stelle etwas kurz gekommen sind.

Bisher (v0.5) sind nur lokal zugängliche Auphonic-Daten für firtz interessant. Liegen die Dateien, die die Produktionen beschreiben auf einem anderen Server, der nicht über ein lokales Filesystem erreichbar ist, ist an dieser Stelle für Dich Schluss. Ich hoffe bis zur 1.0 den remote mode für Auphonic fertig zu haben.

Nehmen wir also an, die Dateien lägen auf dem gleichen Server wie der SupiCast und dort im Verzeichnis */home/supicast/audio/*. Nehmen wir weiterhin an, eine Episode mit dem slug 001 wäre über auphonic gelaufen und alle Dateien, die dazu gehören wären in diesem Ordner angekommen.

Wie klappt das mit den Namen? Es handelt sich natürlich nicht um den Titel (Metadaten) der Folge. Der slug ist in diesem Falle der Teil des Dateinamens vor der Dateiextension (.mp3 .m4a .json...). Ihr könnt ihn in Auphonic als **Output Filename** angeben. Wird dies nicht getan, übernimmt Auphonic den Ursprungsdateinamen. Heißt die zu Auphonic hochgeladene Datei also bereits 001.flac, ist alles gut und Du musst nichts weiter tun.

Im Ordner liegen also nun alle Dateien. Ist der Feed mit einem Attribut **auphonic-path** versehen, sucht firzt in genau diesem Ordner nach den json-Dateien. Gesucht wird nach dem **auphonic-glob**. Steht hier `*.json`, werden alle Dateien gefunden. Steht hier z.B. `sc*.json`, können sich im gleichen Ordner Dateien eines zweiten Podcasts befinden, ohne dass diese hinzugezählt werden.

Gewöhne Dir übrigens an, die Dateinamen vorhersagbar zu halten und nicht zu überladen. Darüberhinaus bleibe dem Dateinamen von Anfang bis Ende treu und wechsle nicht zwischendrin die Benamung, sonst gibt's Tränen. Wähle am besten die Form INITIALE+NUMMER als Slug. Solltest Du mal einen zweiten Podcast beginnen, wirst Du Dich freuen, die Episoden auseinander halten zu können.

Zu guter Letzt ist der **auphonic-mode** korrekt zu wählen. Je nachdem, wie Du das machst, sind vor allem full oder exclusive interessant. Wenn Du mit firtz zusammen einen Podcast beginnst, ist vermutlich exclusive die beste Wahl.

Wählst Du einen Modus, in dem sowohl epi-Dateien als auch json-Dateien ausgewertet werden, bedenke, dass die epi-Dateien die höhere Priorität haben. Damit ist es Dir möglich, in Auphonic fehlende Daten nachträglich einzupflegen oder zu korrigieren, ohne gleich eine neue Produktion anstoßen zu müssen.

Wenn nun alles beisammen ist, geschieht der Rest - je nach Konfiguration - voll- oder halbautomatisch.

Dieser Teil der Dokumentation ist vermutlich noch zu ergänzen. Meine Erfahrung mit Auphonic verstellt mir eventuell den Blick auf den einen oder anderen Haken, der hier noch erwähnt werden will. Aber kommt Zeit, kommt Doku.

## Firtz erweitern

Während der Auslieferungszustand dieses Paketes bereits sehr brauchbar ist, gibt es dennoch viele Stellen, an denen man Gestaltung und Ausgabe modifizieren möchte.

Für diese Zwecke kann man an die [Templates herangehen](#die-seite-modifizieren), indem dort zusätzliche HTML-Schnippsel eingefügt werden.

Was aber, wenn nicht nur am Aussehen, sondern an der kompletten Ausgabe geschraubt werden soll? Wie zum Beispiel wäre ein ATOM-Feed anstelle des RSS2-Feeds einzubinden?

Dafür gibt es im firtz die Ausgabe- und Inhalts-Extensions, die zusätzliche Ausgabekanäle zur Verfügung stellen oder Inhalte modifizieren. Alle dafür benötigten Ordner müssen sich im Ordner *ext/* befinden.

Nehmen wir mal an, wir möchten einen ATOM-Feed haben. Im Ordner *ext/atom/* befinden sich folgende Dateien:

* ext.cfg: die Konfigurationsdatei der Erweiterung
* atom.xml: Das Template, mit dessen Hilfe die Ausgabe erzeugt wird

Im Grunde kopieren die Erweiterungen nur das Verhalten der Standardausgabe: Feedinformationen sammeln, Episoden sammeln und dann über das Template iterieren.

Aber fangen wir vorne an. In der Datei *ext.cfg* befinden sich folgende Attribute:

**slug:** Dies ist die Anweisung, die in der URL transportiert wird, um die Erweiterung aufzurufen. In diesem Falle hieße sie *atom* und würde dazu führen, dass der aufzurufende URL *http://supicast.de/supicast/atom/* lautet.

**arguments:** die nach dem slug zu übergebenden Parameter. Wird die Erweiterung per z.B.: *http://supicast.de/supicast/atom/mp3* aufgerufen, wird dem Template (und im Falle der Variable audio auch firtz selbst!) mitgeteilt, dass der Parameter "mp3" das Audioformat der Ausgabe darstellt.

Die Parameter können im Template mit @PARAMETERNAME referenziert werden. **Ich werde an dieser Stelle noch etwas ändern, allerdings muss ich noch einen vernünftigen Weg dafür finden**. Aktuell können Parameter aus Erweiterungen globale Adressen im firtz überschreiben. Das wird mit ausgewählten Parametern (z.B. @audio) weiterhin möglich sein, allerdings sollten private Parameter, die nur die Extension betreffen in einem eigenen Namensraum stecken, damit sich Erweiterungen nicht gegenseitig zerschießen.

**type:**  
Der Typ der Extension, output oder content.

**template:** Zu guter Letzt muss die Erweiterung auch mitteilen, mit welchem Template firtz rendern soll. An erster Stelle steht der Dateiname des Templates, dass sich im selben Ordner wie die .cfg befinden muss, an zweiter Stelle ein optionaler Mimetype. Wird der weggelassen, geht firtz von "text/html" aus. Templates sind nur bei Output-Extensions nötig.

**script:** Bei Content-Extensions wird kein Template benötigt, sondern ein waschechtes php-Script. Hier wird nur der blanke Dateiname angegeben, das Script muss sich im Extension-Ordner befinden.

Im Script befindet sich üblicherweise eine Funktion. Deren Dateiname baut sich wie folgt auf: 

**$slug_$hook($item)**

$slug ist der Name der Erweiterung. $hook gibt an, an welcher Stelle der Feed/Episodenverarbeitung die Funktion ausgeführt werden soll (aktuell nur: "episode"). Dieser Funktion wird ein Array übergeben, in dem entsprechend des Hooks eine Episode (oder später ein Feed) steckt.

Angenommen, die Erweiterung heißt markdown und soll in jeder Episode den Artikel von .md nach .html konvertieren.

`function markdown_episode($item)   
{  
	$item['article'] = my_md2html($item['article']);  
	return $item;  
}`

kapiert? Nicht? Dann ist das hier sowieso nichts für Dich ;-P

Extensions lassen sich übrigens deaktivieren, indem Du dem Ordnernamen ein "_" voranstellst. Alle Ordner, die mit "_" beginnen werden ignoriert.

##Cloning

Im Grunde ist es ja etwas schizzophren. Nichts ändert sich seltener, als ein Podcastfeed. Immer, wenn eine neue Episode erzeugt wird, ändert sich die Ausgabe der Seite und des Feeds, um dann bis zur nächsten Episode konstant den selben Kram auszugeben.

Wieso also eine dynamische Seite, wo der Inhalt alles, aber nicht wirklich dynamisch ist?

Vielleicht kannst Du php nicht leiden und willst Deinen Webserver nicht damit belästigen?

Für Dich gibt es das Cloning. Das heißt nicht mehr und nicht weniger, als dass die Feeds und Seiten Deines Podcasts einmal erstellt und ab dann statisch genutzt werden können.

Um die statischen Seiten zu generieren, rufst Du die *index.php* des firtz auf der Konsole mit dem CLI-php auf:

`php index.php`

Die Seiten werden daraufhin an einen vorkonfigurierten Ort im Filesystem geschrieben.

Die Konfiguration dazu findet sich in der jeweiligen *feed.cfg*:

**cloneurl:**  
Da zum Zeitpunkt der Generierung natürlich kein Webserver im Spiel ist, muss dem firtz mitgeteilt werden, wie der URL der Seite später lauten wird. Für den supicast wäre das dann **http://supicast.de/**

**clonepath:**  
Da wir uns im Filesystem bewegen, muss der firtz auch wissen, wohin die Daten im lokalen Filesystem geschrieben werden sollen. **/home/supicast/html** wäre ein Beispiel. Du wirst hoffentlich wissen, was Du tust. Im Grunde kannst Du die Dateien überall erzeugen lassen, wenn Du sie dann später an den Bestimmungsort kopierst, z.B. per FTP auf den Webserver.
 
Cloning ist im Moment das jüngste Feature und weitgehend ungetestet. Ich habe das entlang meines Hauptfeeds geschrieben und gestestet, wo alles soweit gut läuft. Ob das auch auf Dich zutrifft, steht auf einem anderen Blatt Papier...

## Ende gut alles gut?

Ich bin an dieser Stelle mit der ersten, sehr groben Version der Dokumentation fertig. Es werden Lücken und Fehler enthalten sein, die über die Zeit ausgemerzt werden. Für Hilfe und Hinweise bin ich immer dankbar!
