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
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Request;

use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Validator class.
 *
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class ValidatorTest extends TestCase
{
    public function testMissingVerb ()
    {
        $params = new Parameters([]);
        $validator = new Validator();
        $validator->validate($params);
        $this->assertTrue($this->error($validator, 'badVerb', "/The required argument 'verb' is missing/u"));
    }

    public function testInvalidVerb ()
    {
        $params = new Parameters(['verb' => 'Invalid']);
        $validator = new Validator();
        $validator->validate($params);
        $this->assertTrue($this->error($validator, 'badVerb', "/is not a legal OAI-PMH verb/u"));
    }

    public function testMissingRequiredArgument ()
    {
        $params = new Parameters(['verb' => 'GetRecord']);
        $validator = new Validator();
        $validator->validate($params);
        $this->assertTrue($this->error($validator, 'badArgument', "/The required argument [^ ]+ is missing/u"));
    }

    public function testIllegalArgument ()
    {
        $params = new Parameters(['verb' => 'Identify', 'illegal' => 'illegal']);
        $validator = new Validator();
        $validator->validate($params);
        $this->assertTrue($this->error($validator, 'badArgument', "/is not a legal OAI-PMH argument/u"));
    }

    public function testIllegalFrom ()
    {
        $params = new Parameters(['verb' => 'ListIdentifiers', 'metadataPrefix' => 'oai_dc', 'from' => 'xxx']);
        $validator = new Validator();
        $validator->validate($params);
        $this->assertTrue($this->error($validator, 'badArgument', "/is not a valid UTCdatetime string/u"));
    }

    public function testIllegalUntil ()
    {
        $params = new Parameters(['verb' => 'ListIdentifiers', 'metadataPrefix' => 'oai_dc', 'until' => 'xxx']);
        $validator = new Validator();
        $validator->validate($params);
        $this->assertTrue($this->error($validator, 'badArgument', "/is not a valid UTCdatetime string/u"));
    }

    public function testGranularityMismatch ()
    {
        $params = new Parameters(['verb' => 'ListIdentifiers', 'metadataPrefix' => 'oai_dc', 'from' => '2000-01-01', 'until' => '2000-01-01T00:00:00Z']);
        $validator = new Validator();
        $validator->validate($params);
        $this->assertTrue($this->error($validator, 'badArgument', "/does not match the granularity of/u"));
    }

    public function testNonexclusiveResumable ()
    {
        $params = new Parameters(['verb' => 'ListIdentifiers', 'metadataPrefix' => 'oai_dc', 'resumptionToken' => 'token']);
        $validator = new Validator();
        $validator->validate($params);
        $this->assertTrue($this->error($validator, 'badArgument', "/is an exclusive argument/u"));
    }

    private function error (Validator $validator, $errorCode, $messageRegex = null)
    {
        foreach ($validator->getErrors() as $error) {
            if ($errorCode === $error->getErrorCode()) {
                if ($messageRegex) {
                    return (boolean)preg_match($messageRegex, $error->getMessage());
                }
                return true;
            }
        }
        return false;
    }
}
