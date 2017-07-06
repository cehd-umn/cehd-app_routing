<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" indent="yes"/>
<!-- 2016-06-01 For HomePage News List -->
<xsl:template match="/rss/channel">
<xsl:for-each select="item">
    <xsl:if test="position() &lt;= 1">
      <xsl:call-template name="News" />
    </xsl:if>
  </xsl:for-each>
</xsl:template>

<xsl:template match="item" name="News">
  <div class="third bitebox">
<!-- Image -->
  <a><xsl:attribute name="href"><xsl:value-of select="link"/></xsl:attribute>
    <xsl:choose>
      <xsl:when test="content:thumbnail!=''">
      	<img width="280" height="160">
          <xsl:attribute name="src"><xsl:value-of select="content:thumbnail" disable-output-escaping="yes"  /></xsl:attribute>
          <xsl:attribute name="alt"><xsl:value-of select="title" disable-output-escaping="yes"  /></xsl:attribute>
        </img>
      </xsl:when>
      <xsl:otherwise>
        <img src="" alt="No Thumbnail Set" width="280" height="160"></img>
      </xsl:otherwise>
    </xsl:choose>
  </a>
<!-- Title -->
  <h3 class="newstitle">
    <xsl:choose>
      <xsl:when test="content:titleinternal!=''">
        <xsl:value-of select="content:titleinternal" disable-output-escaping="yes"  />
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="title" disable-output-escaping="yes"  />
      </xsl:otherwise>
    </xsl:choose>
  </h3>
<!-- News Blurb -->
  <p class="news">
    <xsl:choose>
      <xsl:when test="content:teaser!=''">
        <xsl:value-of select="content:teaser" disable-output-escaping="yes"  />
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="description" disable-output-escaping="yes"  />
      </xsl:otherwise>
    </xsl:choose>
  </p>
  </div>
</xsl:template>
</xsl:stylesheet>
