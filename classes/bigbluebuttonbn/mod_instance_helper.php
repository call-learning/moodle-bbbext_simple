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

use stdClass;

/**
 * Class defining a way to deal with instance save/update/delete in extension
 *
 * @package   bbbext_simple
 * @copyright 2023 onwards, Blindside Networks Inc
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Laurent David (laurent@call-learning.fr)
 */
class mod_instance_helper extends \mod_bigbluebuttonbn\local\extension\mod_instance_helper {
    /**
     * Get additional join tables for instance when extension activated
     *
     * @return array of additional tables names. They all have a field called bigbluebuttonbnid that identifies the bbb instance.
     */
    public function get_additional_tables(): ?array {
        return ['bbbext_simple'];
    }
    /**
     * Runs any processes that must run before a bigbluebuttonbn insert/update.
     *
     * @param stdClass $bigbluebuttonbn BigBlueButtonBN form data
     **/
    public function add_instance(stdClass $bigbluebuttonbn) {
        global $DB;
        $DB->insert_record('bbbext_simple', (object) [
            'bigbluebuttonbnid' => $bigbluebuttonbn->id,
            'newfield' => 2,
            'completionextraisehandtwice' => 0
        ]);
    }

    /**
     * Runs any processes that must be run after a bigbluebuttonbn insert/update.
     *
     * @param stdClass $bigbluebuttonbn BigBlueButtonBN form data
     **/
    public function update_instance(stdClass $bigbluebuttonbn): void {
        global $DB;
        $record = $DB->get_record('bbbext_simple', [
            'bigbluebuttonbnid' => $bigbluebuttonbn->id,
        ]);
        if ($record) {
            $record = (object) array_merge((array) $record,
                array_intersect_key((array) $bigbluebuttonbn, array_fill_keys(['newfield', 'completionextraisehandtwice'], null))
            );
            $DB->update_record('bbbext_simple', $record);
        } else {
            $this->add_instance($bigbluebuttonbn);
        }
    }

    /**
     * Runs any processes that must be run after a bigbluebuttonbn delete.
     *
     * @param int $id
     */
    public function delete_instance(int $id): void {
        global $DB;
        $DB->delete_records('bbbext_simple', [
            'bigbluebuttonbnid' => $id,
        ]);
    }
}
