<?php

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");
//moodleform is defined in formslib.php

class settings_form extends moodleform
{
    
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        //Mautic Url textfield
        $mform->addElement('text', 'mauticurl', 'Mautic Url');
        $mform->setType('mauticurl', PARAM_NOTAGS);
        $mform->addRule('mauticurl', 'This field is required', 'required', '', 'client', false, false);
        $mform->addRule('mauticurl', 'This field must be a url', 'regex', '/http(s?):\/\/\w+.[a-zA-Z]+/gm', 'client', false, false);
        
        
        $this->add_action_buttons(false, get_string('savechanges'));
    }
    
    //Custom validation should be added here
    function validation($data, $files){
    }
}
