<?php

require_once __DIR__ . "/../../vendor/autoload.php";

$arr = [1,2,3,4,5,6,7,8,9,10];

# 1
//$f = function (array $arr, int $len) {
//    $count = 0;
//    $acc = 0;
//    foreach ($arr as $value)
//    {
//        if ($len == $count)
//        {
//            break;
//        }
//        $acc += $value*$value;
//        $count++;
//
////        print_r($value . "\n");
//        print_r($acc . "\n");
//    }
//};
//
//$f($arr, 5);

# 2
// if 한번 -> filter
$filter = function (callable $f, $iter): \Generator {
    foreach ($iter as $item)
    {
        if (call_user_func($f, $item))
        {
            yield $item;
        }
    }
};

$valuesAfterFilter = $filter(function ($item) {
    return $item % 2 === 0;
}, $arr);
//foreach ($valuesAfterFilter as $value)
//{
//    print_r($value);
//}

// elements 들에 한번씩 처리 -> map
$map = function (callable $f, $iter): \Generator {
    foreach ($iter as $value)
    {
        yield $f($value);
    }
};

$valuesAfterMap = $map(function ($value) {
    return $value * $value;
}, $valuesAfterFilter);
//foreach ($valuesAfterMap as $value)
//{
//    print_r($value);
//    print_r("\n");
//}

// #3. (fp - 구체적으로 어떻게 그 일을 할것이냐 기술하는 방식 )
$take = function ($len, $iter) {
    $result = [];
    foreach ($iter as $item)
    {
        $result[] = $item;
        if (count($result) == $len) {
            break;
        }
    }
    return $result;
};


$f = function (array $arr, int $len) use ($take, $map, $filter) {
    return $take($len, $map(function ($value) {
        return $value * $value;
    }, $filter(function ($item) {
        return $item % 2 === 0;
    }, $arr)));
};
//$result = $f($arr, 3);
//foreach ($result as $value)
//{
//    print_r($value);
//    print_r("\n");
//}

// #4. 어떤 이터러블을 특정한 값으로 축약을 하는 역함 함수 -> reduce
$reduce = function ($f, $acc, $iter) {
    foreach ($iter as $value)
    {
        $acc = $f($acc, $value);
    }
    return $acc;
};


$f = function (array $arr, int $len) use ($take, $map, $filter, $reduce) {
    return $reduce(function ($acc, $item) {
        return $acc + $item;
    }, 0, $take($len, $map(function ($value) {
        return $value * $value;
    }, $filter(function ($item) {
        return $item % 2 === 0;
    }, $arr))));
};
$result = $f([1,2,3,4,5], 2);
print_r($result);
print_r("\n");


