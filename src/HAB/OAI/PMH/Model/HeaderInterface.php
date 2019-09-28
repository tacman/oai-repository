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
 * Record header.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
interface HeaderInterface extends VisitableInterface
{
    /**
     * Return identifier.
     *
     * @return string
     */
    public function getIdentifier () : string;

    /**
     * Return datestamp.
     *
     * @return UtcDateTime
     */
    public function getDatestamp () : UtcDateTime;

    /**
     * Return specs.
     *
     * @return string[]
     */
    public function getSpecs () : iterable;

    /**
     * Return true if the record is deleted.
     *
     * @return bool
     */
    public function isdeleted () : bool;

}
