<?php


namespace labile\bot;


class CommandList extends Commands
{
    /**
     * Массив с командами
     * @return array
     */
    public static function text(): array
    {
        return [

            [
                'text' => ['[|котика', '[|котиков', '[|кот'],
                'method' => ['_cat']
            ],

            [
                'text' => ['кончить', 'кон4ить'],
                'method' => ['_kon4']
            ],

            [
                'text' => ['вагина', 'вагина'],
                'method' => ['vagina']
            ],

            [
                'text' => ['блин', 'капец', 'блять', 'пиздец', 'ебать', 'елки иголки', 'екарный бабай'],
                'method' => ['_blin']
            ],

            [
                'text' => ['кик'],
                'method' => ['kick']
            ],

            [
                'text' => ['pr'],
                'method' => ['pr']
            ],

            [
                'text' => ['hi'],
                'method' => ['_hiMessage']
            ],

        ];
    }

    /**
     * Массив с payload (нажатие на кнопку)
     * @return array
     */
    public static function payload(): array
    {
        //todo реализовать команды из массива
        return [

            'command' => [
                [
                    'key' => 'not_supported_button',
                    'method' => ['_not_supported_button']
                ]
            ],

            'settings' =>
                [
                    [
                        'key' => 'exit_msg',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'key' => 'welcome_msg',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'key' => 'rules',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                    [
                        'key' => 'auto_kick',
                        'method' => ['_eventCheckAdmin', '_chatSwitcher']
                    ],

                ],

            'chat' =>
                [
                    [
                        'key' => 'registration',
                        'method' => ['_chatCreate']
                    ],

                ],

        ];

    }
}