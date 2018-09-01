
/**
*  Disqus
*/
if(disqus_short_name != '') {
	var disqus_config = function () {
		this.page.url 			= site_url+'/'+uri_seg_1+'/'+uri_seg_2;  // Replace PAGE_URL with your page's canonical URL variable
		this.page.identifier 	= uri_seg_2; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
		this.page.title 		= uri_seg_2; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
	};
	(function() { // DON'T EDIT BELOW THIS LINE
	var d = document, s = d.createElement('script');
	s.src = 'https://'+disqus_short_name+'.disqus.com/embed.js';
	s.setAttribute('data-timestamp', +new Date());
	(d.head || d.body).appendChild(s);
	})();
}