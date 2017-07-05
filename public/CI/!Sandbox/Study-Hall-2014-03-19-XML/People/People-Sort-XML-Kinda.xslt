<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" omit-xml-declaration="yes" />

<xsl:template match="/People">
<table>
<tr>
    <th>Name | Title</th>
    <th>Professor</th>
    <th>Associate Professor</th>
    <th>Tenure Track</th>
    <th>P&amp;A</th>
</tr>

<xsl:for-each select="person">
    <xsl:call-template name="RowIt" />
</xsl:for-each>
</table>
</xsl:template>

<xsl:template name="RowIt">
<tr>
    <td>
        <strong><xsl:value-of select="firstName" /><xsl:text> </xsl:text><xsl:value-of select="lastName" /></strong>
            <xsl:if test="string(workingTitle)!=' '">
              <xsl:text> | </xsl:text><xsl:value-of select="workingTitle" />
            </xsl:if>
            <br/>
        <!--<xsl:for-each select="tags/tagGroup">
            <xsl:if test="@department!=''">
                <strong><xsl:value-of select="@department" /> Tags: </strong>
                <xsl:for-each select="tag">
                <xsl:value-of select="@tagDescr" />
                    <xsl:if test="position() != last()"> 
                        <xsl:text> | </xsl:text>
                    </xsl:if> 
                </xsl:for-each>
            </xsl:if>
        </xsl:for-each>-->
    </td>
        
    <xsl:for-each select="jobs/job">
        <xsl:sort select="umApptTypeCd"/>
        <xsl:if test="substring(umApptTypeCd, 1, 1)='P' and JobCodeDescr='Professor'"> 
            <td>
                        <xsl:value-of select="effdt" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="substring(umApptTypeCd, 1, 1)" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="JobCodeDescr" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="jobStatus" />
            </td>
            <td></td>
            <td></td>
            <td></td>
        </xsl:if>
        <xsl:if test="substring(umApptTypeCd, 1, 1)='P' and JobCodeDescr='Associate Professor' and jobStatus='Active'"> 
            <td></td>
            <td>
                        <xsl:value-of select="effdt" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="substring(umApptTypeCd, 1, 1)" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="JobCodeDescr" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="jobStatus" />
            </td>
            <td></td>
            <td></td>
        </xsl:if>
        <xsl:if test="substring(umApptTypeCd, 1, 1)='N' and jobStatus='Active'"> 
            <td></td>
            <td></td>
            <td>
                        <xsl:value-of select="effdt" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="substring(umApptTypeCd, 1, 1)" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="JobCodeDescr" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="jobStatus" />
            </td>
            <td></td>
        </xsl:if>
        <xsl:if test="substring(umApptTypeCd, 1, 1)='K' and jobStatus='Active'"> 
            <td></td>
            <td></td>
            <td></td>
            <td>
                        <xsl:value-of select="effdt" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="substring(umApptTypeCd, 1, 1)" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="JobCodeDescr" /><xsl:text> | </xsl:text>
                        <xsl:value-of select="jobStatus" />
            </td>
        </xsl:if>
    </xsl:for-each>
                
                
</tr>
</xsl:template>

<!--<xsl:template name="RowIt">
<tr>
    <td>
    	<strong><xsl:value-of select="firstName" /><xsl:text> </xsl:text><xsl:value-of select="lastName" /></strong>
            
            <xsl:for-each select="jobs/job">
                <br/>
                <xsl:value-of select="substring(umApptTypeCd, 1, 1)" /><xsl:text> | </xsl:text>
                <xsl:value-of select="JobCodeDescr" /><xsl:text> | </xsl:text>
                <xsl:value-of select="jobStatus" />
            </xsl:for-each>
    </td>
    <xsl:for-each select="jobs/job">
        <xsl:sort select="umApptTypeCd"/>
            <xsl:if test="substring(umApptTypeCd, 1, 1)='P' and JobCodeDescr='Professor'"> 
                <td class="yep">
                    <strong>Yep!</strong>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </xsl:if>
            <xsl:if test="substring(umApptTypeCd, 1, 1)='P' and JobCodeDescr='Associate Professor' and jobStatus='Active'"> 
                <td>&nbsp;</td>
                <td class="yep">
                    <strong>Yep!</strong>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </xsl:if>
            <xsl:if test="substring(umApptTypeCd, 1, 1)='N' and jobStatus='Active'"> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="yep">
                    <strong>Yep!</strong>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </xsl:if>
            <xsl:if test="substring(umApptTypeCd, 1, 1)='K' and jobStatus='Active'"> 
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="yep">
                    <strong>Yep!</strong> 
                </td>
                <td>&nbsp;</td>
            </xsl:if>
    </xsl:for-each>
</tr>
</xsl:template>-->
    
</xsl:stylesheet>