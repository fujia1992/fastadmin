<?php

namespace app\admin\controller\kaoshi\examination;

use app\common\controller\Backend;
use think\Db;
use think\Config;
use fast\Random;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

/**
 *
 *
 * @icon fa fa-circle-o
 */
class Exams extends Backend
{

    /**
     * Exams模型对象
     * @var \app\admin\model\kaoshi\examination\KaoshiExams
     */
    protected $model = null;
    protected $dataLimit = 'auth';
    protected $dataLimitField = 'admin_id';

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\kaoshi\examination\KaoshiExams;
        $this->KaoshiQuestions = new \app\admin\model\kaoshi\examination\KaoshiQuestions;
        $this->typeList = ["1" => "单选题", "2" => "多选题", "3" => "判断题","4" => "填空题","5" => "简答题"];
        $this->view->assign("typeList", $this->model->getTypeList());
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
        $this->dataLimit = false;
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->with(['subject', 'admin'])
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->with(['subject', 'admin'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {


            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);

                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                $result = false;
                $namearr = $this->model->where(['deletetime' => NULL, 'subject_id' => $params['subject_id']])->column('exam_name');
                if (in_array($params['exam_name'], $namearr)) {
                    $this->error('卷名已存在！');

                }
                $question_obj = new \app\admin\model\kaoshi\examination\KaoshiQuestions;
                if($params['type'] == '1'){
                    $LevelList = $question_obj->getLevelList();
                    $typenum = [];
                    foreach ($LevelList as $key => $value) {
                        $typenum[$key] = Db::name('KaoshiQuestions')
                            ->where(['deletetime' => NULL, 'subject_id' => $params['subject_id'], 'level' => $key, 'status' => 1])
                            ->group('type')
                            ->column(['type', 'count(id)' => 'num']);
                    }
                    $params['score'] = 0;
                    $settingdata = json_decode($params['settingdata'], true);
                    //echo"<pre>";print_r($settingdata);exit;
                    if (count($settingdata) < 1) {
                        $this->error('请添加考卷设置');
                    }
                    foreach ($settingdata as $key => $value) {
                        $num = intval($value['number']);
                        $mark = intval($value['mark']);
                        if (intval($value['number']) <= 0) {
                            $this->error('考卷设置第' . ($key + 1) . '项数量需大于0');
                        }
                        if (intval($value['mark']) <= 0) {
                            $this->error('考卷设置第' . ($key + 1) . '项分值需大于0');
                        }
                        if (is_array($typenum[$value['level']])) {
                            if (count($typenum[$value['level']]) == 0) {
                                $this->error('考卷设置第' . ($key + 1) . '项，该科目无[' . $LevelList[$value['level']] . ']级别题目');
                            }
                            if (!isset($typenum[$value['level']][$value['type']])) {
                                $this->error('考卷设置第' . ($key + 1) . '项，[' . $LevelList[$value['level']] . ']级别题目没有该题型');
                            } elseif ($typenum[$value['level']][$value['type']] < $value['number']) {
                                $this->error('考卷设置第' . ($key + 1) . '项数量过多[题库仅' . $typenum[$value['level']][$value['type']] . '题]');
                            }
                        }
                        $params['score'] += $num * $mark;
                    }
                    //随机取出题目 插入数据
                    foreach ($settingdata as $key => $value) {
                        $map['type'] = $value['type'];
                        $map['subject_id'] = $params['subject_id'];
                        $map['level'] = $value['level'];
                        $map['status'] = 1;
                        $map['deletetime'] = null;
                        $arr = $question_obj->where($map)->select();
                        $arr = collection($arr)->toArray();
                        $rand_arr = array_rand($arr, intval($value['number']));
                        foreach ($arr as $k=>$v){
                            if (is_array($rand_arr)) {
                                if(in_array($k,$rand_arr)) {
                                    $questionsdata[] = array('questions_id' => $v['id'], 'score' => $value['mark']);
                                }
                            } else {
                                $questionsdata[] = array('questions_id' => $v['id'], 'score' => $value['mark']);
                                break;
                            }
                        }
                    }
                    $params['questionsdata'] = json_encode($questionsdata);
                }elseif ($params['type'] == '2'){
                    if(!isset($params['questions_id']) || count($params['questions_id']) <= 0){
                        $this->error('请添加题目');
                    }
                    foreach ($params['questions_id'] as $key=>$val){
                        if(empty($params['onescore'][$key])){
                            $this->error('请设置第' . ($key + 1) . '项分值需大于0');
                        }
                        $questionsdata[] = array('questions_id' => $val, 'score' => $params['onescore'][$key]);
                    }
                    $params['settingdata'] =  '';
                    $params['questionsdata'] = json_encode($questionsdata);
                }else{
                    $this->error('请求数据有误');
                }


                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were inserted'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        //自选出题
        $subject_id = '';
        $questions_data = array();
        if($row['type'] == '2'){
            $questions_data = \GuzzleHttp\json_decode($row['questionsdata'],true);
            if(count($questions_data) <= 0){
                $this->error('请求数据失败');
            }
            $question_obj = Db::name('KaoshiQuestions');
            foreach ($questions_data as $key=>$val){
                $arr = $question_obj->where('id',$val['questions_id'])->find();
                $questions_data[$key]['type_name'] = $this->typeList[$arr['type']];
                $questions_data[$key]['question'] = $arr['question'];
                $subject_id .= $val['questions_id'].',';
            }
        }
        $this->view->assign('subject_id',trim($subject_id,','));
        $this->view->assign('questions_data',$questions_data);
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                if($params['type'] == '1'){
                    $question_obj = new \app\admin\model\kaoshi\examination\KaoshiQuestions;
                    $LevelList = $question_obj->getLevelList();
                    $typenum = [];
                    foreach ($LevelList as $key => $value) {
                        $typenum[$key] = Db::name('KaoshiQuestions')
                            ->where(['deletetime' => NULL, 'subject_id' => $params['subject_id'], 'level' => $key, 'status' => 1])
                            ->group('type')
                            ->column(['type', 'count(id)' => 'num']);
                    }


                    $params['score'] = 0;
                    $settingdata = json_decode($params['settingdata'], true);
                    if (count($settingdata) < 1) {
                        $this->error('请添加考卷设置');

                    }
                    foreach ($settingdata as $key => $value) {
                        $num = intval($value['number']);
                        $mark = intval($value['mark']);
                        if (intval($value['number']) <= 0) {
                            $this->error('考卷设置第' . ($key + 1) . '项数量需大于0');
                        }
                        if (intval($value['mark']) <= 0) {
                            $this->error('考卷设置第' . ($key + 1) . '项分值需大于0');
                        }
                        if (is_array($typenum[$value['level']])) {
                            if (count($typenum[$value['level']]) == 0) {
                                $this->error('考卷设置第' . ($key + 1) . '项，该科目无[' . $LevelList[$value['level']] . ']级别题目');
                            }
                            if (!isset($typenum[$value['level']][$value['type']])) {
                                $this->error('考卷设置第' . ($key + 1) . '项，[' . $LevelList[$value['level']] . ']级别题目没有该题型');
                            } elseif ($typenum[$value['level']][$value['type']] < $value['number']) {
                                $this->error('考卷设置第' . ($key + 1) . '项数量过多[题库仅' . $typenum[$value['level']][$value['type']] . '题]');
                            }
                        }
                        $params['score'] += $num * $mark;
                    }
                    //随机取出题目 插入数据
                    foreach ($settingdata as $key => $value) {
                        $map['type'] = $value['type'];
                        $map['subject_id'] = $params['subject_id'];
                        $map['level'] = $value['level'];
                        $map['status'] = 1;
                        $map['deletetime'] = null;
                        $arr = $question_obj->where($map)->select();
                        $arr = collection($arr)->toArray();
                        $rand_arr = array_rand($arr, intval($value['number']));
                        foreach ($arr as $k=>$v){
                            if (is_array($rand_arr)) {
                                if(in_array($k,$rand_arr)) {
                                    $questionsdata[] = array('questions_id' => $v['id'], 'score' => $value['mark']);
                                }
                            } else {
                                $questionsdata[] = array('questions_id' => $v['id'], 'score' => $value['mark']);
                                break;
                            }
                        }
                    }
                    $params['questionsdata'] = json_encode($questionsdata);
                }elseif ($params['type'] == '2'){
                    if(count($params['questions_id']) <= 0){
                        $this->error('请添加题目');
                    }
                    foreach ($params['questions_id'] as $key=>$val){
                        if(empty($params['onescore'][$key])){
                            $this->error('请设置第' . ($key + 1) . '项分值需大于0');
                        }
                        $questionsdata[] = array('questions_id' => $val, 'score' => $params['onescore'][$key]);
                    }
                    $params['settingdata'] =  '';
                    $params['questionsdata'] = json_encode($questionsdata);
                }else{
                    $this->error('添加数据失败');
                }
                //echo"<pre>";print_r($params);exit;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    //作废
    public function getquestion_test($ids = null)
    {
        $row = $this->model->get($ids);
        $settingdata = json_decode($row['settingdata'], true);
        $this->view->assign("row", $row);
        $questions = [];
        $question_obj = Db::name('KaoshiQuestions');
        foreach ($settingdata as $key => $value) {
            $map['type'] = $value['type'];
            $map['subject_id'] = $row['subject_id'];
            $map['level'] = $value['level'];
            $map['status'] = 1;
            $map['deletetime'] = null;
            $arr = $question_obj->where($map)->select();

            $rand_arr = array_rand($arr, intval($value['number']));
            if (is_array($rand_arr)) {
                foreach ($rand_arr as $k => $v) {
                    $arr[$v]['selectdata'] = json_decode($arr[$v]['selectdata'], true);
                    if ($value['type'] == 2 || $value['type']=='4') {
                        $arr[$v]['answer'] = explode(',', $arr[$v]['answer']);
                    }
                    $questions[$key][$k] = $arr[$v];
                }

                shuffle($questions[$key]);
            } else {
                $arr[$rand_arr]['selectdata'] = json_decode($arr[$rand_arr]['selectdata'], true);
                if ($value['type'] == 2 || $value['type']=='4') {
                    $arr[$rand_arr]['answer'] = explode(',', $arr[$rand_arr]['answer']);

                }
                $questions[$key][] = $arr[$rand_arr];
            }

        }
        // halt($questions);
        $this->view->assign("typeList", $this->typeList);
        $this->view->assign("questions", $questions);
        return $this->view->fetch();
    }

    public function getquestion($ids = null)
    {
        $row = $this->model->get($ids);
        $questionsdata = json_decode($row['questionsdata'], true);
        $this->view->assign("row", $row);
        $question_obj = Db::name('KaoshiQuestions');
        if(!empty($questionsdata)){
            foreach ($questionsdata as $key => $value) {
                $arr = $question_obj->where('id',$value['questions_id'])->find();
                $questionsdata[$key]['type_name'] = $this->typeList[$arr['type']];
                $questionsdata[$key]['type'] = $arr['type'];
                $questionsdata[$key]['answer'] = $arr['answer'];
                $questionsdata[$key]['question'] = $arr['question'];
                $questionsdata[$key]['selectdata'] = json_decode($arr['selectdata'], true);
                if ($arr['type'] == 2 || $arr['type']=='4') {
                    $questionsdata[$key]['answer'] = explode(',', $arr['answer']);
                }
            }
        }
        $this->view->assign("typeList", $this->typeList);
        $this->view->assign("questions", $questionsdata);
        return $this->view->fetch();
    }
    
    /**
     * 上传文件
     */
    public function upload()
    {
        Config::set('default_return_type', 'json');
        $file = $this->request->file('file');
        if (empty($file)) {
            $this->error(__('No file upload or server upload limit exceeded'));
        }

        //判断是否已经存在附件
        $sha1 = $file->hash();
        $extparam = $this->request->post();

        $upload = Config::get('upload');

        preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
        $type = strtolower($matches[2]);
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
        $fileInfo = $file->getInfo();
        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix = $suffix && preg_match("/^[a-zA-Z0-9]+$/", $suffix) ? $suffix : 'file';

        $mimetypeArr = explode(',', strtolower($upload['mimetype']));
        $typeArr = explode('/', $fileInfo['type']);

        //禁止上传PHP和HTML文件
        if (in_array($fileInfo['type'], ['text/x-php', 'text/html']) || in_array($suffix, ['php', 'html', 'htm'])) {
            $this->error(__('Uploaded file format is limited'));
        }
        //验证文件后缀
        if ($upload['mimetype'] !== '*' &&
            (
                !in_array($suffix, $mimetypeArr)
                || (stripos($typeArr[0] . '/', $upload['mimetype']) !== false && (!in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)))
            )
        ) {
            $this->error(__('Uploaded file format is limited'));
        }
        //验证是否为图片文件
        $imagewidth = $imageheight = 0;
        if (in_array($fileInfo['type'], ['image/gif', 'image/jpg', 'image/jpeg', 'image/bmp', 'image/png', 'image/webp']) || in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'webp'])) {
            $imgInfo = getimagesize($fileInfo['tmp_name']);
            if (!$imgInfo || !isset($imgInfo[0]) || !isset($imgInfo[1])) {
                $this->error(__('Uploaded file is not a valid image'));
            }
            $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
            $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
        }
        $replaceArr = [
            '{year}'     => date("Y"),
            '{mon}'      => date("m"),
            '{day}'      => date("d"),
            '{hour}'     => date("H"),
            '{min}'      => date("i"),
            '{sec}'      => date("s"),
            '{random}'   => Random::alnum(16),
            '{random32}' => Random::alnum(32),
            '{filename}' => $suffix ? substr($fileInfo['name'], 0, strripos($fileInfo['name'], '.')) : $fileInfo['name'],
            '{suffix}'   => $suffix,
            '{.suffix}'  => $suffix ? '.' . $suffix : '',
            '{filemd5}'  => md5_file($fileInfo['tmp_name']),
        ];
        $savekey = $upload['savekey'];
        $savekey = str_replace(array_keys($replaceArr), array_values($replaceArr), $savekey);

        $uploadDir = substr($savekey, 0, strripos($savekey, '/') + 1);
        $fileName = substr($savekey, strripos($savekey, '/') + 1);
        //
        $splInfo = $file->validate(['size' => $size])->move(ROOT_PATH . '/public' . $uploadDir, $fileName);
        if ($splInfo) {
            $params = array(
                'admin_id'    => (int)$this->auth->id,
                'user_id'     => 0,
                'filesize'    => $fileInfo['size'],
                'imagewidth'  => $imagewidth,
                'imageheight' => $imageheight,
                'imagetype'   => $suffix,
                'imageframes' => 0,
                'mimetype'    => $fileInfo['type'],
                'url'         => $uploadDir . $splInfo->getSaveName(),
                'uploadtime'  => time(),
                'storage'     => 'local',
                'sha1'        => $sha1,
                'extparam'    => json_encode($extparam),
            );
            $attachment = model("attachment");
            $attachment->data(array_filter($params));
            $attachment->save();
            //\think\Hook::listen("upload_after", $attachment);
            $this->success(__('Upload successful'), null, [
                'url' => $uploadDir . $splInfo->getSaveName()
            ]);
        } else {
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }

    //word上传
    public function addword()
    {
        return $this->view->fetch();
    }

    //word读取
    public function read()
    {
        $path = $this->request->post('path');
        $path = ROOT_PATH.'/public'.$path;

        $html = '';
        $sections = IOFactory::load($path)->getSections();
        //循环所有元素
        foreach($sections as $section) {
            //获取当前元素的所有子元素
            $elements = $section->getElements();
            //循环当前子元素
            foreach ($elements as $eky => $evl) {
                $html .= '<p>';
                if ($evl instanceof \PhpOffice\PhpWord\Element\TextRun) { //判断是否普通文本
                    $content_elements = $evl->getElements();
                    foreach ($content_elements as $eky2 => $evl2) {
                        $html .= $evl2->getText();
                    }
                }
                $html .= '</p>';
            }
        }
        $html = str_replace('</p>','',$html);
        $data = explode('<p>',$html);
        //echo"<pre>";print_r($data);exit;
        if($data){
            $result = array();
            $key = '';
            foreach ($data as $k=>$val){
                if($val){
                    if(strpos($val,'】')) {
                        preg_match("/(?:\】)(.*)(?:\.)/i", strip_tags($val), $keyarr);
                        preg_match("/(?:\【)(.*)(?:\】)/i", $val, $title);
                        if($keyarr[1]){
                            $key = $keyarr[1];
                            preg_match("/(?:\】)(.*)/i", strip_tags($val), $questionarr);
                            $question = $questionarr[1];
                            $type = $title[1];
                        }
                        if($title[1]=='解析'){
                            $describe = mb_substr($val,4);
                            $result[$key]['describe'] = $describe;
                        }
                        if($title[1]=='答案'){
                            $answer = mb_substr($val,4);
                            $result[$key]['answer'] = trim($answer);
                        }
                    }else{
                        $selectarr = explode('.',$val);
                        $result[$key]['selectdata'][] = array('key'=>$selectarr[0],'value'=>$selectarr[1]);
                    }
                    $result[$key]['type'] = $type;
                    $result[$key]['question'] = $question;
                }
            }
            //echo"<pre>";print_r($result);
            $keyhtml = '';
            $type = ["单选题" => "1", "多选题" => "2", "填空题" => "4","简答题" => "5"];
            $msghtml = '';
            foreach ($result as $k=>$v) {
                $v['answer'] = explode(',', $v['answer']);
                if(is_array($v['selectdata']) && $v['answer']){
                    foreach ($v['selectdata'] as $i=>$ch){
                        $v['selectdata'][$i]['checked'] = in_array($ch['key'], $v['answer']) ? "checked" : "";
                    }
                }
                $keyhtml .= ','.$k;
                $typehtml = $hidden = '';
                if($v['type'] == '单选题' || $v['type'] =='多选题'){
                    $typehtml = '<dl data-template="answer'.$k.$type[$v['type']].'" class="fieldlist fieldlist'.$k.'" data-name="row[selectdata'.$k.']" data-listidx="0">
                            <dd><ins style="width: 50px;">选项</ins><ins style="width: 385px;">答案</ins><ins>正确答案</ins></dd>
                            <dd><a href="javascript:;" class="btn btn-sm btn-success btn-append"><i class="fa fa-plus"></i> 追加</a></dd>
                                <textarea name="row[selectdata'.$k.']" class="form-control hidden" cols="30" rows="5">'.json_encode($v['selectdata']).'</textarea>
                            </dl>';
                }elseif ($v['type']=='填空题'){
                    foreach ($v['answer'] as $an){
                        $answerarr[] = array('value'=>$an);
                    }
                    $typehtml = '<dl data-template="answer'.$k.$type[$v['type']].'" class="fieldlist fieldlist'.$k.'" data-name="row[selectdata'.$k.']" data-listidx="0">
                            <dd><ins style="width: 550px;">正确答案</ins></dd>
                            <dd><a href="javascript:;" class="btn btn-sm btn-success btn-append"><i class="fa fa-plus"></i> 追加</a></dd>
                                <textarea name="row[selectdata'.$k.']" class="form-control hidden" cols="30" rows="5">'.json_encode($answerarr).'</textarea>
                            </dl>';
                }elseif($v['type']=='简答题'){
                    $hidden = 'hidden';
                }
                $msghtml .= '<fieldset style="height: 100%;margin-bottom: 10px;">
                    <legend>
                    <h4 style="margin-bottom:-20px;">题目信息</h4>
                    <a href="javascript:;" class="btn btn-xs btn-danger btn-delone closeexam" data-key="'.$k.'" style="float:right;" data-toggle="tooltip" title="" data-table-id="table" data-field-index="14" data-row-index="0" data-button-index="2" data-original-title="删除"><i class="fa fa-trash"></i></a>
                    </legend>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">题目:</label>
                        <div class="col-xs-12 col-sm-8">
                            <textarea id="c-question" data-rule="required" class="form-control " rows="2" name="row[question'.$k.']" cols="50">' . $v['question'] . '</textarea>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">类型:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select  id="c-type" data-rule="required" class="form-control" name="row[type'.$k.']">
                                <option value='.$type[$v['type']].' selected="">'.$v['type'].'</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group Answer '.$hidden.'">
                        <label class="control-label col-xs-12 col-sm-2">选项:</label>
                         <div class="col-xs-12 col-sm-8">
                            '.$typehtml.'
                            <!--填空题 -->
                            <script id="answer'.$k.'4" type="text/html">
                                <dd class="form-inline">
                                    <ins style="width: 550px;"><input type="text" name="row[answer'.$k.'][]" style="width: 550px;" class="form-control" value="<%=row.value%>" /></ins>
                                    <!--下面的两个按钮务必保留-->
                                    <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-times"></i></span>
                                    <span class="btn btn-sm btn-primary btn-dragsort"><i class="fa fa-arrows"></i></span>
                                </dd>
                            </script>
                            <!--简答题 -->
                            <script id="answer'.$k.'5" type="text/html">
                                <dd class="form-inline">
                                    <ins><input type="text" name="row[answer'.$k.'][]" class="form-control" placeholder="<%=row.value%>" /></ins>
                                </dd>
                            </script>
                            <!--单选题 -->
                            <script id="answer'.$k.'1" type="text/html">
                                <dd class="form-inline">
                                    <ins style="width: 50px;"><input type="text" name="<%=name%>[<%=index%>][key]" style="width: 50px;" class="form-control" placeholder="选项" size="10" value="<%=row.key%>"/></ins>
                                    <ins style="width: 385px;"><input type="text" name="<%=name%>[<%=index%>][value]" style="width: 385px;" class="form-control" placeholder="" value="<%=row.value%>"/></ins>
                                    <ins><input type="radio" class="AnswerXz" name="row[answer'.$k.']" value="<%=row.key%>" <%=row.checked%>/></ins>
                                    <!--下面的两个按钮务必保留-->
                                    <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-times"></i></span>
                                    <span class="btn btn-sm btn-primary btn-dragsort"><i class="fa fa-arrows"></i></span>
                                </dd>
                            </script>
                            <!--多选题 -->
                            <script id="answer'.$k.'2" type="text/html">
                                <dd class="form-inline">
                                    <ins style="width: 50px;"><input type="text" name="<%=name%>[<%=index%>][key]" style="width: 50px;" class="form-control" placeholder="选项" size="10"  value="<%=row.key%>"/></ins>
                                    <ins style="width: 385px;"><input type="text" name="<%=name%>[<%=index%>][value]" style="width: 385px;" class="form-control" placeholder=""  value="<%=row.value%>"/></ins>
                                    <ins><input type="checkbox" class="AnswerDx" name="row[answer'.$k.'][]" value="<%=row.key%>" <%=row.checked%>/></ins>
                                    <!--下面的两个按钮务必保留-->
                                    <span class="btn btn-sm btn-danger btn-remove"><i class="fa fa-times"></i></span>
                                    <span class="btn btn-sm btn-primary btn-dragsort"><i class="fa fa-arrows"></i></span>
                                </dd>
                            </script>
                            <div style="color:red;">*请选中或填写正确答案</div>
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">解析:</label>
                        <div class="col-xs-12 col-sm-8">
                            <textarea id="c-describe"  class="form-control " rows="5" name="row[describe'.$k.']" cols="50">' . $v['describe'] . '</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">分数:</label>
                        <div class="col-xs-12 col-sm-8">
                            <input id="c-score" data-rule="required" class="form-control keyscore" name="row[score'.$k.']" type="number">
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">等级::</label>
                        <div class="col-xs-12 col-sm-8">
                            <select  id="c-level" data-rule="required" class="form-control" name="row[level'.$k.']">
                                <option value="1" selected="">易</option>
                                <option value="2">中</option>
                                <option value="3">难</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-xs-12 col-sm-2">状态:</label>
                        <div class="col-xs-12 col-sm-8">
                            <select  id="c-status" data-rule="required" class="form-control" name="row[status'.$k.']]">
                                <option value="1" >显示</option>
                                <option value="2" >隐藏</option>
                            </select>
                        </div>
                    </div>
                </fieldset>';
            }
            $msghtml .= '<input type="hidden" id="keyhtml" name="row[keynums]" value="'.trim($keyhtml,',').'">';
        }
        $this->success('', null, $msghtml);
    }

    public function save()
    {
        $params = $this->request->post("row/a");
        if ($params) {
            //echo"<pre>";print_r($params);exit;
            $params = $this->preExcludeFields($params);
            if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                $params[$this->dataLimitField] = $this->auth->id;
            }
            $result = false;
            $namearr = $this->model->where(['deletetime' => NULL, 'subject_id' => $params['subject_id']])->column('exam_name');
            if (in_array($params['exam_name'], $namearr)) {
                $this->error('卷名已存在！');
            }
            //先处理小题
            if($params['keynums']<=0){
                $this->error('请先上传题');
            }
            $keyarr = explode(',',$params['keynums']);
            $score = array();
            foreach($keyarr as $i){
                $score[$i] = $params['score'. $i];
                $questions[$i]['admin_id'] = $params['admin_id'];
                $questions[$i]['subject_id'] = $params['subject_id'];
                $questions[$i]['describe'] = $params['describe'. $i];
                $questions[$i]['question'] = $params['question'. $i];
                if(isset($params['selectdata' .$i])){
                    $selectarr = json_decode($params['selectdata' .$i], true);
                    $questions[$i]['selectnumber'] = count($selectarr);
                }
                if(isset($params['answer' .$i])) {
                    $questions[$i]['answer'] = $params['answer' . $i];
                    if (!array_key_exists('answer' . $i, $params)) {
                        $this->error("请选择正确答案!");
                    }
                }
                $questions[$i]['type'] = intval($params['type'.$i]);
                if($questions[$i]['type'] <3){
                    if($selectarr) {
                        foreach ($selectarr as $key => $value) {
                            if (empty($value['key']) && $value['key'] != '0') {
                                $this->error("请填写第" . ($i) . "题选项" . ($key + 1));
                            }
                            if (empty($value['value']) && $value['value'] != '0') {
                                $this->error("请填写第" . ($i) . "题选项" . ($key + 1) . "答案内容");
                            }
                            unset($selectarr[$key]['checked']);
                        }
                        if (count(array_unique(array_map('strtolower', array_column($selectarr, 'key')))) != count($selectarr)) {
                            $this->error("第" . ($i) . "题请不要输入重复选项!【选项不区分大小写】");
                        }
                        if (count(array_unique(array_column($selectarr, 'value'))) != count($selectarr)) {
                            $this->error("第" . ($i) . "题请不要输入重复选项答案!");
                        }
                        $questions[$i]['selectdata'] = json_encode($selectarr);
                    }
                }
                if($questions[$i]['type']  == '4'){
                    foreach ($questions[$i]['answer'] as $k => $v) {
                        if (empty($v)) {
                            $this->error("请填写第" . ($i) . "题选项" . ($k + 1) . "答案内容");
                        }
                    }
                    $questions[$i]['selectnumber'] = count($questions[$i]['answer']);
                    $questions[$i]['selectdata'] = '[]';
                }
                if ($questions[$i]['type'] == '2' || $questions[$i]['type'] == '4') {
                    $questions[$i]['answer'] = implode(',', $questions[$i]['answer']);
                }
                if($questions[$i]['type']  != '5'){
                    if (empty($questions[$i]['answer']) && $questions[$i]['answer'] != '0') {
                        $this->error("请选择正确答案!");
                    }
                }
                if($questions[$i]['type'] == '5'){
                    $questions[$i]['answer'] = '';
                    $questions[$i]['selectdata'] = '[]';
                    $questions[$i]['selectnumber'] = '0';
                }
            }
            //echo"<pre>";print_r($questions);exit;
            Db::startTrans();
            try {
                $result = $this->KaoshiQuestions->allowField(true)->saveAll($questions);
                if($result) {
                    $result = collection($result)->toArray();
                }
                Db::commit();
            } catch (ValidateException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($result !== false) {
                foreach ($result as $kk=>$re){
                    $questionsdata[] = array('questions_id'=>$re['id'],'score'=>$score[$kk]);
                }
                $exams['admin_id'] = $params['admin_id'];
                $exams['subject_id'] = $params['subject_id'];
                $exams['exam_name'] = $params['exam_name'];
                $exams['questionsdata'] = json_encode($questionsdata);
                $exams['img'] = $params['img'];
                $exams['pass'] = $params['pass'];
                $exams['score'] = $params['score'];
                $exams['type'] = '2';
                $exams['hours'] = $params['hours'];
                $exams['keyword'] = $params['keyword'];
                $exams['starttime'] = $params['starttime'];
                $exams['endtime'] = $params['endtime'];
                $exams['status'] = '1';
                $exams['chaos_status'] = isset($params['chaos_status'])?$params['chaos_status']:'0';
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $this->model->validateFailException(true)->validate($validate);
                    }
                    $result = $this->model->allowField(true)->save($exams);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            } else {
                $this->error(__('No rows were inserted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', ''));
    }

}
