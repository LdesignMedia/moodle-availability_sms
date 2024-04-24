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

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use moodle_exception;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Class form_sms_code
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */
class form_sms_request extends \moodleform {

    /**
     * @throws \coding_exception
     */
    protected function definition() {
        global $USER;

        $mform = &$this->_form;

        if (empty($USER->phone1)) {

            $mform->addElement('static', 'phone_empty', '', \html_writer::div(
                get_string('error:phone_missing', 'availability_sms'), 'alert alert-warning'));
        }

        $mform->addElement('static', '1567780487486', '', \html_writer::div(get_string('text:phone_description', 'availability_sms'), ''));

        $mform->addElement('text', 'phone', get_string('form:phone1', 'availability_sms'), [
            'style' => 'width:100%',
            'placeholder' => get_string('form:placeholder_phone1', 'availability_sms'),
        ]);
        $mform->setType('phone', PARAM_ALPHANUM);
        $mform->setDefault('phone', $USER->phone1);
        $mform->addRule('phone', null, 'required', null, 'client');

        $this->add_action_buttons(false, get_string('btn:request', 'availability_sms'));
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

        global $USER;
        require_once(__DIR__ . '/../../vendor/autoload.php');

        if (empty($USER->country)) {
            throw new moodle_exception('error:country_missing', 'availability_sms');
        }

        if (empty($data['phone'])) {
            $errors['phone'] = get_string('error:invalid_phone', 'availability_sms');

            return $errors;
        }

        $phoneutil = PhoneNumberUtil::getInstance();

        try {
            $phone = $phoneutil->parse($data['phone'], $USER->country);
        } catch (NumberParseException $e) {
            $errors['phone'] = 'Error: ' . $e->getMessage();

            return $errors;
        }

        // Verify it's a valid phone number.
        if ($phoneutil->isValidNumber($phone) == false) {
            $errors['phone'] = get_string('error:invalid_phone', 'availability_sms');
        }

        return $errors;
    }

}
