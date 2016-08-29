<?php
/**
 * Location: xml/XmlTagHandler
 *
 * XmlTagHandler
 *
 * Copyright &copy; 2001 eXtremePHP.  All rights reserved.
 *
 * @author Ken Egervari, Remi Michalski
 */
class XmlTagHandler
{
    /**
     * @abstract
     *
     * @return array|string
     */
    public function getName()
    {
    }

    /**
     * @abstract
     * @param SaxParser $parser
     * @param array     $attributes
     * @return void
     */
    public function handleBeginElement(SaxParser $parser, &$attributes)
    {
    }

    /**
     * @abstract
     * @param SaxParser $parser
     * @return void
     */
    public function handleEndElement(SaxParser $parser)
    {
    }

    /**
     * @abstract
     * @param SaxParser $parser
     * @param string    $data
     * @return void
     */
    public function handleCharacterData(SaxParser $parser, &$data)
    {
    }
}
