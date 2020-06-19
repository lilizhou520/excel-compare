<?php

class ExcelTask extends \Phalcon\Cli\Task
{

    protected $excelPath = DATA_PATH ."./excel/";

    public function mainAction()
    {
        echo "This is excel task!";
    }

    /**
     * @return array
     */
    public function dataAA(){
        //读取文件1
        $name = "多麦1-18号销量数据.xls";
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
        return [
            $this->excelPath . $name,
            $flag,
            $read_titles,
            $unique_titles
        ];
    }

    /**
     * 多麦的数据
     * @return array
     */
    public function dataBB(){
        //读取的文件
        $name = "多麦-2020-06-18.xlsx";
        $flag = "【多麦】";
        //要读取的表头列名
        $read_titles=[
            "I"=>"商品净额",
            "P"=>"商品数量",
            "O"=>"商品单价"
        ];
        //作为比较唯一索引的表头列名
        $unique_titles=[
            "F"=>"订单号",
            "J"=>"类目id"
        ];
        return [
            $this->excelPath . $name,
            $flag,
            $read_titles,
            $unique_titles
        ];
    }

    public function compareAction(){
        $compareResult = \Service\Excel\Compare::compare($this->dataAA(), $this->dataBB());
        \Service\Excel\Writer::savePath($compareResult, $this->excelPath."compare-".date("YmdHis").".xlsx");
        echo "~~~~~~~~END~~~~~~~~~~~~";
    }




}
