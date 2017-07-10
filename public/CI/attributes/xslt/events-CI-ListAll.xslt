<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
<xsl:template match="/rss/channel">
<xsl:for-each select="item">
	
	<h2><xsl:value-of select="title" disable-output-escaping="yes"/></h2>
  <p class="Feed-Date">
  	<xsl:value-of select="dateFull" disable-output-escaping="yes"/><br />
    <xsl:if test="startTime!=''">
				<xsl:value-of select="startTime" />
		</xsl:if>
		<xsl:if test="endTime!=''">
				&#8211; <xsl:value-of select="endTime" />
		</xsl:if>
		<xsl:choose>
			<xsl:when test="location!='' and locationURL!=''">
      <xsl:text> </xsl:text>
					<a><xsl:attribute name="href"><xsl:value-of select="locationURL"  disable-output-escaping="yes"/></xsl:attribute>
						<xsl:value-of select="location" disable-output-escaping="yes" />
					</a>	
			</xsl:when>
			
			<xsl:when test="location!=''">
      <xsl:text> </xsl:text>
					<xsl:value-of select="location" disable-output-escaping="yes" />
			</xsl:when>
			
			<xsl:otherwise>
			</xsl:otherwise>
	</xsl:choose>
  </p>
	<xsl:value-of select="description" disable-output-escaping="yes" />
</xsl:for-each>
</xsl:template>
</xsl:stylesheet>
