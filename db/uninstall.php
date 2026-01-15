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
 * Upgrade script for plugin.
 *
 * @package    quizaccess_antiai
 * @author     ETH Zurich (moodle@id.ethz.ch)
 * @copyright  2026 ETH Zurich
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function to upgrade quizaccess_antiai plugin.
 *
 * @return bool Result.
 */
function xmldb_quizaccess_antiai_uninstall() {
    global $DB;
    $dbman = $DB->get_manager();
    $oldrecord = $DB->get_record('external_services', ['shortname' => 'AntiAI-Webservice']);
    if ($oldrecord) {
        $params = ['id' => $oldrecord->id];
        $DB->delete_records('external_services', $params);

        $eparams = ['externalserviceid' => $oldrecord->id];
        $DB->delete_records('external_services_functions', $eparams);

        $uparams = ['externalserviceid' => $oldrecord->id];
        $DB->delete_records('external_services_users', $uparams);
    }
    // Delete possible AntiAI connection data.
    unset_all_config_for_plugin('quizaccess_antiai');

    return true;
}
