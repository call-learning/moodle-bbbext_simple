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
namespace bbbext_simple\bigbluebuttonbn;

use completion_info;
use stdClass;

/**
 * Completion raise hand twice computation class
 *
 * @package   bbbext_simple
 * @copyright 2023 onwards, Blindside Networks Inc
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Laurent David (laurent@call-learning.fr)
 */
class mod_form_addons extends \mod_bigbluebuttonbn\local\extension\mod_form_addons {
    /**
     * Preprocess process data for completion
     *
     * @param array $defaultvalues
     * @return void
     */
    public function data_preprocessing(array &$defaultvalues): void {
        if (!empty($this->bigbluebuttonbndata)) {
            global $DB;
            $record = $DB->get_record('bbbext_simple', [
                'bigbluebuttonbnid' => $this->bigbluebuttonbndata->id,
            ]);
            if (!empty($record)) {
                unset($record->id);
                unset($record->bigbluebuttonbnid);
                $defaultvalues = array_merge($defaultvalues, (array) $record);
            }
        }
    }

    /**
     * Is the form element enabled
     *
     * @param array $data current data allowing to check if completion enabled or not.
     * @return bool
     */
    public function completion_rule_enabled(array $data): bool {
        return !empty($data['completionextraisehandtwice']);
    }

    /**
     * Add additional form elements for this completion group (module editing form)
     *
     * @return array
     */
    public function add_completion_rules(): array {
        $this->mform->addElement('advcheckbox', 'completionextraisehandtwice',
            get_string('completionextraisehandtwice', 'bbbext_simple'),
            get_string('completionextraisehandtwice_desc', 'bbbext_simple'));

        $this->mform->addHelpButton('completionextraisehandtwice', 'completionextraisehandtwice',
            'bbbext_simple');
        $this->mform->disabledIf('completionextraisehandtwice', 'completion', 'neq', COMPLETION_AGGREGATION_ANY);
        return ['completionextraisehandtwice'];
    }

    /**
     * Allows modules to modify the data returned by form get_data().
     * This method is also called in the bulk activity completion form.
     *
     * Only available on moodleform_mod.
     *
     * @param stdClass $data passed by reference
     */
    public function data_postprocessing(stdClass &$data): void {
    }
}
