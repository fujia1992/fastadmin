<?php

namespace app\admin\controller\recruit;

use app\common\controller\Backend;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;
use PHPExcel_Style;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Style_NumberFormat;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Workforce extends Backend
{
    
    /**
     * Workforce模型对象
     * @var \app\admin\model\Workforce
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Workforce');
        $this->view->assign("sexList", $this->model->getSexList());
        $this->view->assign("educationList", $this->model->getEducationList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->with(['user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','village','name','sex','sfzno','education','place','salary','collect','updatetime','tel','skill','intent','content']);
                $row->visible(['user']);
				$row->getRelation('user')->visible(['nickname','mobile','avatar']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function export()
    {
        //当前是否为关联查询
        //$this->relationSearch = true;
        if ($this->request->isPost()) {
            set_time_limit(0);
            $search = $this->request->post('search');
            $ids = $this->request->post('ids');
            $filter = $this->request->post('filter');
            $op = $this->request->post('op');
            $columns = $this->request->post('columns');

            $excel = new PHPExcel();

            $excel->getProperties()
                ->setCreator("FastAdmin")
                ->setLastModifiedBy("FastAdmin")
                ->setTitle("标题")
                ->setSubject("Subject");
            $excel->getDefaultStyle()->getFont()->setName('Microsoft Yahei');
            $excel->getDefaultStyle()->getFont()->setSize(12);

            $this->sharedStyle = new PHPExcel_Style();
            $this->sharedStyle->applyFromArray(
                array(
                    'fill'      => array(
                        'type'  => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '000000')
                    ),
                    'font'      => array(
                        'color' => array('rgb' => "000000"),
                    ),
                    'alignment' => array(
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'indent'     => 1
                    ),
                    'borders'   => array(
                        'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
                    )
                ));

            $worksheet = $excel->setActiveSheetIndex(0);
            $worksheet->setTitle('标题');

            $whereIds = $ids == 'all' ? '1=1' : ['id' => ['in', explode(',', $ids)]];
            $this->request->get(['search' => $search, 'ids' => $ids, 'filter' => $filter, 'op' => $op]);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $line = 1;
            $list = [];
            $columns = str_replace("sex_text","sex",$columns);
            $columns = str_replace("education_text","education",$columns);

            $columns = str_replace(",user.nickname","",$columns);
            $columns = str_replace(",user.avatar","",$columns);
            
            $this->model
                ->field($columns)
                ->where($where)
                ->where($whereIds)
                ->chunk(100, function ($items) use (&$list, &$line, &$worksheet) {
                    $styleArray = array(
                        'font' => array(
                            'bold'  => false,//加粗
                            'color' => array('rgb' => '000000'),//字体颜色
                            'size'  => 10,//字体大小
                            'name'  => 'Verdana'
                        ));
                    //\think\Log::write('hawk8：'.json_encode($items), \think\Log::NOTICE);
                    $list = $items = collection($items)->toArray();
                    foreach ($items as $index => $item) {
                        $line++;
                        $col = 0;
                        foreach ($item as $field => $value) {
                          //时间戳转换，createtime时间的字段
                           if(strpos($field,'updatetime')!==false){
                                $value=date('Y-m-d H:i:s',$value);
                            };
                          //处理身份证，certcode身份证字段
                            if(strpos($field,'sfzno')!==false){
                                $value=' '.$value;
                             };
                            $worksheet->setCellValueByColumnAndRow($col, $line, $value);
                            $worksheet->getStyleByColumnAndRow($col, $line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
                            $worksheet->getCellByColumnAndRow($col, $line)->getStyle()->applyFromArray($styleArray);
                            $col++;
                        }
                    }
                });
            $first = array_keys($list[0]);
            foreach ($first as $index => $item) {
                $indatatstr = __($item);
                if($indatatstr=="性别"){
                   $indatatstr = "性别原始数据";
                }
                if($indatatstr=="学历"){
                   $indatatstr = "学历原始数据";
                }
                if($indatatstr=="sex_text"){
                   $indatatstr = "性别";
                }
                if($indatatstr=="education_text"){
                   $indatatstr = "学历";
                }
                $worksheet->setCellValueByColumnAndRow($index, 1,$indatatstr);
            }

            $excel->createSheet();
            // Redirect output to a client’s web browser (Excel2007)
            $title = date("YmdHis");
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $title . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
            $objWriter->save('php://output');
            return;
        }
    }
}
