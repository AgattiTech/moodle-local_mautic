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

    public function validate_unique_values($column, $value) {
        global $DB;

        return $DB->record_exists($this->tablecoupon, array($column => $value));
    }


    //Edit
    public function edit_coupon($data) {
        global $DB;

        $dataobject = $this->data_object_construct($data);
        $this->edit_coupon_courses($data->id,$data->coursesids);
        return $DB->update_record($this->tablecoupon, $dataobject);
    }

    private function edit_coupon_courses($couponid, $coursesids) {
        $this->delete_courses($couponid);
        $this->create_coupon_courses($couponid, $coursesids);
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

    

    //Validate
    public function db_validate_coupon($coupon, $courseid) {
        global $DB;
        $rtn =  array();
        $now = time();
        $rec = $DB->get_record_sql(
            'SELECT * FROM {enrol_coupon} as ec JOIN {enrol_coupon_coupons} as ecc ON ec.id = ecc.couponid WHERE ec.code = :couponcode AND ec.exptime > :nowtime AND ecc.courseid = :courseid',
            ['couponcode' => $coupon, 'nowtime' => $now, 'courseid' => $courseid]
        );
            if ($rec) {
                return true;
            } else {
                return false;
            }
    }

    public function get_courses_ids($couponid) {
        global $DB;
        return $DB->get_records($this->tablecouponcourses, array('couponid' => $couponid), '', 'courseid');
    }

    private function data_object_construct($data) {

        $dataobject = array(
            'id' => isset($data->id) ? $data->id : '',
            'event' => $data->event,
            'mauticformid' => $data->mauticformid,
            'mautictext1' => $data->mautictext1,
            'moodletext1' => $data->moodletext1,
            'mautictext2' => $data->mautictext2,
            'moodletext2' => $data->moodletext2,
            'mautictext3' => $data->mautictext3,
            'moodletext3' => $data->moodletext3,
            'mautictext4' => $data->mautictext4,
            'moodletext4' => $data->moodletext4,
            'mautictext5' => $data->mautictext5,
            'moodletext5' => $data->moodletext5,
        );

        return $dataobject;
    }
  
    private function format_form_data($data) {
        $dataobject = array("eventform" => array(
            'id' => isset($data->id) ? $data->id : '',
            'event' => $data->event,
            'mauticformid' => $data->mauticformid,
        ),);
        
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
            
        return $dataobject;
    }
    
    public function getformdatafromformevents($formevents) {
        global $DB;

        $data = array();
        
        foreach($formlinks as $form) {
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
        
        $data['firstname'] = $user->firstname;
        $data['lastname'] = $user->lastname;
        $data['phone'] = $user->phone1;
        $data['email'] = $user->email;
        $data['coursefullname'] = $course->fullname;
        $data['courseid'] = $course->id;
        
        return $data;
    }

}
