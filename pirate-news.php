<?php
// Default settings:
$cache_timeout = 3600;
$local_data_file = 'quotescurrent.txt';
$max_items = 50;
$source_data = '';

// Overrule here
include_once('pirate-news.settings.php');

function charset_decode_utf_8 ($string) {
    $search = array('\xc3\xab','\xc3\xaf','\xe2\x80\x99','\xc3\xa2');
    $replace = array("ë","ï","'","â");
    return str_replace($search,$replace,$string);
}

function getData() {
	global $cache_timeout, $source_data, $local_data_file;

	$reload = false;
	if (!file_exists($local_data_file)) {
		$reload = true;
	} elseif (time() > filemtime($local_data_file) +  $cache_timeout) {
		$reload = true;
	}

	$return_data = array();
	if ($reload) {
		$return_data = explode("\n",file_get_contents($source_data));
		file_put_contents($local_data_file, implode("\n",$return_data));
	} else {
		$return_data = explode("\n",file_get_contents($local_data_file));
	}

	return $return_data;
}

function parseData($data) {
	global $max_items;

	$data = array_reverse($data);
	$return_data = array();
	foreach($data as $item) {
		preg_match("/(?P<id>\d+):(?P<datum>\d+).\d+,\d+,['\"]*(?P<url>.*)<title>(?P<titel>.*)<\/title>/", $item, $item);
		if ($item) {
			$return_data[] = array('id' => $item['id'] * 1,
								'datum' => $item['datum'] * 1,
								'url' => str_replace('&','&amp;',trim(strip_tags($item['url']))),
								'title' => charset_decode_utf_8(trim(strip_tags($item['titel']))));
		}
		if (count($return_data) >= $max_items) {
			break;
		}
	}
	return $return_data;
}

function generateItem($item) {
	return '<item>
        <guid>' . $item['url'] . '</guid>
        <title><![CDATA[' . $item['title'] . ']]></title>
        <description><![CDATA[' . $item['title'] . ']]></description>
        <link>' . $item['url'] . '</link>
        <pubDate>' . date("D, d M Y H:i:s O", $item['datum']) . '</pubDate>
    </item>' . "\n";
}

function generateRSS() {
	$return_data = '<?xml version="1.0" ?>
		<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
		<channel>
        <atom:link href="https://theyosh.nl/speeltuin/PPGR/rss.php" rel="self" type="application/rss+xml" />
        <title>Piratenpartij IRC Quotes</title>
        <link>https://piratenpartij.nl</link>
        <description>Random quotes</description>' . "\n";

	foreach(parseData(getData()) as $item) {
		$return_data .= generateItem($item);
	}

	$return_data .= '</channel></rss>';
	return $return_data;
}

header("Content-type: text/xml");
echo generateRSS();
