<?php

/**
 * PHP Class to handle TrackBacks (send/ping, receive, retreive, detect, seed, etc...)
 *
 * <code><?php
 * include('trackback_cls.php');
 * $trackback = new Trackback('BLOGish', 'Ran Aroussi', 'UTF-8');
 * ?></code>
 *
 * ==============================================================================
 *
 * @version $Id: mwtrackback.php 824 2011-12-08 23:50:30Z i.bitcero $
 * @copyright Copyright (c) 2004 Ran Aroussi (http://www.blogish.org)
 * @author Ran Aroussi <ran@blogish.org>
 * @license http://opensource.org/licenses/gpl-license.php GNU General Public License (GPL)
 *
 * ==============================================================================
 */

/**
 * Trackback - The main class
 *
 * @param string $blog_name
 * @param string $author
 * @param string $encoding
 */
class mwtrackback
{
    public $blog_name = ''; // Default blog name used throughout the class (ie. BLOGish)
    public $author = ''; // Default author name used throughout the class (ie. Ran Aroussi)
    public $encoding = ''; // Default encoding used throughout the class (ie. UTF-8)
    public $get_id = ''; // Retreives and holds $_GET['id'] (if not empty)
    public $post_id = ''; // Retreives and holds $_POST['id'] (if not empty)
    public $url = ''; // Retreives and holds $_POST['url'] (if not empty)
    public $title = ''; // Retreives and holds $_POST['title'] (if not empty)
    public $excerpt = ''; // Retreives and holds $_POST['expert'] (if not empty)

    /**
     * Class Constructure
     *
     * @param string $blog_name
     * @param string $author
     * @param string $encoding
     */
    public function __construct($blog_name, $author, $encoding = 'UTF-8')
    {
        $this->blog_name = $blog_name;
        $this->author = $author;
        $this->encoding = $encoding;

        // Gather $_POST information
        if (isset($_GET['id'])) {
            $this->get_id = $_GET['id'];
        }
        if (isset($_POST['id'])) {
            $this->post_id = $_POST['id'];
        }
        if (isset($_POST['url'])) {
            $this->url = $_POST['url'];
        }
        if (isset($_POST['title'])) {
            $this->title = $_POST['title'];
        }
        if (isset($_POST['excerpt'])) {
            $this->excerpt = $_POST['excerpt'];
        }
    }

    /**
     * Sends a trackback ping to a specified trackback URL.
     * allowing clients to auto-discover the TrackBack Ping URL.
     *
     * <code><?php
     * include('trackback_cls.php');
     * $trackback = new Trackback('BLOGish', 'Ran Aroussi', 'UTF-8');
     * if ($trackback->ping('http://tracked-blog.com', 'http://your-url.com', 'Your entry title')) {
     *  echo "Trackback sent successfully...";
     * } else {
     *  echo "Error sending trackback....";
     * }
     * ?></code>
     *
     * @param string $tb
     * @param string $url
     * @param string $title
     * @param string $excerpt
     * @return bool
     */
    public function ping($tb, $url, $title = '', $excerpt = '')
    {
        $response = '';
        $reason = '';
        // Set default values
        if (empty($title)) {
            $title = 'Trackbacking your entry...';
        }
        if (empty($excerpt)) {
            $excerpt = "I found your entry interesting do I've added a Trackback to it on my weblog :)";
        }
        // Parse the target
        $target = parse_url($tb);

        if ((isset($target['query'])) && ('' != $target['query'])) {
            $target['query'] = '?' . $target['query'];
        } else {
            $target['query'] = '';
        }

        if ((isset($target['port']) && !is_numeric($target['port'])) || (!isset($target['port']))) {
            $target['port'] = 80;
        }
        // Open the socket
        $tb_sock = fsockopen($target['host'], $target['port']);
        // Something didn't work out, return
        if (!is_resource($tb_sock)) {
            return '$trackback->ping: can\'t connect to: ' . $tb . '.';
            exit;
        }
        // Put together the things we want to send
        $tb_send = 'url=' . rawurlencode($url) . '&title=' . rawurlencode($title) . '&blog_name=' . rawurlencode($this->blog_name) . '&excerpt=' . rawurlencode($excerpt);

        // Send the trackback
        fwrite($tb_sock, 'POST ' . $target['path'] . $target['query'] . " HTTP/1.1\r\n");
        fwrite($tb_sock, 'Host: ' . $target['host'] . "\r\n");
        fwrite($tb_sock, "Content-type: application/x-www-form-urlencoded\r\n");
        fwrite($tb_sock, 'Content-length: ' . mb_strlen($tb_send) . "\r\n");
        fwrite($tb_sock, "Connection: close\r\n\r\n");
        fwrite($tb_sock, $tb_send);
        // Gather result
        while (!feof($tb_sock)) {
            $response .= fgets($tb_sock, 128);
        }

        // Close socket
        fclose($tb_sock);
        // Did the trackback ping work
        mb_strpos($response, '<error>0</error>') ? $return = true : $return = false;
        // send result
        return $return;
    }

    /**
     * Produces XML response for trackbackers with ok/error message.
     *
     * <code><?php
     * // Set page header to XML
     * header('Content-Type: text/xml'); // MUST be the 1st line
     * //
     * // Instantiate the class
     * //
     * include('trackback_cls.php');
     * $trackback = new Trackb|ack('BLOGish', 'Ran Aroussi', 'UTF-8');
     * //
     * // Get trackback information
     * //
     * $tb_id = $trackback->post_id; // The id of the item being trackbacked
     * $tb_url = $trackback->url; // The URL from which we got the trackback
     * $tb_title = $trackback->title; // Subject/title send by trackback
     * $tb_expert = $trackback->expert; // Short text send by trackback
     * //
     * // Do whatever to log the trackback (save in DB, flatfile, etc...)
     * //
     * if (TRACKBACK_LOGGED_SUCCESSFULLY) {
     *  // Logged successfully...
     *  echo $trackback->recieve(true);
     * } else {
     *  // Something went wrong...
     *  echo $trackback->recieve(false, 'Explain why you return error');
     * }
     * ?></code>
     *
     * @param bool $success
     * @param string $err_response
     * @return bool
     */
    public function recieve($success = false, $err_response = '')
    {
        // Default error response in case of problems...
        if (!$success && empty($err_response)) {
            $err_response = 'An error occured while tring to log your trackback...';
        }
        // Start response to trackbacker...
        $return = '<?xml version="1.0" encoding="' . $this->encoding . '"?>' . "\n";
        $return .= "<response> \n";
        // Send back response...
        if ($success) {
            // Trackback received successfully...
            $return .= "    <error>0</error> \n";
        } else {
            // Something went wrong...
            $return .= "    <error>1</error> \n";
            $return .= '    <message>' . $this->xml_safe($err_response) . "</message>\n";
        }
        // End response to trackbacker...
        $return .= '</response>';

        return $return;
    }

    /**
     * Feteched trackback information and produces an RSS-0.91 feed.
     *
     * <code><?php
     * // 1
     * header('Content-Type: text/xml'); // MUST be the 1st line
     * // 2
     * include('trackback_cls.php');
     * $trackback = new Trackback('BLOGish', 'Ran Aroussi', 'UTF-8');
     * // 3
     * $tb_id = $trackback->get_id;
     * // 4
     * Do whatever to get trackback information by ID (search db, etc...)
     * if (GOT_TRACKBACK_INFO) {
     *  // Successful - pass trackback info as array()...
     *  $tb_info = array('title' => string TRACKBACK_TITLE,
     *          'expert'    => string TRACKBACK_EXPERT,
     *          'permalink' => string PERMALINK_URL,
     *          'trackback' => string TRACKBACK_URL
     *      );
     *  echo $trackback->fetch(true, $tb_info);
     * } else {
     *  // Something went wrong - tell my why...
     *  echo $trackback->fetch(false, string RESPONSE);
     * }
     * ?></code>
     *
     * @param bool $success
     * @param string $response
     * @return string XML response to the caller
     */
    public function fetch($success = false, $response = '')
    {
        if (!$success && empty($response)) {
            $response = 'An error occured while tring to retreive trackback information...';
        }
        // Start response to caller
        $return = '<?xml version="1.0" encoding="' . $this->encoding . '"?>' . "\n";
        $return .= "<response> \n";
        // Send back response...
        if ($success) {
            // Trackback retreived successfully...
            // Sending back an RSS (0.91) - trackback information from $response (array)...
            $return .= "    <error>0</error> \n";
            $return .= "    <rss version=\"0.91\"> \n";
            $return .= "    <channel> \n";
            $return .= '      <title>' . $this->xml_safe($response['title']) . "</title> \n";
            $return .= '      <link>' . $this->xml_safe($response['trackback']) . "</link> \n";
            $return .= '      <description>' . $this->xml_safe($response['expert']) . "</description> \n";
            $return .= "      <item> \n";
            $return .= '        <title>' . $this->xml_safe($response['title']) . "</title> \n";
            $return .= '        <link>' . $this->xml_safe($response['permalink']) . "</link> \n";
            $return .= '        <description>' . $this->xml_safe($response['expert']) . "</description> \n";
            $return .= "      </item> \n";
            $return .= "    </channel> \n";
            $return .= "    </rss> \n";
        } else {
            // Something went wrong - provide reason from $response (string)...
            $return .= "    <error>1</error> \n";
            $return .= '    <message>' . $this->xml_safe($response) . "</message>\n";
        }
        // End response to trackbacker
        $return .= '</response>';

        return $return;
    }

    /**
     * Produces embedded RDF representing metadata about the entry,
     * allowing clients to auto-discover the TrackBack Ping URL.
     *
     * NOTE: DATE should be string in RFC822 Format - Use RFC822_from_datetime().
     *
     * <code><?php
     * include('trackback_cls.php');
     * $trackback = new Trackback('BLOGish', 'Ran Aroussi', 'UTF-8');
     *
     * echo $trackback->rdf_autodiscover(string DATE, string TITLE, string EXPERT, string PERMALINK, string TRACKBACK [, string AUTHOR]);
     * ?></code>
     *
     * @param string $RFC822_date
     * @param string $title
     * @param string $expert
     * @param string $permalink
     * @param string $trackback
     * @param string $author
     * @return string
     */
    public function rdf_autodiscover($RFC822_date, $title, $expert, $permalink, $trackback, $author = '')
    {
        if (!$author) {
            $author = $this->author;
        }

        $return = "<!-- \n";
        $return .= "<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" \n";
        $return .= "    xmlns:dc=\"http://purl.org/dc/elements/1.1/\" \n";
        $return .= "    xmlns:trackback=\"http://madskills.com/public/xml/rss/module/trackback/\"> \n";
        $return .= "<rdf:Description \n";
        $return .= '    rdf:about="' . $this->xml_safe($permalink) . "\" \n";
        $return .= '    dc:identifier="' . $this->xml_safe($permalink) . "\" \n";
        $return .= '    trackback:ping="' . $this->xml_safe($trackback) . "\" \n";
        $return .= '    dc:title="' . $this->xml_safe($title) . "\" \n";
        $return .= "    dc:subject=\"TrackBack\" \n";
        $return .= '    dc:description="' . $this->xml_safe($this->cut_short($expert)) . "\" \n";
        $return .= '    dc:creator="' . $this->xml_safe($author) . "\" \n";
        $return .= '    dc:date="' . $RFC822_date . "\"> \n";
        $return .= "</rdf:RDF> \n";
        $return .= "-->  \n";

        return $return;
    }

    /**
     * Search text for links, and searches links for trackback URLs.
     *
     * <code><?php
     *
     * include('trackback_cls.php');
     * $trackback = new Trackback('BLOGish', 'Ran Aroussi', 'UTF-8');
     *
     * if ($tb_array = $trackback->auto_discovery(string TEXT)) {
     *  // Found trackbacks in TEXT. Looping...
     *  foreach($tb_array as $tb_key => $tb_url) {
     *  // Attempt to ping each one...
     *      if ($trackback->ping($tb_url, string URL, [string TITLE], [string EXPERT])) {
     *          // Successful ping...
     *          echo "Trackback sent to <i>$tb_url</i>...\n";
     *      } else {
     *          // Error pinging...
     *          echo "Trackback to <i>$tb_url</i> failed....\n";
     *      }
     *  }
     * } else {
     *  // No trackbacks in TEXT...
     *  echo "No trackbacks were auto-discover...\n"
     * }
     * ?></code>
     *
     * @param string $text
     * @return array Trackback URLs.
     */
    public function auto_discovery($text)
    {
        // Get a list of UNIQUE links from text...
        // ---------------------------------------
        // RegExp to look for (0=>link, 4=>host in 'replace')
        $reg_exp = '/(http)+(s)?:(\\/\\/)((\\w|\\.)+)(\\/)?(\\S+)?/i';
        // Make sure each link ends with [sapce]
        $text = preg_replace('www.', 'http://www.', $text);
        $text = preg_replace('http://http://', 'http://', $text);
        $text = preg_replace('"', ' "', $text);
        $text = preg_replace("'", " '", $text);
        $text = preg_replace('>', ' >', $text);
        // Create an array with unique links
        $uri_array = [];
        if (preg_match_all($reg_exp, strip_tags($text, '<a>'), $array, PREG_PATTERN_ORDER)) {
            foreach ($array[0] as $key => $link) {
                foreach (([',', '.', ':', ';']) as $t_key => $t_value) {
                    $link = trim($link, $t_value);
                }
                $uri_array[] = ($link);
            }
            $uri_array = array_unique($uri_array);
        }
        // Get the trackback URIs from those links...
        // ------------------------------------------
        // Loop through the URIs array and extract RDF segments
        $rdf_array = []; // <- holds list of RDF segments
        foreach ($uri_array as $key => $link) {
            if ($link_content = implode('', @file($link))) {
                preg_match_all('/(<rdf:RDF.*?<\/rdf:RDF>)/sm', $link_content, $link_rdf, PREG_SET_ORDER);
                for ($i = 0; $i < count($link_rdf); $i++) {
                    if (preg_match('|dc:identifier="' . preg_quote($link) . '"|ms', $link_rdf[$i][1])) {
                        $rdf_array[] = trim($link_rdf[$i][1]);
                    }
                }
            }
        }
        // Loop through the RDFs array and extract trackback URIs
        $tb_array = []; // <- holds list of trackback URIs
        if (!empty($rdf_array)) {
            for ($i = 0; $i < count($rdf_array); $i++) {
                if (preg_match('/trackback:ping="([^"]+)"/', $rdf_array[$i], $array)) {
                    $tb_array[] = trim($array[1]);
                }
            }
        }
        // Return Trackbacks
        return $tb_array;
    }

    /**
     * Other Useful functions used in this class
     * @param mixed $datetime
     */

    /**
     * Converts MySQL datetime to a standart RFC 822 date format
     *
     * @param string $datetime
     * @return string RFC 822 date
     */
    public function RFC822_from_datetime($datetime)
    {
        $timestamp = mktime(
            mb_substr($datetime, 8, 2),
            mb_substr($datetime, 10, 2),
            mb_substr($datetime, 12, 2),
            mb_substr($datetime, 4, 2),
            mb_substr($datetime, 6, 2),
            mb_substr($datetime, 0, 4)
            );

        return date('r', $timestamp);
    }

    /**
     * Converts a string into an XML-safe string (replaces &, <, >, " and ')
     *
     * @param string $string
     * @return string
     */
    public function xml_safe($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }

    /**
     * Cuts a string short (with "...") accroding to $max_length...
     *
     * @param string $string
     * @param int $max_length
     * @return string
     */
    public function cut_short($string, $max_length = 255)
    {
        if (mb_strlen($string) > $max_length) {
            $string = mb_substr($string, 0, $max_length) . '...';
        }

        return $string;
    }
}
