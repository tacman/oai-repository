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

namespace HAB\OAI\PMH\Repository\Doctrine\Command;

use HAB\OAI\PMH\ProtocolError\BadResumptionToken;

use InvalidArgumentException;

/**
 * Hydrate/dehydrate command.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Hydrator
{
    /**
     * Create resumption token.
     *
     * @throws InvalidArgumentException
     *
     * @param  object $command
     * @return string
     */
    public function createResumptionToken ($command) : string
    {
        if (!is_object($command)) {
            throw new InvalidArgumentException(sprintf('Expected command to be an object, %s given', gettype($command)));
        }
        $data = get_object_vars($command);
        $token = base64_encode(http_build_query($data));
        return $token;
    }

    /**
     * Resume command from resumption token.
     *
     * @throws InvalidArgumentException
     * @throws BadResumptionToken
     *
     * @param  object $command
     * @param  string $token
     * @return void
     */
    public function resume ($command, $token) : void
    {
        if (!is_object($command)) {
            throw new InvalidArgumentException(sprintf('Expected command to be an object, %s given', gettype($command)));
        }
        $token = base64_decode($token, true);
        if ($token === false) {
            throw new BadResumptionToken();
        }
        parse_str($token, $data);
        foreach ($data as $key => $value) {
            if (!property_exists($command, $key)) {
                throw new BadResumptionToken();
            }
            $command->$key = $value;
        }
    }
}
