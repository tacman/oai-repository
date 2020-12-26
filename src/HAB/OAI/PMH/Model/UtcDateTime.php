<?php

/**
 * This file is part of HAB OAI Repository.
 *
 * HAB OAI Repository is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * HAB OAI Repository is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HAB OAI Repository.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Model;

use DateTimeZone;
use DateTimeImmutable;

/**
 * UtcDateTime.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
final class UtcDateTime
{
    const G_DATETIME = 'Y-m-d\TH:i:s\Z';
    const G_DATE     = 'Y-m-d';

    /**
     * Datetime.
     *
     * @var DateTimeImmutable
     */
    private $datetime;

    /**
     * Return true if argument is valid UTCdatetime string.
     *
     * @param  string $datetime
     * @return bool
     */
    public static function isValid ($datetime) : bool
    {
        if (preg_match('/^\d\d\d\d-\d\d-\d\d(T\d\d:\d\d:\d\dZ)?$/u', $datetime)) {
            if (strlen($datetime) === 10) {
                $fmtString = self::G_DATE;
            } else {
                $fmtString = self::G_DATETIME;
            }
            @date_create_from_format($fmtString, $datetime);
            $errors = date_get_last_errors();
            if ($errors) {
                if ($errors['warning_count'] > 0 or $errors['error_count'] > 0) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public function __construct (string $datetime = 'now')
    {
        $this->datetime = new DateTimeImmutable($datetime, new DateTimeZone('UTC'));
    }

    /**
     * Return string representation.
     *
     * @return string
     */
    public function __toString () : string
    {
        return (string)$this->datetime->format(self::G_DATETIME);
    }

}
