<?php
// $Id: dopings.php 824 2011-12-08 23:50:30Z i.bitcero $
// --------------------------------------------------------
// MyWords
// Manejo de Artículos
// CopyRight © 2007 - 2008. Red México
// Autor: BitC3R0
// http://www.redmexico.com.mx
// http://www.exmsystem.net
// --------------------------------------------
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License as
// published by the Free Software Foundation; either version 2 of
// the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public
// License along with this program; if not, write to the Free
// Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
// MA 02111-1307 USA
// --------------------------------------------------------
// @copyright: 2007 - 2008 Red México
// @author: BitC3R0

/**
 * Este archivo permite ejecutar los trackbacks especificados
 * en la creación de los artículos
 */
define('MW_LOCATION', '');
include 'header.php';
include_once MW_PATH.'/include/version.php';

function trackback($url, &$post){
	global $xoopsConfig;
	if ($post->isNew() || $post->getApproved()==0 || $post->getStatus()!=1) return;
	$util = new RMUtils();
	//$url = urlencode($url);
	$title = urlencode($post->getTitle());
	$excerpt = urlencode($util->filterTags($post->getExcerpt()!='' ? htmlentities($post->getExcerpt()) : '[...] ' . htmlentities(substr($util->filterTags($post->getText()), 0, $mc['tracklen'])) . ' [...]'));
	$blogname = urlencode($xoopsConfig['sitename']);
	$permalink = urlencode($post->getPermaLink());
	$tburl = urlencode($url);
	$querys = "title=$title&url=$permalink&blog_name=$blogname&excerpt=$excerpt";
	$url = parse_url($url);
	$http_request = 'POST ' . $url['path'] . (isset($url['query']) ? '?'.$url['query'] : '') . " HTTP/1.0\r\n";
	$http_request .= 'Host: '.$url['host']."\r\n";
	$http_request .= 'Content-Type: application/x-www-form-urlencoded; charset='._CHARSET."\r\n";
	$http_request .= 'Content-Length: '.strlen($querys)."\r\n";
	$http_request .= "User-Agent: NaturalPress";
	$http_request .= "\r\n\r\n";
	$http_request .= $querys;
	if ( !isset($url['port']) || '' == $url['port'] )
		$url['port'] = 80;
	$fs = @fsockopen($url['host'], $url['port'], $errno, $errstr, 4);
	@fputs($fs, $http_request);
	@fclose($fs);
	return true;
}

$sql = "SELECT id_post, toping FROM ".$db->prefix("mod_mywords_posts")." WHERE toping<>'' LIMIT 0, 2";
$result = $db->query($sql);
while ($row = $db->fetchArray($result)){
	$tracks = explode(' ',$row['toping']);
	foreach ($tracks as $k){
		if (trackback($k, new NPPost($row['id_post']))){
			$db->queryF("UPDATE ".$db->prefix("mod_mywords_posts")." SET pinged = CONCAT(pinged, '\n', '$k') WHERE id_post = '$row[id_post]'");
			$db->queryF("UPDATE ".$db->prefix("mod_mywords_posts")." SET toping = TRIM(REPLACE(toping, '$k', '')) WHERE id_post = '$row[id_post]'");
		}
	}
}

?>