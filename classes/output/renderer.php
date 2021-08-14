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
 * Output rendering for the plugin.
 *
 * @package     Mautic
 * @copyright   2017 Damyon Wiese
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_mautic\output;

use plugin_renderer_base;
use html_table;
use html_table_cell;
use html_table_row;
use html_writer;
use moodle_url;
use local_mautic\lib\datalib;

defined('MOODLE_INTERNAL') || die();

/**
 * Implements the plugin renderer
 *
 * @copyright 2017 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {
    /**
     * This function will render one beautiful table with all the coupons.
     *
     * @param \enrol\coupon\show_coupons[] $linkedlogins - list of all linked logins.
     * @return string HTML to output.
     */

    public function show_events() {
        $datalib = new datalib();
        $records = $datalib->getformevents();
        $table = new html_table();
        $table->caption = 'Form Events';
        $table->head = array('ID', 'Event', 'Mautic Form ID', 'Actions');
        foreach ($records as $record) {
            $links = '';
            $editurl = new moodle_url('/local/mautic/forms.php', ['id' => $record->id, 'action' => 'edit']);
            $deleteurl = new moodle_url('/local/mautic/forms.php', ['id' => $record->id, 'action' => 'delete']);
            $editlink = html_writer::link($editurl, $this->pix_icon('t/edit', 'gear'));
            $deletelink = html_writer::link($deleteurl, $this->pix_icon('t/delete', 'trash'));
            $links .= ' ' . $editlink;
            $links .= ' ' . $deletelink;
            $actions = new html_table_cell($links);

            $id = $record->id;
            $event = $record->event;
            $mauticformid = $record->mauticformid;
            $table->data[] = array($id, $event, $mauticformid, $actions);
        }
        return html_writer::table($table);
    }
}
