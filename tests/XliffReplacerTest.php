<?php

namespace Matecat\XliffParser\Tests;

use Matecat\XliffParser\Constants\TranslationStatus;
use Matecat\XliffParser\XliffParser;
use Matecat\XliffParser\XliffReplacer\XliffReplacerCallbackInterface;

class XliffReplacerTest extends BaseTest
{
    /**
     * @test
     */
    public function can_replace_a_xliff_12_without_target()
    {
        $data = $this->getData([
            [
                'sid' => 1,
                'segment' => 'Bla Bla',
                'internal_id' => 'NFDBB2FA9-tu519',
                'mrk_id' => '',
                'prev_tags' => '',
                'succ_tags' => '',
                'mrk_prev_tags' => '',
                'mrk_succ_tags' => '',
                'translation' => 'Bla bla bla',
                'status' => TranslationStatus::STATUS_TRANSLATED,
                'eq_word_count' => 1,
                'raw_word_count' => 1,
            ],
                [
                        'sid' => 2,
                        'segment' => 'Bla Bla',
                        'internal_id' => 'NFDBB2FA9-tu52',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => 'Bla bla bla',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 1,
                        'raw_word_count' => 1,
                ],
                [
                        'sid' => 3,
                        'segment' => 'Bla Bla',
                        'internal_id' => 'NFDBB2FA9-tu523',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => 'Bla bla bla',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 1,
                        'raw_word_count' => 1,
                ],
                [
                        'sid' => 4,
                        'segment' => 'Bla Bla',
                        'internal_id' => 'NFDBB2FA9-tu523',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => 'Bla bla bla',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 1,
                        'raw_word_count' => 1,
                ],
                [
                        'sid' => 5,
                        'segment' => 'Bla Bla',
                        'internal_id' => 'NFDBB2FA9-tu522',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => 'Bla bla bla',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 1,
                        'raw_word_count' => 1,
                ],
        ]);

        $inputFile = __DIR__.'/../tests/files/file-with-nested-group-and-missing-target.xliff';
        $outputFile = __DIR__.'/../tests/files/output/file-with-nested-group-and-missing-target.xliff';

        $xliffParser = new XliffParser();
        $xliffParser->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'sk-SK', $outputFile);
        $output = $xliffParser->xliffToArray(file_get_contents($outputFile));

        $expected = 'Bla bla bla';

        $this->assertEquals($output['files'][3]['trans-units'][1]['target']['raw-content'], $expected);
        $this->assertEquals($output['files'][3]['trans-units'][2]['target']['raw-content'], $expected);
        $this->assertEquals($output['files'][3]['trans-units'][3]['target']['raw-content'], $expected);
    }

    /**
     * @test
     */
    public function can_replace_a_xliff_10_without_target_lang()
    {
        $data = $this->getData([
                [
                        'sid' => 1,
                        'segment' => 'Image showing Italian Patreon creators',
                        'internal_id' => 'pendo-image-e3aaf7b7|alt',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => 'Bla bla bla',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 1,
                        'raw_word_count' => 1,
                ]
        ]);

        $inputFile = __DIR__.'/../tests/files/no-target.xliff';
        $outputFile = __DIR__.'/../tests/files/output/no-target.xliff';

        $xliffParser = new XliffParser();
        $xliffParser->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'it-it', $outputFile);
        $output = $xliffParser->xliffToArray(file_get_contents($outputFile));

        $this->assertEquals($output['files'][1]['attr']['target-language'], 'it-it');
    }

    /**
     * @test
     */
    public function can_replace_a_xliff_10()
    {
        $data = $this->getData([
                [
                        'sid' => 1,
                        'segment' => '<g id="1">&#128076;&#127995;</g>',
                        'internal_id' => 'NFDBB2FA9-tu519',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => '<g id="1">&#128076;&#127995;</g>',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 1,
                        'raw_word_count' => 1,
                ]
        ]);

        $inputFile = __DIR__.'/../tests/files/file-with-emoji.xliff';
        $outputFile = __DIR__.'/../tests/files/output/file-with-emoji.xliff';

        $xliffParser = new XliffParser();
        $xliffParser->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'fr-fr', $outputFile);
        $output = $xliffParser->xliffToArray(file_get_contents($outputFile));
        $expected = '<g id="1">&#128076;&#127995;</g>';

        $this->assertEquals($expected, $output['files'][3]['trans-units'][1]['target']['raw-content']);
    }

    /**
     * @test
     */
    public function can_replace_a_xliff_20_without_target()
    {
        $data = $this->getData([
            [
                'sid' => 1,
                'segment' => 'Titolo del documento',
                'internal_id' => 'tu1',
                'mrk_id' => '',
                'prev_tags' => '',
                'succ_tags' => '',
                'mrk_prev_tags' => '',
                'mrk_succ_tags' => '',
                'translation' => 'Document title',
                'status' => TranslationStatus::STATUS_TRANSLATED,
                'eq_word_count' => 1,
                'raw_word_count' => 2,
            ],
            [
                'sid' => 2,
                'segment' => 'Titolo del documento2',
                'internal_id' => 'tu1',
                'mrk_id' => '',
                'prev_tags' => '',
                'succ_tags' => '',
                'mrk_prev_tags' => '',
                'mrk_succ_tags' => '',
                'translation' => 'Document title2',
                'status' => TranslationStatus::STATUS_TRANSLATED,
                'eq_word_count' => 3,
                'raw_word_count' => 4,
            ],
            [
                'sid' => 3,
                'segment' => 'Testo libero contenente <pc id="1" canCopy="no" canDelete="no" dataRefEnd="d1" dataRefStart="d1">corsivo</pc>.',
                'internal_id' => 'tu2',
                'mrk_id' => '',
                'prev_tags' => '',
                'succ_tags' => '',
                'mrk_prev_tags' => '',
                'mrk_succ_tags' => '',
                'translation' => 'Free text containing <pc id="1" canCopy="no" canDelete="no" dataRefEnd="d1" dataRefStart="d1">cursive</pc>.',
                'status' => TranslationStatus::STATUS_TRANSLATED,
                'eq_word_count' => 4,
                'raw_word_count' => 5,
            ],
        ]);

        $inputFile = __DIR__.'/../tests/files/1111_prova.md.xlf';
        $outputFile = __DIR__.'/../tests/files/output/1111_prova.md.xlf';

        (new XliffParser())->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'en-en', $outputFile, false);
        $output = (new XliffParser())->xliffToArray(file_get_contents($outputFile));
        $expected = 'Document title';
        $expected2 = 'Document title2';
        $expected3 = 'Free text containing <pc id="1" canCopy="no" canDelete="no" dataRefEnd="d1" dataRefStart="d1">cursive</pc>.';

        $this->assertNotEmpty($output['files'][1]['trans-units'][1]['target']['raw-content']);
        $this->assertEquals($expected, $output['files'][1]['trans-units'][1]['target']['raw-content'][0]);
        $this->assertNotEmpty($output['files'][1]['trans-units'][1]['target']['raw-content']);
        $this->assertEquals($expected2, $output['files'][1]['trans-units'][1]['target']['raw-content'][1]);
        $this->assertNotEmpty($output['files'][1]['trans-units'][2]['target']['raw-content']);
        $this->assertEquals($expected3, $output['files'][1]['trans-units'][2]['target']['raw-content'][0]);
    }

    /**
     * @test
     */
    public function can_replace_a_xliff_20_with_no_errors()
    {
        $data = $this->getData([
                [
                        'sid' => 1,
                        'segment' => '<pc id="1">Hello <mrk id="m2" type="term">World</mrk> !</pc>',
                        'internal_id' => 'u1',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => '<pc id="1">Buongiorno al <mrk id="m2" type="term">Mondo</mrk> !</pc>',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 100,
                        'raw_word_count' => 200,
                ],
                [
                        'sid' => 2,
                        'segment' => '<pc id="1">Hello <mrk id="m2" type="term">World2</mrk> !</pc>',
                        'internal_id' => 'u2',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => '<pc id="2">Buongiorno al <mrk id="m2" type="term">Mondo2</mrk> !</pc>',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 200,
                        'raw_word_count' => 300,
                ],
        ]);
        $inputFile = __DIR__.'/../tests/files/sample-20.xlf';
        $outputFile = __DIR__.'/../tests/files/output/sample-20.xlf';

        (new XliffParser())->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'fr-fr', $outputFile, false, new DummyXliffReplacerCallback());
        $output = (new XliffParser())->xliffToArray(file_get_contents($outputFile));
        $expected = '<pc id="1">Buongiorno al <mrk id="m2" type="term">Mondo</mrk> !</pc>';

        $this->assertEquals($expected, $output['files'][1]['trans-units'][1]['target']['raw-content'][0]);
    }

    /**
     * @test
     */
    public function can_replace_a_xliff_20_with_consistency_errors()
    {
        $data = $this->getData([
                [
                        'sid' => 1,
                        'segment' => '<pc id="1">Hello <mrk id="m2" type="term">World</mrk> !</pc>',
                        'internal_id' => 'u1',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => '<pc id="1">Buongiorno al <mrk id="m2" type="term">Mondo</mrk> !</pc>',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 100,
                        'raw_word_count' => 200,
                ],
                [
                        'sid' => 2,
                        'segment' => '<pc id="1">Hello <mrk id="m2" type="term">World2</mrk> !</pc>',
                        'internal_id' => 'u2',
                        'mrk_id' => '',
                        'prev_tags' => '',
                        'succ_tags' => '',
                        'mrk_prev_tags' => '',
                        'mrk_succ_tags' => '',
                        'translation' => '<pc id="2">Buongiorno al <mrk id="m2" type="term">Mondo2</mrk> !</pc>',
                        'status' => TranslationStatus::STATUS_TRANSLATED,
                        'eq_word_count' => 200,
                        'raw_word_count' => 300,
                ],
        ]);
        $inputFile = __DIR__.'/../tests/files/sample-20.xlf';
        $outputFile = __DIR__.'/../tests/files/output/sample-20.xlf';

        (new XliffParser())->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'fr-fr', $outputFile, false, new DummyXliffReplacerCallbackWhichReturnTrue());
        $output = (new XliffParser())->xliffToArray(file_get_contents($outputFile));
        $expected = '|||UNTRANSLATED_CONTENT_START|||<pc id="1">Hello <mrk id="m2" type="term">World</mrk> !</pc>|||UNTRANSLATED_CONTENT_END|||';

        $this->assertEquals($expected, $output['files'][1]['trans-units'][1]['target']['raw-content'][0]);
    }

    /**
     * In this case the replacer must do not replace original target
     *
     * @test
     */
    public function can_replace_a_xliff_12_with__translate_no()
    {
        $data = $this->getData([
                [
                    'sid' => 1,
                    'segment' => 'Tools:Review',
                    'internal_id' => '1',
                    'mrk_id' => '',
                    'prev_tags' => '',
                    'succ_tags' => '',
                    'mrk_prev_tags' => '',
                    'mrk_succ_tags' => '',
                    'translation' => 'Tools:Recensione',
                    'status' => TranslationStatus::STATUS_TRANSLATED,
                    'eq_word_count' => 1,
                    'raw_word_count' => 1,
                ]
        ]);

        $inputFile = __DIR__.'/../tests/files/Working_with_the_Review_tool_single_tu.xlf';
        $outputFile = __DIR__.'/../tests/files/output/Working_with_the_Review_tool_single_tu.xlf';

        (new XliffParser())->replaceTranslation($inputFile, $data['data'], $data['transUnits'], 'it-it', $outputFile, false);
        $output = (new XliffParser())->xliffToArray(file_get_contents($outputFile));
        $expected = '<mrk mtype="seg" mid="1" MadCap:segmentStatus="Untranslated" MadCap:matchPercent="0"/>';

        $this->assertEquals($expected, $output['files'][1]['trans-units'][1]['target']['raw-content']);
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function getData($data)
    {
        $transUnits = [];

        foreach ($data as $i => $k) {
            //create a secondary indexing mechanism on segments' array; this will be useful
            //prepend a string so non-trans unit id ( ex: numerical ) are not overwritten
            $internalId = $k[ 'internal_id' ];

            $transUnits[ $internalId ] [] = $i;

            $data[ 'matecat|' . $internalId ] [] = $i;
        }

        return [
            'data' => $data,
            'transUnits' => $transUnits,
        ];
    }
}

class RealXliffReplacerCallback implements XliffReplacerCallbackInterface
{
    /**
     * @inheritDoc
     */
    public function thereAreErrors($segment, $translation, array $dataRefMap = [])
    {
        return false;
    }
}

class DummyXliffReplacerCallback implements XliffReplacerCallbackInterface
{
    /**
     * @inheritDoc
     */
    public function thereAreErrors($segment, $translation, array $dataRefMap = [])
    {
        return false;
    }
}

class DummyXliffReplacerCallbackWhichReturnTrue implements XliffReplacerCallbackInterface
{
    /**
     * @inheritDoc
     */
    public function thereAreErrors($segment, $translation, array $dataRefMap = [])
    {
        return true;
    }
}
