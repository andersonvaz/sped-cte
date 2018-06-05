<?php

namespace NFePHP\CTe\Factories;

/**
 * This class add protocol to a xml CTe
 *
 * @category  NFePHP
 * @package   NFePHP\CTe\Factories\Protocol
 * @copyright NFePHP Copyright (c) 2008-2017
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfe for the canonical source repository
 */

use NFePHP\Common\Strings;
use NFePHP\Common\Validator;
use InvalidArgumentException;
use DOMDocument;

class Protocol
{
    /**
     * Add protocol to xml
     * @param string $xmlnfe xml
     * @param string $protocol response xml
     * @return string
     * @throws InvalidArgumentException
     */
    public static function add(
        $xmlnfe,
        $xmlprotocol
    ) {
        //test if string is a XML
        if (!Validator::isXML($xmlnfe) ||
            !Validator::isXML($xmlprotocol)
        ) {
            throw new \InvalidArgumentException(
                'O documento passado não é um xml.'
            );
        }
        
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlnfe);
        $node = $dom->getElementsByTagName('CTe')->item(0);
        $infTag = $node->getElementsByTagName('infCte')->item(0);
        $procver = $infTag->getAttribute("versao");
        $procns = $node->getAttribute("xmlns");
        
        $dom1 = new \DOMDocument('1.0', 'UTF-8');
        $dom1->formatOutput = false;
        $dom1->preserveWhiteSpace = false;
        $dom1->loadXML($xmlprotocol);
        $node1 = $dom1->getElementsByTagName('protCTe')->item(0);
        
        $proc = new \DOMDocument('1.0', 'UTF-8');
        $proc->formatOutput = false;
        $proc->preserveWhiteSpace = false;
        $procNode = $proc->createElement('cteProc');
        $proc->appendChild($procNode);
        $procNodeAtt1 = $procNode->appendChild($proc->createAttribute('versao'));
        $procNodeAtt1->appendChild($proc->createTextNode($procver));
        $procNodeAtt2 = $procNode->appendChild($proc->createAttribute('xmlns'));
        $procNodeAtt2->appendChild($proc->createTextNode($procns));
        $newnode = $proc->importNode($node, true);
        $procNode->appendChild($newnode);
        $newnode = $proc->importNode($node1, true);
        $procNode->appendChild($newnode);
        $procXML = $proc->saveXML();
        $procXML = Strings::clearProtocoledXML($procXML);
        return $procXML;
    }
    
    /**
     * Remove tag protocol from xml
     * @param string $xmlprocnfe
     * @return string
     * @throws InvalidArgumentException
     */
    public static function remove($xmlprocnfe)
    {
        if (!Validator::isXML($xmlprocnfe)) {
            throw new InvalidArgumentException(
                'O documento passado não é um xml.'
            );
        }
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlprocnfe);
        $node = $dom->getElementsByTagName('CTe')->item(0);
        return '<?xml version="1.0" encoding="utf-8"?>'
            . $dom->saveXML($node);
    }
}