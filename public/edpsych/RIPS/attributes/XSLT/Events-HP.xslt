<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
   <xsl:template match="/rss/channel">
   	<!-- TEST IF THE FEED HAS NO EVENTS --> 
        <xsl:choose>
     		<xsl:when test="string(item/title)='There are no events listed for your selection'"></xsl:when>
     	<xsl:otherwise>
     <!-- Huzaaah! There are events! --> 
   <h1>Upcoming Events</h1>
     <xsl:for-each select="item">
  			<h2 style="margin-bottom:0"><xsl:value-of select="title"/></h2>
  			<p class="time">
  			<a href="/edpsych/rips/events.html"><xsl:value-of select="dateFull" disable-output-escaping="yes"  /></a>
  			</p>
			
	     </xsl:for-each>
	     </xsl:otherwise>
		</xsl:choose>
   </xsl:template>
</xsl:stylesheet>