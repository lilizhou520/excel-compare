<?php
namespace Service\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Writer{


    public static function savePath(Array $results, $path){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getDefaultColumnDimension()->setWidth(15);
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        //写表头
        if (!empty($results)){
            $fields = array_keys($results[0]);
        }else{
            $fields = [];
        }

        $col = 65; //A
        for ($i=0;$i<count($fields);$i++){
            $sheet->setCellValue(chr($col)."1", $results[0][$fields[$i]]);
            $sheet->getStyle(chr($col)."1")->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $col++;
        }

        //写数据
        $row=2;
        foreach ($results as $k=>$data){
            if($k==0){
                continue;
            }
            $col=65;
            for ($i=0; $i<count($fields);$i++){
                $sheet->setCellValue(chr($col).$row, $data[$fields[$i]]);
                $sheet->getStyle(chr($col).$row)->getAlignment()->setWrapText(true)->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $col++;
            }
            $row++;
        }
        //直接指定路径保存
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);
        exit();
    }



















}