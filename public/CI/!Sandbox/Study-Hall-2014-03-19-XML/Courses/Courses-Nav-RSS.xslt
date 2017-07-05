<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>
   
<xsl:template match="/rss/channel">
<!--2012-09-20 In FSoS there are multiple feeds in tabs so we concat SetA, SetB etc. by doing this the anchors and IDs don't repeat
	It requires a new XSLT for each set...but, eh, it works! :-) JFA -->
<!-- Navigation bar for the top of page -->   
	<div id="CourseNav">
   		<p>Jump to:<xsl:text> </xsl:text>
<!-- Select each term with the count node -->   		 
   		<xsl:for-each select="item[count='1']">
   			<xsl:call-template name="NavJump" />
<!-- You are done -->  
   		</xsl:for-each>
   		</p>
   	</div>
   
<!-- Now the course list -->   
    <div id="CourseFeed">
    	<ul>
     		<xsl:for-each select="item">
				<xsl:call-template name="CourseList" />
			</xsl:for-each>
     	</ul>
     </div>
   </xsl:template>

   <xsl:template match="item" name="NavJump">
<!-- If it isn't the last node, pop in a divider pipe -->   			
   		<xsl:choose>
   		<xsl:when test="position() != last()">
   			<a><xsl:attribute name="href"><xsl:value-of select="concat('#',substring(termDescr,1,3),'SetA')" /></xsl:attribute>
   			<xsl:value-of select="termDescr"  />
   			</a><xsl:text> | </xsl:text>
   		</xsl:when>	
   		<xsl:otherwise>
   			<a><xsl:attribute name="href"><xsl:value-of select="concat('#',substring(termDescr,1,3),'SetA')" /></xsl:attribute>
   			<xsl:value-of select="termDescr"  /></a>
   		</xsl:otherwise>		
   		</xsl:choose>
   </xsl:template>

	<xsl:template match="item" name="CourseList">
		<xsl:if test="string(count)='1'">
	     	<xsl:choose>
	     	<xsl:when test="string(MaySession)='true'">
		     	<li class="CourseTitle">
		     	<h2>
		     	<xsl:attribute name="id"><xsl:value-of select="concat(substring(termDescr,1,3),'SetA')" /></xsl:attribute>
		     	May Session Courses<br />
		     	<span class="h-override-black">May 26th - June 12th</span></h2></li>
	     	</xsl:when>
	     	<xsl:otherwise>
	     		<li class="CourseTitle">
	     		<h2>
	     		<xsl:attribute name="id"><xsl:value-of select="concat(substring(termDescr,1,3),'SetA')" /></xsl:attribute>
	     		<xsl:value-of select="termDescr" disable-output-escaping="yes"  /></h2></li>
	     	</xsl:otherwise>
	     	</xsl:choose>
	     </xsl:if>
			<li>
				<xsl:value-of select="subject" disable-output-escaping="yes"  />
				<xsl:text> </xsl:text>
				<xsl:value-of select="catalog_nbr" disable-output-escaping="yes"  />
				<xsl:value-of select="mdash" disable-output-escaping="yes"  />
				<a><xsl:attribute name="href"><xsl:value-of select="link" /></xsl:attribute>
				<xsl:value-of select="description" disable-output-escaping="yes"  /></a>
					<xsl:if test="string(deliveryMethod)='online'">
	     				<em><xsl:text> online course</xsl:text></em>
	     			</xsl:if>
			</li>
	</xsl:template>   	
</xsl:stylesheet>

