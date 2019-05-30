<?php

require_once APPPATH . '/PhpSpreadsheet-1.4/vendor/autoload.php';

require_once APPPATH . '/mpdf/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cFields
 *
 * @author dvlpserver
 */
class cexcel {

    //put your code here
    public $rowIndex = 1;
    public $columnIndex = 1;
    private $SelectedWorksheet;
    private $spreadsheet;

    function __construct() {

        $this->CI = &get_instance();


        //require_once APPPATH."/plugins/PhpSpreadsheet/src/PhpSpreadsheet/Spreadsheet.php";
        //$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        /*
          require_once APPPATH . '/PhpSpreadsheet/src/Bootstrap.php';
          $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
          $sheet = $this->SelectedWorksheet;
          $sheet->setCellValue('A1', 'Hello World !');


          $sheet = $this->SelectedWorksheet;
          $sheet->setCellValue('A1', 'Hello World !');

          $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
          $writer->save('/tmp/hello world.xlsx');
         */

        $this->rowFrom = null;
        $this->rowTo = null;
        $this->columnFrom = null;
        $this->columnTo = null;
        $this->coordStart = null;
        $this->coordEnd = null;
        $this->docrepdefault = -1;
        $this->CI->load->model('docrep/document_repository_model', 'docrepmodel', TRUE);
    }

    public function loadTemplate($templateName) {
        $apppath = APPPATH;
        $this->loadExcel("$apppath/excelTemplates/$templateName.xlsx");
    }

    public function loadExcel($filename) {
        $this->spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
        $this->SelectedWorksheet = $this->spreadsheet->getActiveSheet();


        \PhpOffice\PhpSpreadsheet\Shared\Font::setTrueTypeFontPath('/usr/share/fonts/truetype/msttcorefonts/');
        $this->fillBackground = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;

        $this->chartSheet = $this->spreadsheet->createSheet();
        $this->chartSheet->setTitle('SheetCalc');
        $this->chartSheet->setSheetState(PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);

        \PhpOffice\PhpSpreadsheet\Shared\Font::setAutoSizeMethod(\PhpOffice\PhpSpreadsheet\Shared\Font::AUTOSIZE_METHOD_EXACT);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());
        $this->fillBackground = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
    }

    public function newSpreadSheet($title = '') {
        $this->spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $this->SelectedWorksheet = $this->spreadsheet->getActiveSheet();

        if ($title != '') {
            $this->SelectedWorksheet->setTitle($title);
        }


        //$class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf::class;
        //\PhpOffice\PhpSpreadsheet\IOFactory::createWriter('Pdf', $class);

        $this->chartSheet = $this->spreadsheet->createSheet();
        $this->chartSheet->setTitle('SheetCalc');
        $this->chartSheet->setSheetState(PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_VERYHIDDEN);

        \PhpOffice\PhpSpreadsheet\Shared\Font::setTrueTypeFontPath('/usr/share/fonts/truetype/msttcorefonts/');

        $this->spreadsheet->getDefaultStyle()->getFont()->setName('Arial');
        $this->spreadsheet->getDefaultStyle()->getFont()->setSize(10);
        $this->spreadsheet->getDefaultStyle()->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        \PhpOffice\PhpSpreadsheet\Shared\Font::setAutoSizeMethod(\PhpOffice\PhpSpreadsheet\Shared\Font::AUTOSIZE_METHOD_EXACT);
        \PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new \PhpOffice\PhpSpreadsheet\Cell\AdvancedValueBinder());
        $this->fillBackground = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;


        $this->setShowGridLines(true);
    }

    public function setTitle($title) {
        $this->spreadsheet->getActiveSheet()->setTitle($title);
    }

    public function setShowGridLines($bool) {

        $this->SelectedWorksheet->setShowGridlines($bool);
    }

    public function newSheet($title, $select = true) {
        $this->spreadsheet->createSheet()->setTitle($title);

        if ($select) {
            $this->selectActiveSheet($title);
        }
    }

    public function selectActiveSheet($title) {
        $this->spreadsheet->setActiveSheetIndexByName($title);
        $this->SelectedWorksheet = $this->spreadsheet->getActiveSheet();
    }

    public function setAlignHCenter() {

        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function setAlignHRight() {

        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    }

    public function setAlignHLeft() {

        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    }

    /** set the cell active anywhere
     * @param $column int the column of the cell
     * @param $row int the row of the cell
     */
    public function setCellActive($column, $row) {
        $this->SelectedWorksheet->setSelectedCellByColumnAndRow($column, $row);
    }

    /** set the cell value
     * @param $value string|int the cell value
     * @param $column int the column to set the value,if empty,set the value in the current cell
     * @param $row int the row to set the value,
     */
    public function setCell($value, $column = null, $row = null) {
        if (!empty($row) && !empty($column)) {
            $this->SelectedWorksheet->setCellValueByColumnAndRow($column, $row, $value);
        } else {
            $this->SelectedWorksheet->setCellValueByColumnAndRow($this->columnIndex++, $this->rowIndex, $value);
        }
    }

    /** set active cell to next row
     */
    public function nextRow() {
        $this->columnIndex = 1;
        $this->SelectedWorksheet->setSelectedCellByColumnAndRow($this->columnIndex, ++$this->rowIndex);
    }

    /** set the table head 设置表格头部标题栏
     * @param $titles array the title array
     * @param $column int the start column to set the title, default 1
     * @param $row int the row to set the title, default 1
     * @return bool
     */
    public function setColumnTitle($titles, $column = 1,$row = 1) {
        if (!is_array($titles)) {
            return false;
        }
        foreach ($titles as $title) {
            $this->setItemString($row, $column, $title);
            $column++;
        }
        $this->selectArea($row, 1,$row, $column);
        $this->setFontBold(true);
        $this->setBackgroundColor('C8C8C8');
        $this->rowIndex = $row;
        $this->nextRow();
        return true;
    }

    public function setColumnCollapsed($column, $bool) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getColumnDimensionByColumn($column)->setCollapsed($bool);
    }

    public function setColumnVisible($column, $bool) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getColumnDimensionByColumn($column)->setVisible($bool);
    }

    /*
      public function setItemString($row, $column, $value) {
      $sheet = $this->SelectedWorksheet;

      if ($value == NULL) {
      return;
      }

      $sheet->setCellValueByColumnAndRow($column, $row, trim($value));
      }
     */

    public function setItemString($row, $column, $value, $force = false) {
        $sheet = $this->SelectedWorksheet;
        $value = trim($value);
        $this->selectArea($row, $column);


        //$style = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($column, $row)->getStyle();
        // ||
        if (is_numeric($value) || $force) {
            $sheet->setCellValueExplicitByColumnAndRow($column, $row, $value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        } else {
            $sheet->getCellByColumnAndRow($column, $row)->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueByColumnAndRow($column, $row, $value);
        }
    }

    public function getItem($row, $column) {
        $sheet = $this->SelectedWorksheet;
        $this->selectArea($row, $column);

        //$style = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($column, $row)->getStyle();
        // ||
        return $sheet->getCellByColumnAndRow($column, $row)->getValue();
    }

    public function getItemFormatted($row, $column) {
        $sheet = $this->SelectedWorksheet;
        $this->selectArea($row, $column);

        //$style = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($column, $row)->getStyle();
        // ||
        return $sheet->getCellByColumnAndRow($column, $row)->getFormattedValue();
    }

    //$sheet->setCellValueExplicitByColumnAndRow($column, $row, trim($value), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    //$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //$spreadsheet->getActiveSheet()->getCellByColumnAndRow($column, $row)->setDataType(\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING)
    //$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //
        //$spreadsheet->getActiveSheet()->setCellValueExplicitByColumnAndRow($column, $row, trim($value), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);




    public function addChartBar($rowLabelStart, $colLabelStart, $rowLabelFinish, $colLabelFinish, $rowValueStart, $colValueStart, $rowValueFinish, $colValueFinish) {

        $sheet = $this->SelectedWorksheet;

        $colValueStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colValueStart - 1);
        $colValueFinish = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colValueFinish);

        $colLabelStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colLabelStart);
        $colLabelFinish = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colLabelFinish);

        $title = $sheet->getTitle();
        $dataSeriesLabels = [
            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', $title . '!$B$1', null, 1)
        ];


        $dataSeriesValues = [
            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', $title . '!$' . $colValueStart . '$' . $rowValueStart . ':$' . $colValueFinish . '$' . $rowValueFinish . '', null, 4)
        ];


        $series = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART, // plotType
                \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_STANDARD, // plotGrouping
                range(0, count($dataSeriesValues) - 1), // plotOrder
                $dataSeriesLabels, // plotLabel
                null, // plotCategory
                $dataSeriesValues        // plotValues
        );


        $series->setPlotDirection(\PhpOffice\PhpSpreadsheet\Chart\DataSeries::DIRECTION_COL);

//	Set the series in the plot area
        $plotArea = new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [$series]);
//	Set the chart legend
        $legend = new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_RIGHT, null, false);

        $title = new \PhpOffice\PhpSpreadsheet\Chart\Title('Test Column Chart');
        $yAxisLabel = new \PhpOffice\PhpSpreadsheet\Chart\Title('Value ($k)');

//	Create the chart
        $chart = new \PhpOffice\PhpSpreadsheet\Chart(
                'chart1', // name
                $title, // title
                $legend, // legend
                $plotArea, // plotArea
                true, // plotVisibleOnly
                0, // displayBlanksAs
                null, // xAxisLabel
                $yAxisLabel  // yAxisLabel
        );

//	Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A3');
        $chart->setBottomRightPosition('H20');

//	Add the chart to the worksheet
        $sheet->addChart($chart);
    }

    public function setItemProgress($row, $column, $value) {
        $this->selectArea($row, $column);


        $this->chartSheet->setCellValueByColumnAndRow(0, $row, $value - 0);
        $this->chartSheet->setCellValueByColumnAndRow(1, $row, 100 - $value);

        $col1 = \PhpOffice\PhpSpreadsheet\Cell::stringFromColumnIndex(0);
        $col2 = \PhpOffice\PhpSpreadsheet\Cell::stringFromColumnIndex(1);

        $dataSeriesValues2 = [
            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'SheetCalc!$' . $col1 . '$' . $row . ':$' . $col2 . '$' . $row, null, 2),
        ];

//	Build the dataseries
        $series2 = new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_PIECHART, // plotType
                null, // plotGrouping (Donut charts don't have any grouping)
                range(0, count($dataSeriesValues2) - 1), // plotOrder
                null, // plotLabel
                null, // plotCategory
                $dataSeriesValues2        // plotValues
        );
        //	Set up a layout object for the Pie chart
        $layout2 = new \PhpOffice\PhpSpreadsheet\Chart\Layout();
        $layout2->setShowVal(false);
        $layout2->setShowCatName(false);
        $layout2->setShowSerName(false);


//	Set the series in the plot area
        $plotArea2 = new \PhpOffice\PhpSpreadsheet\Chart\PlotArea($layout2, [$series2]);
        $plotArea2->getLayout()->setShowLeaderLines(false);
        $title = new \PhpOffice\PhpSpreadsheet\Chart\Title($value . '%');
        //$legend = new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_TOPRIGHT, null, false);
//	Create the chart
        $chart2 = new \PhpOffice\PhpSpreadsheet\Chart(
                'chart2' . $row, // name
                null, // title
                null, // legend
                $plotArea2, // plotArea
                true, // plotVisibleOnly
                0, // displayBlanksAs
                null, // xAxisLabel
                null   // yAxisLabel		- Like Pie charts, Donut charts don't have a Y-Axis
        );

//	Set the position where the chart should appear in the worksheet
        //die ($this->coordStart);
        $this->selectArea($row, $column);
        $chart2->setTopLeftPosition($this->coordStart);
        $this->selectArea($row + 1, $column + 1);

        $chart2->setBottomRightPosition($this->coordStart, -1, -1);
//	Add the chart to the worksheet
        $this->SelectedWorksheet->addChart($chart2);
    }

    public function setItemFloat($row, $column, $value, $decimal = 4) {
        $sheet = $this->SelectedWorksheet;

        $this->selectArea($row, $column);


        if (is_numeric($value)) {
            $value = round($value, $decimal);
        }
        if ($decimal != 0) {
            $format = "#,##0." . str_repeat('0', $decimal);
        } else {
            $format = '#,##0';
        }


        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $sheet->setCellValueByColumnAndRow($column, $row, $value);
        $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode($format);
    }

    public function setItemFloatCalc($row, $column, $value, $decimal = 4) {
        $sheet = $this->SelectedWorksheet;

        $this->selectArea($row, $column);


        if (is_numeric($value)) {
            $value = round($value, $decimal);
        }
        if ($decimal != 0) {
            $format = "#,##0." . str_repeat('0', $decimal);
        } else {
            $format = '#,##0';
        }

        $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode($format);


        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->setCellValueExplicitByColumnAndRow(
                $column, $row, '=' . trim($value), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_FORMULA
        );


        //$sheet->setCellValueByColumnAndRow($column, $row, $value);
    }

    public function setItemPercentual($row, $column, $value) {
        $sheet = $this->SelectedWorksheet;
        $this->selectArea($row, $column);

        $sheet->setCellValueByColumnAndRow($column, $row, $value / 100);
        $sheet->getStyleByColumnAndRow($column, $row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE);
    }

    public function setItemDate($row, $column, $value) {
        $sheet = $this->SelectedWorksheet;
        $this->selectArea($row, $column);

        $value = $this->CI->cdbhelper->dateGridtoDb($value);
        if ($value == 'null') {
            return;
        }

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }


        $sheet->setCellValueByColumnAndRow($column, $row, $value);

        $sheet->getStyleByColumnAndRow($column, $row)
                ->getNumberFormat()
                ->setFormatCode('mm/dd/yyyy');
    }

    public function setDateTimeFormat($row, $column, $format) {
        $sheet = $this->SelectedWorksheet;
        $this->selectArea($row, $column);
        
        
        $sheet->getStyleByColumnAndRow($column, $row)
                ->getNumberFormat()
                ->setFormatCode($format);
    }

    public function setItemDateMonthYear($row, $column, $value) {
        $this->selectArea($row, $column);

        $sheet = $this->SelectedWorksheet;

        $sheet->setCellValueByColumnAndRow($column, $row, $value);

        $sheet->getStyleByColumnAndRow($column, $row)
                ->getNumberFormat()
                ->setFormatCode('mm/yyyy');
    }

    public function selectArea($rowfrom, $columnfrom, $rowto = null, $columnto = null) {
        $this->rowFrom = $rowfrom;
        $this->rowTo = $rowto;
        $this->columnFrom = $columnfrom;
        $this->columnTo = $columnto;

        $this->coordStart = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnfrom) . $rowfrom;
        $this->coordEnd = null;
        if ($columnto != null) {
            $this->coordEnd = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnto) . $rowto;
        }
    }

    public function getSelectedAreaString() {
        $coord = $this->coordStart;

        if ($this->coordEnd != null) {
            $coord = $coord . ':' . $this->coordEnd;
        }

        return $coord;
    }

    public function setColumnWidth($column, $width) {
        $column = $column;
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $sheet->getColumnDimensionByColumn($column)->setWidth($width);
    }

    public function setColumnWidthAuto($column) {
        $column = $column;
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }


        $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
    }

    public function setRowHeight($line, $height) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getRowDimension($line)->setRowHeight($height);
    }

    public function setRowHeightAuto($line) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getRowDimension($line)->setRowHeight(-1);
    }

    public function setBorderThin() {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderMedium() {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderThick() {

        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderBottomThin() {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'bottom' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderBottomrMedium() {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'bottom' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderBottomThick() {

        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'bottom' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderTopThin() {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'top' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderTopMedium() {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'top' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderTopThick() {

        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'top' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderOuterThin() {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderOuterMedium() {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderOuterThick() {

        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
                ),
            ),
        );
        $sheet->getStyle($this->getSelectedAreaString())
                ->applyFromArray($styleArray);
    }

    public function setBorderColor($color) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getBorders()
                ->getAllBorders()->getColor()->setRGB($color);
    }

    public function setBackgroundColor($color, $end = null) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getFill()->setFillType($this->fillBackground)->getStartColor()->setRGB($color);


        if ($end != null) {
            $sheet->getStyle($this->getSelectedAreaString())
                    ->getFill()->setFillType($this->fillBackground)->getEndColor()->setRGB($end);
        }
    }

    public function setFontBold($setbold) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getFont()->setBold($setbold);
    }

    public function setFontItalic($setitalic) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getFont()->setItalic($setitalic);
    }

    public function setFontColor($color) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getFont()->getColor()->setRGB($color);
    }

    public function setFontSize($size) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getFont()->setSize($size);
    }

    public function setFontName($name) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $sheet->getStyle($this->getSelectedAreaString())
                ->getFont()->setName($name);
    }

    public function setBackGroundFillSolid() {
        $this->fillBackground = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID;
    }

    public function setBackGroundFillGradient() {
        $this->fillBackground = \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR;
    }

    public function mergeCells() {
        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        }
        $this->SelectedWorksheet->mergeCellsByColumnAndRow($this->columnFrom, $this->rowFrom, $this->columnTo, $this->rowTo);
    }

    public function unMergeCells() {
        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }
        $this->SelectedWorksheet->unmergeCellsByColumnAndRow($this->columnFrom, $this->rowFrom, $this->columnTo, $this->rowTo);
    }

    public function pixelsToPoints($pixels) {
        return PhpOffice\PhpSpreadsheet\Shared\Drawing::pixelsToPoints($pixels);
    }

    public function pointsToPixels($points) {
        return PhpOffice\PhpSpreadsheet\Shared\Drawing::pointsToPixels($points);
    }

    public function pixelsToCellDimension($value) {
        $defaultFont = $this->spreadsheet->getDefaultStyle()->getFont();
        return PhpOffice\PhpSpreadsheet\Shared\Drawing::pixelsToCellDimension($value, $defaultFont);
    }

    public function cellDimensionToPixels($value) {
        $defaultFont = $this->spreadsheet->getDefaultStyle()->getFont();
        return PhpOffice\PhpSpreadsheet\Shared\Drawing::cellDimensionToPixels($value, $defaultFont);
    }

    public function addPicture($row, $column, $picture, $maxHeight = 0, $maxWidth = 0) {
        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }


        $this->selectArea($row, $column);
        $col = $this->getSelectedAreaString();

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath($picture);

        $columnHeight = $this->pointsToPixels($sheet->getRowDimension($row)->getRowHeight());

        $drawing->setOffsetX(1);
        $drawing->setOffsetY(1);

        if ($maxWidth != 0 && $maxHeight != 0) {
            $drawing->setResizeProportional(false);
            $drawing->setHeight($maxHeight);
            $drawing->setWidth($maxWidth);
        } else {
            $drawing->setResizeProportional(true);



            $columnWidth = $sheet->getColumnDimensionByColumn($column - 1)->getWidth();

            if ($maxHeight == 0) {
                $drawing->setHeight($columnHeight - 4);

                $imgWidthPoints = $this->pixelsToCellDimension($drawing->getWidth());


                if ($columnWidth < $imgWidthPoints) {
                    $sheet->getColumnDimensionByColumn($column - 1)->setWidth($imgWidthPoints);
                }
            } else {
                $drawing->setHeight($maxHeight);
            }
        }


        $drawing->setWorksheet($this->SelectedWorksheet);
        $drawing->setCoordinates($col);
    }

    public function wrapText($column) {
        $this->SelectedWorksheet->getStyle($column . "1:$column" . $this->SelectedWorksheet->getHighestRow())->getAlignment()->setWrapText(true);
    }

    public function setFitToWidth($bool) {
        $this->SelectedWorksheet->getPageSetup()->setFitToWidth($bool ? 1 : 0);
    }

    public function setFitToHeight($bool) {
        $this->SelectedWorksheet->getPageSetup()->setFitToHeight($bool ? 1 : 0);
    }

    public function saveAsXLSX($filename) {
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->spreadsheet);
        if (file_exists($filename)) {
            unlink($filename);
        }

        $writer->save($filename);
        chmod($filename, 0777);
    }

    public function saveAsOutput($filename) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);

        $writer->save('php://output');
    }

    public function saveAsOutputPDF($filename) {
        header('Content-Type: application/vnd.oasis.opendocument.spreadsheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($this->spreadsheet, 'Mpdf');

        $writer->save('php://output');
    }

    public function cleanMemory() {
        $this->spreadsheet->garbageCollect();
    }
    
    public function insertNewRowBefore($beforeRow, $rowsToAdd = 1) {
        $this->SelectedWorksheet->insertNewRowBefore($beforeRow, $rowsToAdd);

    }

    public function createExcelByGrid($title, $columns, $titlecolumns, $columngroup, $recordset, $rowheight = 100) {

        $array_column = array();
        $columnindex = 1;
        $columns = (array) $columns;
        $titlecolumns = (array) $titlecolumns;
        //$rowheight = $rowheight;
        $f = $this->CI->cfields;


        if (1 == 2) {
            $f = new Cfields();
        }

        foreach ($columns as $key => $value) {
            $value = (array) $value;

            if ((!isset($value['hidden']) || $value['hidden'] != 1) && isset($titlecolumns[$value['field']])) {

                $datacol = array('type' => $value['internaltype'], 'title' => $titlecolumns[$value['field']], 'columnIndex' => $columnindex);

                if (isset($value["render"])) {
                    $tst = explode(':', $value["render"]);
                    if (count($tst) == 2) {
                        $datacol['decimal'] = $tst[1];
                    }
                }

                $array_column[$value['field']] = $datacol;
                $columnindex++;
            }
        }

        $columnindex--;


        $this->selectArea(1, 1, 1, $columnindex - 1);
        $this->mergeCells();
        $this->setItemString(1, 1, $title);
        $this->setBorderThin();
        $this->setFontSize(13);
        $this->setFontBold(true);

        $this->selectArea(1, $columnindex);
        $this->setItemDate(1, $columnindex, date('m/d/Y'));
        $this->setBorderThin();
        $this->setFontSize(13);
        $this->setFontBold(true);


        //cabecalhos
        foreach ($array_column as $key => $value) {
            $row = 2;

            $this->setItemString($row, $value['columnIndex'], $value['title']);
            $this->selectArea($row, $value['columnIndex']);
            $this->setFontBold(true);

            foreach ($recordset as $key2 => $record) {
                $row++;
                $vlr = '';
                $columnIndex = $value['columnIndex'];
                if (isset($record[$key])) {
                    $vlr = $record[$key];
                }


                if ($columnIndex == 1) {
                    $this->setRowHeight($row, $rowheight);
                }


                switch ($value['type']) {
                    case $f->retTypeImageSku():
                        $this->addPicture($row, $columnIndex, $this->getImageSKU($vlr), $rowheight);


                        break;

                    case $f->retTypeImageSpec():

                        $this->addPicture($row, $columnIndex, $this->getImageSpec($vlr), $rowheight);


                        break;

                    case $f->retTypeFirstPicture():
                        $this->addPicture($row, $columnIndex, $this->getFirstImageDocRep($this->docrepdefault, $vlr), $rowheight);
                        break;

                    case $f->retTypeInteger():
                    case $f->retTypeFloat():
                    case $f->retTypeNum():

                        $vdec = 0;
                        if (isset($value['decimal'])) {
                            $vdec = $value['decimal'];
                        }

                        $this->setItemFloat($row, $columnIndex, $vlr, $vdec);

                        break;

                    case $f->retTypePercentual():
                    case $f->retTypeProgressBar():

                        $this->setItemPercentual($row, $columnIndex, $vlr);
                        break;


                    case $f->retTypeDate():
                    case $f->retTypeDeactivated():
                        $this->setItemDate($row, $columnIndex, $vlr);

                        break;

                    default:
                        $this->setItemString($row, $columnIndex, $vlr);

                        break;
                }
            }

            if ($value['type'] != $f->retTypeImageSku() && $value['type'] != $f->retTypeImageSpec()) {
                $this->setColumnWidthAuto(isset($columnIndex) ? $columnIndex : 0);
            }
        }


        $this->selectArea(2, 1, 2, $columnindex);
        $this->setBorderThin();
        $this->setBackgroundColor('D3D3D3');
        /*
          $this->SelectedWorksheet->fromArray(
          [
          ['', 2010, 2011, 2012],
          ['Q1', 12, 15, 21],
          ['Q2', 56, 73, 86],
          ['Q3', 52, 61, 69],
          ['Q4', 30, 32, 0],
          ]
          ); */

//	Set the X-Axis Labels
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
//	Set the Data values for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
    }

    public function getImageSKU($cd_shoe_sku) {
        $sql = 'SELECT * FROM retShoeSkuPictures(' . $cd_shoe_sku . ') where cd_document_repository != -1 ORDER BY nr_order, ds_spec_picture_type LIMIT 1';

        $resultset = $this->CI->cdbhelper->basicSQLArray($sql);

        if (count($resultset) == 0) {
            $resourcePath = $this->CI->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
        } else {
            $filename = $resultset[0]['ds_document_file_thumbs_path'];
        }


        return $filename;
    }

    public function setItemLabel($row, $column, $value) {
        $this->selectArea($row, $column);

        $this->selectArea($row, $column);
        $this->setItemString($row, $column, $value);
        $this->setFontBold(true);
        $this->setBorderThin();
        $this->setBackgroundColor('D3D3D3');
    }

    public function getImageSpec($cd_generic_shoe_specification) {
        $sql = 'SELECT * FROM retGenSpecPictures(' . $cd_generic_shoe_specification . ') where cd_document_repository != -1 ORDER BY nr_order, ds_spec_picture_type LIMIT 1';

        $resultset = $this->CI->cdbhelper->basicSQLArray($sql);

        if (count($resultset) == 0) {
            $resourcePath = $this->CI->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
        } else {
            $filename = $resultset[0]['ds_document_file_thumbs_path'];
        }


        return $filename;
    }

    public function createExcelGrid($startrow, $startcol, $columns, $recordset, $rowheight = 100, $showTitle = true) {

        $array_column = array();
        $columnindex = 1;
        $columns = (array) $columns;
        //$rowheight = $rowheight;
        $f = $this->CI->cfields;


        if (1 == 2) {
            $f = new Cfields();
        }

        foreach ($columns as $key => $value) {
            $value = (array) $value;

            $datacol = array('type' => $value['internaltype'], 'title' => $value['caption'], 'field' => $value['field']);

            if (isset($value["render"])) {
                $tst = explode(':', $value["render"]);
                if (count($tst) == 2) {
                    $datacol['decimal'] = $tst[1];
                }
            }

            array_push($array_column, $datacol);
        }


        //cabecalhos
        foreach ($array_column as $key => $value) {
            $row = $startrow;
            $col = $key + $startcol;

            if ($showTitle) {
                $this->setItemLabel($row, $col, $value['title']);
                $this->selectArea($row, $col);
                $this->setFontBold(true);
            }

            foreach ($recordset as $key2 => $record) {
                $row++;
                $vlr = '';

                if (isset($record[$value['field']])) {
                    $vlr = $record[$value['field']];
                }

                switch ($value['type']) {
                    case $f->retTypeImageSku():
                        $this->addPicture($row, $col, $this->getImageSKU($vlr), $rowheight);

                        break;

                    case $f->retTypeImageSpec():

                        $this->addPicture($row, $col, $this->getImageSpec($vlr), $rowheight);


                        break;


                    case $f->retTypeInteger():
                    case $f->retTypeFloat():
                    case $f->retTypeNum():

                        $vdec = 0;
                        if (isset($value['decimal'])) {
                            $vdec = $value['decimal'];
                        }

                        $this->setItemFloat($row, $col, $vlr, $vdec);

                        break;

                    case $f->retTypePercentual():
                    case $f->retTypeProgressBar():

                        $this->setItemPercentual($row, $col, $vlr);
                        break;


                    case $f->retTypeDate():
                    case $f->retTypeDeactivated():
                        $this->setItemDate($row, $col, $vlr);

                        break;

                    default:
                        $this->setItemString($row, $col, $vlr);

                        break;
                }
            }

            if ($value['type'] != $f->retTypeImageSku() && $value['type'] != $f->retTypeImageSpec()) {
                $this->setColumnWidthAuto($col);
            }
        }
        return $row;
    }

    public function setPrintBreakRow($row) {
        $this->spreadsheet->getActiveSheet()->setBreak("A$row", \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
    }

    public function addHeader($text) {
        $this->spreadsheet->getHeaderFooter()
                ->setOddHeader($text);
    }

    public function addFooter($text) {
        $this->spreadsheet->getHeaderFooter()
                ->setOddFooter($text);
    }

    public function setFooter($footer, $position) {
        $position = '&' . $position;

        $sheet = $this->SelectedWorksheet;

        if (false) {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $this->SelectedWorksheet;
        }


        $sheet->getHeaderFooter()->setOddFooter($position . $footer);
        ;
    }

    public function addPageNumberFooter($position) {
        $this->setFooter('Page &P of &N', $position);
    }

    public function setRepeatingHeader($rowstart, $rowend) {
        $this->SelectedWorksheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($rowstart, $rowend);
    }

    public function resetPrintArea() {
        $this->printArea = array();
    }

    public function setPaperSize($size) {

        switch ($size) {
            case 'A4':
                $size = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4;
                break;

            case 'A3':
                $size = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3;
                break;

            case 'LETTER':
                $size = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LETTER;

                break;


            default:
                break;
        }

        $this->SelectedWorksheet->getPageSetup()->setPaperSize($size);
    }

    public function setPaperOrientation($orientation) {
        switch ($orientation) {
            case 'L':
                $ori = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE;
                break;
            case 'P':
                $ori = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT;
                break;


            default:
                break;
        }

        $this->SelectedWorksheet->getPageSetup()->setOrientation($ori);
    }

    public function setPrintArea() {
        array_push($this->printArea, $this->coordStart . ':' . $this->coordEnd);
    }

    public function setAlignVCenter() {
        $this->SelectedWorksheet->getStyle($this->getSelectedAreaString())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    }

    public function setAlignVTop() {
        $this->SelectedWorksheet->getStyle($this->getSelectedAreaString())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
    }

    public function setAlignVBottom() {
        $this->SelectedWorksheet->getStyle($this->getSelectedAreaString())->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM);
    }

    public function setDocRep($docrep) {
        $this->docrepdefault = $docrep;
    }

    public function getFirstImageDocRep($id, $pk) {
        $filename = $this->CI->docrepmodel->getFirstPictureThumb($id, $pk);

        if (!$filename) {
            $resourcePath = $this->CI->cdbhelper->getSystemParameters('FULL_RESOURCE_PATH');
            $filename = $resourcePath . 'missing-image-rect.png';
            //die ($filename);
        }



        return $filename;
    }

}
