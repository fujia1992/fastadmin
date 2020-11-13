<?php
namespace addons\recruit\controller;

class Resume extends Base
{
    //protected $noNeedLogin = ['get_c_job']; 
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    public function add_Resume(){
        $row = $this->request->post();
        //增加登录人的信息
        $row['user_id'] = $this->auth->id;

        $Resume = new \app\admin\model\Resume;
        $Resume->user_id     = $row['user_id'];
        $Resume->birthday    = $row['birthday'];
        $Resume->tel    = $row['tel'];

        $Resume->c_avatar    = $row['c_avatar'];
        $Resume->content     = $row['content'];
        $Resume->education   = $row['education'];
        $Resume->name   = $row['name'];
        $Resume->sex    = $row['sex'];
		$Resume->work_city   = $row['work_city'];
		$Resume->gold1   = $row['gold1'];
		$Resume->gold2   = $row['gold2'];

		$Resume->native_place = $row['native_place'];
        $Resume->save();

        $this->success('', $row);
    }

    public function Edit_Resume(){
        $row = $this->request->post();

        $Resume = \app\admin\model\Resume::get($row['id']);
        $Resume->birthday    = $row['birthday'];
        $Resume->tel         = $row['tel'];

        $Resume->c_avatar    = $row['c_avatar'];
        $Resume->content     = $row['content'];
        $Resume->education   = $row['education'];
        $Resume->name   = $row['name'];
        $Resume->sex    = $row['sex'];
        $Resume->work_city   = $row['work_city'];
        $Resume->gold1   = $row['gold1'];
        $Resume->gold2   = $row['gold2'];

        $Resume->native_place = $row['native_place'];
        $Resume->save();

        $this->success('', $row);
    }

    public function get_resumeByID(){
        $id = $this->request->post('id');

        $Resume = \app\admin\model\Resume::get($id);
        if(count($Resume)==0){
             $this->error('不存在简历');
             return;
        }
        //格式化籍贯
        $Resume['native_place_arry'] = explode("/",$Resume['native_place']);
        //格式化年龄、星座、属相
        $Resume['shengxiao'] = $this->GetShengXiao($Resume['birthday']);
        $Resume['xinzuo'] = $this->GetXZD($Resume['birthday']);
        $Resume['age'] = $this->getAge($Resume['birthday']);

        $EducationD = ['文盲','小学','初中','高中','大专','本科','研究生及以上'];
        $Resume['Educationname'] = $EducationD[$Resume['education']];

        $Resume['workcity'] = \app\admin\model\Opencity::get(['Id' => $Resume['work_city']])['city'];
        

        $this->success('', $Resume);
    }

    public function get_my_resume(){
        $user_id = $this->auth->id;

        $Resume = \app\admin\model\Resume::get(['user_id' => $user_id]);
        if(count($Resume)==0){
             $this->error('不存在简历');
             return;
        }
        //格式化年龄、星座、属相
        $Resume['shengxiao'] = $this->GetShengXiao($Resume['birthday']);
        $Resume['xinzuo'] = $this->GetXZD($Resume['birthday']);
        $Resume['age'] = $this->getAge($Resume['birthday']);

        $EducationD = ['文盲','小学','初中','高中','大专','本科','研究生及以上'];
        $Resume['Educationname'] = $EducationD[$Resume['education']];

        $Resume['workcity'] = \app\admin\model\Opencity::get(['Id' => $Resume['work_city']])['city'];
        

        $this->success('', $Resume);
    }

    public function del_my_Resume(){
       $id = $this->request->post('id');
       $Cdd= \app\admin\model\Resume::get(['id' =>  $id]);
       $Cdd->delete();

       $this->success('', $id);
    }

    public function getPhone(){
        include_once "../../library/wechat_lite_coder/wxBizDataCrypt.php";
        if(isset($_POST['sessionKey'])){
            $config = get_addon_config('recruit');

            $appid = $config['wxappid'];
            $sessionKey =  $_POST['sessionKey'];
            $encryptedData = $_POST['encryptedData'];
            $iv = $_POST['iv'];
            
            $pc = new WXBizDataCrypt($appid, $sessionKey);
            $errCode = $pc->decryptData($encryptedData, $iv, $data );
            
            if ($errCode == 0) {
                $this->success('', json_decode($data,true));
            } else {
                $this->error('获取失败', $errCode);
            }
    
        }

    }







    function GetShengXiao($ShengRi){
        $y =substr($ShengRi,0,4);       //截取年
        $sxdict = array('鼠', '牛', '虎', '兔', '龙', '蛇', '马', '羊', '猴', '鸡', '狗', '猪');
        return $sxdict[(($y-4)%12)];
    }
    function GetXZD($ShengRi){
        $birth = $ShengRi;
        
        $month = substr($birth,5,2);//截取月
        $day = substr($birth,8,2);//截取日
              
        $constellation = $this->getConstellation($month, $day);
        return $constellation;
    }

    function getConstellation($month, $day)
    {
        $day   = intval($day);
        $month = intval($month);
        if ($month < 1 || $month > 12 || $day < 1 || $day > 31) return false;
        $signs = array(
            array('20'=>'宝瓶座'),
            array('19'=>'双鱼座'),
            array('21'=>'白羊座'),
            array('20'=>'金牛座'),
            array('21'=>'双子座'),
            array('22'=>'巨蟹座'),
            array('23'=>'狮子座'),
            array('23'=>'处女座'),
            array('23'=>'天秤座'),
            array('24'=>'天蝎座'),
            array('22'=>'射手座'),
            array('22'=>'摩羯座')
        );
        list($start, $name) = each($signs[$month-1]);
        if ($day < $start)
            list($start, $name) = each($signs[($month-2 < 0) ? 11 : $month-2]);
        return $name;
    }

    function getAge($birthday){
        $birthday = strtotime($birthday);
        //格式化出生时间年月日
        $byear=date('Y',$birthday);
        $bmonth=date('m',$birthday);
        $bday=date('d',$birthday);

        //格式化当前时间年月日
        $tyear=date('Y');
        $tmonth=date('m');
        $tday=date('d');

        //开始计算年龄
        $age=$tyear-$byear;
        if($bmonth>$tmonth || $bmonth==$tmonth && $bday>$tday){
            $age--;
        }
        return $age;
    }

    public function add_workforce(){
        $row = $this->request->post();
        //增加登录人的信息
        $row['user_id'] = $this->auth->id;

        $Workforce = new \app\admin\model\Workforce;
        $Workforce->user_id     = $row['user_id'];
        $Workforce->sfzno       = $row['sfzno'];
        $Workforce->tel         = $row['tel'];
        $Workforce->collect     = $row['collect'];
        $Workforce->content     = $row['content'];
        $Workforce->education   = $row['education'];
        $Workforce->intent      = $row['intent'];
        $Workforce->name        = $row['name'];
        $Workforce->place       = $row['place'];
        $Workforce->salary      = $row['salary'];
        $Workforce->sex         = $row['sex'];
        $Workforce->skill       = $row['skill'];
        $Workforce->village     = $row['village'];

        $Workforce->save();

        $this->success('', $row);
    }

    public function my_workforce(){
        //增加登录人的信息
        $user_id = $this->auth->id;
        $Workforce = \app\admin\model\Workforce::where(['user_id' => $user_id])->select();
        $this->success('',$Workforce);
    }

}
