<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" omit-xml-declaration="yes" />
	<!-- USED WITH PEOPLE TOOL -->
<xsl:template match="/rss/channel">
<xsl:for-each select="item">



<xsl:if test="headshotAddr!=''">
<div class="third">
<p>
	<img class="left">
		<xsl:attribute name="src"><xsl:value-of select= "headshotAddr" /></xsl:attribute>
		<xsl:attribute name="alt"><xsl:value-of select= "lastName" /></xsl:attribute>
	</img>
</p>
</div>
</xsl:if>

<div class="twothird">
<div class="vcard">
	<h1 class="fn"><xsl:value-of select="firstName" /><xsl:text> </xsl:text><xsl:value-of select="lastName" />
	<xsl:if test='contains(nameSuffix,"r")'><!-- Filter for Jr and Sr -->
    <xsl:text>, </xsl:text><xsl:value-of select="nameSuffix" />
	</xsl:if>
	</h1>
	
	
	
	<p><xsl:value-of select= "workingTitle" />
	<xsl:if test="degrees!=''">
		<br /><xsl:value-of select="degrees" disable-output-escaping="yes" />
	</xsl:if>
	</p>
<!-- 2011-10-11 Hack for center addresses until FSO cleans up PeopleSoft data -->
	<p>
	<xsl:choose>
	<xsl:when test="contains(degrees, 'Environmental Sciences Building')">
	</xsl:when>
	<xsl:otherwise>
		<xsl:if test="addr1!=''">
			<xsl:value-of select="addr1" />
		</xsl:if>
		<xsl:if test="addr2!=''">
			<br /><span class="extended-address"><xsl:value-of select="addr2" /></span>
		</xsl:if>
		<xsl:if test="addr3!=''">
			<xsl:if test="addr3!='3171'">
			<br /><span class="street-address"><xsl:value-of select="addr3" /></span>
			</xsl:if>
		</xsl:if>
		<xsl:if test="addr4!=''">
			<br /><span class="street-address"><xsl:value-of select="addr4" /></span>
		</xsl:if>
		<xsl:if test="string(phone)">
			<br /><abbr class="type" title="Work">Tel: </abbr><xsl:value-of select="phone" />
		</xsl:if>
	</xsl:otherwise>
	</xsl:choose>	
		<br /><a class="email">
			<xsl:attribute name="href">mailto:<xsl:value-of select="internetId" />@umn.edu</xsl:attribute>	
			<xsl:value-of select="internetId" disable-output-escaping="yes" />@umn.edu	
		</a>
	</p>
</div>	
	<xsl:if test="officeHours!=''">
		<p>Office hours:<br />
			<xsl:value-of select="officeHours" disable-output-escaping="yes" />
		</p>
	</xsl:if>
	<xsl:if test="curriculumVitaeAddr!=''">
		<p><a>
			<xsl:attribute name="href"><xsl:value-of select="curriculumVitaeAddr" /></xsl:attribute>	
			Download Curriculum Vitea</a> [PDF]</p>
	</xsl:if>
</div>


<div class="whole">
<xsl:if test="(string(areasOfInterest))">
		<h2 class="areashead">Key Responsibilities</h2>
			<p class="areas"><xsl:value-of select="areasOfInterest" disable-output-escaping="yes" /></p>
		</xsl:if>

<!-- Check for and display brief biography -->
	<xsl:if test="string(briefBio)">
		<h2 class="areashead">Brief Biography</h2>
        <xsl:value-of select="briefBio" disable-output-escaping="yes" />	
	</xsl:if>
<!-- Check for and display full biography -->
	<xsl:if test="string(fullBio)">
		<h2>Brief Bio</h2><xsl:value-of select="fullBio" disable-output-escaping="yes" />	
	</xsl:if>
<!-- Check for and display publications -->
	<xsl:if test='contains(publications,".")'>
	<h2>Selected Publications</h2>
	<div class="CitationOL">
		<xsl:value-of select="publications" disable-output-escaping="yes" />
	</div>		
	</xsl:if>
<!-- Check for and display presentations  -->
	<xsl:if test='contains(presentations,".")'>
	<h2>Selected Presentations</h2>
	<div class="CitationOL">
		<xsl:value-of select="presentations" disable-output-escaping="yes" />
	</div>		
	</xsl:if>


</div>

</xsl:for-each>

</xsl:template>
</xsl:stylesheet>