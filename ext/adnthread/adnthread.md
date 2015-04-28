ADN-Threads How-To firtz:

Im firtz können nun ADN-Threads als Kommentare angezeigt werden.
Dazu benötigt es zwei Dinge: Ein Token für App.net und natürlich die ID desjenigen Threads.

Es handelt sich nicht! um ein komplettes Kommentarsystem, das im firtz eigenständig nutzbar ist. Es handelt sich lediglich darum, dass ein Thread angezeigt wird und mit Klick auf die alpha-Seite zum Kommentieren verlinkt wird.

Also zurück. Das Token bekommt Ihr, wenn Ihr Entwickler-Status bei ADN habt. Ihr legt dann eine App an und holt Euch ein Token. AAAABER, es geht auch billiger: Auf [http://dev-lite.jonathonduerig.com/](http://dev-lite.jonathonduerig.com/) gibt es lite-Token, die Ihr dafür auch nutzen könnt und die keinen Cent kosten.

Das Token muss nicht viel können, "basic" Scope reicht und sollte auch nicht mehr sein an der Stelle. Sicher ist sicher... Das Token wandert in die ext.cfg mit dem Attribut "adntoken:". Die ThreadID müsst Ihr dann bei alpha rausfriemeln. Kündigt eine Episode an, klickt in alpha auf die Zeitanzeige zum Post (das mit der Uhr) und holt die ID aus der URL: [https://alpha.app.net/firtz/post/6451482](https://alpha.app.net/firtz/post/6451482)

Ratet mal, was die ID ist ;-) Die wandert dann in die Episode unter "adnthread:". Das bedeutet gleichzeitig, dass Ihr im auphonic-mode: full oder episode nutzen müsst, denn die ThreadID werdet Ihr per auphonic nicht ins System bekommen, die muss dann über eine entsprechende .epi-Datei rein.

Das war's auch schon. Bei Fragen wendet Euch an den [firtz](https://alpha.app.net/firtz)!
