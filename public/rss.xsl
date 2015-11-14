<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
<xsl:template match="/rss">
	<html>
	<head>
        <title>RSS - Avtorem.info</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="rss.css" rel="stylesheet" type="text/css" />
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
            a:hover {
                text-decoration: none;
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
            .content .item {
                clear: both;
                padding-bottom: 10px;
                margin-bottom: 10px;
                border-bottom: 1px solid #eee;
                display: inline-block;
                width: 100%;
            }
            .content .item:last-child {
                border: none;
            }
            .content .item img {
                float: left;
                margin: 16px 10px 10px;
            }
            .content .item h3 {
                margin: 0 0 10px;
                font-family: Roboto,Helvetica Neue,Helvetica,Arial,sans-serif;
                font-size: 22px;
            }
            .content .item h3 a {
                text-decoration: none;
            }
            .content .item .info-item {
                display: inline-block;
                height: 25px;
                line-height: 25px;
                float: left;
                padding: 5px 7px;
                background: #F2F2F2;
                font-size: 14px;
            }
            .content .item .info-item a {
                line-height: 25px;
            }
            .content .item .info-item.author img,
            .content .item .info-item.author span {
                float: left;
                margin: 0 5px 0 0;
            }
            .content .item .description {
                display: inline-block !important;
            }
            .content .item .read-more {
                display: block;
                width: 100%;
                height: 36px;
                margin-top: 10px;
                text-align: center;
                background: #1B609C;
                border-radius: 4px;
                line-height: 36px;
                color: #ffffff;
                text-decoration: none;
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
                <xsl:element name="img">
                    <xsl:attribute name="src">
                        <xsl:value-of select="channel/image/url" />
                    </xsl:attribute>
                </xsl:element>
            </a>
            <h1>
                <xsl:element name="a">
                    <xsl:attribute name="href">
                        <xsl:value-of select="channel/link" />
                    </xsl:attribute>
                    <xsl:value-of select="channel/title" />
                </xsl:element>
                <span class="slogan">
                    <xsl:value-of select="channel/description" />
                </span>
            </h1>
		</div>
		<div class="content">
            <xsl:for-each select="channel/item">
                <div class="item">
                    <h3>
                        <xsl:element name="a">
                            <xsl:attribute name="href">
                                <xsl:value-of select="link"/>
                            </xsl:attribute>
                            <xsl:value-of select="title"/>
                        </xsl:element>
                    </h3>
                    <div class="description">
                        <div class="published-date info-item">
                            <xsl:value-of select="pubDate" />
                        </div>
                        <xsl:value-of select="description" disable-output-escaping="yes"/>
                    </div>
                    <xsl:element name="a">
                        <xsl:attribute name="href">
                            <xsl:value-of select="link"/>
                        </xsl:attribute>
                        <xsl:attribute name="class">
                            <xsl:text>read-more</xsl:text>
                        </xsl:attribute>
                        <xsl:text>Читать полностью</xsl:text>
                    </xsl:element>
                </div>
            </xsl:for-each>
		</div>
		<div id="footer">
			<div class="copyright">
                <a href="/" class="logo">
                    <img src="/images/logo-circle-footer.png" width="60"/>
                </a>
                <div class="text">
                    При использовании авторских статей ссылка на сайт обязательна. ©
                    <a href="http://www.avtorem.info" title="Avtorem.info">www.avtorem.info</a>
                    2010 - 2015
                </div>
            </div>
		</div>
	</body>
	</html>
</xsl:template>
</xsl:stylesheet>