<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" omit-xml-declaration="yes" />
	
<xsl:template match="/rss/channel">

		<xsl:for-each select="item">
		<div class="CalEvent">
				
		<h2><xsl:attribute name="id"><xsl:value-of select="concat('z=',occurrenceId)" /></xsl:attribute>
			
		<xsl:choose>
		<xsl:when test="substring(title,1,11)='Book Week: '">
	 <xsl:value-of select="substring(title,12)" disable-output-escaping="yes"  />
   				
		</xsl:when>
	<xsl:otherwise>
	    <xsl:value-of select="title" disable-output-escaping="yes"  />
    </xsl:otherwise>
       </xsl:choose>     
		</h2>
			
		<p class="CalDateTime">
			<xsl:if test="startTime!=''">
				<xsl:value-of select="startTime" />
			</xsl:if>
			<xsl:if test="endTime!=''">
				&#8211; <xsl:value-of select="endTime" />
			</xsl:if>
		<xsl:choose>
			<xsl:when test="location!='' and locationURL!=''">
 	<span style="margin-left: 30px"> 
					<a><xsl:attribute name="href"><xsl:value-of select="locationURL"  disable-output-escaping="yes"/></xsl:attribute>
						<xsl:value-of select="location" disable-output-escaping="yes" />
					</a></span>	
			</xsl:when>
			
			<xsl:when test="location!=''">
				<span style="margin-left: 30px"> 
									<xsl:value-of select="location" disable-output-escaping="yes" />
					</span>	
			</xsl:when>
			
			<xsl:otherwise>
			</xsl:otherwise>
			</xsl:choose>
		</p>
	

		<xsl:value-of select="description" disable-output-escaping="yes" />
		

		<xsl:if test="relatedEvents!=''">
			<p>Related events: 
				<a><xsl:attribute name="href"><xsl:value-of select="relatedEventsURL" /></xsl:attribute>
					<xsl:value-of select="relatedEvents" disable-output-escaping="yes" />
				</a>
			</p>
		</xsl:if>
	
				</div>
		</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>