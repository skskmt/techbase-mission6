<?php
function temp(){
    return true;
}
class testtest {
    public function tempone(){
        echo "tempone";
    }

    public function temptwo(){
        return true;
    }
    
}

$test_ = new testtest();
$test_ -> temptwo();
if ($test_ -> temptwo() == true){
    echo "true1";
}elseif($test_ -> temptwo() == "1"){
    echo "true2";
}else{
    echo "falseee";
}
?>