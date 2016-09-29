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

use IteratorAggregate;
use ArrayIterator;
use ArrayAccess;
use Countable;

/**
 * Request parameters.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Parameters implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * Data.
     *
     * @var array
     */
    private $data;

    public function __construct (array $parameters)
    {
        $this->data = $parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet ($index)
    {
        if (isset($this->data[$index])) {
            return $this->data[$index];
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists ($index)
    {
        return isset($this->data[$index]);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet ($index, $value)
    {
        $this->data[$index] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset ($index)
    {
        unset($this->data[$index]);
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator ()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function count ()
    {
        return count($this->data);
    }
}