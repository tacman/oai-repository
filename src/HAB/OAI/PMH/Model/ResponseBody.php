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

/**
 * Response body default implementation.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class ResponseBody implements ResponseBodyInterface
{
    /**
     * Entries.
     *
     * @var VisitableInterface[]
     */
    private $entries;

    /**
     * Resumption Token.
     *
     * @var ResumptionToken|null
     */
    private $resumptionToken;

    public function __construct ()
    {
        $this->entries = array();
    }

    /**
     * Append entry.
     *
     * @param  VisitableInterface $entry
     * @return void
     */
    public function append (VisitableInterface $entry) : void
    {
        $this->entries []= $entry;
    }

    /**
     * Set resumption token.
     *
     * @param  ResumptionToken $resumptionToken
     * @return void
     */
    public function setResumptiontoken (ResumptionToken $resumptionToken) : void
    {
        $this->resumptionToken = $resumptionToken;
    }

    /**
     * Return resumption token.
     *
     * @return ?ResumptionToken
     */
    public function getResumptiontoken () : ?ResumptionToken
    {
        return $this->resumptionToken;
    }

    /**
     * {@inheritDoc}
     */
    public function accept (VisitorInterface $visitor) : void
    {
        foreach ($this->entries as $entry) {
            $entry->accept($visitor);
        }
        if ($this->resumptionToken) {
            $this->resumptionToken->accept($visitor);
        }
    }
}
