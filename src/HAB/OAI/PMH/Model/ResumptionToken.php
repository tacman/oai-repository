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
 * Resumption token default implementation.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class ResumptionToken implements VisitableInterface
{
    /**
     * Cursor.
     *
     * @var integer
     */
    private $cursor;

    /**
     * Complete List Size.
     *
     * @var integer
     */
    private $completeListSize;

    /**
     * Expiration Date.
     *
     * @var UtcDateTime
     */
    private $expirationDate;

    /**
     * Token.
     *
     * @var string
     */
    private $token;

    public function __construct ($token)
    {
        $this->token = $token;
    }

    /**
     * Return cursor.
     *
     * @return int
     */
    public function getCursor () : int
    {
        return $this->cursor;
    }

    /**
     * Return complete list size.
     *
     * @return int
     */
    public function getCompletelistsize () : int
    {
        return $this->completeListSize;
    }

    /**
     * Return expiration date.
     *
     * @return ?UtcDateTime
     */
    public function getExpirationdate () : ?UtcDateTime
    {
        return $this->expirationDate;
    }

    /**
     * Set cursor.
     *
     * @param  integer $cursor
     * @return void
     */
    public function setCursor ($cursor) : void
    {
        $this->cursor = $cursor;
    }

    /**
     * Set complete list size.
     *
     * @param  integer $completeListSize
     * @return void
     */
    public function setCompletelistsize ($completeListSize) : void
    {
        $this->completeListSize = $completeListSize;
    }

    /**
     * Set expiration date.
     *
     * @param  UtcDateTime $expirationDate
     * @return void
     */
    public function setExpirationdate (UtcDateTime $expirationDate) : void
    {
        $this->expirationDate = $expirationDate;
    }

    /**
     * {@inheritDoc}
     */
    public function accept (VisitorInterface $visitor) : void
    {
        $visitor->visitResumptionToken($this);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString () : string
    {
        return (string)$this->token;
    }
}
