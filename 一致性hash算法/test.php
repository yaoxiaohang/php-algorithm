<?php
/**
 *
 * User: yaoxiaohang
 * Date: 2018/9/30
 * Time: 10:57
 */

include "./CacheHashMap.php";

//加入10个 ip 地址
$nodes = [];
for($i = 0; $i < 10; $i++){
    $ip1 = random_int(10,200);
    $ip2 = random_int(10,200);
    $ip3 = random_int(10,200);
    $ip4 = random_int(10,200);
    $nodes[] = "{$ip1}.{$ip2}.{$ip3}.{$ip4}";
}

// 随机获取 key 返回node
$keys = [];
$keyname = ['a','progent','dsadas','dsddd','rrrr','dddd','ffffg','hhhhh','jjjjj','xxxxx','cccc','vvvvv'];
for($i = 0; $i < 10000; $i++){
    $key = random_int(0,10);
    $keys[] = md5(md5( "{$keyname[$key]}_key" . $i));
}
$cacheHashMap = CacheHashMap::getInstance($nodes);

$getNodes = [];
foreach ($keys as $v){
    $getNodes[] = $cacheHashMap->getNodeByKey($v);
}
file_put_contents("./nodedata.php","<?php\r\nreturn " . var_export($getNodes,true) . ";");

$newArr = [];
foreach ($getNodes as $v){
    if(isset($newArr[$v])){
        $newArr[$v]++;
    }else{
        $newArr[$v] = 1;
    }
}
asort($newArr);
echo end($newArr) - reset($newArr);
file_put_contents("./newnodedata.php","<?php\r\nreturn " . var_export($newArr,true) . ";");
die;