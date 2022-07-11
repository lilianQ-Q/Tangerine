<?php

namespace Tangerine\utils;

class Prettier
{
    static function prettyHtml(string $code)
    {
        $tabs = 0;
        echo "oui\n";
        $code = str_replace(array("\r", "\n"), '', $code);
        $code = str_split(preg_replace("(>(\s+)<)is", '><', $code));
        $new = "";
        foreach($code as $character)
        {
            $new .= $character;
            if ($character === '>')
            {
                $tabs++;
                $new .= "\n";
                for ($i = 1; $i <= $tabs; $i++)
                {
                    $new .= "\t";
                }
            }
        }
        echo $new;
        exit ;
    }
}