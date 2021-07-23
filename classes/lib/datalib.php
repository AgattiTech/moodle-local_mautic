<?php

namespace local_mautic\lib;

defined('MOODLE_INTERNAL') || die();


class datalib {

  public function __construct() {
    $this->tableformevent = 'local_mautic_fevent';
    $this->tableformdata = 'local_mautic_fdata';
  }

  public function create_eventlink($data) {
    global $DB, $CFG;
    $myfile = fopen($CFG->dirroot . "/local/mautic/logs/formdata.txt", "w") or die("Unable to open file!");
	$txt = var_export($data, true);
	fwrite($myfile, $txt);
	fclose($myfile);

    $dataobject = $this->format_form_data($data);

    $eventid = $DB->insert_record($this->tableformevent, $dataobject['eventform'], $returnid = true, $bulk = false);
    $this->registerdatalink($eventid, $dataobject['formdata']);
  }

  private function registerdatalink($eventid, $formdata) {
    global $DB, $CFG;
    foreach($formdata as $key => $data){
        $formdata[$key]['feventid'] = $eventid;
    }
    $myfile = fopen($CFG->dirroot . "/local/mautic/logs/formdatas.txt", "w") or die("Unable to open file!");
	$txt = var_export($formdata, true);
	fwrite($myfile, $txt);
	fclose($myfile);
    $DB->insert_records($this->tableformdata, $formdata);
  }

  public function read_coupon_values($column = '*') {
    global $DB;
    $rec = $DB->get_records($this->tablecoupon, null, null, $column);
    return $rec;
  }

  public function read_coupon_value($conditions) {
    global $DB;
    $rec = $DB->get_record($this->tablecoupon, $conditions);
    return $rec;
  }

  public function validate_unique_values($column, $value) {
    global $DB;
    $rec = $DB->record_exists($this->tablecoupon, array($column => $value));
    return $rec;
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


  //Delete
  private function delete_courses($couponid) {
    global $DB;
    $DB->delete_records($this->tablecouponcourses, array('couponid' => $couponid));

  }

  public function delete_coupon($id) {
    global $DB;
    $DB->delete_records($this->tablecoupon, array('id' => $id));
    $DB->delete_records($this->tablecouponcourses, array('couponid' => $id));
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

}
