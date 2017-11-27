#Attribute im Feed

**title**

Schlicht und einfach der Titel Deines Podcasts

**description**

Beschreibung trifft es wohl nicht ganz. Eigentlich ist das mehr ein kurzer Anreißer, worum es geht. Wie zum Beispiel: "Der großartige Podcast aus Koblenz".

**summary**

Jetzt kannst Du sicher ausführlicher werden als in der description. Übertreibe es aber auch nicht.

**formats**

Sag dem firtz welche Formate Du in Deinem Podcast haben wirst. Die Liste der Bezeichnungen findest Du unter [den Formaten](#die-formate). Das erste angegebene Format ist das Standardformat, das genutzt wird, wenn kein explizites gewählt wird.

**flattrid**

Lass Dich beschenken! Die flattr-ID reicht, um an allen relevanten Stellen Buttons einzubinden.

**author**

Wie heißt Du? Diese Information ist sicherlich wichtig...

**image**

Das Logo Deines Podcasts sollte entweder im Format JPG oder PNG vorliegen. Ideal ist aktuell eine Größe von 1400x1400 Pixeln. Bedenke bei Deiner Wahl aber unbedingt, dass dieses Bild in allen möglichen Größen genutzt wird. Es gilt also wie für jede gute Logogestaltung: Es muss auch in 32x32 noch gut aussehen!

**keywords**

Gib hier ein paar sinnvolle, kommagetrennte Stichwörter zu Deinem Podcast an. Diese Stichwörter helfen, Deine Werke später in den Podcastverzeichnissen zu finden.

**category**
Die Kategorien orientieren sich an den Vorgaben Apples für iTunes. Es gibt Hauptkategorien und jeweils mehrere Unterkategorien, die aber keine weiteren Kategorien unter sich haben. Um dies an dieser Stelle einigermaßen einfach konfigurieren zu können, wird entweder eine Hauptkategorie einzeln angegeben oder eine solche mit Unterkategorie durch -> verbunden:

`Society & Culture`

`Technology -> Podcasting`

Diese Konfiguration würde den Podcast sowohl in die Hauptkategorie *Society & Culture* ohne weitere Unterkategorie setzen und in die Unterkategorie *Podcasting* in der Kategorie *Technology*. Die einzelne Angabe der Hauptkategorie *Technology* ohne *Podcasting* ist in diesem Falle nicht nötig. 
Details zu den Kategorien finden sich [hier](http://www.apple.com/itunes/podcasts/specs.html#categories).

Es ist nicht nötig (und erlaubt!) das "&" als `&amp;` zu kodieren.

**email**

Unter dieser Mailadresse bist Du zu erreichen. Denk dran, dass hier etwas erreichbares steht, schließlich will Dich vielleicht Apple für ein Feature anschreiben! ;-)

**language**

Dies ist die Sprache, in der der Podcast veröffentlicht, Deutsch z.B. wäre "de-DE". Info zu den Sprachcodes finden sich [hier](http://www.rssboard.org/rss-language-codes)

**explicit**

Hast Du Schweinkram auf der Zunge? Dann schütze die Kinder!!eins1ELF!

**itunes**

Ist der Podcast bereits bei iTunes erreichbar, steht hier der komplette Link zur iTunes-Seite. Den findest Du irgendwo auf genau dieser... frag mich nicht!

**auphonic-path**

Ich erkläre den auphonic-Workflow genauer in der Gesamtdokumentation. Hier sei nur erwähnt, dass unter auphonic-path der Pfad auf Deinem Server gemeint ist, unter dem die .json-Dateien gefunden werden. 

**auphonic-glob**

Wenn im auphonic-path die jsons mehrere Podcasts liegen, kannst Du mit diesem Pattern die für diesen Podcast nötigen jsons selektieren. Wenn die z.B. fasel00X.json heißen, die eines anderen Podcasts bla00X.json, gibst Du hier `fasel*.json` an.

**auphonic-mode**

full, exclusive, episode oder off. Genauere Infos in der vollen Dokumentation.

**twitter**

Nur das Twitter-Handle angeben, nicht den vollen Pfad zu Twitter, ohne @.

**adn**

Wenn Du auch bei ADN zu finden bist, nur das Handle, kein Pfad, kein @.

**itunesblock**

Möchtest Du iTunes blockieren? Weil Du gerade am Feed rumspielst? Oder weil iTunes doof ist? Dann schalte das hier auf `on` und Ruhe ist.

**mediabaseurl**

Gibst Du dann an, wenn Du allen Mediendateien einen festen URL voranstellen möchtest. Das macht im Grunde nur Sinn, wenn die Dateien anhand des Namens der .epi-Datei erkannt und gefunden werden sollen. Das wiederum benötigt dann auch den:

**mediabasepath**

Das ist der Ordner, in dem sich die Mediendateien befinden. Das ist nur sinnvoll, wenn Du in den .epi-Dateien nicht explizit auf die Mediendaten hinweist, sondern anhand des Dateinamens (==slug) diese finden lassen möchtest.

**redirect**

Es kann natürlich vorkommen, dass Du den Feed umziehen musst. Anderes System, andere Domain, was auch immer. Um Deine Abonnenten nicht mit einem toten (alten) Feed alleine zu lassen, erzählst Du dem firtz, wo denn der neue Feed zu finden ist. Der führt dann ein redirect (301) aus, was im Allgemeinen auch diverse Dienste wie z.B. iTunes auf den neuen Feed führen sollte.

**bitlove**

Wenn Du bei [bitlove](http://bitlove.org) Deine Feeds torrentifizierst, kannst Du hier - allerdings ausschließlich für das Webseitentemplate - Downloadlinks dafür konfigurieren. Das Format sieht wie folgt aus:

*format* *user* *feedname*

Wenn Deine Torrents also ungefähr so heißen:  
*http://bitlove.org/supicast/supicast-m4a/sc001.m4a.torrent*  
dann sieht die Konfigurationszeile so aus:

bitlove:  
m4a supicast supicast-m4a

Es können beliebig viele Formate konfiguriert werden.

Verwechselt das bitte nicht mit einem torrent-Feed. Das geht auch, funktioniert allerdings genau  so wie die normalen Formate. Du müsstest dafür in die **format:** Konfiguration "torrent" hinzufügen und in jeder Episode den vollständigen Link zu einem Torrentfile angeben. So wäre auch ein Feed ausschließlich mit Torrentlinks denkbar.

**licensename**

Gib hier an, wie die Lizenz heißt, unter der Du Deinen Podcast veröffentlichst. Schau Dich doch mal bei den [Creative Commons](http://creativecommons.org/) um. Da findest Du bestimmt etwas.

**licenseurl**

Unter dieser URL findet sich eine Erklärung Deiner Lizenz.

**licenseimage**

Einige Lizenzen bieten Logos an. CC hat sowas und sollte ein Bild vorliegen: her damit! Sieht besser aus.

**rfc5005**

Kein Mensch kennt den rfc5005. Aber Du solltest ihn mit `on` einschalten, wenn Du absehen kannst, dass Dein Podcast viele Folgen bekommt. Der Sinn dahinter ist, im Feed nicht mehr alle Folgen zu haben, aber eine Verlinkung zu eine Feed mit älteren Folgen. Leider unterstützen viele Podcatcher dieses Feature nicht. Habe dennoch Mut!

**baseurl**

Dieses Attribut benötigst Du nur, wenn Du in einer Installation des firtz mehrere Podcasts unter unterschiedlichen Domains veröffentlichen möchtest. Wenn dieser eine firtz dann mit einer bestimmten Domain angesprochen wird, muss er ja wissen, wo es langgeht. Bitte gib hier den vollen URL mit Schema (also http://) an.

**feedalias**

`format feedname oldfeed`

Das Problem war bei vielen Migrationen, dass frei benannte Feedadressen auf die neue Notation (/slug/format) umgestellt werden müssen.
Wenn man z.B. von Wordpress kommt und der alte MP3-Feed nach *http://supicast.de/feed/mp3* ging, der neue aber *http://supicast.de/sc/mp3* lauten soll, trägt man in dieser Zeile

`mp3 sc /feed/mp3`

ein. Es gibt dann einen 301er redirect und alles bleibt "beim alten". Entsprechend gut programmierte Podcatcher werden die neue Adresse dann übernehmen.

**articles-per-page**

Dieses Attribut beschränkt die Anzahl der Episoden auf der Webseite(!). Standard sind 3 Folgen.

**template**

Wenn Du zusätzlich zum Standardtemplate ein weiteres, ein Child-Theme nutzen möchstest, musst Du dieses hier anmelden. Bitte gib nur den Namen des Ordners an, nicht den vollen Pfad.

**templatevars**

Je nach Template kann es Variablen geben, die die Ausgabe und das Verhalten des Templates steuern. Diese werden, entgegen der üblichen Sitte mit einer einfachen Notation nach

`key value`

angegeben. Für die Variablen des quorx-Templates schaue bitte in die entsprechende Dokumentation.

#Attribute in der Episode

**title**

Muss ich nicht erklären, oder? Ok, für Dich: Dies ist der Titel der Episode.

**description**

Fasse den Inhalt hier kurz zusammen. Kurz! 255 Zeichen maximal, sonst gibt's Ärger!

**article**

Und hier kannst Du dann ausführlicher werden. Aber übertreibe es auch wieder nicht und vor allem lass die Finger von HTML und Co. Hier gehört ausschließlich Markdown rein! Wie das funktioniert, erklärt Dir u.a. [Wikipedia](http://de.wikipedia.org/wiki/Markdown).

**chapters**

Kapitel. Du willst Kapitel! Egal was die anderen sagen. Und auf die Amis hörst Du schon gar nicht. DU - WILLST - KAPITEL! Außer natürlich, die Episode dauert nur zwei Minuten. Das Format sieht so aus:

`HH:MM:SS Kurze Beschreibung des Kapitels <optionaler link> <optional URL zu einem Kapitelbild>`

**duration**

Gib hier die Dauer der Episode an. Nutze dafür bitte die Notation `HH:MM:SS`. Du kannst ggf. auch abkürzen mit `MM:SS`.

**keywords**

Gib hier eine sinnvolle, kommagetrennte Liste der Stichwörter für die Episode an. Der firtz hat übrigens eine Suchfunktion, die in diesen Stichwörtern sucht. Also sei schlau und nutze das sinnvoll.

**image**

Wenn Du möchstest, kannst Du der Episode ein Bild verpassen. Gib dazu den kompletten Link an. Gibst Du das nicht an, wird das Logo des Podcasts aus dem Feed genutzt.

**date**

Optional das Veröffentlichungsdatum der Episode in der Notation `YYYY-MM-DD HH:MM:SS`. Wird das Datum weggelassen, erzeugt sich das Veröffentlichungsdatum aus dem Datum der letzten Änderung der Konfigurationsdatei.

Wird ein Datum in der Zukunft (relativ zur Uhrzeit des Servers) gesetzt, wird die Episode ignoriert und erst nach Erreichen dieser Uhrzeit angezeigt.

**noaudio**

Vielleicht passiert es einmal, dass Du einen Artikel auf der Webseite haben möchtest, der keine Episode Deines Podcasts ist. Du möchstest z.B. eine Urlaubspause verkünden. Dann gib hier `on` an, damit die entsprechende .epi-Datei nicht ignoriert wird.

**location**

tbw (to be written)

#Die Formate

Hier folgt eine Liste der unterstützten Formate. Unterstützt heißt nun nicht, dass im Player auf der Webseite alle diese Formate angezeigt oder abgespielt werden können. Das heißt an der Stelle nur, dass der firtz weiß, dass es sich bei diesem Format um eines handelt, das einen Feed erzeugen kann. Die Formate sind auch die slugs für alle Angaben z.B. im Feed.

Aber auch in den URLs werden Dir diese Slugs jedes mal wieder begegnen. In Klammern stelle ich sicherheitshalber dahinter, ob es sich um Audio oder Video handelt. Ein paar Formate sind beides nicht...

* mp3 (audio)
* mpg (video)
* m4a (audio)
* m4v (video)
* oga (audio)
* ogg (audio)
* ogv (video)
* webma (audio)
* webm (video)
* flac (audio)
* opus (audio)
* mka (audio)
* mkv (video)
* pdf
* epub (ebook)
* mobi (ebook)
* png
* jpg
* torrent (und nochmal: bitte nicht mit der bitlove-Option verwechseln!)
