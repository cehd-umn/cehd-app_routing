<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>

<xsl:template match="/rss/channel">
<div id="News">
	<xsl:for-each select="item">
		<xsl:if test="position()&lt;=10">
        	<xsl:call-template name="Basketball" />
        </xsl:if>
	</xsl:for-each>
</div>
</xsl:template>

<xsl:template match="item" name="Basketball">
	<article>
		<h3>
        	<a>
            <xsl:attribute name="href">
            	<xsl:value-of select="link" />
            </xsl:attribute>
            	<xsl:value-of select="title" disable-output-escaping="yes" />
        	</a>
            <br />
            <span><xsl:value-of select="content:prettyDate" disable-output-escaping="yes" /></span>
        </h3>
        <p><xsl:value-of select="description" disable-output-escaping="yes" /></p>
	</article>
</xsl:template>

</xsl:stylesheet>