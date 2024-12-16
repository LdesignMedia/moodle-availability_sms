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
 * Main sms provider should be extended.
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

namespace availability_sms;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use moodle_exception;

defined('MOODLE_INTERNAL') || die;

/**
 * Class provider
 *
 * @package availability_sms
 */
abstract class provider implements interfaces\provider {

    /**
     * @var \stdClass
     */
    protected $config;

    /**
     * provider constructor.
     *
     * @throws \dml_exception
     */
    public function __construct() {
        $this->config = get_config('availability_sms');
    }

    /**
     * Some default sending validation.
     *
     * @param string $country ISO 3166-1
     * @param string $phone
     * @param string $message
     *
     * @return mixed|void
     * @throws moodle_exception
     */
    public function send_sms(string $country, string $phone, string $message = '') {

        // Validation.
        if (empty($phone)) {
            throw new moodle_exception('error:empty_phone', 'availability_sms');
        }

        if (empty($message)) {
            throw new moodle_exception('error:empty_message', 'availability_sms');
        }

        if (empty($country)) {
            throw new moodle_exception('error:empty_country', 'availability_sms');
        }
    }

    /**
     * Parse phone and throw exception on failure
     *
     * Online testing with
     * https://giggsey.com/libphonenumber/
     *
     * @param string $phone
     * @param string $country
     *
     * @return string
     * @throws moodle_exception
     */
    final protected function parse_phone_number(string $phone, string $country): string {

        // Start with 00.
        // Country code.
        // Phone number.
        // No + sign.
        // No spaces.
        // No -.
        $phoneutil = PhoneNumberUtil::getInstance();

        try {
            $phone = $phoneutil->parse($phone, $country);
        } catch (NumberParseException $e) {
            throw new $e;
        }

        // Verify it's a valid phone number.
        if ($phoneutil->isValidNumber($phone) == false) {
            throw new moodle_exception('error:invalid_phone', 'availability_sms' , '',s($phone));
        }

        // Check if there are zeros added to the start.
        return '00' . $phone->getCountryCode() . $phone->getNationalNumber();
    }

    /**
     * Parse message
     *
     * @param string $message
     *
     * @return string
     * @throws moodle_exception
     */
    final protected function parse_message(string $message) {
        // Required. This is the message text.
        // Restrictions: the maximum length is 160 characters.
        if (strlen($message) > 160) {
            throw new moodle_exception('error:message_length', 'availability_sms');
        }

        return $message;
    }

}
