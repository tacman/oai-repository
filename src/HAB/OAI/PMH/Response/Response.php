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

namespace HAB\OAI\PMH\Response;

use HAB\OAI\PMH\Model\UtcDateTime;
use HAB\OAI\PMH\Model\ResponseBodyInterface;

use HAB\OAI\PMH\Request\Parameters;
use HAB\OAI\PMH\ProtocolError\BadVerb;
use HAB\OAI\PMH\ProtocolError\BadArgument;
use HAB\OAI\PMH\ProtocolError\ProtocolError;

/**
 * OAI-PMH 2.0 response.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Response
{
    /**
     * Base Url.
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Parameters.
     *
     * @var Parameters|null
     */
    private $parameters;

    /**
     * Response Date.
     *
     * @var UtcDateTime
     */
    private $responseDate;

    /**
     * Response body.
     *
     * @var ResponseBodyInterface
     */
    private $responseBody;

    /**
     * Errors.
     *
     * @var ProtocolError[]
     */
    private $errors;


    public function __construct ($baseUrl, Parameters $parameters)
    {
        $this->baseUrl = $baseUrl;
        $this->parameters = $parameters;
        $this->errors = array();
        $this->responseDate = new UtcDateTime();
    }

    /**
     * Return response date.
     *
     * @return string
     */
    public function getResponsedate ()
    {
        return (string)$this->responseDate;
    }

    /**
     * Return parameters.
     *
     * @return Parameters|null
     */
    public function getParameters ()
    {
        return $this->parameters;
    }

    /**
     * Return base URL.
     *
     * @return string
     */
    public function getBaseurl ()
    {
        return $this->baseUrl;
    }

    /**
     * Add protocol error.
     *
     * @param  ProtocolError $error
     * @return void
     */
    public function addProtocolError (ProtocolError $error)
    {
        if ($error instanceof BadVerb or $error instanceof BadArgument) {
            $this->parameters = null;
        }
        $this->errors []= $error;
    }

    /**
     * Return errors.
     *
     * @return ProtocolError[]
     */
    public function getErrors ()
    {
        return $this->errors;
    }

    /**
     * Return verb.
     *
     * @return string
     */
    public function getVerb ()
    {
        return $this->parameters['verb'];
    }

    /**
     * Return response body.
     *
     * @return ResponseBodeInterface
     */
    public function getResponsebody ()
    {
        return $this->responseBody;
    }

    /**
     * Set response body.
     *
     * @param  ResponseBody $responseBody
     * @return 
     */
    public function setResponseBody (ResponseBodyInterface $responseBody)
    {
        $this->responseBody = $responseBody;
    }
}