<?php

namespace addons\recruit\controller;

//use think\addons\Controller;
//use app\common\model\Addon;

use addons\recruit\model\News;

use app\admin\model\Job;
use app\admin\model\Company;

class Index extends Base
{
	protected $noNeedLogin = '*';
	
	public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->error("当前插件暂无前台页面");
    }

    public function get_index_all_data(){
        $city_id = $this->request->post('city_id');

        $bannerList = [];
        $list = News::getBannerList(['type' => 'focus', 'status' => 'normal' ,'row' => 5,'cache'=>0]);
        foreach ($list as $index => $item) {
            $bannerList[] = ['image' => cdnurl($item['image'], true), 'url' => '/', 'title' => $item['title'], 'name' => $item['name'],'id'=> $item['id']];
        }

        //这里把最新的10条职位列表拖出来
        $JobsList = [];
        $JobsList_m = new Job();
        if($city_id=='no'||$city_id=='undefined'){
            $JobsList = $JobsList_m->with('recruitcompany')->limit(20)
                                ->order('updatetime', 'desc')
                                ->select();
        }else{
            $JobsList = $JobsList_m->with('recruitcompany')->where('city_id', $city_id)->limit(20)
                                ->order('updatetime', 'desc')
                                ->select();
        }

        $ZhusD = ['不提供住宿','提供住宿','提供夫妻房'];
        $FoodD = ['不提供伙食','提供午饭','提供三餐','有餐补'];
        $SafeD = ['不提供社保','缴纳三险','缴纳五险','缴纳五险一金'];
        $EducationD = ['无学历要求','小学','初中','高中','大专','本科','研究生及以上'];
        $comxinzn = ['国有企业','私营企业','中外合作企业','中外合资企业','外商独资企业'];
        foreach ($JobsList as $key=>&$item) {
            if($item['recruitcompany']['xinzhi']==null){
                unset($JobsList[$key]);
                continue ;
            }
           $tempd = $item['recruitcompany']['xinzhi'];
           $item['XinZhiname'] = $comxinzn[$tempd];

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

        //拖取最新的8条企业数据
        $companyList = [];
        foreach ($JobsList as $keyJ=>$itemJ) {
            if(count($companyList)>=8){
                break;
            }

            $HasT = false;
            foreach ($companyList as $keyC=>$itemC) {
                if($itemC['name']==$itemJ['recruitcompany']['name']){
                    $HasT = true;
                    break;
                }
            }
            if(!$HasT){
                $tempd = $itemJ['recruitcompany']['xinzhi'];
                $itemJ['recruitcompany']['XinZhiname'] = $comxinzn[$tempd];

                $itemJ['recruitcompany']['jobCount'] = Job::where('c_id', $itemJ['recruitcompany']['Id'])->count();
                array_push($companyList,$itemJ['recruitcompany']);
            }
        }

        $data = [
            'bannerList'   => $bannerList,
            'companyList'  => $companyList,
            'JobsList'  => $JobsList
        ];
        $this->success('', $data);
    }
}
