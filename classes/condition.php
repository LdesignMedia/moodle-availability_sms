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
 * Condition class
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

namespace availability_sms;

use core_availability\info;

defined('MOODLE_INTERNAL') || die;

/**
 * Class condition
 *
 * @package   availability_sms
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */
class condition extends \core_availability\condition {

    /**
     * condition constructor.
     *
     * @param \stdClass $structure
     */
    public function __construct($structure) {
    }

    /**
     * Determines whether a particular item is currently available
     * according to this availability condition.
     *
     * If implementations require a course or modinfo, they should use
     * the get methods in $info.
     *
     * The $not option is potentially confusing. This option always indicates
     * the 'real' value of NOT. For example, a condition inside a 'NOT AND'
     * group will get this called with $not = true, but if you put another
     * 'NOT OR' group inside the first group, then a condition inside that will
     * be called with $not = false. We need to use the real values, rather than
     * the more natural use of the current value at this point inside the tree,
     * so that the information displayed to users makes sense.
     *
     * @param bool $not        Set true if we are inverting the condition
     * @param info $info       Item we're checking
     * @param bool $grabthelot Performance hint: if true, caches information
     *                         required for all course-modules, to make the front page and similar
     *                         pages work more quickly (works only for current user)
     * @param int $userid      User ID to check availability for
     *
     * @return bool True if available
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function is_available($not, info $info, $grabthelot, $userid) {
        global $SESSION, $PAGE;

        $contextid = $info->get_context()->id;
        $course = $info->get_course();

        if (has_capability('moodle/site:manageblocks', $info->get_context())) {
            return true;
        }

        if (!empty($SESSION->availability_sms[$course->id][$contextid])) {
            return true;
        }

        // Redirect if not the course page and id matches instance..
        $id = optional_param('id', 0, PARAM_INT);
        if (stristr($PAGE->url->get_path(), '/mod/') &&
            $info->get_context()->instanceid == $id) {

            $SESSION->wantsurl = $PAGE->url->out(false);
            redirect(new \moodle_url('/availability/condition/sms/view.php', [
                'courseid' => $course->id,
                'contextid' => $contextid,
            ]));
        }

        return false;
    }

    /**
     * Obtains a string describing this restriction (whether or not
     * it actually applies). Used to obtain information that is displayed to
     * students if the activity is not available to them, and for staff to see
     * what conditions are.
     *
     * The $full parameter can be used to distinguish between 'staff' cases
     * (when displaying all information about the activity) and 'student' cases
     * (when displaying only conditions they don't meet).
     *
     * If implementations require a course or modinfo, they should use
     * the get methods in $info.
     *
     * The special string <AVAILABILITY_CMNAME_123/> can be returned, where
     * 123 is any number. It will be replaced with the correctly-formatted
     * name for that activity.
     *
     * @param bool $full Set true if this is the 'full information' view
     * @param bool $not  Set true if we are inverting the condition
     * @param info $info Item we're checking
     *
     * @return string Information string (for admin) about all restrictions on
     *   this item
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function get_description($full, $not, info $info) {
        return get_string('require_condition', 'availability_sms');
    }

    /**
     * Obtains a representation of the options of this condition as a string,
     * for debugging.
     *
     * @return string Text representation of parameters
     */
    protected function get_debug_string() {
        return 'sms ON';
    }

    /**
     * Returns a JSON object which corresponds to a condition of this type.
     *
     * Intended for unit testing, as normally the JSON values are constructed
     * by JavaScript code.
     *
     * @return \stdClass Object representing condition
     */
    public static function get_json() {
        return (object) [
            'type' => 'sms',
        ];
    }

    /**
     * Saves tree data back to a structure object.
     *
     * @return \stdClass Structure object (ready to be made into JSON format)
     */
    public function save() {
        return (object) [
            'type' => 'sms',
        ];
    }

}
