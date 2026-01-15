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
 * @package   quizaccess_antiai
 * @copyright  2026 ETH Zurich
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_antiai extends access_rule_base {
    #[\Override]
    public static function make(quiz_settings $quizobj, $timenow, $canignoretimelimits) {

        if ($quizobj->get_quiz()->browsersecurity !== 'antiai') {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    #[\Override]
    public function setup_attempt_page($page) {
        global $SESSION;
        $page->set_popup_notification_allowed(false); // Prevent message notifications.
        $page->set_title($this->quizobj->get_course()->shortname . ': ' . $page->title);
        $page->set_pagelayout('secure');
        $this->antiai_getsessioninfo();
        if ($SESSION->quizaccess_antiai_access == 0) {
            throw new \moodle_exception(get_string('aiextensionfound', 'quizaccess_antiai'));
        }
    }

    /**
     * Describe any messages to show on the attempt page.
     *
     * @return array
     * @throws coding_exception
     */
    public function description(): array {
        global $PAGE, $SESSION;
        $messages = [];
        $this->antiai_getsessioninfo();
        if (isset($SESSION->quizaccess_antiai_access) && $SESSION->quizaccess_antiai_access == 0) {
            $messages = [html_writer::div(get_string('aiextensionfound', 'quizaccess_antiai'), 'alert alert-warning')];
        }
        return $messages;
    }

    /**
     * Include the JavaScript module to get session info from client side.
     *
     * @return void
     */
    public static function antiai_getsessioninfo() {
         global $PAGE;
        $PAGE->requires->js_call_amd('quizaccess_antiai/antiai', 'init');
    }

    /**
     * Get any browser security choices added by this rule.
     *
     * @return array key => lang string any choices to add to the quiz Browser security settings menu.
     * @throws coding_exception
     */
    public static function get_browser_security_choices() {
        return ['antiai' =>
            get_string('popupwithjavascriptsupport', 'quizaccess_antiai')];
    }
}
