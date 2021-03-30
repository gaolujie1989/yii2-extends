<?php
/**
 * @copyright Copyright (c) 2019
 */

return [
    [
        [
            "country" => "FR",
            "name1" => "",
            "name2" => "Nicolas Blanc",
            "name3" => "",
            "address1" => "219 b route de Verlioz",
            "address2" => "",
            "address3" => "",
        ],
        //数字开头，数字到空格处为门牌号，特别是UK/FR
        [
            "country" => "FR",
            "name1" => "",
            "name2" => "Nicolas",
            "name3" => "Blanc",
            "address1" => "route de Verlioz",
            "address2" => "219-b",
            "address3" => "",
        ]
    ],
    [
        [
            "country" => "FR",
            "name1" => "",
            "name2" => "Nicolas Blanc",
            "name3" => "",
            "address1" => "219b route de Verlioz",
            "address2" => "",
            "address3" => "",
        ],
        //数字开头，数字到空格处为门牌号，特别是UK/FR
        [
            "country" => "FR",
            "name1" => "",
            "name2" => "Nicolas",
            "name3" => "Blanc",
            "address1" => "route de Verlioz",
            "address2" => "219b",
            "address3" => "",
        ]
    ],
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
        //数字开头，数字到空格处为门牌号，特别是UK/FR
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
        //数字结尾，数字到最后为门牌号
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
            "name2" => "Matthias Hose",
            "name3" => "",
            "address1" => "Fontanestr. 12 b",
            "address2" => "",
            "address3" => "",
        ],
        //数字后带单个字母结尾，数字到最后为门牌号
        [
            "country" => "DE",
            "name1" => "",
            "name2" => "Matthias",
            "name3" => "Hose",
            "address1" => "Fontanestr.",
            "address2" => "12-b", //12b
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
        //多个数字空格连接结尾，数字为门牌号，-连接
        [
            "country" => "DE",
            "name1" => "",
            "name2" => "Enedin",
            "name3" => "Tajic",
            "address1" => "Lichtstraße",
            "address2" => "5-7",
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
        //数字后带单个字母开头，数字到空格处为门牌号，特别是UK/FR
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
            "name2" => "Laura",
            "name3" => "Croker",
            "address1" => "52",
            "address2" => "",
            "address3" => "Woollerton Crescent Wendover",
        ],
        //address1为数字，address2为空，address3有地址
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
        //FLAT+数字，需要放到address3，如果没有其他数字，则address2留空
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Charles",
            "name3" => "webb",
            "address1" => "BURNEY HOUSE HIGHBURY DRIVE",
            "address2" => "*",
            "address3" => "FLAT-35",
        ],
    ],
    [
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Joe",
            "name3" => "Hurst",
            "address1" => " FLAT 4 65 SUMMERFIELD CRESCENT",
            "address2" => "",
            "address3" => "",
        ],
        //FLAT+数字，需要放到address3，如果有其他数字，则address2填入改数字
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Joe",
            "name3" => "Hurst",
            "address1" => " SUMMERFIELD CRESCENT",
            "address2" => "65",
            "address3" => "FLAT-4 ",
        ],
    ],
    [
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Joe",
            "name3" => "Hurst",
            "address1" => "SUMMERFIELD CRESCENT 60 FLAT 4",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Joe",
            "name3" => "Hurst",
            "address1" => "SUMMERFIELD CRESCENT ",
            "address2" => "60",
            "address3" => "FLAT-4",
        ],
    ],
//    [
//        [
//            "country" => "UK",
//            "name1" => "",
//            "name2" => "Daniel",
//            "name3" => "Paunescu",
//            "address1" => " APARTMENT 23 PARK 5 CLARENCE STREET",
//            "address2" => "",
//            "address3" => "",
//        ],
//        //@TODO
//        [
//            "country" => "UK",
//            "name1" => "",
//            "name2" => "Daniel",
//            "name3" => "Paunescu",
//            "address1" => "  PARK 5 CLARENCE STREET",
//            "address2" => "*",
//            "address3" => "APARTMENT 23",
//        ]
//    ],
//    [
//        [
//            "country" => "ES",
//            "name1" => "",
//            "name2" => "Mª Carmen castilla Montes",
//            "name3" => "",
//            "address1" => "Avenida Virgen de la Palma Nº 18 Portal 1, 1ºC",
//            "address2" => "",
//            "address3" => "",
//        ],
//        //@TODO
//        [
//            "country" => "ES",
//            "name1" => "",
//            "name2" => "Mª Carmen castilla Montes",
//            "name3" => "",
//            "address1" => "Avenida Virgen de la Palma  ",
//            "address2" => "Nº 18",
//            "address3" => "Portal 1, 1ºC",
//        ]
//    ],
//    [
//        [
//            "country" => "ES",
//            "name1" => "",
//            "name2" => "MARIA LUISA LAVIN COBO",
//            "name3" => "",
//            "address1" => "Luis de la Sierra Cano 27/2/ 2 C",
//            "address2" => "",
//            "address3" => "",
//        ],
//        //@TODO
//        [
//            "country" => "ES",
//            "name1" => "",
//            "name2" => "MARIA LUISA LAVIN COBO",
//            "name3" => "",
//            "address1" => "Luis de la Sierra Cano ",
//            "address2" => "27",
//            "address3" => "2/2C",
//        ]
//    ],
    [
        [
            "country" => "DE",
            "name1" => "",
            "name2" => "Jan Schmidt",
            "name3" => "",
            "address1" => "Am Waldberg 28 - OT Knobelsdorf",
            "address2" => "",
            "address3" => "",
        ],
        //@TODO
        [
            "country" => "DE",
            "name1" => "",
            "name2" => "Jan",
            "name3" => "Schmidt",
            "address1" => "Am Waldberg",
            "address2" => "28--",
            "address3" => "OT Knobelsdorf",
        ]
    ],
    [
        [
            "country" => "DE",
            "name1" => "",
            "name2" => "Robin Goldbach",
            "name3" => "",
            "address1" => "Zürnstraße 7 Zimmer 5004",
            "address2" => "",
            "address3" => "",
        ],
        //@TODO 如果有多个数字，取第一个数字为门牌号，后面的文本为address3 ???
        [
            "country" => "DE",
            "name1" => "",
            "name2" => "Robin ",
            "name3" => "Goldbach",
            "address1" => "Zürnstraße ",
            "address2" => "7",
            "address3" => "Zimmer 5004",
        ]
    ],
    [
        [
            "country" => "ES",
            "name1" => "",
            "name2" => "Cristina Oliveira gonzalez",
            "name3" => "",
            "address1" => "Rua carriarico N18. 4A",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "ES",
            "name1" => "",
            "name2" => "Cristina Oliveira",
            "name3" => "gonzalez",
            "address1" => "Rua carriarico",
            "address2" => " N18.-4A",
            "address3" => "",
        ]
    ],
    [
        [
            "country" => "ES",
            "name1" => "",
            "name2" => "Mario Soláns",
            "name3" => "",
            "address1" => "Rúa Río Barbantiño",
            "address2" => "",
            "address3" => "10-2b",
        ],
        //如果address1为空，如果address2为空，address3有数字，移动address3到address2
        [
            "country" => "ES",
            "name1" => "",
            "name2" => "Mario",
            "name3" => "Soláns",
            "address1" => "Rúa Río Barbantiño",
            "address2" => "10-2b",
            "address3" => "",
        ]
    ],
    [
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Daniel",
            "name3" => "Howard",
            "address1" => "209A",
            "address2" => "",
            "address3" => "THE VALE",
        ],
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Daniel",
            "name3" => "Howard",
            "address1" => "THE VALE",
            "address2" => "209A",
            "address3" => "",
        ]
    ],
    [
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Omar",
            "name3" => "Allouji",
            "address1" => "Flat 5, Matcham Buildings\n20 Warburton Road",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Omar",
            "name3" => "Allouji",
            "address1" => " Warburton Road",
            "address2" => "20",
            "address3" => "Flat-5, Matcham Buildings",
        ]
    ],
    [
        [
            "country" => "FR",
            "name1" => "",
            "name2" => "GHENANIA said",
            "name3" => "",
            "address1" => "2 rue du 8 mai 1945",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "FR",
            "name1" => "",
            "name2" => "GHENANIA",
            "name3" => "said",
            "address1" => "rue du 8 mai 1945",
            "address2" => "2",
            "address3" => "",
        ]
    ],
//    [
//        [
//            "country" => "UK",
//            "name1" => "",
//            "name2" => "MR J H",
//            "name3" => "ROGERS",
//            "address1" => " SW&L 3 Station Mews, Old Station Drive",
//            "address2" => "",
//            "address3" => "",
//        ],
//        [
//            "country" => "UK",
//            "name1" => "SW&L",
//            "name2" => "MR J H",
//            "name3" => "ROGERS",
//            "address1" => " Station Mews",
//            "address2" => "3",
//            "address3" => "Old Station Drive",
//        ]
//    ],
//    [
//        [
//            "country" => "DE",
//            "name1" => "",
//            "name2" => "Ingo",
//            "name3" => "Flad",
//            "address1" => "PC Service Flad",
//            "address2" => "",
//            "address3" => "Wormser Str 56",
//        ],
//        [
//            "country" => "DE",
//            "name1" => "PC Service Flad",
//            "name2" => "Ingo",
//            "name3" => "Flad",
//            "address1" => "Wormser Str",
//            "address2" => "56",
//            "address3" => "",
//        ]
//    ],
    [
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Russell",
            "name3" => "Flynn",
            "address1" => " Fletcher Lodge Northcourt Road",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Russell",
            "name3" => "Flynn",
            "address1" => " Fletcher Lodge Northcourt Road",
            "address2" => "*",
            "address3" => "",
        ]
    ],
    [
        [
            "country" => "ES",
            "name1" => "C/Morell 21 1 3",
            "name2" => "Alba Vicente",
            "name3" => "Sanllehi",
            "address1" => "Edificio",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "ES",
            "name1" => "C/Morell 21 1 3",
            "name2" => "Alba Vicente",
            "name3" => "Sanllehi",
            "address1" => "Edificio",
            "address2" => "*",
            "address3" => "",
        ]
    ],
    [
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "R",
            "name3" => "Attchison",
            "address1" => "3 b brown road",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "R",
            "name3" => "Attchison",
            "address1" => "brown road",
            "address2" => "3-b",
            "address3" => "",
        ]
    ],
    [
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Jafer",
            "name3" => "rejib",
            "address1" => "Flat 9, Daffodil Court\n169 Granville Road",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "UK",
            "name1" => "",
            "name2" => "Jafer",
            "name3" => "rejib",
            "address1" => " Granville Road",
            "address2" => "169",
            "address3" => "Flat-9, Daffodil Court",
        ]
    ],
    [
        [
            "country" => "DE",
            "name1" => "Gewerbestraße 9 / Büro",
            "name2" => "Anett",
            "name3" => "Zeidler",
            "address1" => "Zeidler GmbH",
            "address2" => "",
            "address3" => "",
        ],
        [
            "country" => "DE",
            "name1" => "Zeidler GmbH",
            "name2" => "Anett",
            "name3" => "Zeidler",
            "address1" => "Gewerbestraße",
            "address2" => "9-/",
            "address3" => "Büro",
        ]
    ],
    [
        [
            "country" => "DE",
            "name1" => "",
            "name2" => "Fridtjof Hansen",
            "name3" => "",
            "address1" => "Rosseer Weg 19-23",
            "address2" => "",
            "address3" => "Raiffeisen Technik Ostküste GmbH",
        ],
        [
            "country" => "DE",
            "name1" => "Raiffeisen Technik Ostküste GmbH",
            "name2" => "Fridtjof",
            "name3" => "Hansen",
            "address1" => "Rosseer Weg",
            "address2" => "19-23",
            "address3" => "",
        ]
    ],
    [
        [
            "country" => "DE",
            "name1" => "",
            "name2" => "Justin Schmitz",
            "name3" => "",
            "address1" => "Fahrschule Rettig Gmbh",
            "address2" => "Frankfurter Str 24",
            "address3" => "",
        ],
        [
            "country" => "DE",
            "name1" => "Fahrschule Rettig Gmbh",
            "name2" => "Justin",
            "name3" => "Schmitz",
            "address1" => "Frankfurter Str ",
            "address2" => "24",
            "address3" => "",
        ]
    ],
    [
        [

        ],
        [

        ]
    ],
];
