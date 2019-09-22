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
 * Send SMS provider cmtelecom.com
 * https://docs.cmtelecom.com/en/api/business-messaging-api/1.0/index
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

namespace availability_sms\provider;

use availability_sms\provider;
use CMText\TextClient;
use moodle_exception;

defined('MOODLE_INTERNAL') || die;

/**
 * Class cm
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */
class cm extends provider {

    /**
     * Send sms
     *
     * @param string $country
     * @param string $phone
     * @param string $message
     *
     * @return mixed
     * @throws moodle_exception
     */
    public function send_sms(string $country, string $phone, string $message = '') {
        global $SITE;

        // Validation.
        parent::send_sms($country, $phone, $message);

        // Send SMS.
        require_once(__DIR__ . '/../../vendor/autoload.php');
        $config = $this->config;
        $client = new TextClient($config->cm_producttoken);

        $message = $this->parse_message($message);
        $phone = $this->parse_phone_number($phone, $country);

        $result = $client->SendMessage($message, $SITE->shortname, [$phone]);

        if ($result->statusCode != 0 && !empty($result->statusMessage)) {
            throw new \Exception($result->statusMessage);
        }

        return true;
    }

}