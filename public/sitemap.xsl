<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>Sitemap</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style type="text/css">
                    body {
                        font-family: "Lucida Grande", "Lucida Sans Unicode", Tahoma, Verdana;
                        font-size: 13px;
                        margin: 0px;
                    }

                    #header {
                        text-align: center;
                    }

                    #header h1 {
                        color: #F56734
                    }

                    #intro {
                        background-color: #4BA1B2;
                        padding: 5px 13px 5px 13px;
                        margin: 10px;
                    }

                    #intro p {
                        line-height: 16.8667px;
                        class: #ffffff;
                        font-size: 18px;
                    }

                    table {
                        width: 100%;
                        margin: 10px 20px;
                    }

                    td {
                        font-size: 11px;
                    }

                    th {
                        text-align: left;
                        padding-right: 30px;
                        font-size: 11px;
                    }

                    tr.high {
                        background-color: whitesmoke;
                    }

                    #footer {
                        padding: 2px;
                        margin: 10px;
                        font-size: 8pt;
                        color: gray;
                    }

                    #footer a {
                        color: gray;
                    }

                    a {
                        color: black;
                    }

                    #content {
                        background: #F7F7F7;
                        border-top: 1px solid #DFDFDF;
                    }

                </style>
            </head>
            <body>
                <div id="header">
                    <h1>Avtorem.info</h1>
                </div>
                <div id="intro">
                    Sitemap XML
                </div>
                <div id="content">
                    <table cellpadding="5">
                        <tr style="border-bottom:1px black solid;">

                            <th>URL</th>
                            <th>Priority</th>
                            <th>Change Frequency</th>
                            <th>Last Change</th>
                        </tr>
                        <xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'"/>
                        <xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'"/>
                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                            <tr>
                                <xsl:if test="position() mod 2 != 1">
                                    <xsl:attribute name="class">high</xsl:attribute>
                                </xsl:if>

                                <td>
                                    <xsl:variable name="itemURL1">
                                        <xsl:value-of select="sitemap:loc"/>
                                    </xsl:variable>
                                    <a href="{$itemURL1}">
                                        <xsl:value-of select="sitemap:loc"/>
                                    </a>
                                </td>
                                <td>
                                    <xsl:value-of select="concat(sitemap:priority*100,'%')"/>
                                </td>
                                <td>
                                    <xsl:value-of
                                            select="concat(translate(substring(sitemap:changefreq, 1, 1),concat($lower, $upper),concat($upper, $lower)),substring(sitemap:changefreq, 2))"/>
                                </td>
                                <td>
                                    <xsl:value-of
                                            select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
