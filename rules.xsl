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

<!-- NEWLINES-->
<xsl:template match="br"> <br /> </xsl:template>
<!-- PARAGRAPH -->
<xsl:template match="p"> <p> <xsl:apply-templates /> </p></xsl:template>

<!-- FORMATING TEXT -->
<xsl:template match="g"> <strong> <xsl:apply-templates /> </strong>  </xsl:template>
<xsl:template match="i"> <em>	    <xsl:apply-templates /> </em> </xsl:template>
<xsl:template match="s"> <span class="underline"> <xsl:apply-templates /> </span> </xsl:template>
<xsl:template match="b"> <span class="strike">   	<xsl:apply-templates /> </span> </xsl:template>

<!-- HEADING -->
<xsl:template match="titre">
  <h2>
    <xsl:attribute name="id">titre_<xsl:value-of select="count(preceding::titre)+1" /></xsl:attribute>
    <xsl:value-of select="." />
  </h2>
</xsl:template>
<xsl:template match="stitre">
  <h3>
    <xsl:attribute name="id">stitre_<xsl:value-of select="count(preceding::stitre)+1" /></xsl:attribute>
    <xsl:value-of select="." />
  </h3>
</xsl:template>


<!-- QUOTES -->
<xsl:template match="citation">
  <blockquote>
    <xsl:apply-templates />
  </blockquote>
</xsl:template>
<xsl:template match="cite">
  <q>
    <xsl:apply-templates />
  </q>
</xsl:template>


<!-- LISTS -->
<xsl:template match="liste">
  <xsl:choose>
    <xsl:when test="@type='definition'">
      <dl>
        <xsl:apply-templates />
      </dl>
    </xsl:when>
    <xsl:when test="@type!=''">
      <ol>
        <xsl:attribute name="class">
          <xsl:choose>
            <xsl:when test="@type='greek'">list-lower-greek </xsl:when>
            <xsl:when test="@type='1'"><xsl:text>list-decimal </xsl:text></xsl:when>
            <xsl:when test="@type='i'"><xsl:text>list-lower-roman </xsl:text></xsl:when>
            <xsl:when test="@type='I'"><xsl:text>list-upper-roman </xsl:text></xsl:when>
            <xsl:when test="@type='a'"><xsl:text>list-lower-alpha </xsl:text></xsl:when>
            <xsl:when test="@type='A'"><xsl:text>list-upper-alpha </xsl:text></xsl:when>
            <xsl:when test="@type='01'"><xsl:text>list-decimal-leading-zero </xsl:text></xsl:when>
          </xsl:choose>
        </xsl:attribute>
        <xsl:apply-templates />
      </ol>
    </xsl:when>
    <xsl:otherwise>
      <ul>
        <xsl:apply-templates />
      </ul>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>
<xsl:template match="puce">
  <xsl:choose>
    <xsl:when test="(../@type!='definition') or not(../@type)">
      <li> <xsl:apply-templates /> </li>
    </xsl:when>
    <xsl:otherwise>
      <dt><xsl:value-of select="@nom" /></dt>
      <dd><xsl:apply-templates /></dd>
    </xsl:otherwise>
  </xsl:choose>
</xsl:template>

</xsl:stylesheet>

