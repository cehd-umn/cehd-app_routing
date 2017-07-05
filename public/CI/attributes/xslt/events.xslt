<xsl:stylesheet version=”1.0″>
<xsl:output method=”html” omit-xml-declaration=”yes”/>
<xsl:template match=”/rss/channel”>
<!– Start template for RSS Feed –>
<xsl:for-each select=”item”>
<!– Do the following for each item –><p><xsl:value-of select=”dateFull” disable-output-escaping=”yes”/></p>
<p><xsl:value-of select=”title” disable-output-escaping=”yes”/></p></xsl:for-each>
<!– Stop when you run out of items –></xsl:template><!– End template –>
</xsl:stylesheet>
<!– End stylesheet –>
