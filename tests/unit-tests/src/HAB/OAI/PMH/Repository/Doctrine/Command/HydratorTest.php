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
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Repository\Doctrine\Command;

use PHPUnit_Framework_TestCase as TestCase;

/**
 * Unit tests for the command hydrator.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class HydratorTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testCreateResumptionTokenThrowsOnNonObject ()
    {
        $hydrator = new Hydrator();
        $hydrator->createResumptionToken(null);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testResumeThrowsOnNonObject ()
    {
        $hydrator = new Hydrator();
        $hydrator->resume(null, 'token');
    }

    /**
     * @expectedException HAB\OAI\PMH\ProtocolError\BadResumptionToken
     */
    public function testResumeThrowsOnInvalidEncoding ()
    {
        $hydrator = new Hydrator();
        $hydrator->resume(new \StdClass(), 'token');
    }

    public function testHydrationCycle ()
    {
        $command = new \StdClass();
        $command->foobar = "Foobar";
        
        $hydrator = new Hydrator();
        $token = $hydrator->createResumptionToken($command);
        
        $this->assertInternalType('string', $token);
        $command = new \StdClass();
        $command->foobar = null;
        $hydrator->resume($command, $token);
        $this->assertEquals('Foobar', $command->foobar);
    }
}