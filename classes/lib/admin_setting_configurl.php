<?php

require_once("$CFG->libdir/adminlib.php");

class admin_setting_configurl extends admin_setting_configtext {

    /**
     * Validate data before storage
     * @param string data
     * @return mixed true if ok string if error found
     */
    public function validate($data) {
        // allow paramtype to be a custom regex if it is the form of /pattern/
        if(preg_match('/http(s?):\/\/\w+.[a-zA-Z]+/', $data)) {
            return true;
        } else {
            return get_string('mauticurlvalidateerror', 'local_mautic');
        }
    }

}
