<?php

namespace local_mautic\lib;

defined('MOODLE_INTERNAL') || die();


class datalib {

    protected $tableformevent;

    protected $tableformdata;

    public function __construct() {
        $this->tableformevent = 'local_mautic_fevent';
        $this->tableformdata = 'local_mautic_fdata';
    }

    public function createformevent($data) {
        global $DB, $CFG;

        $dataobject = $this->format_form_data($data);
        $eventid = $DB->insert_record($this->tableformevent, $dataobject['eventform'], $returnid = true, $bulk = false);

        if(isset($dataobject['formdata'])) {
            $this->createformdata($eventid, $dataobject['formdata']);
        }
    }

    private function createformdata($eventid, $formdata) {
        global $DB, $CFG;

        foreach($formdata as $key => $data){
            $formdata[$key]['feventid'] = $eventid;
        }
        $DB->insert_records($this->tableformdata, $formdata);
    }

    public function getformevents($column = '*') {
        global $DB;

        return $DB->get_records($this->tableformevent, null, null, $column);;
    }

    public function getformevent($formeventid) {
        global $DB;

        return $DB->get_record($this->tableformevent, ['id' => $formeventid]);;
    }
    
    public function getformdatafromformeventid($formeventid) {
        global $DB;

        return $DB->get_records($this->tableformdata, ['feventid' => $formeventid]);;
    }

    public function updateformevent($data) {
        global $DB;
        
        $dataobject = $this->format_form_data($data);
        $this->updateformdata($dataobject);
        return $DB->update_record($this->tableformevent, $dataobject['eventform']);
    }

    private function updateformdata($data) {
        $this->deleteformdatafromformeventid($data['eventform']['id']);
        $this->createformdata($data['eventform']['id'], $data['formdata']);
    }

    public function deleteformevent($id) {
        global $DB;

        $this->deleteformdatafromformeventid($id);
        $DB->delete_records($this->tableformevent, array('id' => $id));
    }

    //Delete
    private function deleteformdatafromformeventid($formeventid) {
        global $DB;

        $DB->delete_records($this->tableformdata, array('feventid' => $formeventid));
    }

    private function format_form_data($data) {
        $dataobject = array("eventform" => array(
            'id' => isset($data->id) ? $data->id : '',
            'event' => $data->event,
            'mauticformid' => $data->mauticformid,
        ),);

        if(!empty($data->mautictext0) && !empty($data->moodletext0)){
            $dataobject['formdata'][] = array(
                'mauticfield' => $data->mautictext0,
                'moodlesource' => $data->moodletext0,
            );
        }

        if(!empty($data->mautictext1) && !empty($data->moodletext1)){
            $dataobject['formdata'][] = array(
                'mauticfield' => $data->mautictext1,
                'moodlesource' => $data->moodletext1,
            );
        }

        if(!empty($data->mautictext2) && !empty($data->moodletext2)){
            $dataobject['formdata'][] = array(
                'mauticfield' => $data->mautictext2,
                'moodlesource' => $data->moodletext2,
            );
        }

        if(!empty($data->mautictext3) && !empty($data->moodletext3)){
            $dataobject['formdata'][] = array(
                'mauticfield' => $data->mautictext3,
                'moodlesource' => $data->moodletext3,
            );
        }

        if(!empty($data->mautictext4) && !empty($data->moodletext4)){    
            $dataobject['formdata'][] = array(
                'mauticfield' => $data->mautictext4,
                'moodlesource' => $data->moodletext4,
            );
        }

        if(!empty($data->mautictext5) && !empty($data->moodletext5)){    
            $dataobject['formdata'][] = array(
                'mauticfield' => $data->mautictext5,
                'moodlesource' => $data->moodletext5,
            );
        }
        
        if(!empty($data->mautictext6) && !empty($data->moodletext6)){    
            $dataobject['formdata'][] = array(
                'mauticfield' => $data->mautictext6,
                'moodlesource' => $data->moodletext6,
            );
        }

        return $dataobject;
    }

    public function getformdatafromformevents($formevents) {
        global $DB;

        $data = array();

        foreach($formevents as $form) {
            $data[$form['id']] = $DB->get_records('local_mautic_fdata', ['feventid' => $form['id']]);
        }

        return $data;
    }

    public function getformeventsfromevent($event) {
        global $DB, $CFG;

        $data = array();
        $eventname = $event->target;
        
        $formevents = $DB->get_records('local_mautic_fevent', ['event' => $eventname]);

        foreach($formevents as $key => $formevent) {
            $data[$key] = (array) $formevent;
        }

        return $data;
    }

    public function getsignificantvalues($event) {
        global $DB, $CFG;

        $data = array();
        $eventname = $event->target;

        $user = $DB->get_record('user', ['id' => $event->relateduserid]);
        $course = $DB->get_record('course', ['id' => $event->courseid]);
        
        $myfile = fopen($CFG->dirroot . "/local/mautic/logs/euc_usercourse.txt", "w") or die("Unable to open file!");
	    $txt = var_export($user, true);
	    $txt .= "\n\n";
	    $txt .= var_export($course, true); 
	    fwrite($myfile, $txt);
	    fclose($myfile);

        $data['firstname'] = $user->firstname;
        $data['lastname'] = $user->lastname;
        $data['phone'] = $user->phone1;
        $data['email'] = $user->email;
        $data['coursefullname'] = $course->fullname;
        $data['courseid'] = $course->id;
        $data['enrol_method'] = $event->other['enrol'];

        return $data;
    }

}
