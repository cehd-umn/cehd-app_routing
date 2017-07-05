<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" omit-xml-declaration="yes" />

<xsl:template match="/People">
<table>
<tr>
    <th>P - Indefinite Tenure | Faculty</th>
    <th>J - Multiple Year Contract</th>
    <th>N - Tenure Track</th>
    <th>K - Annual Renewable Contract</th>
</tr>
<tr>
    <td>
        <xsl:for-each select="person/jobs/job">
            <xsl:if test="substring(umApptTypeCd, 1, 1)='P'">
                <xsl:call-template name="RowIt" />
            </xsl:if>
        </xsl:for-each>
    </td>
    <td>
        <xsl:for-each select="person/jobs/job">
            <xsl:if test="substring(umApptTypeCd, 1, 1)='J'">
                <xsl:call-template name="RowIt" />
            </xsl:if>
        </xsl:for-each>
    </td>
    <td>
        <xsl:for-each select="person/jobs/job">
            <xsl:if test="substring(umApptTypeCd, 1, 1)='N'">
                <xsl:call-template name="RowIt" />
            </xsl:if>
        </xsl:for-each>
    </td>
    <td>
        <xsl:for-each select="person/jobs/job">
            <xsl:if test="substring(umApptTypeCd, 1, 1)='K'">
                <xsl:call-template name="RowIt" />
            </xsl:if>
        </xsl:for-each>
    </td>
</tr>
</table>
</xsl:template>

<xsl:template name="RowIt">
        <p>
    		<strong><xsl:value-of select="../../firstName" /><xsl:text> </xsl:text><xsl:value-of select="../../lastName" /></strong>
                <xsl:if test="string(../../workingTitle)!=' '">
    		          <xsl:text> | </xsl:text><xsl:value-of select="../../workingTitle" />
    			</xsl:if>
                <br />
                <xsl:for-each select="../../jobs/job">
                        <xsl:value-of select="effdt" /><xsl:text> | </xsl:text>
    				    <xsl:value-of select="substring(umApptTypeCd, 1, 1)" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="JobCodeDescr" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="jobStatus" />
                        <br />
    			</xsl:for-each>
                <xsl:for-each select="../../tags/tagGroup">
                    <xsl:if test="@department!=''">
                        <strong><xsl:value-of select="@department" /> Tags: </strong>
                        <xsl:for-each select="tag">
                        <xsl:value-of select="@tagDescr" />
                            <xsl:if test="position() != last()"> 
                                <xsl:text> | </xsl:text>
                            </xsl:if> 
                        </xsl:for-each>
                    </xsl:if>
                </xsl:for-each>
    	</p>
</xsl:template>
    
</xsl:stylesheet>