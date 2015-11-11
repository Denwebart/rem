<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
    xmlns:html="http://www.w3.org/TR/REC-html40"
    xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>Sitemap - Avtorem.info</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style type="text/css">
                    body {
                        padding: 0 !important;
                        margin: 0 !important;
                        font-family: 'Open Sans', sans-serif;
                        font-weight: 400;
                        background: #E5E5E5 url('../images/bg.jpg') repeat;
                    }
                    a {
                        color: #337AB7;
                    }
                    .clearfix {
                        clear: both;
                    }
                    #logo {
                        margin: 10px auto;
                        max-width: 900px;
                        min-width: calc(600px - 2em);
                    }
                    #logo .image {
                        float: left;
                        width: 50%;
                        margin-bottom: 10px;
                    }
                    #logo .image img {
                        width: 100%;
                    }
                    #logo h1 {
                        text-align: left;
                        margin: 0 0 0 30px;
                        font-size: 26px;
                        font-weight: 400;
                        color: #2f68a1;
                        display: inline-block;
                        float: left;
                        width: calc(50% - 30px);
                    }
                    #logo h1 span {
                        display: inline-block;
                        font-size: 18px;
                        color: #333333;
                    }
                    .content {
                        clear: both;
                        border-width:0;
                        background-color:#FFF;
                        max-width: 900px;
                        min-width: calc(600px - 2em);
                        margin: 0 auto;
                        padding: 1em;
                    }
                    .content tr td {
                        border-bottom: 1px solid #eee;
                    }
                    #footer {
                        background: url("../images/footer.jpg");
                        margin: 30px 0 0;
                        min-height: 100px;
                        color: #ffffff;
                        text-align: center;
                    }
                    #footer .copyright {
                        color: #E1E1E1;
                        line-height: 60px;
                        width: auto;
                        display: inline-block;
                    }
                    #footer .copyright a {
                        color: #03A9F4;
                    }
                    #footer .copyright .text,
                    #footer .logo {
                        margin: 20px 20px 0 0;
                        float: left;
                    }
                </style>
            </head>
            <body>
                <div id="logo">
                    <a href="/" class="image">
                        <img src="/images/logo.png" />
                    </a>
                    <h1>
                        <a href="/">
                            Школа авторемонта.
                        </a>
                        <span class="slogan">
                            Статьи, советы и рекомендации по ремонту и обслуживанию автомобилей своими руками.
                        </span>
                    </h1>
                </div>
                <div class="content">
                    <table cellpadding="5">
                        <tr style="border-bottom:1px black solid;">
                            <th>URL</th>
                            <th>Priority</th>
                            <th>Change Frequency</th>
                            <th width="150px">Last Change</th>
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
                                <td width="150px">
                                    <xsl:value-of
                                            select="concat(substring(sitemap:lastmod,0,11),concat(' ', substring(sitemap:lastmod,12,5)))"/>
                                </td>
                            </tr>
                        </xsl:for-each>
                    </table>
                </div>
                <div id="footer">
                    <div class="copyright">
                        <a href="/" class="logo">
                            <img src="/images/logo-circle-footer.png" width="60"/>
                        </a>
                        <div class="text">
                            При использовании авторских статей ссылка на сайт обязательна. ©
                            <a href="http://www.avtorem.info" title="Школа авторемонта">www.avtorem.info</a>
                            2010 - 2015
                        </div>
                    </div>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>