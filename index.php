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
 * Display user list.
 *
 * @package     local_coursereport
 * @copyright   2021 Jo Beaver
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_coursereport\output\index;

require_once(__DIR__ . '/../../config.php');

require_login();

$context = context_system::instance();
$PAGE->set_context($context);

$url = new moodle_url('/local/coursereport/index.php');
$title = get_string('pluginname', 'local_coursereport');

$PAGE->set_url($url);
$PAGE->set_heading($title);
$PAGE->set_title($title);
$PAGE->add_body_class('report');

$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('pluginname', 'local_coursereport'));

echo $OUTPUT->header();

$data = array_values(index::get_user_data());

echo $OUTPUT->render_from_template('local_coursereport/index', ['rows' => $data]);

echo $OUTPUT->footer();
