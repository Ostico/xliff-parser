<?php

namespace Matecat\XliffParser\Tests;

use Matecat\XliffParser\XliffParser;

class XliffParserV2Test extends BaseTest
{
    /**
     * @test
     */
    public function can_parse_xliff_v2_metadata()
    {
        $parsed = ( new XliffParser() )->toArray($this->getTestFile('uber-v2.xliff'));
        $attr   = $parsed[ 'files' ][ 1 ][ 'attr' ];

        $this->assertCount(3, $attr);
        $this->assertEquals($attr[ 'source-language' ], 'en-us');
        $this->assertEquals($attr[ 'target-language' ], 'el-gr');
        $this->assertEquals($attr[ 'original' ], '389108a4-rtapi.xml');
    }

    /**
     * @test
     */
    public function can_parse_xliff_v2_notes()
    {
        $parsed = ( new XliffParser() )->toArray($this->getTestFile('sample-20.xlf'));
        $notes  = $parsed[ 'files' ][ 1 ][ 'notes' ];

        $this->assertCount(3, $notes);
        $this->assertEquals($notes[ 0 ][ 'raw-content' ], 'note for file.');
        $this->assertEquals($notes[ 1 ][ 'raw-content' ], 'note2 for file.');
        $this->assertEquals($notes[ 2 ][ 'json' ], '{
                    "key": "value",
                    "key2": "value2",
                    "key3": "value3"
                }');
    }

    /**
     * @test
     */
    public function can_parse_xliff_v2_trans_units_metadata()
    {
        $parsed = ( new XliffParser() )->toArray($this->getTestFile('sample-20.xlf'));
        $units  = $parsed[ 'files' ][ 1 ][ 'trans-units' ];

        $this->assertCount(2, $units);
        $this->assertEquals($units[ 1 ][ 'attr' ][ 'id' ], 'u1');
        $this->assertEquals($units[ 1 ][ 'attr' ][ 'translate' ], 'test');
        $this->assertEquals($units[ 2 ][ 'attr' ][ 'id' ], 'u2');
    }

    /**
     * @test
     */
    public function can_parse_xliff_v2_trans_units_originalData()
    {
        $parsed = ( new XliffParser() )->toArray($this->getTestFile('uber-v2.xliff'));
        $units  = $parsed[ 'files' ][ 1 ][ 'trans-units' ];

        $this->assertEquals($units[ 5 ][ 'original-data' ][ 0 ][ 'raw-content' ], '${redemptionLimit}');
        $this->assertEquals($units[ 5 ][ 'original-data' ][ 0 ][ 'attr' ][ 'id' ], 'source1');
    }

    /**
     * @test
     */
    public function can_parse_xliff_v2_trans_units_notes()
    {
        $parsed = ( new XliffParser() )->toArray($this->getTestFile('uber-v2.xliff'));
        $units  = $parsed[ 'files' ][ 1 ][ 'trans-units' ];
        $note   = $units[ 1 ][ 'notes' ];

        $this->assertEquals($note[ 0 ][ 'raw-content' ], 'note for unit');
        $this->assertEquals($note[ 1 ][ 'raw-content' ], 'another note for file.');
        $this->assertEquals($note[ 2 ], [
                'raw-content' => '01d35857-b9bd-4835-8db1-40febcdcc8e9',
                'attr'        => [
                        'type' => 'key'
                ]
        ]);
        $this->assertEquals($note[ 3 ], [
                'raw-content' => 'Repo: &lt;a href ="https://i18n.uberinternal.com/rosetta2/repo/rtapi/keys" target="_blank"&gt;rtapi&lt;/a&gt; Key Name: &lt;a href="https://i18n.uberinternal.com/rosetta2/repo/rtapi/key/driver_tasks.delivery_reminders.order.wda.title/overview" target="_blank"&gt;driver_tasks.delivery_reminders.order.wda.title&lt;/a&gt; Description: &lt;font color="blue"&gt;Title for delivery reminders when an eater has changed the dropoff location for an order&lt;/font&gt;',
                'attr'        => [
                        'type' => 'key-note'
                ]
        ]);
    }

    /**
     * @test
     */
    public function can_parse_xliff_v2_trans_units_source_and_target()
    {
        $parsed = ( new XliffParser() )->toArray($this->getTestFile('sample-20.xlf'));
        $units  = $parsed[ 'files' ][ 1 ][ 'trans-units' ];
        $this->assertCount(2, $units);

        $this->assertStringContainsString('<pc id="1">Hello <mrk id="m1" type="term">World</mrk>!</pc>', $units[ 1 ][ 'source' ][ 'content' ]);
        $this->assertStringContainsString('<pc id="2">Hello <mrk id="m2" type="term">World2</mrk>!</pc>', $units[ 2 ][ 'source' ][ 'content' ]);
        $this->assertStringContainsString('<pc id="1">Bonjour le <mrk id="m1" type="term">Monde</mrk> !</pc>', $units[ 1 ][ 'target' ][ 'content' ]);
        $this->assertStringContainsString('<pc id="2">Bonjour le <mrk id="m2" type="term">Monde2</mrk> !</pc>', $units[ 2 ][ 'target' ][ 'content' ]);
        $this->assertEquals($units[ 1 ][ 'source' ][ 'attr' ], []);
        $this->assertEquals($units[ 2 ][ 'source' ][ 'attr' ], []);
        $this->assertEquals($units[ 1 ][ 'target' ][ 'attr' ], []);
        $this->assertEquals($units[ 2 ][ 'target' ][ 'attr' ], []);
    }

    /**
     * @test
     */
    public function can_parse_xliff_v2()
    {
        $parsed = ( new XliffParser() )->toArray($this->getTestFile('sample-20.xlf'));

        $exp = [
            'attr' =>
                [
                    'original'        => '389108a4-rtapi.xml',
                    'source-language' => 'en',
                    'target-language' => 'fr',
                ],
            'notes' =>
                [
                    0 => ['raw-content' => 'note for file.', ],
                    1 => ['raw-content' => 'note2 for file.',],
                    2 => ['json' => '{
                    "key": "value",
                    "key2": "value2",
                    "key3": "value3"
                }',
                    ],
                ],
            'trans-units' =>
                [
                    1 => [
                        'attr' => [
                            'id' => 'u1',
                            'translate' => 'test',
                        ],
                        'notes' => [
                            0 => ['raw-content' => 'note for unit',],
                            1 => ['raw-content' => 'another note for unit.',],
                            2 => ['json' => '{
                        "key": "value",
                        "key2": "value2",
                        "key3": "value3"
                    }',
                            ],
                        ],
                        'original-data' => [],
                         'source' => [
                             'content' => '<pc id="1">Hello <mrk id="m1" type="term">World</mrk>!</pc>',
                             'attr'    => [],
                         ],
                         'target' => [
                            'content' => '<pc id="1">Bonjour le <mrk id="m1" type="term">Monde</mrk> !</pc>',
                             'attr'    => [],
                         ],
                    ],
                    2 => [
                        'attr' => [
                            'id' => 'u2',
                        ],
                        'notes' => [
                            0 => [ 'raw-content' => 'note for unit2', ],
                            1 => [ 'raw-content' => 'another note for unit2.', ],
                            2 => [ 'json' => '{
                        "key": "value",
                        "key2": "value2",
                        "key3": "value3"
                    }',
                         ],
                    ],
                    'original-data' => [],
                    'source' => [
                            'content' => '<pc id="2">Hello <mrk id="m2" type="term">World2</mrk>!</pc>',
                            'attr'    => [],
                    ],
                    'target' => [
                            'content' => '<pc id="2">Bonjour le <mrk id="m2" type="term">Monde2</mrk> !</pc>',
                            'attr'    => [],
                    ],
                ],
            ],
        ];

        $this->assertArraySimilar( $parsed[ 'files' ][ 1 ], $exp );
    }
}