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
 * Helper functions
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/

namespace availability_sms;

use core_availability\info_module;
use moodle_exception;

defined('MOODLE_INTERNAL') || die;

/**
 * Class helper
 *
 * @package   availability_sms
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2019-07-19 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 */
class helper {

    /**
     * crypto_rand_secure
     *
     * @param int    $min
     * @param string $max
     *
     * @return string
     */
    public static function crypto_rand_secure($min, $max) {

        $range = $max - $min;
        if ($range < 0) {
            return $min;
        }

        $log = log($range, 2);
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes((int)($log / 8) + 1)));
            $rnd = $rnd & (int)(1 << (int)$log + 1) - 1;
        } while ($rnd >= $range);

        return $min + $rnd;
    }

    /**
     * get unique code
     *
     * @param int    $length
     *
     * @param string $prefix
     *
     * @return string
     */
    public static function get_token($length, $prefix = '-') : string {

        $token = "";
        $codealphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codealphabet .= "0123456789";
        for ($i = 0; $i < $length; $i++) {
            if ($i % 2 === 0 && $i > 0) {
                $token .= $prefix;
            }
            $token .= $codealphabet[self::crypto_rand_secure(0, strlen($codealphabet))];
        }

        return $token;
    }

    /**
     * course_has_sms_condition
     *
     * @param int $courseid
     *
     * @return bool
     * @throws moodle_exception
     */
    public static function course_has_sms_condition(int $courseid) : bool {
        $modinfo = get_fast_modinfo($courseid);
        $activityinstances = $modinfo->get_cms();

        foreach ($activityinstances as $instance) {

            if (!$instance->availability) {
                continue;
            }

            $ci = new info_module($instance);
            $tree = $ci->get_availability_tree();
            $sms = $tree->get_all_children('availability_sms\condition');

            if (!empty($sms)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Redirect to SMS verification page
     *
     * @throws moodle_exception
     */
    public static function show_sms_page(int $courseid) {

        redirect(new \moodle_url('/availability/condition/sms/view.php', [
            'course' => $courseid
        ]));
    }

    /**
     * Check if the user has entered a valid SMS token
     *
     * @param int $courseid
     *
     * @return bool
     */
    public static function user_has_verified_sms(int $courseid) : bool {
        global $SESSION;

        if (!empty($SESSION->availability_sms[$courseid])) {
            return true;
        }

        return false;

    }
}