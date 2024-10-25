<?php

namespace Sibas\Http\Controllers\Excel;

use DB;
use Illuminate\Http\Request;

use Sibas\Http\Requests;
use Sibas\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;




class ExportXlsController extends Controller {

    var $fileName = 'file';
    var $array = array();
    var $freezeColumn = false;
    var $freezeFila = false;
    var $fontFamily = 'Arial';
    var $cabecera = false;
    var $fontSize = 10;
    /**
     * 
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function retorna($val) {
        return $val+10;
    }
    
    /**
     * funcion cargar array/objeto y nombre de archivo 
     * @param type $object_arr
     * @param type $fileName
     */
    public function arrayObj($object_arr, $fileName, $obj) {
        $this->fileName = $fileName . '_' . date('d-m-Y');
        if ($obj) {
            $this->array = json_decode(json_encode($object_arr), true);
        } else {
            $this->array = $object_arr;
        }
    }

    /**
     * fucnion congela columna selecionada
     * @param type $param
     */
    public function freezeColumn($param) {
        $this->freezeColumn = $param;
    }

    /**
     * funcion congela fila seleccionada
     * @param type $param
     */
    public function freezeFila($param) {
        $this->freezeFila = $param;
    }

    /**
     * funcion carga tipo de letra
     * @param type $param
     */
    public function fontFamily($param) {
        $this->fontFamily = $param;
    }

    /**
     * funcion selecciona y pone estilos a cabecera
     * @param type $param
     */
    public function cabecera($param) {
        $this->cabecera = $param;
    }
    
    /**
     * tamaño de letra del documento
     * @param type $param
     */
    public function fontSize($param) {
        $this->fontSize = $param;
    }

    /**
     * funcion exporta xls
     */
    public function exportXls() {
        $data = $this->array;        
        Excel::create($this->fileName, function($excel) use($data) {
            $excel->sheet('Sheetname', function($sheet) use($data) {

                $sheet->fromArray($data);                
                $sheet->setFontFamily($this->fontFamily);
                $sheet->setFontSize($this->fontSize);
                $sheet->setFontBold(false);
                $sheet->setAutoFilter();
                if ($this->freezeFila)
                    $sheet->setFreeze($this->freezeFila);

                if ($this->freezeColumn)
                    $sheet->freezeFirstRowAndColumn($this->freezeColumn);

                $sheet->setAutoSize(true);

                if ($this->cabecera) {
                    $sheet->cells($this->cabecera, function($cells) {
                        $cells->setBackground('#337ab7');
                        $cells->setFontColor('#ffffff');
                        $cells->setFontFamily('Arial');
                        $cells->setFontSize($this->fontSize);
                        $cells->setAlignment('center');
                    });
                }

                $limit = count($data) + 1;
                $sheet->cells('A2:M' . $limit, function($cells) {
                    $cells->setFontFamily($this->fontFamily);
                    $cells->setFontSize($this->fontSize);
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
    }

}
