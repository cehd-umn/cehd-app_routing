<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
<!-- 2013-09-19 CI Programs List -->
<xsl:template match="/rss/channel">
<h2>News</h2>
	<xsl:for-each select="item">
     		<xsl:variable name="NumberOfItems" select="5" />
			<xsl:if test="position() &lt;= $NumberOfItems">
			<xsl:choose>
				<xsl:when test="position()=1">
					<h3 class="news1"><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute><xsl:value-of select="title" disable-output-escaping="yes" /></a></h3>
				</xsl:when>
				<xsl:otherwise>
  					<h3><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute><xsl:value-of select="title" disable-output-escaping="yes" /></a></h3>
  				</xsl:otherwise>
  			</xsl:choose>
  			<p><xsl:value-of select="summary" disable-output-escaping="yes" /></p>
  			<p class="more-link"><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute>More</a></p>
 	 		</xsl:if>
	</xsl:for-each>
	<h3><a href="http://blog.lib.umn.edu/cehd/news/departments/curriculum-instruction/">See all Curriculum &amp; Instruction News...</a></h3>
	<br />
   </xsl:template>
</xsl:stylesheet>
