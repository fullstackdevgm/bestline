<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:fmp="http://www.filemaker.com/fmpxmlresult">
  <xsl:output method="text"/>
  <xsl:variable name="newline" select="'&#10;'"/>
  <xsl:variable name="tab" select="'&#9;'"/>
  
  <xsl:template match="/">
    <xsl:for-each select="//fmp:METADATA/fmp:FIELD">
      <xsl:value-of select="@NAME"/>
      <xsl:value-of select="$tab"/>
      <xsl:value-of select="@TYPE"/>
      <xsl:value-of select="$tab"/>
      <xsl:value-of select="@MAXREPEAT"/>
      <xsl:value-of select="$newline"/>
    </xsl:for-each>
  </xsl:template>
  
</xsl:stylesheet>