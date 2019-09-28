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
 * Record header default implementation.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Header implements HeaderInterface, VisitableInterface
{

    /**
     * Identifier.
     *
     * @var string
     */
    private $identifier;

    /**
     * Datestamp.
     *
     * @var UtcDateTime
     */
    private $datestamp;

    /**
     * Specs.
     *
     * @var string[]
     */
    private $specs = array();

    /**
     * Is the record deleted?
     *
     * @var boolean
     */
    private $isDeleted;

    public function __construct ($identifier, UtcDateTime $datestamp, $specs = null, $isDeleted = false)
    {
        $this->identifier = $identifier;
        $this->datestamp = $datestamp;
        if ($specs) {
            foreach ($specs as $spec) {
                $this->specs []= $spec;
            }
        }
        $this->isDeleted = $isDeleted;
    }

    /**
     * Return identifier.
     *
     * @return string
     */
    public function getIdentifier ()
    {
        return $this->identifier;
    }

    /**
     * Return datestamp.
     *
     * @return UtcDateTime
     */
    public function getDatestamp ()
    {
        return $this->datestamp;
    }

    /**
     * Return specs.
     *
     * @return string[]
     */
    public function getSpecs ()
    {
        return $this->specs;
    }

    /**
     * Return true if the record is deleted.
     *
     * @return boolean
     */
    public function isdeleted ()
    {
        return $this->isDeleted;
    }

    /**
     * {@inheritDoc}
     */
    public function accept (VisitorInterface $visitor)
    {
        $visitor->visitHeader($this);
    }
}
