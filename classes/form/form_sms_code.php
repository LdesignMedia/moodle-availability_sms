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
 * Sms request form
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

namespace availability_sms\form;


defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Class form_sms_request
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */
class form_sms_code extends \moodleform {

    /**
     * @throws \coding_exception
     */
    protected function definition() {
        global $USER;
        $mform = &$this->_form;
        // Fix issue with validation.
        $mform->addElement('hidden', 'phone');
        $mform->setType('phone', PARAM_ALPHANUM);

        $mform->addElement('static', 'phone_send', '', \html_writer::div(
            get_string('text:request_new', 'availability_sms', $USER), 'alert alert-warning'));

        $mform->addElement('text', 'code', get_string('form:code', 'availability_sms'), [
            'placeholder' => 'XX-XX-XX-XX',
        ]);
        $mform->setType('code', PARAM_TEXT);
        $mform->addRule('code', null, 'required', null, 'client');

        $this->add_action_buttons(false, get_string('btn:validate', 'availability_sms'));

    }

    /**
     * validation
     *
     * @param array $data
     * @param array $files
     *
     * @return array
     * @throws \coding_exception
     * @throws moodle_exception
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        $status = \availability_sms\helper::validate_sms_code((object) $data);

        if(empty($status)){
            $errors['code'] = get_string('error:incorrect_code' , 'availability_sms');
        }

        return $errors;

    }

}