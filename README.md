#firtz


*firtz podcast publisher*  
*Version 1.3*

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

Du brauchst irgendwo Platz im Web. Das ist dann doch noch nötig. Dieser Platz im Web muss im Grunde nur eine Bedingung erfüllen: **php ab Version 5.3** muss ausgeführt werden können. 

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

`attribute:  
value`

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

Für jede Episode gibt's dann einen eigenen [Podlove Webplayer](https://github.com/gerritvanaaken/podlove-web-player), [flattr](http://flattr.com)-Buttons und auch einen [disqus](http://disqus.com)-Thread, so denn disqus in der Konfiguration des Feeds angegeben wurde.

Einzelne Episoden sind auch verlinkbar, indem an den Pfad der Slug der Episode (praktisch der Dateiname ohne Endung) angehangen wird: `http://tollerneuerpodcst.de/demo/show/001`.

## Kontakt

Melde Dich. Entweder per [Mail](mailto:info@hoersuppe.de), auf [Twitter](https://twitter.com/the_firtz) oder [app.net](https://alpha.app.net/firtz) oder natürlich hier auf github in den issues.

## Links

Ich habe firtz mit [fatfree framework](https://github.com/bcosca/fatfree) und [bootstrap](http://twitter.github.com/bootstrap/) zusammengeschraubt.

Weiterhin hilft der [Podlove Webplayer](https://github.com/gerritvanaaken/podlove-web-player) massiv dazu bei, dass alles halbwegs hübsch aussieht :)

[Stefan Thesing](https://alpha.app.net/hirnbloggade) hat einen knackigen [Artikel](http://www.hirnbloggade.de/2013/04/26/der-firtz-und-ich/) verfasst, in dem er seine Installationserfahrungen niedergeschrieben hat. Vielleicht kann Euch das weiterhelfen.

# English Version


*firtz podcast publisher*  
*Version 1.3*

## About

You obviously have the urge to test this stuff or to create a new Podcast. Whatever your intention is to use firtz: It's a good one.

For publishing a Podcast actually two things are necessary: audio files on the web and somewhere a XML file which describes the feed. Everything else is more or less a nice to have which requires additional work. 

But why investing so much work and dragging a lot of ballast? Why using Wordpress when you publish every two months. Why a SQL Database for five Articles a year? Why worrying about the installation of plugins when a couple KBytes of XML are enough? 

There is a second aspect. During my daily work with the [Hörsuppe](http://hoersuppe.de) where I blog and podcast about german Podcasts, I'm more or less confronted with Podcasts which put a lot of effort into the spoken word but fail with problems during the publishing process.

This ends up with broken feeds. Feeds where important metadata about the audio files is missing and it is not impossible but hard to subscribe the feed.

Beyond that it is a relieve for the Hörsuppe when all metadata is correct.

firtz should help to decrease the pressure about technical issues. 

That's why firtz. firtz is the result of 1.5 days of "work" which I dedicated for producing something which works more or less. 

So how is it working? With configuration files. One for the feed with general data and one file for each episode with its details. 

If you want to have an easier life you should use auphonic and let process firtz all files which generates one or more feeds. It just could be easier if auphonic would provide it on its own. 


## Requirements 

But let's get started by the beginning. What do you need? 

You need space somewhere on the net. This space just has to fulfil one requirement to execute **php greater than 5.3**

You don't need databases or stuff like that however the user of the web server needs permissions to create directories and write files. 

If you want to use firtz in subdirectories of your domain you need to adjust the RewriteBase of the .htaccess file from: 

`RewriteBase /`

to

`RewriteBase /UNTERORDNER`

To address problems with write-access to the temporary folder, you might want to add this line to .htaccess:

`AddHandler php5-cgi .php`

Don't do that unless you have error messages stating any problems with writing to **tmp/**.

## Let's go

You're downloading the [firtz archive](https://github.com/eazyliving/firtz/) extract it in your destination directory on the web server and change to the `feeds` directory. 

In this location is a demo directory listed. The name of this directory is the future name of your feed. 

The demo directory contains two files: `feed.cfg` and `001.epi`. The configuration syntax of those files is the same. 

`#: is a comment don't forget the ":"`

Attributes are noted like the following: 

`attribute: 
value`

Please keep attention that the attribute lines are always stand alone. Blank lines are ignored unless it is a textfield like the summary. 

At the end of the configuration file you can insert a new line with 

`---end---`

Everything below is ignored you can write down notes or trash. Just take a look into the files and play a little bit around. 

**It It important that all files are UTF-8 encoded otherwise you create weird and crazy feeds!** 

When you're using auphonic (http://auphonic.com/) you probably have a much easier life. Activate the Production-Description in your outgoing files make sure that firtz finds them and the episodes are created almost automatically thanks to the metadata passed by auphonic. Additional information can be found in the documentation.

Lets assume you're ready. How do you access the feed? We suppose that the URL of your web server is `http://mynewsuperawesomepodcast.de/` . The feed is still called demo so the RSS2 URL would be: 

`http://mynewsuperawesomepodcast.de/feed`

When you're using several audio formats you also can use:

`http://mynewsuperawesomepodcast.de/feed/mp3`

As a bonus a website will be generated. For this feed you can access it via: `mynewsuperawesomepodcast.de/feed/show`. Each episode has its own [Podlove Webplayer](https://github.com/gerritvanaaken/podlove-web-player), [flattr](http://flattr.com) buttons and a [disqus](http://disqus.com) thread as long as it is specified in the configuration of the feed. 
Single episodes are also linkable just by adding the slug of the episode (the file name without the suffix): 
`mynewsuperawesomepodcast.de/demo/show/001`

## Contact

Contact me. Either via [Mail](mailto:info@hoersuppe.de), at [Twitter](https://twitter.com/the_firtz) or [app.net](https://alpha.app.net/firtz) and of course here at github at the issues section. 

## Links 

I build firtz with [fatfree framework](https://github.com/bcosca/fatfree) and [bootstrap](http://twitter.github.com/bootstrap/).

Additionaly the [Podlove Webplayer](https://github.com/gerritvanaaken/podlove-web-player) helps that everything looks nicely :)

[Stefan Thesing](https://alpha.app.net/hirnbloggade) wrote an [article](http://www.hirnbloggade.de/2013/04/26/der-firtz-und-ich/), describing his procedure of installing firtz. Maybe you can find some helpful hints there. Sorry, german only :-)
