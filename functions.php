<?php
// functions.php

function getData(GearmanJob $job = null)
{
    $rss = array(
        'http://news.ycombinator.com/rss',
        'http://www.reddit.com/r/programming/.rss',
        'http://www.planet-php.net/rss/',
        'http://www.joelonsoftware.com/rss.xml',
        'http://steve-yegge.blogspot.com/feeds/posts/default',
        'http://www.aaronsw.com/2002/feeds/pgessays.rss',
        'http://feeds.feedburner.com/37signals/beMH',
    );

    $data = array();
    $i = 0;
    foreach ($rss as $url) {
        if ($job !== null) {
            $job->sendStatus($i, count($rss));
        }
        $d = file_get_contents($url);
        $xml = new SimpleXmlElement($d);
        foreach ($xml->channel->item as $item) {
            $data[(string) $item->link] = (string) $item->title; 
        }

        $i++;
    }
    return $data; 
}

function layout($content) {
    $template = <<<EOT
<!DOCTYPE html>
    <html>
        <head>
            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojo/dojo.xd.js"></script>
            <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dijit/dijit.xd.js"></script>
            <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojo/resources/dojo.css"> 
            <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/dojo/1.6/dijit/themes/claro/claro.css">
            <meta charset="utf-8"/>
            <title>Links</title>
            <style type="text/css">
                body {
                    width:400px;
                    margin:10px auto;
                }
            </style>
        </head>
        <body class="claro">
        $content
        </body>
    </html>
EOT;
    return $template;
}


