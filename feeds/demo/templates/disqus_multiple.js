<script>
	var disqus_shortname = '{{@feedattr.disqus}}';
	var disqus_identifier; //made of post id and guid
	var disqus_url; //post permalink

	function loadDisqus(source, identifier, url) {

	if (window.DISQUS) {

	   jQuery('#disqus_thread').insertAfter(source); //append the HTML after the link

	   //if Disqus exists, call it's reset method with new parameters
	   DISQUS.reset({
		  reload: true,
		  config: function () {
		  this.page.identifier = identifier;
		  this.page.url = url;
		  }
	   });

	} else {

	   //insert a wrapper in HTML after the relevant "show comments" link
	   jQuery('<div id="disqus_thread"></div>').insertAfter(source);
	   disqus_identifier = identifier; //set the identifier argument
	   disqus_url = url; //set the permalink argument

	   //append the Disqus embed script to HTML
	   var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	   dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
	   jQuery('head').append(dsq);

	}
	};
	</script>