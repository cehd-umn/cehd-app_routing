<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
<!-- 2010-01-04 For HomePage News List -->
<xsl:template match="/rss/channel">
<h2>News</h2>
	<xsl:for-each select="item">
     		<xsl:variable name="NumberOfItems" select="6" />
			<xsl:if test="position() &lt;= $NumberOfItems">
			<xsl:choose>
				<xsl:when test="position()=1">
					<div class="HPNewsItem"><h3><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute><xsl:value-of select="title" disable-output-escaping="yes" /></a></h3>
                <img title="Read the article">
				<xsl:attribute name="src">
				<xsl:value-of select='thumbnail/span/a/@href' />
				</xsl:attribute>
				<xsl:attribute name="alt">
				<xsl:value-of select='title' />
				</xsl:attribute>
				</img>
</div>
				</xsl:when>
				<xsl:otherwise>
  					<div class="HPNewsItem"><h3><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute><xsl:value-of select="title" disable-output-escaping="yes" /></a></h3><img title="Read the article">
				<xsl:attribute name="src">
				<xsl:value-of select='thumbnail/span/a/@href' />
				</xsl:attribute>
				<xsl:attribute name="alt">
				<xsl:value-of select='title' />
				</xsl:attribute>
				</img></div>
  				</xsl:otherwise>
  			</xsl:choose>
 	 		</xsl:if>
	</xsl:for-each>
   </xsl:template>
</xsl:stylesheet>
