<?php
namespace Service\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Writer{


    public static function savePath(Array $results, $path){

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getDefaultRowDimension()->setRowHeight(15);

        //写数据
        $row=1;
        foreach ($results as $k=>$data){
            $col=65;
            for ($colIndex=0; $colIndex<count($data);$colIndex++){
                $colValue = $data[$colIndex];
                if ($row == 1){
                    if ($colIndex == count($data)-1){
                        $sheet->getColumnDimension(chr($col))->setWidth(100);
                    }else{
                        $sheet->getColumnDimension(chr($col))->setWidth(strlen($colValue));
                    }
                }
                $sheet->setCellValue(chr($col).$row, $colValue);
                $sheet->getStyle(chr($col).$row)->getFont()->setSize(10);
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