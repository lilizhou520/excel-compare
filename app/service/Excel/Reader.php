<?php
namespace Service\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * excel 导入
 * @author lemon
 * @datetime 2020/6/13 11:16
 */
class Reader{
    /**
     * @var \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    protected $sheet;

    /**
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     */
    protected function __construct($sheet){
        $this->sheet = $sheet;
    }

    /**
     * @param $fileName
     * @param int $sheetIndex
     * @return Reader
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function instance($fileName, $sheetIndex=0){
        $sheet = self::loadExcel($fileName, $sheetIndex);
        return new self($sheet);
    }



    /**
     * 获取 表头内容
     * @param integer $rowIndex 表头行索引
     * @return array
     */
    public function getSheetHeaders($rowIndex=1){
        $headers = [];
        $data = $this->sheet->getHighestRowAndColumn();
        // 最大列
        $maxCol = $data['column'];
        $maxColIndex = self::getColumnIndex($maxCol);
        //表头一般是第一行
        for ($c = 0; $c <= $maxColIndex; $c++) {
            $cell = $this->sheet->getCellByColumnAndRow($c+1, $rowIndex);
            $cTitle = trim($cell->getValue());
            $cTitle = trim($cTitle," ");
            $headers [$cell->getColumn()] = $cTitle;
        }
        return $headers;
    }

    /**
     * 获取 表头内容
     * @param integer $startRowIndex 表主体 开始行索引
     * @return array
     */
    public function getSheetBody($startRowIndex=2){
        $body = [];
        $data = $this->sheet->getHighestRowAndColumn();
        // 最大列
        $maxColIndex = $this->getColumnIndex($data['column']);
        for ($rIndex = $startRowIndex; $rIndex <= $data['row']; $rIndex++) {
            $rowColumn=null;
            $r = [];
            for ($c = 0; $c <= $maxColIndex; $c++) {
                $cell = $this->sheet->getCellByColumnAndRow($c+1, $rIndex);
                $cBody = trim($cell->getValue());
                $cBody = trim($cBody," ");
                $r [$cell->getColumn()] = $cBody;
                $rowColumn = $cell->getRow();
            }
            $body[$rowColumn] = $r;
        }
        return $body;
    }



    /**
     * @param $fileName
     * @param int $index
     * @return \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function loadExcel($fileName, $index=0){
        $excel = IOFactory::load($fileName);
        return $excel->getSheet($index);
    }


    /**
     * 获取列的索引
     *
     * @param string $column
     *        	A -> ZZ
     * @return int
     */
    public static function getColumnIndex($column = 'A') {
        $column = strtoupper ( $column );
        if (strlen ( $column ) == 1) {
            return ord ( $column ) - 65;
        } else {
            $first = $column [0];
            $second = $column [1];

            return 26 * (ord ( $first ) - 64) + ord ( $second ) - 65;
        }
    }





}