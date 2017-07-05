<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
   <xsl:template match="/rss/channel">
     <div id="CourseFeed">
     <ul>
     <xsl:for-each select="item">
	     <xsl:if test="string(count)='1'">
	     	<xsl:choose>
	     	<xsl:when test="string(MaySession)='true'">
		     	<li class="CourseTitle"><h2>May Session Courses<br />
		     	<span class="h-override-black">May 28th - June 14th</span></h2></li>
	     	</xsl:when>
	     	<xsl:otherwise>
	     		<li class="CourseTitle"><h2><xsl:value-of select="termDescr" disable-output-escaping="yes"  /></h2></li>
	     	</xsl:otherwise>
	     	</xsl:choose>
	     </xsl:if>
			<li>
				<xsl:value-of select="subject" disable-output-escaping="yes"  />
				<xsl:text> </xsl:text>
				<xsl:value-of select="catalog_nbr" disable-output-escaping="yes"  />
				<xsl:value-of select="mdash" disable-output-escaping="yes"  />
				<a><xsl:attribute name="href"><xsl:value-of select="link" /></xsl:attribute>
				<xsl:value-of select="description" disable-output-escaping="yes"  /></a>
					<xsl:if test="string(deliveryMethod)='online'">
	     				<em><xsl:text> online course</xsl:text></em>
	     			</xsl:if>
			</li>
	      </xsl:for-each>
      </ul>
      </div>
   </xsl:template>
</xsl:stylesheet>

