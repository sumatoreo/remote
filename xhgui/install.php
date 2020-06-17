<?php
/**
 * Basic install script for XHGui2.
 *
 * Does the following things.
 *
 */
function out($out) {
    if (is_string($out)) {
        echo $out . "\n";
    }
    if (is_array($out)) {
        foreach ($out as $line) {
            out($line);
        }
    }
}


/**
 * File permissions.
 */
out('Checking permissions for cache directory.');
$worldWritable = bindec('0000000111');

// Get current permissions in decimal format so we can bitmask it.
$currentPerms = octdec(substr(sprintf('%o', fileperms('./cache')), -4));

if (($currentPerms & $worldWritable) != $worldWritable) {
	out('Attempting to set permissions on cache/');
	$result = chmod(__DIR__ . '/cache', $currentPerms | $worldWritable);
	if ($result) {
		out('Permissions set on cache/');
	} else {
		out('Failed to set permissions on cache/ you must do it yourself.');
	}
} else {
	out('Permissions on cache/ are ok.');
}
