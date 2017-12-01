<?php

/*$v = 3;

$f = function ($x) use ($v){
    return $x * $v;
};

echo $f(5).PHP_EOL;

$v = 10;

echo $f(5).PHP_EOL;

unset($v);

echo $f(5).PHP_EOL;*/

/*function multiplica ($v){
    return function ($x) use ($v){
        return $x*$v;
    };
}

$duplica = multiplica(2);
$triplica = multiplica(3);

//echo $duplica(5).PHP_EOL;
//echo $triplica(5).PHP_EOL;

function f(callable $c, $p)
{
    echo call_user_func($c,$p) . PHP_EOL;
}

class C
{
    public static function m($r)
    {
        return "Es un metodo estatico, y recibe $r";
    }
}


$f = function($x) {return "Hoola $x";};

f($f, "Manolo");

$o = new DateTime;

f([$o, 'format'],"d-m-y");

f('strlen','hola');

f(['C','m'],75);*/

$a = [
    [3,5,6],
    [2,15],
    [2,67,8,2,1],
    [18],
];

usort($a, function ($x,$y){return max($x) <=> max($y);});
print_r($a);
