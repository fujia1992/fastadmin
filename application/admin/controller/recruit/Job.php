<?php

namespace app\admin\controller\recruit;

use app\common\controller\Backend;
use think\Db;
use think\Config;
/**
 * 职位信息
 *
 * @icon fa fa-circle-o
 */
class Job extends Backend
{
    
    /**
     * Job模型对象
     * @var \app\admin\model\Job
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('Job');
        $this->view->assign("ageList", $this->model->getAgeList());
        $this->view->assign("stayList", $this->model->getStayList());
        $this->view->assign("foodList", $this->model->getFoodList());
        $this->view->assign("safeList", $this->model->getSafeList());
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
                    ->with(['recruitcompany','recruitopencity','user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->with(['recruitcompany','recruitopencity','user'])
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['Id','name','age','stay','food','safe','neednum','education','updatetime','gold1','gold2']);
                $row->visible(['recruitcompany']);
				$row->getRelation('recruitcompany')->visible(['name']);
				$row->visible(['recruitopencity']);
				$row->getRelation('recruitopencity')->visible(['city']);
				$row->visible(['user']);
				$row->getRelation('user')->visible(['username','nickname']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $gold =  explode(",",$params['gold']);
                    $params['gold1'] = $gold[0];
                    $params['gold2'] = $gold[1];
                    $result = $this->model->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }
    public function edit($ids = NULL)
    {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $gold =  explode(",",$params['gold']);
                    $params['gold1'] = $gold[0];
                    $params['gold2'] = $gold[1];
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    public function selectpage_job_com()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {

           //设置过滤方法
        $this->request->filter(['strip_tags', 'htmlspecialchars']);

        //搜索关键词,客户端输入以空格分开,这里接收为数组
        $word = (array)$this->request->request("q_word/a");
        //当前页
        $page = $this->request->request("pageNumber");
        //分页大小
        $pagesize = $this->request->request("pageSize");
        //搜索条件
        $andor = $this->request->request("andOr", "and", "strtoupper");
        //排序方式
        $orderby = (array)$this->request->request("orderBy/a");
        //显示的字段
        $field = $this->request->request("showField");
        //主键
        $primarykey = $this->request->request("keyField");
        $tmpType = $_REQUEST['type'];
        if($tmpType==3){
            $primarykey="j.Id";
        }
        //主键值
        $primaryvalue = $this->request->request("keyValue");
        //搜索字段
        $searchfield = (array)$this->request->request("searchField/a");
        //自定义搜索条件
        $custom = (array)$this->request->request("custom/a");
        $order = [];
        foreach ($orderby as $k => $v) {
            $order[$v[0]] = $v[1];
        }
        $field = $field ? $field : 'name';

        //如果有primaryvalue,说明当前是初始化传值
        if ($primaryvalue !== null) {
            $where = [$primarykey => ['in', $primaryvalue]];
        } else {
            $where = function ($query) use ($word, $andor, $field, $searchfield, $custom) {
                $logic = $andor == 'AND' ? '&' : '|';
                $searchfield = is_array($searchfield) ? implode($logic, $searchfield) : $searchfield;
                foreach ($word as $k => $v) {
                    $query->where('j.'.str_replace(',', $logic, $searchfield), "like", "%{$v}%");
                }
                if ($custom && is_array($custom)) {
                    foreach ($custom as $k => $v) {
                        $query->where('j.'.$k, '=', $v);
                    }
                }
            };
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
           $this->model->alias('j')->where($this->dataLimitField, 'in', $adminIds);
        }
        $list = [];
        $total = $this->model->alias('j')->where($where)->count();
        if ($total > 0) {
            if (is_array($adminIds)) {
                $this->model->alias('j')->where($this->dataLimitField, 'in', $adminIds);
            }
            $order = 'j.Id';

            //实在没办法 就用最原始的方法 db直接操作
            $prefix = Config::get('database.prefix');
            $datalist = Db::table($prefix.'recruit_job')
                ->alias('j')
                ->join($prefix.'recruit_company c','j.c_id = c.Id')
                ->where($where)
                ->order($order)
                ->page($page, $pagesize)
                ->field($this->selectpageFields)
                ->field('c.name as comname')
                ->field('j.name as name')
                ->field('j.Id as id')
                ->select();

            foreach ($datalist as $index => $item) {
                unset($item['password'], $item['salt']); 
                if($tmpType==3){
                    $primarykey="id";
                }
                $jname = isset($item[$field]) ? $item[$field] : '';
                if($tmpType==2){
                    $jname .= isset($item['comname']) ? '('.$item['comname'].")" : '';
                }
                $list[] = [
                    $primarykey => isset($item[$primarykey]) ? $item[$primarykey] : '',
                    $field      => $jname ,
                    'comname'      => isset($item['comname']) ? $item['comname'] : ''
                ];
            }
        }
        //这里一定要返回有list这个字段,total是可选的,如果total<=list的数量,则会隐藏分页按钮
        return json(['list' => $list, 'total' => $total]);
    }
    }
}
