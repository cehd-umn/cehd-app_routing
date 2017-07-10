<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>

<xsl:template match="/rss/channel">
	<div class="third">
		<xsl:for-each select="item">
		    <xsl:call-template name="List-n-Link" />
			<xsl:if test="position()=30"><h2 style="color:tomato">30 Names</h2></xsl:if>
		    <xsl:if test="position()=60"><h2 style="color:tomato">60 Names</h2></xsl:if>
		    <xsl:if test="position()=90"><h2 style="color:tomato">90 Names</h2></xsl:if>
		</xsl:for-each>
	</div>
</xsl:template>
   
<xsl:template match="item" name="List-n-Link">

<!-- Write first letter of alphabet to start list -->
	<!-- Test if the first letter of the last name is different than the one before -->
   	<xsl:if test="(substring(preceding-sibling::*[1]/lastName, 1, 1) != substring(lastName, 1, 1))">
  	 	<h2><xsl:attribute name="id"><xsl:value-of select="substring(lastName,1,1)" /></xsl:attribute>
  	 		<xsl:value-of select="substring(lastName,1,1)" /></h2>
 	</xsl:if>

<!-- Build out directory listing -->   
   		<p style="margin-left:40px">
			<a>
			<xsl:choose>
			<!-- Check if they have a profile page listed -->
				<xsl:when test="(string(webPage))">
					<xsl:attribute name="href"><xsl:value-of select="webPage" /></xsl:attribute>
				</xsl:when>
				<xsl:otherwise>
					<xsl:attribute name="href"><xsl:value-of select="link" /></xsl:attribute>
				</xsl:otherwise>
			</xsl:choose>
				<xsl:value-of select="lastName" />, <xsl:value-of select="firstName" />
			</a>
			<!-- Check for a working title -->
			<xsl:if test="(string(description))">
				<br /><em><xsl:value-of select="description" /></em>
			</xsl:if>
		</p>
   </xsl:template>
</xsl:stylesheet>

