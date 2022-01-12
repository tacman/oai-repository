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
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Repository\Command;

use StdClass;

use PHPUnit\Framework\TestCase;

use HAB\OAI\PMH\ProtocolError\BadResumptionToken;

/**
 * Unit tests for the Hydrator default implementation.
 *
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class HydratorTest extends TestCase
{
    public function testHydrateUnknownProperty () : void
    {
        $hydrator = new Hydrator();
        $command = new StdClass();
        $command->property = 1;
        $token = $hydrator->extract($command);

        $command = new StdClass();
        $command->otherprop = 2;

        $this->assertFalse($hydrator->hydrate($command, $token));
    }

    public function testHydrateInvalidToken () : void
    {
        $hydrator = new Hydrator();
        $command = new StdClass();
        $command->property = 1;

        $this->assertFalse($hydrator->hydrate($command, ''));
    }

    public function testHydrate () : void
    {
        $hydrator = new Hydrator();
        $command = new StdClass();
        $command->property = 1;
        $token = $hydrator->extract($command);

        $command = new StdClass();
        $command->property = null;

        $this->assertTrue($hydrator->hydrate($command, $token));
        $this->assertEquals('1', $command->property);
    }

}
