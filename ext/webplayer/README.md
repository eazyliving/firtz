### About Podlove Webplayer 4 (Beta) Extension

Diese Erweiterung erzeugt einen Webplayer mit Sharing Funktionen, zum Einbinden des Audioplayers als iframe.
Er ist Teil des Projekts Podlove Webplayer und steht unter der MIT Lizenz.


#### Endpoints:

Entpunkte sind URL Pfade deines firtz, die unterschiedliche Daten bereit stellen, damit der Webplayer auch angezeigt werden kann.


**Playerdaten einer Episode:**

Zum Sharen (verbreiten und einbinden als iframe) benötigt der Webplayer die Episodendaten im JSON Format.
Er bekommt sie über den folgenden URL Pfad:

<pre>http://www.democast.tld/demo/share/004</pre>


**Share Endpunkt:**

Um den Webplayer als iFrame einbinden zu können, benötigt der Player die Episodendaten und weitere Informationen, wie etwa die Größe und Breite des iFrames.
Der Endpunkt baut sich wie folgt zusammen:

<pre>http://www.democast.tld/share?episode=http://www.democast.tld/demo/share/004</pre>


#### Einbinden eines Players:

Der Player generiert dir automatisch ein iFrame mit festen URL Daten. Klicke auf "Share". Unter
"Embed" kannst Du die Größe bestimmen und das iFrame durch klicken auf "Copy" in den Zwischenspeicher laden.

Ein solch generiertes iFrame sieht dann etwa so aus:

    &lt;iframe width="768" height="290"
    src="http://www.democast.tld/share?episode=http://www.democast.tld/demo/share/004"
    frameborder="0" scrolling="no"></iframe>


**Beispiel: iFrame**

<iframe width="768" height="290" src="/share?episode=/demo/share/004" frameborder="0" scrolling="no"></iframe>


##### Links:

- Website: [Podlove Webplayer Version 4](https://podlove.org/podlove-web-player/)
- Github: [firtz extension: podlove-webplayer4](https://github.com/Firtz-Designs/QuorX-III)