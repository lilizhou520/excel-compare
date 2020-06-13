<?php
namespace Service\Excel;
class Compare{

    /**
     * 获取需要做比较的数据
     * @param $read_file
     * @param $read_titles
     * @param $unique_titles
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function getData($read_file, $read_titles, $unique_titles){
        $reader = Reader::instance($read_file);
        //$headTitles = $reader->getSheetHeaders();
        $body = $reader->getSheetBody();

        $data = [];
        foreach ($body as $rIndex=>$rCols){
            //逐行获取
            $read_unique_values=[];
            $read_values = [];

            foreach ($read_titles as $cIndex=> $title){
                $cIndex = strtoupper($cIndex);
                $read_values[] = self::formatValue($rCols[$cIndex]);
            }
            foreach ($unique_titles as $cIndex=> $title){
                $cIndex = strtoupper($cIndex);
                $read_unique_values[] = self::formatValue($rCols[$cIndex]);
            }
            $uniqueKey = join("_", $read_unique_values);
            $uniqueValues = join("_", $read_values);
            $data[$uniqueKey] = $uniqueValues;
        }
        return $data;
    }


    public static function formatValue($cCol){
        if (is_numeric($cCol)){
            if (is_float($cCol)){
                return round(trim($cCol), 3);
            }else{
                return intval(trim($cCol));
            }
        }else{
            return trim($cCol);
        }
    }

}