<?php
namespace Service\Excel;
class Compare{


    /**
     * @param $dataA
     * @param $dataB
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public static function compare($dataA, $dataB){
        list($pathA, $flagA, $columnsA, $uniqueColumnsA) = $dataA;
        list($pathB, $flagB, $columnsB, $uniqueColumnsB) = $dataB;

        //最终结果输出的标题
        $outputUnique = array_values($uniqueColumnsA);

        $outputTitleA = array_values($columnsA);
        foreach ($outputTitleA as &$title){
            $title = $flagA.$title;
        }
        $outputTitleB = array_values($columnsB);
        foreach ($outputTitleB as &$title){
            $title = $flagB.$title;
        }
        $outputCompareTitles = [];
        foreach ($outputTitleA as $k=>$titleA){
            $outputCompareTitles[] = $titleA."/".$outputTitleB[$k];
        }


        //获取要比较的数据
        $dataAA = self::getData($pathA, $columnsA, $uniqueColumnsA);
        $dataBB = self::getData($pathB, $columnsB, $uniqueColumnsB);
        //输出结果
        $compareResult[] = array_merge(
            $outputUnique,
            $outputCompareTitles,
            [
            "备注说明"
        ]);
        //多出的订单
        $leftAA = $leftBB = [];
        //开始比较
        foreach ($dataAA as $key=>$valueA){
            if (isset($dataBB[$key])){
                $valueB = $dataBB[$key];
                $comment = [];
                $compare = [];
                foreach ($valueA as $index=>$value){
                    $compare[] = $value."/".$valueB[$index];
                    if ($value != $valueB[$index]){
                        $comment[] = join(",", [
                            $outputTitleA[$index]."=". $value,
                            $outputTitleB[$index]."=" .$valueB[$index]
                        ]);
                    }
                }
                if (!empty($comment)){
                    $compareResult[] = array_merge(
                        explode("_", $key),
                        $compare,
                        [
                            join("\t", $comment)
                        ]
                    );
                }
                unset($dataBB[$key]);
            }else{
                $leftAA[] = $key;
            }
        }
        //剩余A的
        if (!empty($leftAA)){
            foreach ($leftAA as $key=>$valueA){
                $compare = [];
                foreach ($valueA as $value){
                    $compare[] = $value."/";
                }
                $compareResult[] = array_merge(
                    explode("_", $key),
                    $compare,
                    [
                        "没有找到".$flagB."对应的订单"
                    ]
                );
            }
        }

        //剩余B的
        $leftBB = $dataBB;
        if (!empty($leftBB)){
            foreach ($leftBB as $key=>$valueB){
                $compare = [];
                foreach ($valueB as $value){
                    $compare[] = "/" . $value;
                }
                $compareResult[] = array_merge(
                    explode("_", $key),
                    $compare,
                    [
                        "没有找到".$flagA."对应的订单"
                    ]
                );
            }
        }
        return $compareResult;
    }















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
            $uniqueValues = $read_values;
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