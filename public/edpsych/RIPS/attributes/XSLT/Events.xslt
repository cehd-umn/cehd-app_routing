<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
   <xsl:template match="/rss/channel">
   <!-- TEST IF THE FEED HAS NO EVENTS --> 
   <xsl:choose>
     <xsl:when test="string(item/title)='There are no events listed for your selection'">
     <p>Sorry, we don't have any upcoming events.<br />Please check again later!</p>
     </xsl:when>
     <xsl:otherwise>
     <!-- Huzaaah! There are events! --> 

	<xsl:for-each select="item">
  			<h2 class="EventTitle"><xsl:value-of select="title"/><br />
  			<span class="h-override-black"><xsl:value-of select="dateFull" disable-output-escaping="yes"  />
  			</span></h2>
  			<p class="time"><xsl:value-of select="startTime" />
			<xsl:if test="endTime!=''">
				&#8211; <xsl:value-of select="endTime" />
			</xsl:if>
			</p>	
			<xsl:if test="description!=''">
			<xsl:value-of select="description" disable-output-escaping="yes"  />
			</xsl:if>
			<xsl:if test="extended!=''">
			<xsl:value-of select="extended" disable-output-escaping="yes"  />
			</xsl:if>
			<xsl:choose>
			<xsl:when test="location!='' and locationURL!=''">
				<p>Location: 
					<a><xsl:attribute name="href"><xsl:value-of select="locationURL"  disable-output-escaping="yes"/></xsl:attribute>
							<xsl:value-of select="location" disable-output-escaping="yes" />
							</a>	
						</p>
					</xsl:when>
					<xsl:when test="location!=''">
						<p>Location: 
							<xsl:value-of select="location" disable-output-escaping="yes" />
						</p>
					</xsl:when>
					<xsl:otherwise>
					</xsl:otherwise>
				</xsl:choose>
	</xsl:for-each>
	</xsl:otherwise>
	</xsl:choose>
	
   </xsl:template>
</xsl:stylesheet>