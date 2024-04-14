<?php

/**
 * Template Name: Mikrotik Blocklist Builder
 *
 * Template for displaying a blank page.  
 *
 * @package Understrap
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;

#https://www.spamhaus.org/drop/edrop.txt
#https://www.spamhaus.org/drop/drop.txt

function spamhaus_read($file, $list="BLACKLIST_DROP") {
    
    # remove all list entries with tagged $list; 
    echo "/ip firewall address-list remove [find where list=\"$list\"]; ". "\n";
    echo "/ip firewall address-list ". "\n"; 
    
    $spamhaus_drop = file_get_contents($file);
    $array = explode("\n", $spamhaus_drop);  

    foreach ($array as $row) {
        $arr[] = trim(strstr($row, ';', true));
    }
    
    $result = NULL;

    foreach ($arr as $row) {
        if (is_null($row) || $row != "" || $row = NULL) {
            $result .= "add list=$list address=" . $row . "\n";
        }
    }
    
    return $result;
}

#https://iplists.firehol.org/files/firehol_level2.netset

function firehol_read($file, $list="BLACKLIST_DROP") {
    
    # remove all list entries with tagged $list; 
    echo "/ip firewall address-list remove [find where list=\"$list\"]; ". "\n"; 
    
    echo "/ip firewall address-list ". "\n";
    # get the file content from firehol
    $droplist = file_get_contents($file);
    $array = array_filter(explode("\n", $droplist), fn($value) => !is_null($value) && $value !== '');
    
    $result = NULL;
    
    
    foreach ($array as $row) {
       
        if(strpos($row,"#")=== false) {
           $result.= "add list=$list address=" . $row . "\n";
        }
    }

    return $result;
    
}

//echo spamhaus_read("https://www.spamhaus.org/drop/drop.txt", "SPAMHAUS_DROP");
//echo spamhaus_read("https://www.spamhaus.org/drop/edrop.txt", "SPAMHAUS_EDROP");

echo firehol_read("https://iplists.firehol.org/files/firehol_level1.netset", "FIREHOL_LEVEL1");
echo firehol_read("https://iplists.firehol.org/files/firehol_level2.netset", "FIREHOL_LEVEL2");
echo firehol_read("https://iplists.firehol.org/files/firehol_level3.netset", "FIREHOL_LEVEL3");




?>