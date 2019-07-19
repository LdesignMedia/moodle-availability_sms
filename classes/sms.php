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
 * SMS provider wrapper
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

namespace availability_sms;
use availability_sms\proxy\cm;

defined('MOODLE_INTERNAL') || die;

/**
 * Class sms
 *
 * @package availability_sms
 */
final class sms {

    /**
     * @var cm
     */
    private $provider;

    /**
     * sms constructor.
     */
    public function __construct() {
        // Load the selected proxy.

        // For now only 1 provider supported.
        $this->provider = new cm();
    }

    /**
     * Send a SMS
     *
     * @param \stdClass $user
     * @param string    $message
     *
     * @throws \moodle_exception
     */
    public function send(\stdClass $user , string $message = '') {
        $this->provider->send_sms($user->selected_phone , $message);
    }

}