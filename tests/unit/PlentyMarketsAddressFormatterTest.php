<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit;

use lujie\plentyMarkets\PlentyMarketAddressFormatter;
use lujie\plentyMarkets\PlentyMarketsAdminClient;
use Yii;

class PlentyMarketsAddressFormatterTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $addresses = [
            [
                [
                    "country" => "FR",
                    "name1" => "",
                    "name2" => "Nicolas Blanc",
                    "name3" => "",
                    "address1" => "219 route de Verlioz",
                    "address2" => "",
                    "address3" => "",
                ],
                [
                    "country" => "FR",
                    "name1" => "",
                    "name2" => "Nicolas",
                    "name3" => "Blanc",
                    "address1" => "route de Verlioz",
                    "address2" => "219",
                    "address3" => "",
                ]
            ],
            [
                [
                    "country" => "IT",
                    "name1" => "",
                    "name2" => "Riccardo Lorenzato",
                    "name3" => "",
                    "address1" => "Via Jerago 3",
                    "address2" => "",
                    "address3" => "",
                ],
                [
                    "country" => "IT",
                    "name1" => "",
                    "name2" => "Riccardo",
                    "name3" => "Lorenzato",
                    "address1" => "Via Jerago",
                    "address2" => "3",
                    "address3" => "",
                ]
            ],
            [
                [
                    "country" => "DE",
                    "name1" => "",
                    "name2" => "Florian Busch",
                    "name3" => "",
                    "address1" => "Maurepasstraße 99g",
                    "address2" => "",
                    "address3" => "",
                ],
                [
                    "country" => "DE",
                    "name1" => "",
                    "name2" => "Florian",
                    "name3" => "Busch",
                    "address1" => "Maurepasstraße",
                    "address2" => "99g",
                    "address3" => "",
                ],
            ],
            [
                [
                    "country" => "DE",
                    "name1" => "",
                    "name2" => "Enedin Tajic",
                    "name3" => "",
                    "address1" => "Lichtstraße 5 7",
                    "address2" => "",
                    "address3" => "",
                ],
                [
                    "country" => "DE",
                    "name1" => "",
                    "name2" => "Enedin",
                    "name3" => "Tajic",
                    "address1" => "Lichtstraße",
                    "address2" => "5 7",
                    "address3" => "",
                ],
            ],
            [
                [
                    "country" => "FR",
                    "name1" => "",
                    "name2" => "Vacheron Jean-Laurent",
                    "name3" => "",
                    "address1" => "10b rue du Puits Poulton",
                    "address2" => "",
                    "address3" => "",
                ],
                [
                    "country" => "FR",
                    "name1" => "",
                    "name2" => "Vacheron",
                    "name3" => "Jean-Laurent",
                    "address1" => "rue du Puits Poulton",
                    "address2" => "10b",
                    "address3" => "",
                ],
            ],
            [
                [
                    "country" => "UK",
                    "name1" => "",
                    "name2" => "Kieran",
                    "name3" => "Boswell",
                    "address1" => "18 LEISURE WALK",
                    "address2" => "",
                    "address3" => "WILNECOTE",
                ],
                [
                    "country" => "UK",
                    "name1" => "",
                    "name2" => "Kieran",
                    "name3" => "Boswell",
                    "address1" => "LEISURE WALK",
                    "address2" => "18",
                    "address3" => "WILNECOTE",
                ],
            ],
            [
                [
                    "country" => "UK",
                    "name1" => "",
                    "name2" => "Laura",
                    "name3" => "Croker",
                    "address1" => "52",
                    "address2" => "",
                    "address3" => "Woollerton Crescent Wendover",
                ],
                [
                    "country" => "UK",
                    "name1" => "",
                    "name2" => "Laura",
                    "name3" => "Croker",
                    "address1" => "Woollerton Crescent Wendover",
                    "address2" => "52",
                    "address3" => "",
                ],
            ],
            [
                [
                    "country" => "UK",
                    "name1" => "",
                    "name2" => "Charles",
                    "name3" => "webb",
                    "address1" => " FLAT 35 BURNEY HOUSE HIGHBURY DRIVE",
                    "address2" => "",
                    "address3" => "",
                ],
                [
                    "country" => "UK",
                    "name1" => "",
                    "name2" => "Charles",
                    "name3" => "webb",
                    "address1" => "BURNEY HOUSE HIGHBURY DRIVE",
                    "address2" => "FLAT 35",
                    "address3" => "",
                ],
            ],
        ];
        foreach ($addresses as [$address, $exceptedAddress]) {
            $this->assertEquals($exceptedAddress, PlentyMarketAddressFormatter::format($address));
        }
    }
}
