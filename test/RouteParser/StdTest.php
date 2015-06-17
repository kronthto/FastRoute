<?php

namespace FastRoute\RouteParser;

class StdTest extends \PhpUnit_Framework_TestCase {
    /** @dataProvider provideTestParse */
    public function testParse($routeString, $expectedRouteDatas) {
        $parser = new Std();
        $routeDatas = $parser->parse($routeString);
        $this->assertSame($expectedRouteDatas, $routeDatas);
    }

    /** @dataProvider provideTestParseError */
    public function testParseError($routeString, $expectedExceptionMessage) {
        $parser = new Std();
        $this->setExpectedException('FastRoute\\BadRouteException', $expectedExceptionMessage);
        $parser->parse($routeString);
    }

    public function provideTestParse() {
        return [
            [
                '/test',
                [
                    ['/test'],
                ]
            ],
            [
                '/test/{param}',
                [
                    ['/test/', ['param', '[^/]+']],
                ]
            ],
            [
                '/te{ param }st',
                [
                    ['/te', ['param', '[^/]+'], 'st']
                ]
            ],
            [
                '/test/{param1}/test2/{param2}',
                [
                    ['/test/', ['param1', '[^/]+'], '/test2/', ['param2', '[^/]+']]
                ]
            ],
            [
                '/test/{param:\d+}',
                [
                    ['/test/', ['param', '\d+']]
                ]
            ],
            [
                '/test/{ param : \d{1,9} }',
                [
                    ['/test/', ['param', '\d{1,9}']]
                ]
            ],
            [
                '/test[opt]',
                [
                    ['/test'],
                    ['/testopt'],
                ]
            ],
            [
                '/test[/{param}]',
                [
                    ['/test'],
                    ['/test/', ['param', '[^/]+']],
                ]
            ],
            [
                '/{param}[opt]',
                [
                    ['/', ['param', '[^/]+']],
                    ['/', ['param', '[^/]+'], 'opt']
                ]
            ],
            [
                '/test[/{name}[/{id:[0-9]+}]]',
                [
                    ['/test'],
                    ['/test/', ['name', '[^/]+']],
                    ['/test/', ['name', '[^/]+'], '/', ['id', '[0-9]+']],
                ]
            ]
        ];
    }

    public function provideTestParseError() {
        return [
            [
                '/test[opt[opt2]',
                "Found more opening '[' than closing ']'"
            ],
            [
                '/testopt]',
                "Found more closing ']' than opening '['"
            ],
            [
                '/test[]',
                "Empty optional part"
            ],
            [
                '/test[[opt]]',
                "Empty optional part"
            ],
        ];
    }
}
