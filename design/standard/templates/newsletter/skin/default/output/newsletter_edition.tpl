{set-block variable=$subject scope=root}{ezini('NewsletterMailSettings', 'EmailSubjectPrefix', 'newsletter.ini')} {$contentobject.name|wash}{/set-block}{set-block variable=$html_mail}<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{$#subject}</title>
    </head>
    <body>
        <table id="table-main">
            <tr align="left">
                {def $edition_data_map = $contentobject.data_map}
                <td valign="top">
                    <table id="table-content">
                        <tr>
                            <td><a href={'/'|ezurl()} title="{ezini('SiteSettings', 'SiteName')}"><img src={'images/newsletter/skin/default/newsletter_header.jpg'|ezdesign()} alt="{ezini('SiteSettings', 'SiteName')}" class="newsletter_header" /></a></td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 0 0 15px">
                                {* Title *}
                                {if $edition_data_map.title.has_content}
                                    <h1>{$edition_data_map.title.content|wash()}</h1>
                                {/if}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 0 30px 0 15px">
                                {* Text *}
                                {if $edition_data_map.description.has_content}
                                    {$edition_data_map.description.content.output.output_text|wash(xml)}
                                {/if}
                                <br />
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 30px 10px 10px;">
                                <p>
                                    {'To unsubscribe from this newsletter please visit the following link'|i18n('newsletter/skin/default')}:
                                    <a href="{'/newsletter/unsubscribe/#_hash_unsubscribe_#'|ezurl('no')}">{'unsubscribe'|i18n('newsletter/skin/default')}</a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body></html>
    {/set-block}{$html_mail|newsletter_str_replace( hash( 
		'<body>', '<body text="#666666" link="#666666" vlink="#666666" alink="#666666" style="margin:0;padding:0;">',
		'<table id="table-main">', '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" style="margin:0;padding:0; height: 100%;width: 100%">',
		'<table id="table-content">', '<table width="800" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="margin:auto">',
		'<a', '<a style="color:#666666;font-family:arial,helvetica,sans-serif;padding:0;line-height:1.5;"',
		'class="newsletter_header"', 'border="0" width="800" height="137"',
		'<li>', '<li style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:0.75em;padding:0;line-height:1.5;">',
		'<p>', '<p style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:0.75em;padding:0;line-height:1.5;">',
		'<h1>', '<h1 style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:1.75em;font-weight:bold;line-height:1;padding:0">',
		'<h2>', '<h2 style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:1.3em;font-weight:bold;line-height:1;padding:0">',
		'<h3>', '<h3 style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:1.2em;font-weight:bold;line-height:1;padding:0">'
    ) )}
