<?php
namespace Admin\Controller;
use Think\Controller;
class ApiController extends CommonController {


    public function index(){
        define('UID',is_login());
        if( !UID ){
            $this->redirect('Public/login');
        }else{
            $user = M('user');
            $usermodel = $user->where('uid = '.session('user.uid'))->find();
            if(!$usermodel["token"]){
                $this->redirect('index/ppdapi');
            }else{
                $this->redirect('index/today');
            }
        }
    }
    public function bid(){
        header("Content-type:text/html;charset=utf-8");
        $url = "http://gw.open.ppdai.com/invest/LLoanInfoService/LoanList";
        date_default_timezone_set("Etc/GMT-8");
        $dt = date("Y-m-d H:i:s");
        $dt = date("Y-m-d H:i:s",strtotime("$dt - 500second"));
        $request = '{
            "PageIndex": 1,
            "StartDateTime": "' . $dt . '.000"
        }';
        $result = send($url, $request);
        echo $result;
    }

    public function bidd(){
        header("Content-type:text/html;charset=utf-8");
        $url = "http://gw.open.ppdai.com/invest/LLoanInfoService/BatchListingInfos";
        date_default_timezone_set("Etc/GMT-8");
        $request = '{
            "ListingIds": [' .I('lid') . ']
        }';
        $result = send($url, $request);
        echo $result;
    }

    public function deal($lid){
        $bid = M('bid');
        $bidmodel = $bid->where('uid = '.session('user.uid') . 'AND Listingid = '. $lid )->count();
        if($bidmodel==0){
            $row["uid"] = session('user.uid');
            $row["listingid"] = $lid;
            $row["share"] = "500";
            $row["biddate"] = time();
            $row["trandate"] = "NaN";
            if($bid->add($row)){
                $usrInfo = array('status'=>'success');

            }else{
                $usrInfo = array('status'=>'fail','content'=>'这个您已经投资过了');
            }
        }else{
            $usrInfo = array('status'=>'fail','content'=>'这个您已经投资过了');
        }
        echo $this->ajaxReturn($usrInfo);
    }

    public function listd(){
        $lid = I('get.lid');
        $data = M('data');
        $datamodel = $data->where('Listingid = '.$lid )->select();
        echo $this->ajaxReturn($datamodel[0]);
    }
    public function update(){
        $data = D('Data');
        echo $this->ajaxReturn($data->update());
    }

    public function amount(){
        echo $this->ajaxReturn(F('amount'));
    }

    public function rate(){
        echo $this->ajaxReturn(F('rate'));
    }

    public function credit(){
        echo $this->ajaxReturn(F('credit'));
    }
    public function creditratio(){
        echo $this->ajaxReturn(F('creditratio'));
    }

    public function cleartoken(){
        $user = D('user');
        $userdata["token"] = "";
        $userdata["tokentime"] = "";
        $user->where('uid = '.session('user.uid'))->save($userdata);
        $data["content"] = "清除授权成功";
        echo $this->ajaxReturn($data);
    }
}
?>
