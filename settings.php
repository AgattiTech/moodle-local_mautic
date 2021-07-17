<?php

defined('MOODLE_INTERNAL') || die;

require_once('../config.php');
require_once('classes/form/settings_form.php');
require_once('classes/lib/admin_setting_configurl.php');

$settings = new admin_settingpage( 'local_mautic', 'Manage Mautic' );

$ADMIN->add( 'localplugins', $settings );

$section = optional_param('section', '', PARAM_ALPHAEXT);

if ($ADMIN->fulltree) {

    $settings->add(new admin_setting_configurl('local_mautic/mauticurl', get_string('mauticurl', 'local_mautic'), get_string('mauticurldesc', 'local_mautic'), 0));
        
    if ($section == 'local_mautic') {
        
//        $form_createsettings = new settings_form();
//        
//        echo $OUTPUT->heading('Settings');
//        $form_createsettings->display();
    }
}
