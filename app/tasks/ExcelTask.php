<?php

class ExcelTask extends \Phalcon\Cli\Task
{

    protected $excelPath = DATA_PATH ."./excel/";

    public function mainAction()
    {
        echo "This is excel task!";
    }


    public function dataAA(){
        //读取文件1
        $name = "982b5c4ce0e0aea5.xls";
        $flag = "【联想】";
        //要读取的表头列名
        $read_titles=[
          "M" => "商品实际支付金额",
          "J"=>"商品数量",
          "k"=>"商品单价"
        ];
        //作为比较唯一索引的表头列名
        $unique_titles=[
            "A"=>"订单编号",
            "G"=>"产品组"
        ];
        $data = Service\Excel\Compare::getData($this->excelPath . $name, $read_titles, $unique_titles);
        return [
            $flag,
            $data
        ];
    }

    public function dataBB(){
        //读取的文件
        $name = "lenovo-20200611(1).xlsx";
        $flag = "【多麦】";
        //要读取的表头列名
        $read_titles=[
            "H"=>"商品净额",
            "O"=>"商品数量",
            "N"=>"商品单价"
        ];
        //作为比较唯一索引的表头列名
        $unique_titles=[
            "E"=>"订单号",
            "I"=>"类目id"
        ];
        $data = Service\Excel\Compare::getData($this->excelPath . $name, $read_titles, $unique_titles);
        return [
            $flag,
            $data
        ];
    }

    public function compareAction(){
        list($flagA, $dataA) = $this->dataAA();
        list($flagB, $dataB) = $this->dataBB();

        //比较结果文件
        $compareFile = $this->excelPath."/compare.csv";
        $leftA=[];
        $leftB = [];

        $fp = fopen($compareFile, 'w');

        foreach ($dataA as $key=>$value){
            if (isset($dataB[$key])){
                if ($dataB[$key] != $value){
                    fwrite($fp, "(". $key. " ) : ".$flagA."=(" . $value ."),  ".$flagB."=(". $dataB[$key].")");
                    fwrite($fp, "\n");
                }
                unset($dataB[$key]);
            }else{
                $leftA[] = $key;
            }
        }
        //剩余A的
        if (!empty($leftA)){
            fwrite($fp, "\n\n");
            fwrite($fp, "==============". $flagA ." LEFT START======================");
            fwrite($fp, "\n");
            foreach ($leftA as $key){
                fwrite($fp, $key);
                fwrite($fp, "\n");
            }
            fwrite($fp, "==============". $flagA ." LEFT END======================");
            fwrite($fp, "\n");
            fwrite($fp, "\n\n");
        }


        //剩余B的
        $leftB = $dataB;
        if (!empty($leftB)){
            fwrite($fp, "\n\n");
            fwrite($fp, "==============".$flagB." LEFT START======================");
            fwrite($fp, "\n");
            foreach ($leftB as $key){
                fwrite($fp, $key);
                fwrite($fp, "\n");
            }
            fwrite($fp, "==============".$flagB." LEFT END======================");
            fwrite($fp, "\n");
            fwrite($fp, "\n\n");
        }

        fclose($fp);
        echo "~~~~~~~~END~~~~~~~~~~~~";
    }




}
