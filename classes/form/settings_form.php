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
            'user_enrolment' => 'User Enrolment',
        );
        
        //firstname, lastname, email, telephone, coursefullname, courseid
        $moodlefieldoptions = array(
            '0' => 'Select Field', 
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'coursefullname' => 'Course Name',
            'courseid' => 'Course Id',
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
        $textgroup1[] =& $mform->createElement('select', 'moodletext1', 'T1', $moodlefieldoptions);
        $textgroup1[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext1', PARAM_TEXT);
        $mform->setType('moodletext1', PARAM_TEXT);
        $mform->addGroup($textgroup1, 'text1', '', ' ', false);

        $textgroup2 = [];
        $textgroup2[] =& $mform->createElement('html', '<tr><td>');
        $textgroup2[] =& $mform->createElement('text', 'mautictext2', 'MT2');
        $textgroup2[] =& $mform->createElement('html', '</td><td>');
        $textgroup2[] =& $mform->createElement('select', 'moodletext2', 'T2', $moodlefieldoptions);
        $textgroup2[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext2', PARAM_TEXT);
        $mform->setType('moodletext2', PARAM_TEXT);
        $mform->addGroup($textgroup2, 'text2', '', ' ', false);

        $textgroup3 = [];
        $textgroup3[] =& $mform->createElement('html', '<tr><td>');
        $textgroup3[] =& $mform->createElement('text', 'mautictext3', 'MT3');
        $textgroup3[] =& $mform->createElement('html', '</td><td>');
        $textgroup3[] =& $mform->createElement('select', 'moodletext3', 'T3', $moodlefieldoptions);
        $textgroup3[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext3', PARAM_TEXT);
        $mform->setType('moodletext3', PARAM_TEXT);
        $mform->addGroup($textgroup3, 'text3', '', ' ', false);

        $textgroup4 = [];
        $textgroup4[] =& $mform->createElement('html', '<tr><td>');
        $textgroup4[] =& $mform->createElement('text', 'mautictext4', 'MT4');
        $textgroup4[] =& $mform->createElement('html', '</td><td>');
        $textgroup4[] =& $mform->createElement('select', 'moodletext4', 'T4', $moodlefieldoptions);
        $textgroup4[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext4', PARAM_TEXT);
        $mform->setType('moodletext4', PARAM_TEXT);
        $mform->addGroup($textgroup4, 'text4', '', ' ', false);

        $textgroup5 = [];
        $textgroup5[] =& $mform->createElement('html', '<tr><td>');
        $textgroup5[] =& $mform->createElement('text', 'mautictext5', 'MT5');
        $textgroup5[] =& $mform->createElement('html', '</td><td>');
        $textgroup5[] =& $mform->createElement('select', 'moodletext5', 'T5', $moodlefieldoptions);
        $textgroup5[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext5', PARAM_TEXT);
        $mform->setType('moodletext5', PARAM_TEXT);
        $mform->addGroup($textgroup5, 'text5', '', ' ', false);
        
        $textgroup6 = [];
        $textgroup5[] =& $mform->createElement('html', '<tr><td>');
        $textgroup5[] =& $mform->createElement('text', 'mautictext6', 'MT6');
        $textgroup5[] =& $mform->createElement('html', '</td><td>');
        $textgroup5[] =& $mform->createElement('select', 'moodletext6', 'T6', $moodlefieldoptions);
        $textgroup5[] =& $mform->createElement('html', '</td></tr>');
        $mform->setType('mautictext6', PARAM_TEXT);
        $mform->setType('moodletext6', PARAM_TEXT);
        $mform->addGroup($textgroup6, 'text6', '', ' ', false);

        $mform->addElement('html', '</table>');

        $mform->hideIf('enrolevgroup', 'event', 'neq', 'user_enrolment');
        $mform->hideIf('text1', 'event', 'neq', 'user_enrolment');
        $mform->hideIf('text2', 'event', 'neq', 'user_enrolment');
        $mform->hideIf('text3', 'event', 'neq', 'user_enrolment');
        $mform->hideIf('text4', 'event', 'neq', 'user_enrolment');
        $mform->hideIf('text5', 'event', 'neq', 'user_enrolment');
        $mform->hideIf('text6', 'event', 'neq', 'user_enrolment');

        $this->add_action_buttons(false, get_string('save'));
    }
    
    //Custom validation should be added here
    function validation($data, $files){
    }
}
