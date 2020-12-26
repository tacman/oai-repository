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
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Model;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the UtcDateTime class.
 *
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class UtcDateTimeTest extends TestCase
{
    public function testIsValid ()
    {
        $this->assertTrue(UtcDateTime::isValid('2000-01-01'));
        $this->assertTrue(UtcDateTime::isValid('2000-01-01T00:00:00Z'));
        $this->assertFalse(UtcDateTime::isValid('now'));
        $this->assertFalse(UtcDateTime::isValid('2000-13-01'));
        $this->assertFalse(UtcDateTime::isValid('2000-01-01T25:00:00Z'));
    }
}
