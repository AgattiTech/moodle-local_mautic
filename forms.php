<?php

require_once('../../config.php');
require_once('./classes/form/settings_form.php');



$PAGE->set_url(new moodle_url("$CFG->wwwroot/local/mautic/forms.php"));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Mautic Admin Config');
$PAGE->set_heading('Mautic Administration');


//$action = optional_param('action', '', PARAM_ALPHAEXT);
//$couponid = optional_param('id', '', PARAM_RAW);
$form_createsettings = new settings_form();

echo $OUTPUT->header();
echo $OUTPUT->heading('Settings');
$form_createsettings->display();
echo $OUTPUT->footer();

//require_admin();

//$renderer = $PAGE->get_renderer('enrol_coupon');

//$datalib = new \enrol_coupon\data\datalib();

//if ($couponid) {
//  $coupon = $datalib->read_coupon_value(array('id' => $couponid));
//  $coursesids = $datalib->get_courses_ids($coupon->id);
//  $courseslist = array();
//  foreach ($coursesids as $obj) {
//    array_push($courseslist, $obj->courseid);
//  }

//  $form_createcoupon = new coupon_form($coupon, $courseslist);
//  if (!$coupon) {
//    print_error('invaliddata');
//  }
//}

//if ($action == 'create') {
//  echo $OUTPUT->header();
//  echo $OUTPUT->heading('Create Coupons');
//  $form_createcoupon->display();
//  echo $OUTPUT->footer();
//} else if ($action == 'delete') {
//  if (!optional_param('confirm', false, PARAM_BOOL)) {
//    $continueparams = ['action' => 'delete', 'id' => $couponid, 'sesskey' => sesskey(), 'confirm' => true];
//    $continueurl = new moodle_url('/enrol/coupon/coupons.php', $continueparams);
//    $cancelurl = new moodle_url('/enrol/coupon/coupons.php');
//    echo $OUTPUT->header();
//    echo $OUTPUT->confirm(get_string('deletecoupon', 'enrol_coupon', s($coupon->code)), $continueurl, $cancelurl);
//    echo $OUTPUT->footer();
//  } else {
//    require_sesskey();
//    $datalib->delete_coupon($couponid);
//    redirect($PAGE->url, 'Coupon deleted', null, \core\output\notification::NOTIFY_SUCCESS);
//  }
//} else {
//  if ($data = $form_createcoupon->get_data()) {
//    try {
//      //Create
//      $datalib->create_coupon($data);
//      redirect($PAGE->url, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);
//    } catch (Exception $e) {
//      redirect($PAGE->url, $e->getMessage(), null, \core\output\notification::NOTIFY_ERROR);
//    }
//  } else {
//    echo $OUTPUT->header();
//    echo $OUTPUT->heading('Create Coupons');
//    echo $renderer->show_coupons();
//    $params = ['action' => 'create'];
//    $addurl = new moodle_url("$CFG->wwwroot/enrol/coupon/coupons.php", $params);
//    echo $renderer->single_button($addurl, get_string('createnewcoupon', 'enrol_coupon'));
//    echo $OUTPUT->footer();
//  }
//}
