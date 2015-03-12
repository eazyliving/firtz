#Extensions für den firtz2

Um den firtz zu erweitern, gibt es bereits seit einer der ersten Versionen ein System für Extensions.

Mit Version 2 wurde dieses System aufgebohrt. Es ist nun fast an allen Stellen möglich, das Aussehen und die Ausgabe des firtz zu erweitern.

Was in der aktuellen Version noch nicht gut möglich ist, ist das _Verändern_ der Ausgabe, in dem Sinne, standardmäßige Ausgaben zu modifizieren.

Was aber tut es denn nun? Fangen wir vorne an:

Es gibt im Wesentlichen dreieinhalb Wege, in die Ausgabe des firtz hineinzugreifen (ohne die Standardinstallation zu modifizieren):

1. Child-Templates ergänzen und überschreiben das Standardtemplate des firtz 

2. Templatehooks ermöglichen, die Ausgabe der Webseite oder des Feeds an bestimmten Stellen durch Template-Snippets zu ergänzen

3. Scripthooksin ermöglichen, die Ausgabe der Webseite oder des Feeds an bestimmten Stellen durch php-Skripte zu ergänzen

4. Ein komplett neues Template erzeugen, das über einen bestimmten URL aufgerufen wird. Hier erwähne ich gerne die ATOM-Extension, die über http://supicast.de/sc/atom/mp3 z.B. den mp3-Feed als ATOM ausgibt.




Das ist weniger kompliziert, als es sich anhört. Es gilt nur, sich an ein paar Regeln zu halten, vor allem wenn es um die Hooks geht, die Namenskonventionen entsprechen müssen, um gefunden zu werden.

Fangen wir mit der einfachsten Methode an: 

##Child-Templates

Der Standardordner des Templates ist unter *templates/default/* zu finden. Nun kann man durchaus an Ort und Stelle Änderungen vornehmen. Beim nächsten Update des firtz ist das dann aber wieder weg, wenn man nicht vorher ein Backup macht.
Besser ist es, im Ordner *templates/* einen zweiten Ordner anzulegen, nennen wir ihn *child*. In der *feed.cfg* muss dieses Template nun mittels des Attributes ´template:´ angemeldet werden.

Jede Datei, die in diesem Ordner zu finden ist, hat höhere Priorität als eine gleichnamige Datei im Standardordner. 

##Templatehooks

Innerhalb des Standardtemplates (site.html) wird an bestimmten Stellen Dateien inkludiert, die in besonderen Unterordnern des template-Ordners liegen. Zu jeder Episode z.B. wird in den Ordner *episodes/* geschaut und dort liegende HTML-Dateien inkludiert.
Solche Hooks gibt es für folgende Punkte:

*head/*

Diese Dateien werden in den Kopf der HTML-Datei inkludiert, also zwischen &lt;head&gt; und &lt;/head&gt;.

*header/*

Diese Dateien werden in den Kopf der Seite inkludiert.

*episode/*

Diese Dateien werden hinter jede Episode inkludiert.

*sidebar/*

Diese Dateien werden in die Seitenleiste inkludiert.

*footer/*

Diese Dateien werden in den Fuß der Seite inkludiert.

*foot/*

Diese Dateien werden in den Fuß der HTML-Seite, also kurz vor dem &lt;/body&gt;-Tag inkludiert.

Inkludiert werden .html-Dateien. In diesen Dateien können direkte Templateanweisungen vorkommen. Außerdem werden .js-Dateien inkludiert, allerdings nicht direkt in den Quelltext wie .html, sondern per script-Anweisung. Wenn javascript direkt in den Quelltext hineingelangen soll, dann inkludiert ihn in einer html-Datei mit script-Tags.
