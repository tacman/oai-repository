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

namespace HAB\OAI\PMH\Model;

/**
 * Interface of a model entity visitor.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
interface VisitorInterface
{
    /**
     * Visit a header entity.
     *
     * @param  HeaderInterface $header
     * @return void
     */
    public function visitHeader (HeaderInterface $header) : void;

    /**
     * Visit a record entity.
     *
     * @param  RecordInterface $record
     * @return void
     */
    public function visitRecord (RecordInterface $record) : void;

    /**
     * Visit a record metadata entity.
     *
     * @param  Metadata $metadata
     * @return void
     */
    public function visitMetadata (Metadata $metadata) : void;

    /**
     * Visit a set entity.
     *
     * @param  SetInterface $set
     * @return void
     */
    public function visitSet (SetInterface $set) : void;

    /**
     * Visit a metadata format entity.
     *
     * @param  MetadataFormatInterface $metadataFormat
     * @return void
     */
    public function visitMetadataFormat (MetadataFormatInterface $metadataFormat) : void;

    /**
     * Visit a resumption token entity.
     *
     * @param  ResumptionToken $resumptionToken
     * @return void
     */
    public function visitResumptionToken (ResumptionToken $resumptionToken) : void;

    /**
     * Visit a XML serializable entity.
     *
     * @param  XmlSerializableInterface $entity
     * @return void
     */
    public function visitXmlSerializable (XmlSerializableInterface $entity) : void;

    /**
     * Visit body of a Identify response.
     *
     * @param  Identity<string, mixed> $identity
     * @return void
     */
    public function visitIdentity (Identity $identity) : void;

}
