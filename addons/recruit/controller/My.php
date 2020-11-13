<?php

namespace addons\recruit\controller;

/**
 * 我的
 */
class My extends Base
{

    protected $noNeedLogin = ['aboutus'];

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 提交报名
     */
    public function add_baoming(){
        $row = $this->request->post();
        //增加登录人的信息
        $row['user_id'] = $this->auth->id;

        $Jobfair = new \app\admin\model\Jobfair;
        $Jobfair->user_id     = $row['user_id'];
        $Jobfair->block_id    = $row['block_id'];
        $Jobfair->block_title = $row['block_title'];

        $Jobfair->tname    = $row['tname'];
        $Jobfair->ttel     = $row['ttel'];
        
        $Jobfair->save();

        $this->success('', $row);
    }


    /**
     * 这里拖取 本人的简历投递情况 和 简历是否存在的情况
    */
    public function Job_re_stat(){
        $id = $this->request->post('id');
        //首先判断 是否有简历
        $user_id = $this->auth->id;
        $Resume = \app\admin\model\Resume::get(['user_id' => $user_id]);
        $ResumeNum = count($Resume);
      
        //投递过简历信息
        $red_D = \app\admin\model\Resumedelivery::get(['user_id' => $user_id,'job_id' => $id]);
        $row = array();
        $row['ResumeNum'] = $ResumeNum;
        $row['red_D'] = $red_D;

        $this->success('', $row);
    }

    /**
     * 投递简历
     */
    public function add_resume_resumedelivery(){
        $newData = new \app\admin\model\Resumedelivery();

        $user_id = $this->auth->id;
        $newData['user_id'] = $user_id;
        $Resume = \app\admin\model\Resume::get(['user_id' => $user_id]);

        $newData['re_id'] = $Resume['id'];
        $newData['re_name'] = $Resume['name'];
        $newData['re_tel'] = $Resume['tel'];

        $newData['job_id'] = $this->request->post('id');
        $newData['com_name'] = $this->request->post('com_name');
        $newData['job_name'] = $this->request->post('job_name');
        
        $newData->save();

        $this->success('', $newData);
    }

    /**
     * 投递简历
     */
    public function My_resumedelivery(){
        //当前是否为关联查询
        $this->relationSearch = true;
        //登录人的信息
        $ResumedeliveryList = \app\admin\model\Resumedelivery::with(['job'])->where(['resumedelivery.user_id' => $this->auth->id])
            ->order('Id desc')
            ->limit(30)
            ->select();

        $ZhusD = ['不提供住宿','提供住宿','提供夫妻房'];
        $SafeD = ['不提供社保','缴纳三险','缴纳五险','缴纳五险一金'];
        $EducationD = ['无学历要求','小学','初中','高中','大专','本科','研究生及以上'];

        foreach ($ResumedeliveryList as $key=>&$item) {
           //这里格式化一下 住宿、社保、学历
           $item['job']['zhusuname'] = $ZhusD[$item['job']['stay']];
           $item['job']['Safename'] = $SafeD[$item['job']['safe']];
           $item['job']['Educationname'] = $EducationD[$item['job']['education']];
           //这里需要格式化一下 工资薪水
           if( $item['job']['gold1'] == $item['job']['gold2']){
                $item['job']['goldtext'] = ($item['job']['gold1']/1000)."K";
                if($item['job']['gold1']==3000){
                    $item['job']['goldtext'].="以下";
                }
                if($item['job']['gold1']==10000){
                    $item['job']['goldtext'].="以上";
                }
           }else{
                $item['job']['goldtext'] = ($item['job']['gold1']/1000)."K-".($item['job']['gold2']/1000)."K";
           }
        }



        $row = array();
        $row['ResumedeliveryList'] = $ResumedeliveryList;
        $this->success('', $row);
    }
}
