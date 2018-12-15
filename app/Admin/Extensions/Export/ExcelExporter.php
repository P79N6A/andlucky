<?php
namespace App\Admin\Extensions\Export;

use Illuminate\Support\Arr;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Encore\Admin\Grid\Column;

class ExcelExporter extends AbstractExporter
{

    /**
     *
     * {@inheritdoc}
     */
    public function export()
    {
        $titles = [];
        
        $filename = $this->getTable() . '.xls';
        
        $data = $this->getData();
        include_once app_path('Support/PHPExcel.php');
        $excelObj = new \PHPExcel();
        $excelObj->getProperties()
            ->setTitle('后台数据导出')
            ->setSubject('数据导出')
            ->setCompany(config('app.name'));
        $sheet = $excelObj->setActiveSheetIndex(0);
        
        if (! empty($data)) {
            $columns = array_dot($this->sanitize($data[0]));
            $titles = array_keys($columns);
        }
        $Index = 65;
        foreach ($this->grid->columns() as $val) {
            $char = chr($Index ++);
            $sheet->setCellValue($char . '1', $val->getLabel())
                ->getStyle($char . '1')
                ->getAlignment()
                ->setHorizontal(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
                ->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $sheet->getStyle($char . '1')
                ->getFont()
                ->setBold(true)
                ->setSize(14);
        }
        Column::setOriginalGridData($data);
        $this->grid->columns()->map(function (Column $column) use (&$data) {
            $data = $column->fill($data);
        });
        
        $line = 2;
        foreach ($data as $row) {
            $Index = 65;
            foreach ($this->grid->columns() as $val) {
                $char = chr($Index ++);
                $v = data_get($row, $val->getName());
                if (preg_match("/^\d{10,}$/", $v)) {
                    $v = "'" . $v;
                }
                $v = strip_tags($v);
                $sheet->setCellValue($char . $line, $v);
            }
            $line ++;
        }
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($excelObj, 'Excel5');
        $objWriter->save('php://output');
        exit();
    }

    /**
     * Remove indexed array.
     *
     * @param array $row
     *
     * @return array
     */
    protected function sanitize(array $row)
    {
        return collect($row)->reject(function ($val, $_) {
            return is_array($val) && ! Arr::isAssoc($val);
        })->toArray();
    }
}
