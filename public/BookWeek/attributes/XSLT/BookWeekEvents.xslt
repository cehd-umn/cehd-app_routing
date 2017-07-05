<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" omit-xml-declaration="yes" />
	<xsl:template match="/rss/channel">
		<ul>
			<xsl:for-each select="item">
				<li>
					<xsl:if test="@sequenceByDate='1'">
					<xsl:value-of select="dateMD" disable-output-escaping="yes" />, <xsl:value-of select="dateYear" disable-output-escaping="yes" />
					<br/></xsl:if>

	
	<xsl:choose>
		<xsl:when test="substring(title,1,11)='Book Week: '">
		<a>
              <xsl:attribute name="href">
                  <xsl:value-of select="link" />
               </xsl:attribute>
               <p><xsl:value-of select="startTime" disable-output-escaping="yes" />
              <xsl:text> - </xsl:text> 
 <xsl:value-of select="substring(title,12)" disable-output-escaping="yes"  />
        </p></a>					
		</xsl:when>
	<xsl:otherwise>
		<a>
               <xsl:attribute name="href">
                  <xsl:value-of select="link" />
               </xsl:attribute>
             <p><xsl:value-of select="startTime" disable-output-escaping="yes" />  
             <xsl:text> </xsl:text><br/>
             <xsl:value-of select="title" disable-output-escaping="yes"  />
            </p></a>	
    </xsl:otherwise>
       </xsl:choose>     
			</li>
			</xsl:for-each>
		</ul>
	</xsl:template>
</xsl:stylesheet>
