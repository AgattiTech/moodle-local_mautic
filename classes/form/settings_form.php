<?php

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");
//moodleform is defined in formslib.php

class settings_form extends moodleform
{
    
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        $eventsoptions = array(
            '0' => 'Select Event', 
            'student_enrolment' => 'User Enrolment',
        );

        $mform->addElement('select', 'event', get_string('eventselect', 'local_mautic'), $eventsoptions);
        $mform->setType('event', PARAM_TEXT);
        
        $mform->addElement('text', 'mauticformid', 'Mautic Form ID');
        $mform->setType('mauticformid', PARAM_TEXT);

        $descriptiongroup = [];
        $descriptiongroup[] =& $mform->createElement('static', 'enrolevdesc', '', get_string('enrolevdesc', 'local_mautic'));
        $mform->addGroup($descriptiongroup, 'enrolevgroup', '', ' ', false);
        
        $mform->addElement('html', '<table style="text-align:center; margin: 0 auto 30px">');

        $textgroup1 = [];
        $textgroup1[] =& $mform->createElement('html', '<tr><th>Mautic Form Field</th><th>Moodle Data</th>');
        $textgroup1[] =& $mform->createElement('html', '<tr><td>');
        $textgroup1[] =& $mform->createElement('text', 'mautictext1', 'MT1');
        $textgroup1[] =& $mform->createElement('html', '</td><td>');
        $textgroup1[] =& $mform->createElement('text', 'moodletext1', 'T1');
        $textgroup1[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext1', PARAM_TEXT);
        $mform->setType('moodletext1', PARAM_TEXT);
        $mform->addGroup($textgroup1, 'text1', '', ' ', false);

        $textgroup2 = [];
        $textgroup2[] =& $mform->createElement('html', '<tr><td>');
        $textgroup2[] =& $mform->createElement('text', 'mautictext2', 'MT2');
        $textgroup2[] =& $mform->createElement('html', '</td><td>');
        $textgroup2[] =& $mform->createElement('text', 'moodletext2', 'T2');
        $textgroup2[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext2', PARAM_TEXT);
        $mform->setType('moodletext2', PARAM_TEXT);
        $mform->addGroup($textgroup2, 'text2', '', ' ', false);

        $textgroup3 = [];
        $textgroup3[] =& $mform->createElement('html', '<tr><td>');
        $textgroup3[] =& $mform->createElement('text', 'mautictext3', 'MT3');
        $textgroup3[] =& $mform->createElement('html', '</td><td>');
        $textgroup3[] =& $mform->createElement('text', 'moodletext3', 'T3');
        $textgroup3[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext3', PARAM_TEXT);
        $mform->setType('moodletext3', PARAM_TEXT);
        $mform->addGroup($textgroup3, 'text3', '', ' ', false);

        $textgroup4 = [];
        $textgroup4[] =& $mform->createElement('html', '<tr><td>');
        $textgroup4[] =& $mform->createElement('text', 'mautictext4', 'MT4');
        $textgroup4[] =& $mform->createElement('html', '</td><td>');
        $textgroup4[] =& $mform->createElement('text', 'moodletext4', 'T4');
        $textgroup4[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext4', PARAM_TEXT);
        $mform->setType('moodletext4', PARAM_TEXT);
        $mform->addGroup($textgroup4, 'text4', '', ' ', false);

        $textgroup5 = [];
        $textgroup5[] =& $mform->createElement('html', '<tr><td>');
        $textgroup5[] =& $mform->createElement('text', 'mautictext5', 'MT5');
        $textgroup5[] =& $mform->createElement('html', '</td><td>');
        $textgroup5[] =& $mform->createElement('text', 'moodletext5', 'T5');
        $textgroup5[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext5', PARAM_TEXT);
        $mform->setType('moodletext5', PARAM_TEXT);
        $mform->addGroup($textgroup5, 'text5', '', ' ', false);

        $mform->addElement('html', '</table>');

        $mform->hideIf('enrolevgroup', 'event', 'neq', 'student_enrolment');
        $mform->hideIf('text1', 'event', 'neq', 'student_enrolment');
        $mform->hideIf('text2', 'event', 'neq', 'student_enrolment');
        $mform->hideIf('text3', 'event', 'neq', 'student_enrolment');
        $mform->hideIf('text4', 'event', 'neq', 'student_enrolment');
        $mform->hideIf('text5', 'event', 'eq', 'student_enrolment');

        $this->add_action_buttons(false, get_string('save'));
    }
    
    //Custom validation should be added here
    function validation($data, $files){
    }
}
