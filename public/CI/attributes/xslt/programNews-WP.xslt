<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
<!-- 2014-01-22 For Program Page News -->
<xsl:template match="/rss/channel">
	<h2>News</h2>
	<xsl:for-each select="item">
		<xsl:if test="position() &lt;= 5">
			<xsl:call-template name="News" />
		</xsl:if>
	</xsl:for-each>
    <h3><a href="http://news.cehd.umn.edu/category/departments/curriculum-and-instruction/">See all Curriculum &amp; Instruction News...</a></h3>
	<br />
</xsl:template>

<xsl:template match="item" name="News">
	<xsl:choose>
        <xsl:when test="position()=1">
            <h3 class="news1"><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute><xsl:value-of select="title" disable-output-escaping="yes" /></a></h3>
        </xsl:when>
        <xsl:otherwise>
            <h3><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute><xsl:value-of select="title" disable-output-escaping="yes" /></a></h3>
        </xsl:otherwise>
    </xsl:choose>
	<p><xsl:value-of select="description" disable-output-escaping="yes" /></p>
  	<p class="more-link"><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute>More</a></p>  	
</xsl:template>
</xsl:stylesheet>