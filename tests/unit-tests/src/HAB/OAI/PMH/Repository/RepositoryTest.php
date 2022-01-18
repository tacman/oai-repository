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
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Repository;

use HAB\OAI\PMH\Model\Identity;

use Psr\Container\ContainerInterface;

use PHPUnit\Framework\TestCase;

use InvalidArgumentException;

/**
 * Unit tests for the default repository implementation.
 *
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class RepositoryTest extends TestCase
{
    public function testInvalidArgumentExceptionOnUnsupportedVerb () : void
    {
        $this->expectException(InvalidArgumentException::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')
                  ->willReturn(false);
        $identity = new Identity();
        $hydrator = new Command\Hydrator(hash_hmac_algos()[0], 'key');

        $repository = new Repository($identity, $container, $hydrator);
        $repository->getRecord(null, null);
    }

    public function testInvalidArgumentExceptionOnUnresumable () : void
    {
        $this->expectException(InvalidArgumentException::class);
        $command = $this->createMock(Command\GetRecord::class);
        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')
                  ->willReturn(true);
        $container->method('get')
                  ->willReturn($command);
        $identity = new Identity();
        $hydrator = new Command\Hydrator(hash_hmac_algos()[0], 'key');

        $repository = new Repository($identity, $container, $hydrator);
        $repository->resume('GetRecord', 'invalid');
    }
}
