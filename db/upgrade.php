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
 *
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @package   availability_sms
 * @copyright 27/08/2019 Mfreak.nl | LdesignMedia.nl - Luuk Verhoeven
 * @author    Luuk Verhoeven
 **/
defined('MOODLE_INTERNAL') || die;
function xmldb_availability_sms_upgrade($oldversion) {

    global $DB;
    $dbman = $DB->get_manager();
    if ($oldversion < 2019082700) {

        // Define table availability_sms to be created.
        $table = new xmldb_table('availability_sms');

        // Adding fields to table availability_sms.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('phone', XMLDB_TYPE_CHAR, '100', null, null, null, null);
        $table->add_field('course', XMLDB_TYPE_INTEGER, '11', null, null, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '11', null, null, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '11', null, null, null, '0');

        // Adding keys to table availability_sms.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for availability_sms.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Sms savepoint reached.
        upgrade_plugin_savepoint(true, 2019082700, 'availability', 'sms');
    }

    if ($oldversion < 2019091900) {

        // Define field contextid to be added to availability_sms.
        $table = new xmldb_table('availability_sms');
        $field = new xmldb_field('contextid', XMLDB_TYPE_INTEGER, '11', null, null, null, '0', 'userid');

        // Conditionally launch add field contextid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Sms savepoint reached.
        upgrade_plugin_savepoint(true, 2019091900, 'availability', 'sms');
    }

    return true;

}
