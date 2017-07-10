
<footer id="deptfoot">
	<address id="contactblock" itemscope itemtype="http://schema.org/Organization">
		<span class="breaker" itemprop="name">Department of Curriculum and Instruction</span>
     		<div id="postal" itemscope itemprop="Address" itemtype="http://schema.org/PostalAddress">
				<span class="breaker" itemprop="streetAddress">125 Peik Hall</span>
				<span class="breaker" itemprop="streetAddress">159 Pillsbury DR SE</span>
				<span class="breaker" ><span itemprop="addressLocality">Minneapolis</span>, <span itemprop="addressRegion">MN</span> <span itemprop="postalCode">55455</span></span>
			</div>
		<span id="emailus"><a href="mailto:CIinfo@umn.edu">Email Curriculum and Instruction</a></span>
		<span id="telecom" itemscope itemtype="http://schema.org/ContactPoint">P: 612.625.4006 Fax: 612.624.8277</span>
	</address>
	<address itemscope itemtype="http://schema.org/Organization">
	</address>
	<address itemscope itemtype="http://schema.org/Organization">
    <a href="https://www.facebook.com/teachereducationumn/" target="_blank"><img src="http://drydock.cehd.umn.edu/ci/assets/images/UMN-social-FACEBOOK-dark_32x32.png" width="32" height="32" alt=""/></a>
    <a href="https://twitter.com/UMN_CI" target="_blank"><img src="http://drydock.cehd.umn.edu/ci/assets/images/UMN-social-TUMBLR_32x32.png" width="32" height="32" alt=""/></a>
	</address>
  <p class="actionbutton"><a href="/ci/Give.html">Give <i class="fa fa-heart fa-1x"></i></a></p>
  <ul id="footerlinks">
  	<li><a href="http://www1.umn.edu/pts/">Parking &amp; Transportation</a></li>
  	<li><a href="http://campusmaps.umn.edu/tc/">Maps &amp; Directions</a></li>
  	<li><a href="/directory/">College Directory</a></li>
  </ul>
</footer>
<footer id="umnfoot">
	<p><%response.write("&copy;"&Year(Date))%> Regents of the University of Minnesota. All rights reserved. 
		The University of Minnesota is an equal opportunity educator and employer. 
		<br />
		<a href="/privacy/">Privacy Policy</a> | Last Modified on 
		<%
			Dim fs,f,myPath
			Set fs=Server.CreateObject("Scripting.FileSystemObject")
			Set myPath = Request.ServerVariables("PATH_TRANSLATED")
			Set f=fs.GetFile(myPath)
			Response.Write(formatdatetime(f.DateLastModified, 2))
			set f=nothing	
			set fs=nothing
		%>
	</p>
</footer>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-6229970-4', 'auto'); // Main Account
	  ga('create', 'UA-6229970-10', 'auto', {'name': 'testProperty'});  // Testing Account.
	  ga('send', 'pageview');
	  ga('testProperty.send', 'pageview'); // Send page view for testing account.
	</script>