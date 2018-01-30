<?php

class Render extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index(){
		$this->error();
	}

	public function excel(){
		
		$userfile = $_FILES['file1'];
		$target_file = $userfile['tmp_name'];

        require WWW_LIBS. 'PHPOffice/PHPExcel.php';
        require WWW_LIBS. 'PHPOffice/PHPExcel/IOFactory.php';

        $inputFileName = $target_file;
        $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($inputFileName);

        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        $headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
        $headingsArray = $headingsArray[1];

        $r = -1;
        $data = array();
        $startRow = 1;

        for ($row = $startRow; $row <= $highestRow; ++$row) {
            $dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);

            ++$r;
            $col = 0;
            foreach ($headingsArray as $columnKey => $columnHeading) {
                $val = $dataRow[$row][$columnKey];

                $text = '';
                foreach (explode(' ', trim($val)) as $value) {

                    if( $value=='' ) continue;
                    $text .= !empty($text) ? ' ':'';
                    $text .= $value;
                }

                // if( $text=='' ) continue;

                $data[$r][$col] = trim($text);
                $col++;
            }
        }

        $fields = array();
        $type = isset($_REQUEST['type']) ? $_REQUEST['type']: '';
        if( $type=='company' ){
            $fields[] = array('id'=>'name','name'=>'name');
        } 
        elseif( $type=='people' ){
            $fields[] = array('id'=>'name','name'=>'name');
            // $fields[] = array('id'=>'address','name'=>'address');
            $fields[] = array('id'=>'organization','name'=>'organization');
        }
        

        echo json_encode( array(
        	'fields' => $fields,
        	'column' => $col+1,
        	'row' => count($data),
        	'filename' => $userfile['name'],
        	'lists'=> $data
        ) );

	}
}