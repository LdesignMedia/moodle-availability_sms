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
 * Enter SMS token page.
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/
require_once('../../../config.php');
defined('MOODLE_INTERNAL') || die;

$courseid = required_param('courseid', PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);

$course = $DB->get_record('course', ['id' => $courseid]);
require_login($course);

$PAGE->set_url('/availability/condition/sms/view.php', [
    'courseid' => $courseid,
    'action' => $action,
]);
$PAGE->set_title($course->fullname);
$PAGE->set_heading($course->fullname);

switch ($action) {

    case 'validate':
        $form = new \availability_sms\form\form_sms_code($PAGE->url);
        if (($data = $form->get_data()) != false) {
            redirect(new moodle_url('/course/view.php', ['id' => $COURSE->id]) ,
                get_string('text:success_enter_course' , 'availability_sms') , 5);
        }
        break;

    default:
        $form = new \availability_sms\form\form_sms_request($PAGE->url);

        if (($data = $form->get_data()) != false) {
            \availability_sms\helper::request_sms($data);
            redirect(new moodle_url('/availability/condition/sms/view.php', [
                'action' => 'validate',
                'courseid' => $courseid,
            ]));
        }
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('header:sms_validation', 'availability_sms'));

echo $form->render();

echo $OUTPUT->footer();