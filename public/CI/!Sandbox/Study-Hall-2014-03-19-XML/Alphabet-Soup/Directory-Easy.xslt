<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>

<xsl:key name="Alpha" match="item" use="substring(lastName,1,1)" />

<xsl:template match="/rss/channel">

<div class="third">
	<xsl:for-each select="key('Alpha','A')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>

	<xsl:for-each select="key('Alpha','B')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','C')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','D')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','E')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','F')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
	
	<xsl:for-each select="key('Alpha','G')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	
</div>


<div class="third">
	<xsl:for-each select="key('Alpha','H')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>

	<xsl:for-each select="key('Alpha','I')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','J')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','K')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','L')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
	
	<xsl:for-each select="key('Alpha','M')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
	
	<xsl:for-each select="key('Alpha','N')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
	
</div>

<div class="third">

	<xsl:for-each select="key('Alpha','O')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','P')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','Q')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','R')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','S')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','T')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','U')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','V')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','W')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','X')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','Y')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>
 
	<xsl:for-each select="key('Alpha','Z')">
	     	<xsl:call-template name="List-n-Link" />
	</xsl:for-each>

</div>

</xsl:template>
   
   <xsl:template match="item" name="List-n-Link">

<!-- Check and see if the set has any names in it -->
   <xsl:if test="string(lastName)!=''">

<!-- Write first letter of alphabet to start list -->
   		<xsl:if test="position()=1">
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
	</xsl:if>
   </xsl:template>
</xsl:stylesheet>

