<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
<!-- 2014-01-24 For HomePage News List -->
<xsl:template match="/rss/channel">
<h2><a href="/news/">News</a></h2>
<xsl:for-each select="item">
    <xsl:if test="position() &lt;= 20">
      <xsl:call-template name="News" />
    </xsl:if>
  </xsl:for-each>
  <p class="HPFeed-More"><a href="http://news.cehd.umn.edu/">More news<span class="DB-3gts"> </span></a></p>
</xsl:template>

<xsl:template match="item" name="News">
    <h3><a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute>
    <xsl:value-of select="title" disable-output-escaping="yes" /></a></h3>
    <p><xsl:value-of select="content:prettyDate" disable-output-escaping="yes" /></p>
    <p><xsl:value-of select="dc:creator" disable-output-escaping="yes" /></p>
    <p><xsl:value-of select="description" disable-output-escaping="yes" /></p>
	
	<ol>
	<xsl:for-each select="category">
		<li><xsl:value-of select="." disable-output-escaping="yes" /></li>
	</xsl:for-each>
	</ol>
</xsl:template>
</xsl:stylesheet>