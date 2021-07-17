<?php
// This code is brought to you by AgattiTech


namespace local_mautic;

class mauticobserver {

    public static function enrolusercourse($event) {
        $myfile = fopen("/var/www/moodle/local/mautic/logs/enrolusercourse.txt", "w") or die("Unable to open file!");
		$txt = var_export($event, true);
		fwrite($myfile, $txt);
		fclose($myfile);
    }

    function submitform($fields) {
        
    }
}
