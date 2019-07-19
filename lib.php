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
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

defined('MOODLE_INTERNAL') || die;

/**
 * This hook was introduced in moodle 3.3.
 *
 * @throws dml_exception
 * @throws coding_exception
 * @throws moodle_exception
 */
function availability_sms_before_http_headers() {
    global $PAGE, $COURSE;

    if ($PAGE->url->get_path() !== '/course/view.php') {
        return;
    }

    if (\availability_sms\helper::course_has_sms_condition($COURSE->id) === false) {
        return;
    }

    // Check if popup validation required.
    if (\availability_sms\helper::user_has_verified_sms($COURSE->id) === true) {
        return;
    }

    $blockcourseaccess = get_config('availability_sms', 'course_popup');

    if (empty($blockcourseaccess) || $PAGE->user_allowed_editing()) {
        return;
    }

    // Show verify popup.
    \availability_sms\helper::show_sms_page($COURSE->id);
}