<?php

function getConfigs()
{
    foreach (getDefense() as $defense) {
        foreach (getAttacks() as $attacks) {
            $pv = 35 - $defense - array_sum($attacks);
            yield [$defense, $attacks[0], $attacks[1], $attacks[2], $pv];
        }
    }
}

function getDefense()
{
    yield from getCombinations(range(0,4));
}

function getAttacks()
{
    foreach (getCombinations([5,6,7]) as $a1) {
        foreach(getCombinations(range($a1+1, 8)) as $a2) {
            foreach(getCombinations(range($a2+1, 9)) as $a3) {
                yield [$a1, $a2, $a3];
            }
        }
    }
}

function getCombinations(array $possibleValues)
{
    foreach ($possibleValues as $value) {
        yield $value;
    }
}


return getConfigs();

