<div id="contactinfo">
  <ul>
    <li>jeff@umn.edu</li>
    <li>G5 Layout - Firefly Design</li>
    <li>202b Burton Hall</li>
    <li>178 Pillsbury Dr. SE</li>
    <li>Minneapolis, MN 55455 USA</li>
    <li>Tel: 612-624-6034</li>
    <li class="last">Fax: 612-600-0000 </li>
  </ul>
</div>
<footer>

<ul class="right">
	<li class="first">CEHD:</li>
	<li><a href="http://www.cehd.umn.edu/">College Homepage</a></li>
	<li class="last"><a href="http://www.cehd.umn.edu/privacy/">Privacy</a></li>
</ul>


<ul class="right L2">
	<li class="first">Twin Cities:</li>
	<li><a href="http://www1.umn.edu/pts/">Parking &amp; Transportation</a></li>
	<li class="last"><a href="http://www.umn.edu/twincities/maps/index.html">Maps &amp; Directions</a></li>
</ul>
<ul class="right L3">
	<li><a href="http://www.directory.umn.edu/">Directories</a></li>
	<li class="last"><a href="http://www.umn.edu/">U of MN</a></li>
</ul>



		<p class="left L1"><%response.write("&copy; "&Year(Date))%> Regents of the University of Minnesota. All rights reserved.
		<br />The University of Minnesota is an equal opportunity educator and employer.</p>
<p class="left L3">Last Modified on 

<%

Dim fs,f,myPath

Set fs=Server.CreateObject("Scripting.FileSystemObject")
Set myPath = Request.ServerVariables("PATH_TRANSLATED")
Set f=fs.GetFile(myPath)

Response.Write(formatdatetime(f.DateLastModified, 2))

set f=nothing
set fs=nothing

%>.</p>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6229970-4");
pageTracker._trackPageview();
} catch(err) {}</script>
</footer>