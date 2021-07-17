<?php

defined('MOODLE_INTERNAL') || die;

$settings = new admin_settingpage( 'local_mautic', 'Manage Mautic' );

$ADMIN->add( 'localplugins', $settings );

$section = optional_param('section', '', PARAM_ALPHAEXT);

if ($ADMIN->fulltree) {
    if ($section == 'local_mautic') {
        redirect(new moodle_url('/local/mautic/forms.php'));
    }
}
