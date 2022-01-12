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

use HAB\OAI\PMH\ProtocolError\BadResumptionToken;
use HAB\OAI\PMH\Model\ResumptionToken;

/**
 * Resumable commands.
 *
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
trait Resumable
{
    /**
     * Cursor position.
     *
     * @var int
     */
    public $cursor = 0;

    /**
     * Number of items per page.
     *
     * @var int
     */
    public $itemsPerPage = 50;

    /**
     * Create an resumption token.
     *
     * @param  HydratorInterface $hydrator
     * @param  int $cursor
     * @param  int $completeListSize
     * @return ResumptionToken|null
     */
    public function createResumptionToken (HydratorInterface $hydrator, int $cursor, int $completeListSize) : ?ResumptionToken
    {
        if ($cursor + $this->itemsPerPage < $completeListSize) {
            $value = $hydrator->extract($this);
            $token = new ResumptionToken($value);
            $token->setCursor($this->cursor);
            $token->setCompleteListSize($completeListSize);
            return $token;
        }
        return null;
    }

    /**
     * Resume command.
     *
     * @throws BadResumptionToken
     *
     * @param  HydratorInterface $hydrator
     * @param  string $token
     * @return void
     */
    public function resume (HydratorInterface $hydrator, string $token) : void
    {
        if ($hydrator->hydrate($this, $token) === false) {
            throw new BadResumptionToken();
        }
        $this->cursor += $this->itemsPerPage;
    }
}
