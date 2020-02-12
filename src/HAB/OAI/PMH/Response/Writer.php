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
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */

namespace HAB\OAI\PMH\Response;

use HAB\OAI\PMH\Model;

use XMLWriter;

/**
 * Write response.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2020 by Staats- und Universitätsbibliothek Hamburg
 * @copyright (c) 2016-2019 by Herzog August Bibliothek Wolfenbüttel
 * @license   http://www.gnu.org/licenses/gpl.txt GNU General Public License v3 or higher
 */
class Writer implements Model\VisitorInterface
{
    /**
     * Writer.
     *
     * @var XMLWriter
     */
    private $writer;

    /**
     * @var bool
     */
    private $dateGranularity;

    public function __construct (bool $dateGranularity = false)
    {
        $this->dateGranularity = $dateGranularity;
        $this->writer = new XMLWriter();
    }

    /**
     * Serialize response as XML.
     *
     * @param  Response $response
     * @return string
     */
    public function serialize (Response $response) : string
    {
        $this->writer->openMemory();
        $this->writer->startDocument();
        $this->writer->startElementNS('', 'OAI-PMH', 'http://www.openarchives.org/OAI/2.0/');
        $this->writer->writeAttributeNS(
            'xsi', 'schemaLocation', 'http://www.w3.org/2001/XMLSchema-instance',
            'http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd'
        );
        $this->element('responseDate', $response->getResponseDate());
        $this->element('request', $response->getBaseUrl(), $response->getParameters());

        foreach ($response->getErrors() as $error) {
            $this->element('error', $error->getMessage(), array('code' => $error->getErrorCode()));
        }

        if ($responseBody = $response->getResponseBody()) {
            $this->start($response->getVerb());
            $responseBody->accept($this);
            $this->end();
        }

        $this->writer->endElement();
        $this->writer->endDocument();
        return $this->writer->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function visitHeader (Model\HeaderInterface $header) : void
    {
        $this->start('header');
        if ($header->isDeleted()) {
            $this->attribute('status', 'deleted');
        }
        $this->element('identifier', $header->getIdentifier());
        if ($this->dateGranularity) {
            $this->element('datestamp', substr($header->getDatestamp(), 0, 10));
        } else {
            $this->element('datestamp', $header->getDatestamp());
        }
        foreach ($header->getSpecs() as $spec) {
            $this->element('setSpec', $spec);
        }
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitRecord (Model\RecordInterface $record) : void
    {
        $this->start('record');
        $record->getHeader()->accept($this);
        $record->getMetadata()->accept($this);
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitMetadata (Model\Metadata $metadata) : void
    {
        $this->start('metadata');
        $this->xml($metadata->toXML());
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitSet (Model\SetInterface $set) : void
    {
        $this->start('set');
        $this->element('setSpec', $set->getSpec());
        $this->element('setName', $set->getName());
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitMetadataFormat (Model\MetadataFormatInterface $metadataFormat) : void
    {
        $this->start('metadataFormat');
        $this->element('metadataPrefix', $metadataFormat->getPrefix());
        $this->element('schema', $metadataFormat->getSchemaUri());
        $this->element('metadataNamespace', $metadataFormat->getNamespaceUri());
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitResumptionToken (Model\ResumptionToken $resumptionToken) : void
    {
        $attrs = array();
        $cursor = $resumptionToken->getCursor();
        if (is_int($cursor)) {
            $attrs['cursor'] = $cursor;
        }
        if ($completeListSize = $resumptionToken->getCompleteListSize()) {
            $attrs['completeListSize'] = $completeListSize;
        }
        if ($expirationDate = $resumptionToken->getExpirationDate()) {
            $attrs['expirationDate'] = $expirationDate;
        }
        $this->element('resumptionToken', (string)$resumptionToken, $attrs);
    }

    /**
     * {@inheritDoc}
     */
    public function visitXmlSerializable (Model\XmlSerializableInterface $entity) : void
    {
        $this->xml($entity->toXML());
    }

    /**
     * {@inheritDoc}
     */
    public function visitIdentity (Model\Identity $identity) : void
    {
        foreach ($identity as $name => $values) {
            foreach ($values as $value) {
                if ($value instanceof Model\VisitableInterface) {
                    $this->start($name);
                    $value->accept($this);
                    $this->end();
                } else {
                    $this->element($name, $value);
                }
            }
        }
    }

    ///

    protected function start ($name) : void
    {
        $this->writer->startElement($name);
    }

    protected function end () : void
    {
        $this->writer->endElement();
    }

    protected function attribute ($name, $value) : void
    {
        $this->writer->writeAttribute($name, $value);
    }

    protected function element ($name, $content = null, $attrs = null) : void
    {
        $this->writer->startElement($name);
        if ($attrs) {
            foreach ($attrs as $name => $value) {
                $this->writer->writeAttribute($name, $value);
            }
        }
        if ($content) {
            $this->writer->text($content);
        }
        $this->writer->endElement();
    }

    protected function xml ($xmlContent) : void
    {
        $this->writer->writeRaw($xmlContent);
    }
}
