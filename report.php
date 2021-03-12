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
 * Display user course completion information.
 *
 * @package     local_coursereport
 * @copyright   2021 Jo Beaver
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once("{$CFG->libdir}/completionlib.php");

$userid = required_param('id', PARAM_INT);
$user = $DB->get_record('user', ['id' => $userid]);
$username = fullname($user);

require_login();

$context = context_system::instance();
$PAGE->set_context($context);

$url = new moodle_url('/local/coursereport/report.php');
$title = get_string('pluginname', 'local_coursereport');

$PAGE->set_url($url);
$PAGE->set_heading($title);
$PAGE->set_title($title);
$PAGE->add_body_class('report');

$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('pluginname', 'local_coursereport'));

echo $OUTPUT->header();

$courses = enrol_get_all_users_courses($userid);

$coursedata = [];
foreach ($courses as $course) {

    $coursename = html_writer::link(new moodle_url('/course/view.php',['id'=>$course->id]), $course->fullname);

    // Get course completion data.
    $info = new completion_info($course);

    $coursecomplete = $info->is_course_complete($userid);
    if (!$coursecomplete) {
        $completion = 'Not complete';
    } else if ($coursecomplete) {
        $completion = 'Complete';
    }

    // Load course completion.
    $params = array(
        'userid' => $userid,
        'course' => $course->id
    );
    $ccompletion = new completion_completion($params);

    $coursedata[] = [
        'course' => $coursename,
        'completion' => $completion,
        'timecomplete' => $ccompletion->timecompleted
    ];
}

$data = [
    'name' => $username,
    'row' => array_values($coursedata)
];

echo $OUTPUT->render_from_template('local_coursereport/report', $data);

echo $OUTPUT->footer();
