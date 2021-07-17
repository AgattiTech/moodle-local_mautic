<?php
// This code is brought to you by AgattiTech


defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => '\core\event\user_enrolment_created',
        'callback' => '\local_mautic\mauticobserver::enrolusercourse',
    ),
);
