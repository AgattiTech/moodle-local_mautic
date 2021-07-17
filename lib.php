<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * local_mautic
 *
 * @author      Fred Woolard <woolardfa@appstate.edu>
 * @copyright   (c) 2018 Appalachian State Universtiy, Boone, NC
 * @license     GNU General Public License version 3
 * @package     local_mautic
 */

defined('MOODLE_INTERNAL') || die();

//require_once( '../lib/accesslib.php' );
//require_once('config.php');

/**
 * Called by the core outputrender's standard_head_html().
 *
 * @return string Markup to be included in page's HTML head element
 */
function local_mautic_before_standard_html_head() : string {
    global $OUTPUT, $USER;
    
    $params = array();
    
    $params['loggedin'] = isloggedin();
    
    if ($params['loggedin']) {
        $params['firstname'] = $USER->firstname;
        $params['lastname']  = $USER->lastname;
        $params['email']     = $USER->email;
    }

    return $OUTPUT->render_from_template("local_mautic/mauticjs", $params) . "\n";

}

