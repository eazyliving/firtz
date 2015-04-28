<?php
/**
* Easy Search Redirect GeHackE
*/


# Infos für den Christian:
# --------------------------
# {{@BASEURL}}{{@feedattr.slug}} muss irgend wie aus php hier übergeben werden :)
# dann noch "irgend ein hook zeugs" um es in der site.html auszugeben, was in der 
# => /head/search.html steht.

# Info: Ist jedoch erst einmal in der site.html als form eingearbeitet!
# Siehe dazu Zeile 242: in der site.html!


//der Popel-Quasten redirect hier, macht das Enter über Web möglich :-O 
//(Jedoch "noch nicht" über phone/tablets - kommt noch!)
if ( isset( $_GET["s"] ) && !empty( $_GET["s"] ) && isset( $_GET["r"] ) && !empty( $_GET["r"] ) ) {

	$tag = @$_GET["s"]; #laber%20rababer
	
	# hier muss noch <https://domain.tld> und <feedname> über php firtz dings vars, ausgelesen werden.
	# dann muss man nicht mehr die gesamte url im formular mitschleifen.
	$url = @$_GET["r"]; #<https://domain.tld>/<feedname>/show/search/ 

	header('location: '.$url. '/show/search/'.$tag.'');
}

# Das oder was noch so einem einfallen kann, was nicht über ein js ausgeführt werden muss.
?>