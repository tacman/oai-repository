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

namespace HAB\OAI\PMH\Request;

use HAB\OAI\PMH\Model\UtcDateTime;

use HAB\OAI\PMH\ProtocolError\ProtocolError;
use HAB\OAI\PMH\ProtocolError\BadArgument;
use HAB\OAI\PMH\ProtocolError\BadVerb;

/**
 * Validate request parameters.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Validator
{

    /**
     * Validation rules, indexed by verb.
     *
     * @var array
     */
    private static $rules = array(
        'Identify' => array(
            'required' => array('verb'),
            'optional'  => array(),
            'resumable' => false
        ),
        'GetRecord' => array(
            'required'  => array('verb', 'identifier', 'metadataPrefix'),
            'optional'  => array(),
            'resumable' => false
        ),
        'ListIdentifiers' => array(
            'required'  => array('verb', 'metadataPrefix'),
            'optional'  => array('from', 'until', 'set'),
            'resumable' => true
        ),
        'ListRecords' => array(
            'required'  => array('verb', 'metadataPrefix'),
            'optional'  => array('from', 'until', 'set'),
            'resumable' => true
        ),
        'ListSets' => array(
            'required'  => array('verb'),
            'optional'  => array(),
            'resumable' => true
        ),
        'ListMetadataFormats' => array(
            'required'  => array('verb'),
            'optional'  => array('identifier'),
            'resumable' => false
        )
    );


    /**
     * Errors.
     *
     * @var ProtocolError[]
     */
    private $errors = array();

    /**
     * Validate request parameters.
     *
     * @param  Parameters $parameters
     * @return void
     */
    public function validate (Parameters $parameters) : void
    {
        $this->errors = array();
        if (!$parameters['verb']) {
            $this->errors []= new BadVerb("The required argument 'verb' is missing");
            return;
        }

        $verb = $parameters['verb'];
        if (!array_key_exists($verb, self::$rules)) {
            $this->errors []= new BadVerb(sprintf("The value '%s' of the argument 'verb' is not a legal OAI-PMH verb", $verb));
            return;
        }

        $rules = self::$rules[$verb];

        if ($parameters['resumptionToken'] and $rules['resumable']) {
            if (count($parameters) > 2) {
                $this->errors []= new BadArgument("The argument 'resumptionToken' is an exclusive argument");
                return;
            }
            return;
        }

        foreach ($rules['required'] as $name) {
            if (!$parameters[$name]) {
                $this->errors []= new BadArgument(sprintf("The required argument '%s' is missing", $name));
            }
        }

        foreach ($parameters as $name => $value) {
            if (!in_array($name, $rules['required']) and !in_array($name, $rules['optional'])) {
                $this->errors []= new BadArgument(sprintf("The argument '%s' is not a legal OAI-PMH argument", $name));
            }
        }

        if ($parameters['from'] and !UtcDateTime::isValid($parameters['from'])) {
            $this->errors []= new BadArgument(sprintf("The value '%s' of argument 'from' is not a valid UTCdatetime string", $parameters['from']));
        }

        if ($parameters['until'] and !UtcDateTime::isValid($parameters['until'])) {
            $this->errors []= new BadArgument(sprintf("The value '%s' of argument 'until' is not a valid UTCdatetime string", $parameters['until']));
        }

        if ($parameters['from'] and $parameters['until']) {
            if (strlen($parameters['from']) !== strlen($parameters['until'])) {
                $this->errors []= new BadArgument("The granularity of the argument 'from' does not match the granularity of the argument 'until'");
            }
        }
    }

    /**
     * Return errors.
     *
     * @return ProtocolError[]
     */
    public function getErrors () : iterable
    {
        return $this->errors;
    }
}
