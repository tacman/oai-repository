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

namespace HAB\OAI\PMH\Repository;

/**
 * Interface of a repository.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
interface RepositoryInterface
{
    /**
     * Retrieve an individual metadata record.
     *
     * @throws ProtocolError\CannotDisseminateFormat
     * @throws ProtocolError\IdDoesNotExist
     *
     * @param  string $identifier
     * @param  string $metadataPrefix
     * @return Model\ResponseBodyInterface
     */
    public function getRecord ($identifier, $metadataPrefix);

    /**
     * Retrieve information about the repository.
     *
     * @return Model\Identify
     */
    public function identify ();

    /**
     * Abbreviated form of listRecords(), retrieving only headers.
     *
     * @throws ProtocolError\BadArgument
     * @throws ProtocolError\CannotDisseminateFormat
     * @throws ProtocolError\NoRecordsMatch
     * @throws ProtocolError\NoSetHierarchy
     *
     * @Param  string $metadataPrefix
     * @param  string $from
     * @param  string $until
     * @param  string $set
     * @return Model\ResponseBodyInterface
     */
    public function listIdentifiers ($metadataPrefix, $from = null, $until = null, $set = null);

    /**
     * Harvest records from the repository.
     *
     * @throws ProtocolError\BadArgument
     * @throws ProtocolError\CannotDisseminateFormat
     * @throws ProtocolError\NoRecordsMatch
     * @throws ProtocolError\NoSetHierarchy
     *
     * @Param  string $metadataPrefix
     * @param  string $from
     * @param  string $until
     * @param  string $set
     * @return Model\ResponseBodyInterface
     */
    public function listRecords ($metadataPrefix, $from = null, $until = null, $set = null);

    /**
     * Retrieve the metadata formats available.
     *
     * @throws ProtocolError\IdDoesNotExist
     * @throws ProtocolError\NoMetadataFormats
     *
     * @param  string $identifier
     * @return Model\ResponseBodyInterface
     */
    public function listMetadataFormats ($identifier = null);

    /**
     * Retrieve the set structure.
     *
     * @throws ProtocolError\NoSetHierarchy
     *
     * @return Model\ResponseBodyInterface
     */
    public function listSets ();

    /**
     * Resume operation.
     *
     * @throws ProtocolError\BadResumptionToken
     *
     * @param  string $verb
     * @param  string $resumptionToken
     * @return Model\ResponseBodyInterface
     */
    public function resume ($verb, $resumptionToken);

}