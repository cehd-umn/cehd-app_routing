
<footer id="deptfoot">
	<address id="contactblock" itemscope itemtype="http://schema.org/Organization">
		<span class="breaker" itemprop="name">Department of Educational Psychology</span>
     		<div id="postal" itemscope itemprop="Address" itemtype="http://schema.org/PostalAddress">
				<p><span class="breaker" itemprop="streetAddress">Education Sciences Building 56 East River Road </span>Minneapolis, MN 55455<span class="breaker" ><span itemprop="postalCode"></span></span></p>
			</div>
   		<span id="telecom" itemscope itemtype="http://schema.org/ContactPoint">P: 612-624-6083 | F: 612-624-8241</span>
	</address>
  <ul id="footerlinks">
  	<li><a href="http://www1.umn.edu/pts/">Parking &amp; Transportation</a></li>
  	<li><a href="http://campusmaps.umn.edu/tc/">Maps &amp; Directions</a></li>
  	<li><a href="/directory/">College Directory</a></li>
  	<li><a href="/">CEHD</a></li>
  	<li><a href="http://www.umn.edu">U of M</a></li>
  </ul>
</footer>
<footer id="umnfoot">
	<p><%response.write("&copy;"&Year(Date))%> Regents of the University of Minnesota. All rights reserved. 
		The University of Minnesota is an equal opportunity educator and employer. 
		<br /><a href="/privacy/">Privacy Plolicy</a> | Last Modified on 
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