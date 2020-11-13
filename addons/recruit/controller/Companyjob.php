<?php
namespace addons\recruit\controller;

use app\admin\model\Company;
use app\admin\model\Job;
//use think\Controller;
use app\common\model\Area;
use app\common\model\Version;
use fast\Random;
use think\Config;

use app\common\model\Attachment;


class Companyjob extends Base
{

    protected $noNeedLogin = ['get_c_job','get_companyById','get_JobList_resrch']; 
    protected $noNeedRight = '*';

    private $db_pf = '';

    public function _initialize()
    {
        parent::_initialize();
        $this->db_pf = Config::get('database.prefix');
    }

    public function get_companyById(){
      $id = $this->request->post('id');
      $CompanyD = Company::get($id);
      if(count($CompanyD)==0){
          $this->error('不存在企业');
          return;
      }

      $comxinzn = ['国有企业','私营企业','中外合作企业','中外合资企业','外商独资企业'];
      $CompanyD['XinZhiname'] = $comxinzn[$CompanyD['xinzhi']];

      //这里把企业对应的 职位全部列表展示出来
      $JobsList = Job::where('c_id', $id)->select();
      $ZhusD = ['不提供住宿','提供住宿','提供夫妻房'];
      $FoodD = ['不提供伙食','提供午饭','提供三餐','有餐补'];
      $SafeD = ['不提供社保','缴纳三险','缴纳五险','缴纳五险一金'];
      $EducationD = ['无学历要求','小学','初中','高中','大专','本科','研究生及以上'];
      foreach ($JobsList as $key=>&$item) {

           //这里格式化一下 住宿、社保、学历
           $item['zhusuname'] = $ZhusD[$item['stay']];
           $item['Safename'] = $SafeD[$item['safe']];
           $item['Educationname'] = $EducationD[$item['education']];

           //这里需要格式化一下 工资薪水
           if( $item['gold1'] == $item['gold2']){
                $item['goldtext'] = ($item['gold1']/1000)."K";
                if($item['gold1']==3000){
                    $item['goldtext'].="以下";
                }
                if($item['gold1']==10000){
                    $item['goldtext'].="以上";
                }
           }else{
                $item['goldtext'] = ($item['gold1']/1000)."K-".($item['gold2']/1000)."K";
           }
      }
      if(count($JobsList)==0){
        $JobsList = null;
      }
      $CompanyD['JobsList'] = $JobsList;


      $this->success('', $CompanyD);
    }

    public function get_my_company(){
      $my_id = $this->auth->id;
      $CompanyD = Company::get(['user_id' => $my_id]);
      if(count($CompanyD)==0){
          $this->error('不存在企业');
          return;
      }

      $comxinzn = ['国有企业','私营企业','中外合作企业','中外合资企业','外商独资企业'];
      $CompanyD['XinZhiname'] = $comxinzn[$CompanyD['xinzhi']];
      $this->success('', $CompanyD);
    }

    public function get_my_companyName_jobsnum(){
      $my_id = $this->auth->id;
      $CompanyD = Company::get(['user_id' => $my_id]);
      if(count($CompanyD)==0){
        $CompanyD['name'] = '';
        $CompanyD['jobCount'] = 0;
      }else{
        $CompanyD['jobCount'] = Job::where('c_id', $CompanyD['Id'])->count();
      }
      //这里拖取 resume的数据
      $Resume = \app\admin\model\Resume::get(['user_id' => $my_id]);
      $CompanyD['resum_num'] = count($Resume);

      //这里拖取招聘会报名的次数
      $JobfairCount = \app\admin\model\Jobfair::where('user_id', $my_id)->count();
      $CompanyD['JobfairCount'] = $JobfairCount;

      $this->success('', $CompanyD);
    }

    public function get_JobList_resrch(){
      $page = $this->request->post('page');
      $page_block = $this->request->post('page_block');

      $YueXin = $this->request->post('YueXin');
      $XueLi_num = $this->request->post('XueLi_num');
      $ZhuSu_num = $this->request->post('ZhuSu_num');

      $Re_input = $this->request->post('Re_input');

      $ShowCity_id = $this->request->post('ShowCity_id');
      //格式化 搜索职位和公司名称
      if($Re_input !=''){
         $Re_input = " (Job.name like '%$Re_input%'or recruitcompany.name like '%$Re_input%') ";
      }

      $tmp_m = new Job();

       $page = max(1, $page);
       $limit = ($page - 1) * $page_block . ','.$page_block;

       $cache = false;
       if($YueXin==0&&$XueLi_num==0&&$ZhuSu_num==0){
        $JobsList = $tmp_m->with(['recruitcompany','recruitopencity'])->where($Re_input)->where('city_id', $ShowCity_id)->order($this->db_pf.'recruit_job.updatetime', 'desc')->limit($limit)->cache($cache)->select();
       }else{
        //format 住宿
        $ZhuSu_num = $ZhuSu_num == 0 ? '' : 'stay = '.$ZhuSu_num ;

        $XueLi_num--;

        $JobsList = $tmp_m->with(['recruitcompany','recruitopencity'])
        ->where('gold2','>=',$YueXin)
        ->where('city_id', $ShowCity_id)
        //->where('education','>=',$XueLi_num)
        ->where('education',['>=',$XueLi_num],['=',0],'or')
        ->where($ZhuSu_num)
        ->where($Re_input)
        ->order($this->db_pf.'recruit_job.updatetime', 'desc')->limit($limit)->cache($cache)->select();
       }

        $ZhusD = ['不提供住宿','提供住宿','提供夫妻房'];
        $FoodD = ['不提供伙食','提供午饭','提供三餐','有餐补'];
        $SafeD = ['不提供社保','缴纳三险','缴纳五险','缴纳五险一金'];
        $EducationD = ['无学历要求','小学','初中','高中','大专','本科','研究生及以上'];
        $AgeD = ['无要求','18-30岁','30-45岁','45-50岁','其他'];
        $comxinzn = ['国有企业','私营企业','中外合作企业','中外合资企业','外商独资企业'];

        foreach ($JobsList as $key=>&$row) {
            if($row['recruitcompany']['xinzhi']==null){
                unset($JobsList[$key]);
                continue ;
            }
            $row['zhusuname'] = $ZhusD[$row['stay']];
            $row['Safename'] = $SafeD[$row['safe']];
            $row['FoodDname'] = $FoodD[$row['food']];
            $row['Agename'] = $AgeD[$row['age']];
            $row['Educationname'] = $EducationD[$row['education']];

             if( $row['gold1'] == $row['gold2']){
                $row['goldtext'] = ($row['gold1']/1000)."K";
                if($row['gold1']==3000){
                    $row['goldtext'].="以下";
                }
                if($row['gold1']==10000){
                    $row['goldtext'].="以上";
                }
           }else{
                $row['goldtext'] = ($row['gold1']/1000)."K-".($row['gold2']/1000)."K";
           }

            $row['XinZhiname'] = $comxinzn[$row['recruitcompany']['xinzhi']];
        }

      $this->success('', $JobsList);
    }

    public function get_all_myjob(){
        $my_id = $this->auth->id;
        //首先查看是否有 企业，若没有则返回没有企业
        $CompanyD = Company::get(['user_id' => $my_id]);
        if(count($CompanyD)==0){
            $this->error('不存在企业');
            return;
        }

        $tmp_m = new Job();
        $JobsList = $tmp_m->with(['recruitcompany','recruitopencity'])->where('recruitcompany.user_id',$my_id)->order($this->db_pf.'recruit_job.updatetime', 'desc')->select();

        $ZhusD = ['不提供住宿','提供住宿','提供夫妻房'];
        $FoodD = ['不提供伙食','提供午饭','提供三餐','有餐补'];
        $SafeD = ['不提供社保','缴纳三险','缴纳五险','缴纳五险一金'];
        $EducationD = ['无学历要求','小学','初中','高中','大专','本科','研究生及以上'];
        $AgeD = ['无要求','18-30岁','30-45岁','45-50岁','其他'];
        $comxinzn = ['国有企业','私营企业','中外合作企业','中外合资企业','外商独资企业'];
        foreach ($JobsList as $key=>&$row) {
            if($row['recruitcompany']['xinzhi']==null){
                unset($JobsList[$key]);
                continue ;
            }
            $row['zhusuname'] = $ZhusD[$row['stay']];
            $row['Safename'] = $SafeD[$row['safe']];
            $row['FoodDname'] = $FoodD[$row['food']];
            $row['Agename'] = $AgeD[$row['age']];
            $row['Educationname'] = $EducationD[$row['education']];

             if( $row['gold1'] == $row['gold2']){
                $row['goldtext'] = ($row['gold1']/1000)."K";
                if($row['gold1']==3000){
                    $row['goldtext'].="以下";
                }
                if($row['gold1']==10000){
                    $row['goldtext'].="以上";
                }
           }else{
                $row['goldtext'] = ($row['gold1']/1000)."K-".($row['gold2']/1000)."K";
           }

            $row['XinZhiname'] = $comxinzn[$row['recruitcompany']['xinzhi']];
        }

        $outData=[];
        $outData['JobsList'] = $JobsList;
        $outData['Company'] = $CompanyD;

        $this->success('', $outData);

    }

    public function get_c_job(){
    	$id = $this->request->post('id');
    	$tmp_m = new Job();
    	$row = $tmp_m->with(['recruitcompany','recruitopencity'])->where($this->db_pf.'recruit_job.Id',$id)->limit(1)->select();
        if (!$row)
            $this->error(__('No Results were found'));

        $row = $row[0];
        //格式化数据
        $ZhusD = ['不提供','提供','提供夫妻房'];
        $FoodD = ['不提供','提供午饭','提供三餐','有餐补'];
        $SafeD = ['不提供','缴纳三险','缴纳五险','缴纳五险一金'];

        $row['zhusuname'] = $ZhusD[$row['stay']];
        $row['Safename'] = $SafeD[$row['safe']];
        $row['FoodDname'] = $FoodD[$row['food']];

        $AgeD = ['无要求','18-30岁','30-45岁','45-50岁','其他'];
        $row['Agename'] = $AgeD[$row['age']];

        $EducationD = ['无学历要求','小学','初中','高中','大专','本科','研究生及以上'];
        $row['Educationname'] = $EducationD[$row['education']];

        //这里需要格式化一下 工资薪水
         if( $row['gold1'] == $row['gold2']){
              $row['goldtext'] = $row['gold1'];
              if($row['gold1']==3000){
                  $row['goldtext'].="以下";
              }
              if($row['gold1']==10000){
                  $row['goldtext'].="以上";
              }
         }else{
              $row['goldtext'] = $row['gold1']." - ".$row['gold2'];
         }

        $comxinzn = ['国有企业','私营企业','中外合作企业','中外合资企业','外商独资企业'];
        $row['XinZhiname'] = $comxinzn[$row['recruitcompany']['xinzhi']];

        $this->success('', $row);
    }

     public function del_my_company(){
       $id = $this->request->post('Id');
       $Companyd= Company::get(['Id' =>  $id]);
       $Companyd->delete();

       $this->success('', $id);
     }
      public function del_my_job(){
       $id = $this->request->post('Id');
       $Companyd= Job::get(['Id' =>  $id]);
       $Companyd->delete();

       $this->success('', $id);
     }

     public function edit_job(){
        $row = $this->request->post();
        //增加登录人的信息
        $row['user_id'] = $this->auth->id;

        $job= Job::get(['Id' =>  $row['id']]);

        //增加登录人的信息
        $row['user_id'] = $this->auth->id;

        $job->user_id    = $row['user_id'];
        $job->name     = $row['name'];
        $job->neednum    = $row['neednum'];
        $job->age    = $row['age'];
        $job->city_id    = $row['city_id'];
        $job->content    = $row['content'];

        $job->education    = $row['education'];
        $job->food    = $row['food'];
        $job->gold1    = $row['gold1'];
        $job->gold2    = $row['gold2'];
        $job->safe    = $row['safe'];
        $job->stay    = $row['stay'];
        $job->save();

        $this->success('', $row);
     }

     public function add_job(){
        $row = $this->request->post();

        //增加登录人的信息
        $row['user_id'] = $this->auth->id;

        $job = new Job;
        $job->user_id    = $row['user_id'];
        $job->name     = $row['name'];
        $job->neednum    = $row['neednum'];
        $job->age    = $row['age'];
        $job->c_id    = $row['c_id'];
        $job->city_id    = $row['city_id'];
        $job->content    = $row['content'];

        $job->education    = $row['education'];
        $job->food    = $row['food'];
        $job->gold1    = $row['gold1'];
        $job->gold2    = $row['gold2'];
        $job->safe    = $row['safe'];
        $job->stay    = $row['stay'];
        $job->save();

        $this->success('', $row);


     }

     public function add_com(){
        $row = $this->request->post();

        //增加登录人的信息
        $row['user_id'] = $this->auth->id;

        //如果发现已经有公司了 则不予增加
        if(count(Company::get(['user_id' => $row['user_id']]))>0){
          $this->error('已有管理公司');
          return;
        }

        $Company           = new Company;
        $Company->name     = $row['name'];
        $Company->tel    = $row['tel'];
        $Company->no    = $row['no'];
        $Company->xinzhi    = $row['xinzhi'];
        $Company->adress    = $row['adress'];
        $Company->content    = $row['content'];
        $Company->user_id    = $row['user_id'];

        $Company->cimage   = !isset($row['cimage'])? '':$row['cimage'];
        $Company->cimages   = !isset($row['cimages'])? '':$row['cimages'];
        $Company->save();


        $this->success('', $row);
     }

      public function edit_com(){
        $row = $this->request->post();

        //增加登录人的信息
        $row['user_id'] = $this->auth->id;

        $Company = Company::get(['user_id' => $row['user_id']]);

        if(count($Company)!=1){
          $this->error('关联公司错误');
          return;
        }

        $Company->name     = isset($row['name']) ? $row['name'] : $Company->name;
        $Company->tel     = isset($row['tel']) ? $row['tel'] : $Company->tel;
        $Company->no     = isset($row['no']) ? $row['no'] : $Company->no;
        $Company->xinzhi     = isset($row['xinzhi']) ? $row['xinzhi'] : $Company->xinzhi;
        $Company->adress     = isset($row['adress']) ? $row['adress'] : $Company->adress;
        $Company->content     = isset($row['content']) ? $row['content'] : $Company->content;
        $Company->user_id     = isset($row['user_id']) ? $row['user_id'] : $Company->user_id;
        $Company->cimage     = isset($row['cimage']) ? $row['cimage'] : $Company->cimage;
        $Company->cimages     = isset($row['cimages']) ? $row['cimages'] : $Company->cimages;

        $Company->save();
        $this->success('', $row);
     }

    public function upload()
    {
        $file = $this->request->file('files');
        if (empty($file)) {
            $this->error(__('No file upload or server upload limit exceeded'));
        }

        //判断是否已经存在附件
        $sha1 = $file->hash();

        $upload = Config::get('upload');

        preg_match('/(\d+)(\w+)/', $upload['maxsize'], $matches);
        $type = strtolower($matches[2]);
        $typeDict = ['b' => 0, 'k' => 1, 'kb' => 1, 'm' => 2, 'mb' => 2, 'gb' => 3, 'g' => 3];
        $size = (int)$upload['maxsize'] * pow(1024, isset($typeDict[$type]) ? $typeDict[$type] : 0);
        $fileInfo = $file->getInfo();
        $suffix = strtolower(pathinfo($fileInfo['name'], PATHINFO_EXTENSION));
        $suffix = $suffix ? $suffix : 'file';

        $mimetypeArr = explode(',', strtolower($upload['mimetype']));
        $typeArr = explode('/', $fileInfo['type']);

        //验证文件后缀
        if ($upload['mimetype'] !== '*' &&
            (
                !in_array($suffix, $mimetypeArr)
                || (stripos($typeArr[0] . '/', $upload['mimetype']) !== false && (!in_array($fileInfo['type'], $mimetypeArr) && !in_array($typeArr[0] . '/*', $mimetypeArr)))
            )
        ) {
            $this->error(__('Uploaded file format is limited'));
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
            $imagewidth = $imageheight = 0;
            if (in_array($suffix, ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf'])) {
                $imgInfo = getimagesize($splInfo->getPathname());
                $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
                $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
            }
            $params = array(
                'admin_id'    => 0,
                'user_id'     => (int)$this->auth->id,
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
            );
            //$attachment = model("attachment");
            $attachment = new Attachment();
            $attachment->data(array_filter($params));
            $attachment->save();
            \think\Hook::listen("upload_after", $attachment);
            $this->success(__('Upload successful'), [
                'url' => $uploadDir . $splInfo->getSaveName()
            ]);
        } else {
            // 上传失败获取错误信息
            $this->error($file->getError());
        }
    }


}
