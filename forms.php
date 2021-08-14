<?php

require_once('../../config.php');
require_once('../../lib/formslib.php');
require_once('./classes/form/settings_form.php');

require_admin();

$PAGE->set_url(new moodle_url("$CFG->wwwroot/local/mautic/forms.php"));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Mautic Admin Config');
$PAGE->set_heading('Mautic Administration');

$action = optional_param('action', '', PARAM_ALPHAEXT);
$formeventid = optional_param('id', '', PARAM_RAW);

if(is_null($formeventid)) {
    $form_createsettings = new settings_form();
} else {
    $form_createsettings = new settings_form($formeventid);
}
$datalib = new \local_mautic\lib\datalib();

$renderer = $PAGE->get_renderer('local_mautic');

if ($formeventid) {
    $formevent = $datalib->getformevent($formeventid);
}

if ($data = $form_createsettings->get_data()) {
    if($data->id == ''){
        try {
            $datalib->createformevent($data);
            redirect($PAGE->url, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
        } catch (Exception $e) {
            redirect($PAGE->url, $e->getMessage(), null, \core\output\notification::NOTIFY_ERROR);
        }
    } else {
        try {
            $datalib->updateformevent($data);
            redirect($PAGE->url, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
        } catch (Exception $e) {
            redirect($PAGE->url, $e->getMessage(), null, \core\output\notification::NOTIFY_ERROR);
        }
    }
    
} else if ($action == 'delete') {
    if (!optional_param('confirm', false, PARAM_BOOL)) {
        $continueparams = ['action' => 'delete', 'id' => $formeventid, 'sesskey' => sesskey(), 'confirm' => true];
        $continueurl = new moodle_url('/local/mautic/forms.php', $continueparams);
        $cancelurl = new moodle_url('/local/mautic/forms.php');
        echo $OUTPUT->header();
        echo $OUTPUT->confirm(get_string('deleteformevent', 'local_mautic'), $continueurl, $cancelurl);
        echo $OUTPUT->footer();
    } else {
        require_sesskey();
        $datalib->deleteformevent($formeventid);
        redirect($PAGE->url, 'Form Event deleted', null, \core\output\notification::NOTIFY_SUCCESS);
    }
} else {
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Settings');
    echo $renderer->show_events();
    $form_createsettings->display();
    echo $OUTPUT->footer();
}
