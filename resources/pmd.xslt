<?xml version="1.0" encoding="UTF-8"?>
<!-- $Header$ -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN"
                doctype-system="http://www.w3.org/TR/html4/loose.dtd" indent="yes"/>

    <xsl:template name="message">
        <xsl:value-of disable-output-escaping="yes" select="."/>
    </xsl:template>

    <xsl:template name="priorityDiv">
        <xsl:if test="@priority = 1">p1</xsl:if>
        <xsl:if test="@priority = 2">p2</xsl:if>
        <xsl:if test="@priority = 3">p3</xsl:if>
        <xsl:if test="@priority = 4">p4</xsl:if>
        <xsl:if test="@priority = 5">p5</xsl:if>
    </xsl:template>

    <xsl:template name="timestamp">
        <xsl:value-of select="substring-before(//pmd/@timestamp, 'T')"/> -
        <xsl:value-of select="substring-before(substring-after(//pmd/@timestamp, 'T'), '.')"/>
    </xsl:template>

    <xsl:template match="pmd">
        <html>
            <head>
                <title>PMD
                    <xsl:value-of select="//pmd/@version"/> Report
                </title>
                <script type="text/javascript" src="sorttable.js"></script>
                <style type="text/css">
                    body { margin-left: 2%; margin-right: 2%; font:normal verdana,arial,helvetica; color:#000000; }
                    table.sortable tr th { font-weight: bold; text-align:left; background:#a6caf0; }
                    table.sortable tr td { background:#eeeee0; }
                    table.classcount tr th { font-weight: bold; text-align:left; background:#a6caf0; }
                    table.classcount tr td { background:#eeeee0; }
                    table.summary tr th { font-weight: bold; text-align:left; background:#a6caf0; }
                    table.summary tr td { background:#eeeee0; text-align:center;}
                    .p1 { background:#FF9999; }
                    .p2 { background:#FFCC66; }
                    .p3 { background:#FFFF99; }
                    .p4 { background:#99FF99; }
                    .p5 { background:#9999FF; }
                    div.top{text-align:right;margin:1em 0;padding:0}
                    div.top div{display:inline;white-space:nowrap}
                    div.top div.left{float:left}
                    #content>div.top{display:table;width:100%}
                    #content>div.top div{display:table-cell}
                    #content>div.top div.left{float:none;text-align:left}
                    #content>div.top div.right{text-align:right}
                </style>
            </head>
            <body>
                <H1>
                    <div class="top">
                        <div class="left">PMD
                            <xsl:value-of select="//pmd/@version"/> Report
                        </div>
                        <div class="right">
                            <xsl:call-template name="timestamp"/>
                        </div>
                    </div>
                </H1>
                <hr/>
                <h2>Summary</h2>
                <table border="0" class="summary">
                    <tr>
                        <th>Files</th>
                        <th>Total</th>
                        <th>Priority 1</th>
                        <th>Priority 2</th>
                        <th>Priority 3</th>
                        <th>Priority 4</th>
                        <th>Priority 5</th>
                    </tr>
                    <tr>
                        <td>
                            <xsl:value-of select="count(//file)"/>
                        </td>
                        <td>
                            <xsl:value-of select="count(//violation)"/>
                        </td>
                        <td>
                            <div class="p1">
                                <xsl:value-of select="count(//violation[@priority = 1])"/>
                            </div>
                        </td>
                        <td>
                            <div class="p2">
                                <xsl:value-of select="count(//violation[@priority = 2])"/>
                            </div>
                        </td>
                        <td>
                            <div class="p3">
                                <xsl:value-of select="count(//violation[@priority = 3])"/>
                            </div>
                        </td>
                        <td>
                            <div class="p4">
                                <xsl:value-of select="count(//violation[@priority = 4])"/>
                            </div>
                        </td>
                        <td>
                            <div class="p5">
                                <xsl:value-of select="count(//violation[@priority = 5])"/>
                            </div>
                        </td>
                    </tr>
                </table>
                <hr/>
                <table border="0" width="100%" class="sortable" id="sortable_id">
                    <tr>
                        <th>Prio</th>
                        <th>File</th>
                        <th>Line</th>
                        <th align="left">Description</th>
                    </tr>
                    <xsl:for-each select="file">
                        <xsl:for-each select="violation">
                            <tr>
                                <td style="padding: 3px" align="right">
                                    <div>
                                        <xsl:attribute name="class">
                                            <xsl:call-template name="priorityDiv"/>
                                        </xsl:attribute>
                                        <xsl:value-of disable-output-escaping="yes" select="@priority"/>
                                    </div>
                                </td>
                                <td style="padding: 3px" align="left">
                                    <a>
                                        <xsl:attribute name="href">../ws/src/<xsl:value-of
                                            select="substring-after(../@name,'src/')"/>/*view*/
                                        </xsl:attribute>
                                        <xsl:value-of disable-output-escaping="yes"
                                                      select="substring-after(../@name,'src/')"/>
                                    </a>
                                </td>
                                <td style="padding: 3px" align="right">
                                    <xsl:value-of disable-output-escaping="yes" select="@beginline"/>
                                </td>
                                <td style="padding: 3px" align="left" width="100%">
                                    <xsl:if test="@externalInfoUrl">
                                        <a>
                                            <xsl:attribute name="href">
                                                <xsl:value-of select="@externalInfoUrl"/>
                                            </xsl:attribute>
                                            <xsl:call-template name="message"/>
                                        </a>
                                    </xsl:if>
                                    <xsl:if test="not(@externalInfoUrl)">
                                        <xsl:call-template name="message"/>
                                    </xsl:if>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </xsl:for-each>
                </table>
                <br/>
                <p>Generated by
                    <a href="http://pmd.sourceforge.net">PMD
                        <b>
                            <xsl:value-of select="//pmd/@version"/>
                        </b>
                    </a>
                    on<xsl:call-template name="timestamp"/>.
                </p>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
