<?php

namespace Bolero\Commands;

use Bolero\Framework\Console\Commands\Attributes\Command;
use Bolero\Framework\Console\Commands\CommandInterface;
use Bolero\Framework\Registry\StateRegistry;

#[Command(name: "help")]
#[Command(desc: "Shows this help.")]
class Help implements CommandInterface
{

    public function execute(array $params = []): int
    {

//        echo <<<COWSAY
//        ___________________________
//        /       It looks like       \
//        | you don't know what to do |
//        \ Use php bin/exec help  /
//         ---------------------------
//             \  ^__^
//              \ (oo)\________
//                (__)\        )\/\
//                    ||----w |
//                    ||     ||
//        COWSAY . PHP_EOL;

        $helpCommands = StateRegistry::read('commands:help');

        $helpLines = [];
        foreach ($helpCommands as $index => $commandHelp) {

            $name = key($commandHelp);
            $desc = $commandHelp[$name];

            $helpLines[] = sprintf("\t- %s => %s", $name, $desc);
        }

        sort($helpLines);

        array_unshift($helpLines, "Bolero exec accepts the following commands:");
        array_push($helpLines, PHP_EOL);

        $help = implode(PHP_EOL, $helpLines);

        echo $help;

        return 0;
    }

}
