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
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Repository\Command;

/**
 * Hydrator default implementation.
 *
 * @author    David Maus <david.maus@sub.uni-hamburg.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @copyright (c) 2022 by Staats- und Universitätsbibliothek Hamburg
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
final class Hydrator implements HydratorInterface
{
    /**
     * {@inheritDoc}
     */
    public function hydrate (object $command, string $token) : bool
    {
        if ($token === '') {
            return false;
        }
        $token = base64_decode($token, true);
        if ($token === false) {
            return false;
        }

        parse_str($token, $data);
        $properties = get_object_vars($command);
        if (array_diff_key($data, $properties)) {
            return false;
        }

        foreach ($data as $key => $value) {
            $command->$key = $value;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function extract (object $command) : string
    {
        return base64_encode(http_build_query(get_object_vars($command)));
    }
}
