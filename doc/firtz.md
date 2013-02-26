# firtz podcast publisher

* [Einleitung](#Einleitung)
* [Was braucht es?](#was-braucht-es)
* [Schnellstart](#schnellstart)
* [der Feed](#der-feed)
* [die Episode](#die-episode)
* [Webseite](#webseite)
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

**Ich empfehle Anfängern, die sich noch im Unklaren sind, wie das die nötigen Arbeitsschritte vor der Veröffentlichung aussehen, dringend den Artikel [How to Podcast for Free](https://auphonic.com/blog/2013/02/07/how-to-podcast-for-free/) durchzulesen. Wem der erste grobe Durchblick fehlt, diesen Artikel aber nicht gelesen hat, der braucht auch hier erstmal nicht weiter zu lesen.**

