<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" omit-xml-declaration="yes" />

<xsl:template match="/rss/channel">
<table class="basic">
<tr>
    <th>Tenure</th>
    <th>Tenure Track</th>
    <th>P &amp; A</th>
</tr>
<!-- What are some options?
	P - Indefinite Tenure | Faculty
    J - Multiple Year Contract
    K - Annual Renewable Contract
    X - Outside Term
    Y - Number of Years Restricted</h2>
-->
<tr>
<td>
<xsl:for-each select="item">
    <xsl:if test="umApptTypeCd[contains(text(), 'P')]">
            <xsl:call-template name="RowIt" />
    </xsl:if>
</xsl:for-each>
</td>
<td>
<xsl:for-each select="item">
    <xsl:if test="umApptTypeCd[contains(text(), 'N')]">
            <xsl:call-template name="RowIt" />
    </xsl:if>
</xsl:for-each>
</td>
<td>
<xsl:for-each select="item">
    <xsl:if test="umApptTypeCd[contains(text(), 'K')]">
            <xsl:call-template name="RowIt" />
    </xsl:if>
</xsl:for-each>
</td>
</tr>
</table>


</xsl:template>
    
<xsl:template match="item" name="RowIt">
    	<p>
    		<strong><xsl:value-of select="firstName" /><xsl:text> </xsl:text><xsl:value-of select="lastName" /></strong>
    		<xsl:text> | </xsl:text>
    		<xsl:value-of select="umApptTypeCd" />
    	</p>
</xsl:template>
    
</xsl:stylesheet>