<?php

class jisuan
{
    private $optr = [];
    private $opnd = [];

    public function run($str)
    {
        $arr = str_split($str);
        foreach ($arr as $v){
            $this->check($v);
        }
        while(count($this->optr)){
            $a = array_pop($this->opnd);
            $b = array_pop($this->opnd);
            $c = array_pop($this->optr);
            $top = 0;
            echo $b . $c . $a . "-3\n";;
            eval('$top =' . $b . $c . $a . ";");
            array_push($this->opnd,$top);
        }
        var_dump($this->optr);
        var_dump($this->opnd);
        return 1;
    }

    private function check($str)
    {
        if(is_numeric($str)){
//            echo $str . "-1\n";
            array_push($this->opnd,$str);
        }else{
            if(count($this->optr)){
                $jsfh = end($this->optr) . $str;
//                echo $jsfh. "-2\n";;
                switch ($this->getPre($jsfh)){
                    case "<":
                        array_push($this->optr,$str);
                        break;
                    case "=":
                        array_pop($this->optr);
                        break;
                    case ">":
                        $a = array_pop($this->opnd);
                        $b = array_pop($this->opnd);
                        $c = array_pop($this->optr);
//                        echo $b . $c . $a . "-3\n";;
                        $top = 0;
                        eval('$top =' . $b . $c . $a . ";");
                        array_push($this->opnd,$top);
                        $this->check($str);
                        break;
                }
            }else{
                array_push($this->optr,$str);
            }
        }
    }

    private function getPre($params)
    {
        $fuhaos = [
            '++' => '>',
            '+-' => '>',
            '+*' => '<',
            '+/' => '<',
            '+(' => '<',
            '+)' => '>',
            '-+' => '>',
            '--' => '>',
            '-*' => '<',
            '-/' => '<',
            '-(' => '<',
            '-)' => '>',
            '*+' => '>',
            '*-' => '>',
            '**' => '>',
            '*/' => '>',
            '*(' => '<',
            '*)' => '>',
            '/+' => '>',
            '/-' => '>',
            '/*' => '>',
            '//' => '>',
            '/(' => '<',
            '/)' => '>',
            '(+' => '<',
            '(-' => '<',
            '(*' => '<',
            '(/' => '<',
            '((' => '<',
            '()' => '=',
            ')+' => '>',
            ')-' => '>',
            ')*' => '>',
            ')/' => '>',
            ')(' => '>',
            '))' => '>',
        ];
        return $fuhaos[$params];

    }
}


$str = "1+3*3+(3*5)/2+7*12/2";
$obj = new jisuan();
$a = $obj->run($str);
echo $a;
