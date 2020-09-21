<?php

namespace Matecat\XliffParser\XliffParser;

use Matecat\XliffParser\Utils\Strings;
use Matecat\XliffParser\XliffUtils\DataRefReplacer;
use Psr\Log\LoggerInterface;

abstract class AbstractXliffParser
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * XliffParser constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param \DOMDocument $dom
     *
     * @return array
     */
    abstract public function parse(\DOMDocument $dom, $output = []);

    /**
     * @param \DOMDocument $dom
     * @param \DOMElement  $node
     *
     * @return array
     */
    protected function extractContent(\DOMDocument $dom, \DOMElement $node)
    {
        return [
            'raw-content' => $this->extractTagContent($dom, $node),
            'attr' => $this->extractTagAttributes($node)
        ];
    }

    /**
     * Extract attributes if they are present
     *
     * Ex:
     * <p align=center style="font-size: 12px;">some text</p>
     *
     * $attr->nodeName == 'align' :: $attr->nodeValue == 'center'
     * $attr->nodeName == 'style' :: $attr->nodeValue == 'font-size: 12px;'
     *
     * @param \DOMElement $element
     *
     * @return array
     */
    protected function extractTagAttributes(\DOMElement $element)
    {
        $tagAttributes = [];

        if ($element->hasAttributes()) {
            foreach ($element->attributes as $attr) {
                $tagAttributes[ $attr->nodeName ] = $attr->nodeValue;
            }
        }

        return $tagAttributes;
    }

    /**
     * @param \DOMDocument $dom
     * @param \DOMElement  $element
     *
     * @return string
     */
    protected function extractTagContent(\DOMDocument $dom, \DOMElement $element)
    {
        $childNodes = $element->hasChildNodes();
        $extractedContent = '';

        if (!empty($childNodes)) {
            foreach ($element->childNodes as $node) {
                $extractedContent .= Strings::fixNonWellFormedXml($dom->saveXML($node));
            }
        }

        return $extractedContent;
    }

    /**
     * @param \DOMDocument $dom
     * @param \DOMElement  $childNode
     * @param array $originalData
     *
     * @return array
     */
    protected function extractContentWithMarksAndExtTags(\DOMDocument $dom, \DOMElement $childNode, array $originalData = [])
    {
        $source = [];

        // example:
        // <g id="1"><mrk mid="0" mtype="seg">An English string with g tags</mrk></g>
        $raw = $this->extractTagContent($dom, $childNode);

        $markers = preg_split('#<mrk\s#si', $raw, -1);

        $mi = 0;
        while (isset($markers[ $mi + 1 ])) {
            unset($mid);

            preg_match('|mid\s?=\s?["\'](.*?)["\']|si', $markers[ $mi + 1 ], $mid);

            //re-build the mrk tag after the split
            $originalMark = trim('<mrk ' . $markers[ $mi + 1 ]);

            $mark_string  = preg_replace('#^<mrk\s[^>]+>(.*)#', '$1', $originalMark); // at this point we have: ---> 'Test </mrk> </g>>'
            $mark_content = preg_split('#</mrk>#si', $mark_string);

            $sourceArray = [
                    'mid' => (isset($mid[ 1 ])) ? $mid[ 1 ] : $mi,
                    'ext-prec-tags' => ($mi == 0 ? $markers[ 0 ] : ""),
                    'raw-content' => $mark_content[ 0 ],
                    'ext-succ-tags' => $mark_content[ 1 ],
            ];

            if(!empty($originalData)) {
                $dataRefMap = $this->getDataRefMap($originalData);
                $sourceArray['replaced-content'] = (new DataRefReplacer($dataRefMap))->replace($mark_content[ 0 ]);
            }

            $source[] = $sourceArray;

            $mi++;
        }

        return $source;
    }

    /**
     * @param array $originalData
     *
     * @return array
     */
    protected function getDataRefMap($originalData)
    {
        // dataRef map
        $dataRefMap = [];
        foreach ($originalData as $datum){
            if(isset($datum['attr']['id'])){
                $dataRefMap[$datum['attr']['id']] = $datum['raw-content'];
            }
        }

        return $dataRefMap;
    }

    /**
     * @param $raw
     *
     * @return bool
     */
    protected function stringContainsMarks($raw)
    {
        $markers = preg_split('#<mrk\s#si', $raw, -1);

        return isset($markers[1]);
    }

    /**
     * @param $noteValue
     *
     * @return array
     * @throws \Exception
     */
    protected function JSONOrRawContentArray($noteValue)
    {
        if (Strings::isJSON($noteValue)) {
            return ['json' => Strings::cleanCDATA($noteValue)];
        }

        return ['raw-content' => Strings::fixNonWellFormedXml($noteValue)];
    }
}
