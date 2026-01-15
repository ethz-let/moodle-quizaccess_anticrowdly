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
 * External Web Service for AntiAi
 *
 * @package    quizaccess_antiai
 * @copyright  2026 ETH Zurich (moodle@id.ethz.ch)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
/**
 * Service functions.
 */
class quizaccess_antiai_external extends external_api{
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.3
     */
    public static function manageaccess_parameters() {
        return new external_function_parameters(
            [
                       'status' => new external_value(PARAM_INT, 'Status from JS', VALUE_REQUIRED, '', NULL_NOT_ALLOWED),

            ]
        );
    }

    /**
     * Function to manage access
     *
     * @param int $status for status change
     * @throws \moodle_exception
     */
    public static function manageaccess($status) {
        global $USER, $DB, $CFG, $SESSION;
        // Parameter validation.
        $params = self::validate_parameters(
            self::manageaccess_parameters(),
            ['status' => $status]
        );

        $status = $params['status'];
        if (!isset($SESSION->quizaccess_antiai_access)) {
            $SESSION->quizaccess_antiai_access = 0;
        }
        $SESSION->quizaccess_antiai_access = $status;
        $statusdata[] = [
            'status' => $status,
        ];

        $result = [];
        $result['data'] = $statusdata;
        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.2
     */
    public static function manageaccess_returns() {

        return new external_single_structure(
            [
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        [
                            'status' => new external_value(PARAM_INT, 'The status code'),
                        ]
                    ),
                    'Manage Access'
                ),
            ]
        );
    }
}
