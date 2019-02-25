<?php
if ( ! defined('MODULE_IMPORT_EXCEL')) exit('Access diened');
$moduledir = __DIR__;

header('Content-Type: text/plain; charset=utf-8');

include_once(MODX_BASE_PATH.'core/vendor/phpoffice/phpexcel/Classes/PHPExcel.php');

class chunkReadFilter implements PHPExcel_Reader_IReadFilter
{
	private $_startRow = 0;
	private $_endRow = 0;
	public function setRows($startRow, $chunkSize) {
		$this->_startRow = $startRow;
		$this->_endRow   = $startRow + $chunkSize;
	}
	public function readCell($column, $row, $worksheetName='') {
		if (($row==1) || ($row>=$this->_startRow && $row<$this->_endRow)) {
			return true;
		}
		return false;
	}
}

$file = MODX_BASE_PATH.'box/01.xlsx';
$info = $file.'.inf';
$info_a = false;
if (file_exists($info)) {
	$fh = fopen($info, 'rb');
	if ($fh) {
		$info_d = '';
		while ( ! feof($fh)) $info_d .= fread($fh, 1024*8);
		fclose($fh);
		if ($info_d) $info_a = unserialize($info_d);
	}
}
if ( ! is_array($info)) {
	$info_a = array(
		'file'     => $file,
		'step'     => 1,
		'startRow' => 1,
		'brand'    => false,
		'parent'   => 0,
	);
}

$file     = $info_a['file'];
$step     = $info_a['step'];
$startRow = $info_a['startRow'];
$brand    = $info_a['brand'];
$parent   = $info_a['parent'];

if (1 == $step) {
	$reader = PHPExcel_IOFactory::createReaderForFile($file);
	$data   = $reader->load($file);
	$wsheet = $data->getActiveSheet();

	$items_exists = false;
	$lvl = 0;
	$empty = 0;
	foreach ($wsheet->getRowIterator() AS $row) {
		$i = $row->getRowIndex();
		$row_lvl = $wsheet->getRowDimension($i)->getOutlineLevel();

		$nm = $wsheet->getCellByColumnAndRow(0, $i)->getValue();
		if ( ! $nm) {
			$empty++;
			if ($empty > 50) break;
			continue;
		}
		$empty = 0;

		if ($row_lvl > $lvl) {
			
		}

		if ( ! $row_lvl) {
			$brand = $nm;
			continue;
		}

		$prc = $wsheet->getCellByColumnAndRow(2, $i)->getValue();
		if ($prc) {
			$items_exists = true;
			continue;
		}
	}
}

if (2 == $step) {
	$reader = PHPExcel_IOFactory::createReaderForFile($file);
	$chunkFilter = new chunkReadFilter();
	$reader->setReadFilter($chunkFilter);
	$chunkFilter->setRows($startRow, 500);
	$reader->setReadDataOnly(true);
	$data = $reader->load($file);
	$wsheet = $data->getActiveSheet();

	foreach ($wsheet->getRowIterator() AS $row) {
		$cellIterator = $row->getCellIterator();
		// $cellIterator->setIterateOnlyExistingCells(true);
		$i = $row->getRowIndex();
		$lvl = $wsheet->getRowDimension($i)->getOutlineLevel();
		$empty = 0;
		foreach ($cellIterator AS $cell) {
			$val = $cell->getValue();
			if ( ! $val) $empty++;
			if ($empty > 5) break;
			$col = $cell->getColumn();
			$arrLevel[$i]['lvl'] = $lvl;
			$arrLevel[$i]['data'][$col] = $val;
		}
	}
}

print_r($arrLevel);


