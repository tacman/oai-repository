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

use PHPUnit\Framework\TestCase;

use HAB\OAI\PMH\Model\ResumptionToken;
use HAB\OAI\PMH\ProtocolError\BadResumptionToken;

/**
 * Unit tests for the Resumable trait.
 *
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class ResumableTest extends Testcase
{
    public function testCreateResumptionTokenNoTokenRequired () : void
    {
        $hydrator = $this->createMock(HydratorInterface::class);
        $command = $this->getMockForTrait(Resumable::class);
        $token = $command->createResumptionToken($hydrator, 0, 10);
        $this->assertNull($token);
    }

    public function testCreateResumptionToken () : void
    {
        $hydrator = $this->getMockBuilder(HydratorInterface::class)
                         ->getMock();
        $hydrator->method('extract')
                 ->willReturn('payload');

        $command = $this->getMockForTrait(Resumable::class);
        $token = $command->createResumptionToken($hydrator, 0, 60);
        $this->assertInstanceOf(ResumptionToken::class, $token);
        $this->assertEquals('payload', $token->__toString());
    }

    public function testResumeInvalidToken () : void
    {
        $this->expectException(BadResumptionToken::class);
        $hydrator = $this->getMockBuilder(HydratorInterface::class)
                         ->getMock();
        $hydrator->method('hydrate')
                 ->willReturn(false);
        $command = $this->getMockForTrait(Resumable::class);
        $command->resume($hydrator, 'does-not-matter');
    }
}
