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
 * @copyright (c) 2020 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Response;

use HAB\OAI\PMH\Request\Parameters;

use HAB\OAI\PMH\Model\Header;
use HAB\OAI\PMH\Model\UtcDateTime;
use HAB\OAI\PMH\Model\ResponseBody;

use PHPUnit\Framework\TestCase;

use DOMDocument;

/**
 * Unit tests for the Writer class.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2020 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class WriterTest extends TestCase
{
    public function testSerializeDefaultNamespace ()
    {
        $params = new Parameters(['verb' => 'Testing']);
        $responseBody = new ResponseBody();
        $responseBody->append(new Header('identifier', new UtcDateTime()));
        $response = new Response('http://oai.example.com', $params);
        $response->setResponseBody($responseBody);
        $writer = new Writer();
        $payload = $writer->serialize($response);

        $document = new DOMDocument();
        $this->assertTrue($document->loadXML($payload));
    }
}
