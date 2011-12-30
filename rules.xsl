<?xml version="1.0" encoding="UTF-8" ?>

<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:php="http://php.net/xsl"
  	exclude-result-prefixes="php"
  	>

<xsl:output
  method="xml"
  encoding="UTF-8"
  omit-xml-declaration="yes"
  indent="yes" />

<xsl:strip-space elements="*" />

<!-- FORMATING TEXT -->
<xsl:template match="g"> <b>  <xsl:apply-templates /> </b>  </xsl:template>
<xsl:template match="i"> <i>	<xsl:apply-templates /> </i> </xsl:template>
<xsl:template match="s"> <span class="underline"> <xsl:apply-templates /> </span> </xsl:template>
<xsl:template match="b"> <span class="strike">   	<xsl:apply-templates /> </span> </xsl:template>


</xsl:stylesheet>

