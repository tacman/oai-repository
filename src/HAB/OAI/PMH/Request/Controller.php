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

namespace HAB\OAI\PMH\Request;

use Exception;

use HAB\OAI\PMH\Repository\RepositoryInterface;
use HAB\OAI\PMH\ProtocolError\ProtocolError;
use HAB\OAI\PMH\Response\Response;

/**
 * OAI repository controller.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Controller
{
    /**
     * Repository.
     *
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct (RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle ($baseUrl, Parameters $params)
    {
        $response = new Response($baseUrl, $params);
        if ($errors = $this->validate($params)) {
            foreach ($errors as $error) {
                $response->addProtocolError($error);
            }
        } else {
            try {
                $responseBody = $this->delegate($params);
                $response->setResponseBody($responseBody);
            } catch (ProtocolError $error) {
                $response->addProtocolError($error);
            }
        }
        return $response;
    }

    /**
     * Validate parameters and return validation errors.
     *
     * @param  Parameters $params
     * @return ProtocolError[]
     */
    private function validate (Parameters $params)
    {
        $validator = new Validator();
        $validator->validate($params);
        return $validator->getErrors();
    }

    /**
     * Delegate to repository implementation.
     *
     * @throws ProtocolError
     *
     * @param  Parameters $p
     * @return ResponseBodyInterface
     */
    private function delegate (Parameters $p)
    {
        if ($p['resumptionToken']) {
            return $this->repository->resume($p['verb'], $p['resumptionToken']);
        } else {
            switch ($p['verb']) {
            case 'Identify':
                return $this->repository->identify();
            case 'GetRecord':
                return $this->repository->getRecord($p['identifier'], $p['metadataPrefix']);
            case 'ListSets':
                return $this->repository->listSets();
            case 'ListMetadataFormats':
                return $this->repository->listMetadataFormats($p['identifier']);
            case 'ListIdentifiers':
                return $this->repository->listIdentifiers($p['metadataPrefix'], $p['from'], $p['until'], $p['set']);
            case 'ListRecords':
                return $this->repository->listRecords($p['metadataPrefix'], $p['from'], $p['until'], $p['set']);
            default:
                throw new Exception(sprintf("Unknown verb: %s", $p['verb']));
            }
        }
    }
}