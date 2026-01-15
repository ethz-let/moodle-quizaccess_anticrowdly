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

use mod_quiz\local\access_rule_base;
use mod_quiz\quiz_settings;

/**
 * A rule for ensuring that the quiz is opened in a popup, with some JavaScript
 * to prevent copying and pasting, etc.
 *
 * @package   quizaccess_anticrowdly
 * @copyright  2026 ETH Zurich
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_anticrowdly extends access_rule_base {

    public static function make(quiz_settings $quizobj, $timenow, $canignoretimelimits) {

        if ($quizobj->get_quiz()->browsersecurity !== 'anticrowdly') {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    public function setup_attempt_page($page) {
        global $SESSION;
        $page->set_popup_notification_allowed(false); // Prevent message notifications.
        $page->set_title($this->quizobj->get_course()->shortname . ': ' . $page->title);
        $page->set_pagelayout('secure');
        $this->anticrowdly_getsessioninfo();
        if($SESSION->quizaccess_anticrowdly_access == 0){
            throw new \moodle_exception(get_string('aiextensionfound','quizaccess_anticrowdly'));
        }
    }
    
    public function description(): array {
        global $PAGE, $SESSION;
        $messages = [];
        $this->anticrowdly_getsessioninfo();
        if($SESSION->quizaccess_anticrowdly_access == 0){
            $messages = [html_writer::div(get_string('aiextensionfound','quizaccess_anticrowdly'), 'alert alert-warning')];
        }
        return $messages;

    }
    public static function anticrowdly_getsessioninfo() {
         global $PAGE, $SESSION, $CFG;
         if (!isset($SESSION->quizaccess_anticrowdly_access)) {
            $SESSION->quizaccess_anticrowdly_access = 0;
        }
        $PAGE->requires->js_call_amd('quizaccess_anticrowdly/anticrowdly', 'init', [$SESSION->quizaccess_anticrowdly_access]);
        $PAGE->requires->js('/mod/quiz/accessrule/anticrowdly/changes.js');
    }

    /**
     * @return array key => lang string any choices to add to the quiz Browser
     *      security settings menu.
     */
    public static function get_browser_security_choices() {
        return ['anticrowdly' =>
                get_string('preventcrowdly', 'quizaccess_anticrowdly')];
    }
}
