<?php

namespace Funbox\Commands;

use Funbox\Framework\Console\Commands\Attributes\Command;
use Funbox\Framework\Console\Commands\CommandInterface;
use Funbox\Framework\Registry\StateRegistry;

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
//        \ Use php bin/console help  /
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

        array_unshift($helpLines, "Funbox console accepts the following commands:");
        array_push($helpLines, PHP_EOL);

        $help = implode(PHP_EOL, $helpLines);

        echo $help;

        return 0;
    }

}
