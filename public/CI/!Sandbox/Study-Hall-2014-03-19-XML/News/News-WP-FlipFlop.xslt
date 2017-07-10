<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/elements/1.1/">
<xsl:output method="xml" omit-xml-declaration="yes" version="1.0" encoding="iso-8859-1" indent="yes"/>

<!-- Establish where to start: in channel, which is in rss -->
<xsl:template match="/rss/channel">

<!-- Start the div and an h2 -->
<div id="News">
<h2>News Freshness</h2>

<!-- You want each node called "item" -->
	<xsl:for-each select="item">
<!-- And if its position is 10 or less... -->
        <xsl:if test="position() &lt;= 10">
<!-- Do this: run this other template. -->
          <xsl:call-template name="News" />
<!-- That's it. -->
        </xsl:if>
<!-- Nothing else happening outside that if statement. -->
	</xsl:for-each>
<!-- Whoa, done with the for-each item -->
</div>
<!-- Closed the div -->
</xsl:template>
<!-- This first template is done. See next template below -->

<xsl:template match="item" name="News">
<!-- Oooo using the article tag... sweet! -->
<article>
<!-- Modulous method to determine even nodes -->
	<xsl:if test="position() mod 2 = 0">
<!-- Apply this attribute and value to the article node -->
    	<xsl:attribute name="class">
			<xsl:text>FlipIt</xsl:text>
		</xsl:attribute>
    </xsl:if>
<!-- Done! -->

<!-- Oooo... make a choice. Does a thumbnail exist? -->
	<xsl:choose>
    	<xsl:when test="string(content:thumbnail)!=''">
        	<img>
				<xsl:attribute name="src">
					<xsl:value-of select='content:thumbnail' />
				</xsl:attribute>
				<xsl:attribute name="alt">
					<xsl:value-of select='title' />
				</xsl:attribute>
                <xsl:attribute name="title">
					<xsl:value-of select='title' />
				</xsl:attribute>
			</img>
        </xsl:when>
<!-- So if a thumbnail does not exist, do this. -->
    	<xsl:otherwise>
        	<img>
				<xsl:attribute name="src">
                	<xsl:text>Slugs/</xsl:text>
						<xsl:value-of select='position()' />
                    <xsl:text>.png</xsl:text>
				</xsl:attribute>
				<xsl:attribute name="alt">
					<xsl:value-of select='title' />
				</xsl:attribute>
                <xsl:attribute name="title">
					<xsl:value-of select='title' />
				</xsl:attribute>
			</img>
		</xsl:otherwise>
    </xsl:choose>
<!-- Done with the thumbnail choice. -->

<!-- Start an h3 for the title -->
    <h3>
<!-- Build a link for the title -->
    	<a>
        	<xsl:attribute name="href">
            	<xsl:value-of select="link"/>
            </xsl:attribute>
<!-- Here is the title! -->
    		<xsl:value-of select="title" disable-output-escaping="yes" />
        </a>
<!-- Done! -->
        <br />
<!-- Add the pretty date -->
    	<span><xsl:value-of select="content:prettyDate" disable-output-escaping="yes" /></span>
    </h3>
<!-- Add the summary -->
    <p><xsl:value-of select="description" disable-output-escaping="yes" /></p>
<!-- Add the read more text -->
    <p>
    	<a>
        	<xsl:attribute name="href">
            	<xsl:value-of select="link"/>
            </xsl:attribute>
        	<xsl:attribute name="title">
				<xsl:value-of select='title' />
			</xsl:attribute>
            Get the full meal deal...
        </a>
	</p>
<!-- Whew! Done! -->
</article>
</xsl:template>
</xsl:stylesheet>