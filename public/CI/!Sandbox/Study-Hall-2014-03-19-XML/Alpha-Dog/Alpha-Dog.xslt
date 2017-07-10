<?xml version="1.0" encoding="utf-8" ?><!-- DWXMLSource="https://apps.cehd.umn.edu/people/admin/RSSFeed.aspx?de=11191" -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" omit-xml-declaration="yes" />

<!--Set a key to ID the Alpha Dogs!-->
<xsl:key name="AlphaDog" match="item" use="string(internetId)" />

<xsl:template match="/rss/channel">
<table class="basic PeopleResults">
	<thead>
		<tr>
			<th class="PR-Name">Name &amp; Position</th>
			<th class="PR-Dept">Email</th>
			<th class="PR-Phone">Phone</th>
		</tr>
	</thead>
	<tbody>

<!--Call up your Alpha Dogs-->
        <xsl:for-each select="key('AlphaDog','jquam')">
            <xsl:call-template name="RowIt" />
        </xsl:for-each>
        <xsl:for-each select="key('AlphaDog','hoove004')">
            <xsl:call-template name="RowIt" />
        </xsl:for-each>
        
<!--Get everyone else... BUT! Skip the Alpha Dogs-->
        <xsl:for-each select="item">
        	<xsl:choose>
        		<xsl:when test="(string(internetId)='jquam')">
            	</xsl:when>
                <xsl:when test="(string(internetId)='hoove004')">
            	</xsl:when>
            	<xsl:otherwise>
                	<xsl:call-template name="RowIt" />
           		</xsl:otherwise>
        </xsl:choose>
		</xsl:for-each>
		</tbody>
	</table>	
	</xsl:template>
    
    <xsl:template match="item" name="RowIt">
    <tr><td>
<!--Do they have a headshot? Lay it down brother!-->
	<xsl:if test="headshotAddr!=''">
	<img class="left" width="60" height="90">
		<xsl:attribute name="src"><xsl:value-of select= "headshotAddr" /></xsl:attribute>
		<xsl:attribute name="alt"><xsl:value-of select= "lastName" /></xsl:attribute>
	</img>
	</xsl:if>
    
<!--Do they have a profile page? If not, link to U directory... Booo-->
		<a>
			<xsl:choose>
				<xsl:when test="(string(webPage))">
						<xsl:attribute name="href"><xsl:value-of select="webPage" /></xsl:attribute>
							</xsl:when>
							<xsl:otherwise>
								<xsl:attribute name="href"><xsl:value-of select="link" /></xsl:attribute>
							</xsl:otherwise>
						</xsl:choose>
						<xsl:value-of select="lastName" />, <xsl:value-of select="firstName" />
					</a>
					<xsl:if test="(string(description))">
						<br /><em><xsl:value-of select="description" disable-output-escaping="yes" /></em>
					</xsl:if>	
				</td>
				<td>
                <xsl:if test="(string(internetId))">
					<a>
						<xsl:attribute name="href">mailto:<xsl:value-of select="internetId" />@umn.edu</xsl:attribute>	
						<xsl:value-of select="internetId" disable-output-escaping="yes" />@umn.edu	
					</a>
				</xsl:if>
                </td>
				<td>	
					<xsl:if test="(string(phone))">
						<xsl:value-of select="substring(phone,1,3)" />-<xsl:value-of select="substring(phone,5)" />
					</xsl:if>	
				</td>
			</tr>
    
    </xsl:template>
    
</xsl:stylesheet>