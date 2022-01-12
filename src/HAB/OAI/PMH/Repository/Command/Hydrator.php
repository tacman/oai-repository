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

use InvalidArgumentException;

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
     * HMAC algorithm.
     *
     * @var string
     */
    private $algorithm;

    /**
     * HMAC key.
     *
     * @var string
     */
    private $key;

    /**
     * Hash and value delimiter.
     *
     * @var string
     */
    private $delimiter = ';';

    public function __construct (string $algorithm, string $key)
    {
        if (!in_array($algorithm, hash_hmac_algos(), true)) {
            throw new InvalidArgumentException("Unknown or unsupported hash algorithm: {$algorithm}");
        }
        $this->algorithm = $algorithm;
        $this->key = $key;
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate (object $command, string $token) : bool
    {
        $data = explode($this->delimiter, $token);
        if (!is_array($data) || count($data) != 2) {
            return false;
        }

        list($value, $hash) = $data;

        if (!hash_equals($hash, hash_hmac($this->algorithm, $value, $this->key))) {
            return false;
        }

        $value = base64_decode($value, true);
        if ($value === false) {
            return false;
        }

        parse_str($value, $data);
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
        $value = base64_encode(http_build_query(get_object_vars($command)));
        $hash = hash_hmac($this->algorithm, $value, $this->key);
        return "{$value}{$this->delimiter}{$hash}";
    }
}
