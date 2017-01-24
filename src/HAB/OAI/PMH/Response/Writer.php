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

use HAB\OAI\PMH\Model;

use XMLWriter;

/**
 * Write response.
 *
 * @author    David Maus <maus@hab.de>
 * @copyright (c) 2016 by Herzog August Bibliothek Wolfenbüttel
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

    public function __construct ()
    {
        $this->writer = new XMLWriter();
    }

    /**
     * Serialize response as XML.
     *
     * @param  Response $response
     * @return string
     */
    public function serialize (Response $response)
    {
        $this->writer->openMemory();
        $this->writer->startDocument();
        $this->writer->startElementNS(null, 'OAI-PMH', 'http://www.openarchives.org/OAI/2.0/');
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
    public function visitHeader (Model\Header $header)
    {
        $this->start('header');
        if ($header->isDeleted()) {
            $this->attribute('status', 'deleted');
        }
        $this->element('identifier', $header->getIdentifier());
        $this->element('datestamp', $header->getDatestamp());
        foreach ($header->getSpecs() as $spec) {
            $this->element('setSpec', $spec);
        }
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitRecord (Model\Record $record)
    {
        $this->start('record');
        $record->getHeader()->accept($this);
        $record->getMetadata()->accept($this);
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitMetadata (Model\Metadata $metadata)
    {
        $this->start('metadata');
        $this->xml($metadata->toXML());
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitSet (Model\Set $set)
    {
        $this->start('set');
        $this->element('setSpec', $set->getSpec());
        $this->element('setName', $set->getName());
        $this->end();
    }

    /**
     * {@inheritDoc}
     */
    public function visitMetadataFormat (Model\MetadataFormat $metadataFormat)
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
    public function visitResumptionToken (Model\ResumptionToken $resumptionToken)
    {
        $attrs = array();
        if ($cursor = $resumptionToken->getCursor()) {
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
    public function visitXmlSerializable (Model\XmlSerializableInterface $entity)
    {
        $this->xml($entity->toXML());
    }

    ///

    private function start ($name)
    {
        $this->writer->startElement($name);
    }

    private function end ()
    {
        $this->writer->endElement();
    }

    private function attribute ($name, $value)
    {
        $this->writer->writeAttribute($name, $value);
    }

    private function element ($name, $content = null, $attrs = null)
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

    private function xml ($xmlContent)
    {
        $this->writer->writeRaw($xmlContent);
    }
}