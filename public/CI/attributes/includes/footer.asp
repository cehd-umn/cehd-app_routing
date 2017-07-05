
<footer>



  <p id="footccee"><%response.write("&copy;"&Year(Date))%> Regents of the University of Minnesota. All rights reserved. The University of Minnesota is an equal opportunity educator and employer. <br />Last Modified on 
			<%

Dim fs,f,myPath

Set fs=Server.CreateObject("Scripting.FileSystemObject")
Set myPath = Request.ServerVariables("PATH_TRANSLATED")
Set f=fs.GetFile(myPath)

Response.Write(formatdatetime(f.DateLastModified, 2))

set f=nothing
set fs=nothing

%></p>
  <ul id="footerlinks">
  	<li><a href="/privacy/">Privacy Policy</a></li>
  	<li><a href="http://www.cehd.umn.edu">CEHD</a></li>
  	<li><a href="http://www.umn.edu">U of M</a></li>
  	<li><a href="http://www1.umn.edu/pts/">Parking and Transportation</a></li>
  	<li><a href="http://campusmaps.umn.edu/tc/">Maps &amp; Directions</a></li>
  	<li><a href="http://www1.umn.edu/systemwide/directories/">Directories</a></li>
  </ul>




</footer>