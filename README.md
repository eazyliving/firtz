firtz
=====

firtz podcast publisher

firtz 0.9

Podcastpublishing mit EDLIN

+++ ACHTUNG +++ 

Das hier ist unterhalb 1.0. Könnte man beta nennen, klingt nur doof heutzutage.
Solltest Du damit einen Podcast veröffentlichen wollen: Sei gewarnt, es wird schmerzhaft, denn ich garantiere Dir nicht, dass die Handhabung bleibt wie sie ist.
Ordnernamen, Ordnerorte, Dateinamen, Formate können und werden sich ändern. Solltest Du nicht experiementierfreudig sein, warte lieber noch ein oder zwei Wochen oder frag mich ruhig persönlich.

+++ ACHTUNG +++

Du hast offensichtlich den Drang, entweder diesen Kram hier auszuprobieren, oder einen Podcast aus der Taufe zu heben.
Was auch immer Dich dazu bewogen hat, firtz auszuprobieren: Gute Idee :)

Worum geht es?
Um einen Podcast zu veröffentlichen, reichen im Grunde zwei Dinge: Audiodateien im Web und irgendwo eine XML-Datei, die den Feed beschreibt. Alles andere ist Beiwerk, das mehr oder minder nötig und arbeitsintensiv ist.

Warum aber an vielen Stellen so viel Arbeit machen, so viel Ballast herumschleppen? Wieso ein Wordpress, wenn man alle zwei Monate mal was veröffentlicht? Wieso eine SQL-Datenbank für fünf Artikel in einem Jahr? Wieso sich den Kopf zerbrechen über die Installation diverser Plugins, wenn es ein paar KByte XML tun?

Deshalb firtz. firtz ist das Ergebnis von knapp einskommafünf Tagen "Arbeit", die ich eigentlich nur gemacht habe, um mal wieder irgendwas zusammen zu schrauben, das halbwegs funktioniert.

Wie funktioniert das nun? Über Konfigurationsdateien. Eine für den Feed mit allgemeinen Daten und einer Datei für jede Episode mit den Details eben jener.

Aber fangen wir vorne an. Was brauchst Du? Du brauchst irgendwo Platz im Web. Das ist dann doch noch nötig... ;)
Dieser Platz im Web muss im Grunde nur eine Bedingung erfüllen: php muss ausgeführt werden können.
Datenbanken und anderen Kram braucht nicht, allerdings muss der ausführende Nutzer des Webservers in der Lage, Verzeichnisse zu erzeugen und Dateien in diese hineinzuschreiben.

Du besorgst Dir das firtz-Archiv (https://github.com/eazyliving/firtz/archive/master.zip), entpackst es in den Ordner, in dem Du das auf dem Webserver haben willst und bewegst Dich darauf hin in den Unterordner feeds/.
In diesem findest Du ein Demo-Verzeichnis. Der Name dieses Ordners, ist der zukünftige Name Deines Feeds.

Im Demo-Ordner sind zwei Dateien: feed.cfg und 001.epi. Das nicht syntaktisch zu nennende Prinzip dieser Dateien ist identisch:

\#: ist ein Kommentar nicht den ":" vergessen!

Attribute werden wie folgt notiert:

attribute:  
value

Achtet bitte darauf, dass die Attribut-Zeile alleine steht. Leerzeilen werden ignoriert, es sei denn, es geht um eine Textfeld wie summary.

An's Ende der Konfiguration kannst Du in einer neuen Zeile ein "---end---" setzen, alles dahinter wird ignoriert. Du kannst da Notizen oder Müll unterbringen ;) Ich werde das hier im Moment noch nicht weiter ausbreiten. Schau in die Dateien und spiel damit rum.

WICHTIG IST, DASS ALLE KONFIGURATIONSDATEIEN UTF8 SIND!

Wenn Du nicht aufpasst, gibt's Müsli im Feed!

Solltest Du Auphonic (http://auphonic.com/) nutzen, bist Du vermutlich ganz fein raus. Aktiviere in den Ausgangsdateien die Production-Description, sorge dafür, dass firtz sie im Dateisystem findet und schon erstellen sich die Folgen fast von selbst anhand der von Auphonic weitergereichten Metadaten. Weitere Informationen dazu finden sich in der Dokumentation.

Nehmen wir an, Du hast das soweit fertig. Wie erreichst du den Feed? Angenommen der URL zu Deinem Webserver würde http://tollerneuerpodcast.de/ lauten. Der Feed heißt ja noch demo, also wäre der URL zum RSS2-Feed:

http://tollerneuerpodcast.de/demo/

Wenn Du mehrere Audioformate nutzt, kannst Du auch z.B. http://tollerneuerpodcast.de/demo/mp3 nutzen.

Eine Webseite gibt's ja auch als Bonus. Die kannst Du für diesen Feed so erreichen: http://tollerneuerpodcast.de/demo/show
Für jede Episode gibt's dann einen eigenen Player (podlove webplayer, großartige Sache!), flattr-Buttons und auch ein disqus-Thread, so denn disqus in der Konfiguration des Feeds angegeben wurde.
Einzelne Episoden sind auch verlinkbar, indem an den Pfad der Slug der Episode (praktisch der Dateiname ohne Endung) angehangen wird: http://tollerneuerpodcst.de/demo/show/001

So, das war's für's erste. Denke dran: Du bist im Grunde Betatester. Die 1.0 wird erreicht werden, wenn ich zufrieden bin. Also ~2015.
Es sind jetzt schon features drin, die ich nicht erwähnt habe. Neue sind schon im Kopf, aber nicht ausgeführt. Und Du hast bestimmt auch welche!

Melde Dich. Entweder unter info@hoersuppe.de oder auf Twitter an @the_firtz!

Weil's wichtig ist: Ich habe dieses Zeug mit fatfree framework (https://github.com/bcosca/fatfree) und bootstrap (http://twitter.github.com/bootstrap/) zusammengeschraubt.
Weiterhin hilft der Podlove Webplayer (https://github.com/gerritvanaaken/podlove-web-player) massiv dazu bei, dass alles halbwegs hübsch aussieht :)

English Version
---------------

firtz Podcast publisher

firtz 0.9

Podcast publishing with EDLIN

+++ ATTENTION +++ 

The project is below the 1.0. You could call it beta but that sounds weird today. 
If you want to publish a Podcast with firtz be aware. It's going to be painful because I don't guarantee that the handling will remain the same.
Directory names, locations, file names, formats could be changed. When you're not want to try new things just wait for one or two weeks or ask me. 

+++ ATTENTION +++
You obviously have the urge to test this stuff here or to create a new Podcast. Whatever your intention is to use firtz: It's a good idea.

What is it about? 
For publishing a Podcast actually two things are necessary: audio files on the web and somewhere a XML file which describes the feed. Everything else is more or less a nice to have which requires additional work. 

But why investing so much work and dragging a lot of ballast? Why using Wordpress when you publish every two months. Why a SQL Database for 5 Articles a year? Why worrying about the installation of plugins when a couple KBytes of XML are enough? 

That's why firtz. firtz is the result of 1.5 days of "work" which I dedicated for producing something which works more or less. 

So how is it working? With configuration files. One for the feed with general data and one file for each episode with its details. 

But let's get started from Scratch. What do you need? You need space somewhere on the web. This space requires php. Databases and stuff like that are not required. However the user of the server needs permissions to create and write directories. 

You're downloading the firtz archive (https://github.com/eazyliving/firtz/archive/master.zip) extract it in your destination directory on the web server and change to the feeds directory. In this location is a demo directory listed. The name of this directory is the future name of your feed. 

The demo directory contains two files: feed.cfg and 001.epi. The   configuration syntax of those files is the same. 

\#: is a comment don't forget the ":"

Attributes are noted like the following: 

attribute: 
value

Please keep attention that the attribute lines are always stand alone. Blank lines are ignored unless it is a textfield like the summary. 

At the end of the configuration file you can insert a new line with "---end---". Everything below is ignored you can write down notes or trash. Just take a look into the files and play a little bit around. 

IT IS IMPORTANT THAT ALL CONFIGURATION FILES ARE UTF-8!

When you don't pay attention to it you create crazy and weird feeds. 

When you're using Auphonic (http://auphonic.com/) you probably have a much easier life. Activate the Production-Description in your outgoing files make sure that firtz finds them and the episodes are created almost automatically thanks to the metadata passed by Auphonic. Additional information can be found in the documentation.

Lets assume you're ready. How do you access the feed? We suppose that the URL of your web server is http://mynewsuperawesomepodcast.de/ . The feed is still called demo so the RSS2 URL would be: 

http://mynewsuperawesomepodcast.de/feed

When you're using several audio formats you also can use:

http://mynewsuperawesomepodcast.de/feed/mp3

As a bonus a website will be generated. For this feed you can access it via: mynewsuperawesomepodcast.de/feed/show . Each episode has its own player (podlove web player, awesome stuff!), flattr button and a disqus thread as long as it is specified in the configuration of the feed. 
Single episodes are also linkable just by adding the slug of the episode (the file name without the suffix): 
mynewsuperawesomepodcast.de/demo/show/001

So that's it. Keep in mind you're a beta tester. The 1.0 will be released when I'm glad with it. Around 2015.
I already added features which I didn't mention. New ones are in my mind but not implemented yet. You probably also got some!

Contact me. Either via info@hoersuppe.de or Twitter under @the_firtz

Because it is important. I created firtz with the fatfree framework (https://github.com/bcosca/fatfree) and bootstrap (http://twitter.github.com/bootstrap/)
Furthermore the Podlove Webplayer (https://github.com/gerritvanaaken/podlove-web-player) helps massively that everything looks charming. 
