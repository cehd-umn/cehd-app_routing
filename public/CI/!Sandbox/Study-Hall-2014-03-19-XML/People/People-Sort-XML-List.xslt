<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" omit-xml-declaration="yes" />

<!--Set a key to ID the Alpha Dogs!-->
<xsl:key name="AlphaDog" match="person" use="substring(jobs/job/umApptTypeCd, 1, 1)" />

<xsl:template match="/People">


<!--Call up your Alpha Dogs-->
	<h2>P - Indefinite Tenure | Faculty</h2>
        <xsl:for-each select="key('AlphaDog','P')">
            <xsl:call-template name="RowIt" />
        </xsl:for-each>
    <h2>J - Multiple Year Contract</h2>
        <xsl:for-each select="key('AlphaDog','J')">
            <xsl:call-template name="RowIt" />
        </xsl:for-each>
    <h2>K - Annual Renewable Contract</h2>
        <xsl:for-each select="key('AlphaDog','K')">
            <xsl:call-template name="RowIt" />
        </xsl:for-each>
    <h2>X - Outside Term</h2>
        <xsl:for-each select="key('AlphaDog','X')">
            <xsl:call-template name="RowIt" />
        </xsl:for-each>
    <h2>Y - Number of Years Restricted</h2>
        <xsl:for-each select="key('AlphaDog','Y')">
            <xsl:call-template name="RowIt" />
        </xsl:for-each>
        
<!--Get everyone else... BUT! Skip the Alpha Dogs-->
	<h2>Not Matched</h2>
        <xsl:for-each select="person">
        	<xsl:choose>
        		<xsl:when test="(string(jobs/job/umApptTypeCd)='P')">
            	</xsl:when>
                <xsl:when test="(string(jobs/job/umApptTypeCd)='J')">
            	</xsl:when>
            	<xsl:when test="(string(jobs/job/umApptTypeCd)='K')">
            	</xsl:when>
                <xsl:when test="(string(jobs/job/umApptTypeCd)='X')">
            	</xsl:when>
            	<xsl:when test="(string(jobs/job/umApptTypeCd)='Y')">
            	</xsl:when>
            	<xsl:otherwise>
                	<xsl:call-template name="RowIt" />
           		</xsl:otherwise>
        </xsl:choose>
		</xsl:for-each>
		
	</xsl:template>
    
    <xsl:template match="item" name="RowIt">
    	<p>
    		<strong><xsl:value-of select="firstName" /><xsl:text> </xsl:text><xsl:value-of select="lastName" /></strong>
    		<xsl:text> | </xsl:text><xsl:value-of select="workingTitle" /><br />
    			<xsl:for-each select="jobs/job">
    				<xsl:value-of select="umApptTypeCd" /><xsl:text> | </xsl:text><xsl:value-of select="jobStatus" /><br />
    			</xsl:for-each>
    	</p>
    </xsl:template>
    
</xsl:stylesheet>