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
 * Class containing data for the index page.
 *
 * @package     local_coursereport
 * @copyright   2021 Jo Beaver
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_coursereport\output;

use renderable;
use templatable;
use html_writer;
use moodle_url;
use renderer_base;

class index implements renderable, templatable {

    /**
     * Get the list of users.
     */
    public static function get_user_data() {
        global $DB;

        $records = $DB->get_records('user');

        $data = [];
        foreach ($records as $record) {
            $name = fullname($record);
            $name = html_writer::link(new moodle_url('/local/coursereport/report.php',['id'=>$record->id]), $name);
            $data[] = [
                'name' => $name
            ];
        }
        return $data;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param renderer_base $output
     * @return array $data
     */
    public function export_for_template(renderer_base $output) {

        $data = $this->get_user_data();

        return $data;
    }
}
