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

use Psr\Container\ContainerInterface;

use HAB\OAI\PMH\Model;

use InvalidArgumentException;

/**
 * Repository default implementation.
 *
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
final class Repository implements RepositoryInterface
{
    /**
     * @var Model\Identity<string, mixed>
     */
    private $identity;

    /**
     * @var ContainerInterface
     */
    private $commands;

    /**
     * @var Command\Hydrator
     */
    private $hydrator;

    public function __construct (Model\Identity $identity, ContainerInterface $commands, Command\Hydrator $hydrator)
    {
        $this->identity = $identity;
        $this->commands = $commands;
        $this->hydrator = $hydrator;
    }

    /**
     * {@inheritDoc}
     */
    public function identify () : Model\Identity
    {
        return $this->identity;
    }

    public function getRecord ($identifier, $metadataPrefix) : Model\ResponseBodyInterface
    {
        /** @var Command\GetRecord */
        $command = $this->createCommand('GetRecord');
        $command->identifier = $identifier;
        $command->metadataPrefix = $metadataPrefix;
        return $command->execute();
    }

    public function listIdentifiers ($metadataPrefix, $from = null, $until = null, $set = null) : Model\ResponseBodyInterface
    {
        /** @var Command\ListIdentifiers */
        $command = $this->createCommand('ListIdentifiers');
        $command->metadataPrefix = $metadataPrefix;
        $command->from = $from;
        $command->until = $until;
        $command->set = $set;
        return $command->execute();
    }

    public function listRecords ($metadataPrefix, $from = null, $until = null, $set = null) : Model\ResponseBodyInterface
    {
        /** @var Command\ListRecords */
        $command = $this->createCommand('ListRecords');
        $command->metadataPrefix = $metadataPrefix;
        $command->from = $from;
        $command->until = $until;
        $command->set = $set;
        return $command->execute();
    }

    public function listMetadataFormats ($identifier = null) : Model\ResponseBodyInterface
    {
        /** @var Command\ListMetadataFormats */
        $command = $this->createCommand('ListSets');
        $command->identifier = $identifier;
        return $command->execute();

    }

    public function listSets () : Model\ResponseBodyInterface
    {
        /** @var Command\ListSets */
        $command = $this->createCommand('ListSets');
        return $command->execute();
    }

    public function resume ($verb, $resumptionToken) : Model\ResponseBodyInterface
    {
        /** @var Command\ResumableCommandInterface */
        $command = $this->createCommand($verb);
        if (!$command instanceof Command\ResumableCommandInterface) {
            throw new InvalidArgumentException("The respository does not support resuming the '{$verb}' opertaion");
        }
        $command->resume($this->hydrator, $resumptionToken);
        return $command->execute();
    }

    protected function createCommand (string $verb) : Command\CommandInterface
    {
        if (!$this->commands->has($verb)) {
            throw new InvalidArgumentException("The repository does not support the '{$verb}' operation");
        }
        return $this->commands->get($verb);
    }
}
