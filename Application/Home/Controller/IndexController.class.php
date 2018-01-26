<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\common;
class IndexController extends Controller {
	//首页
    public function index(){
		//栏目分类
		$this->Navigation();
		//快捷入口
		$quick = M('quick')->where("type='0'")->order('ord desc')->select();
		$this->assign('quick',$quick);
		//视频入口
		$quick_video = M('quick')->where("type='1'")->order('ord desc')->select();
		$this->assign('quick_video',$quick_video);
		//轮播图
		$advertisement = M('carousel')->order('ord')->select();
		$this->assign('advertisement',$advertisement);
		// 判断是否是本地连接
		$httpArr=array('http','https');
		foreach ($advertisement as $k => $vv) {
			$htpstr=substr($vv['link'], 0,4);
			if(in_array($htpstr, $httpArr)){
				$this->assign("houId",$k);
			}
		}
		//跳转到单独视频页
		// $houArr=array(swf,flv,mp4,rmvb,avi,mpeg,ra,ram,mov,wmv);
		// foreach ($advertisement as $k=>$vv) {
		// 	$hou=array_pop(explode('.', $vv['link']));
		// 	if(in_array($hou,$houArr)){
		// 		$this->assign("houId",$k);
		// 	}
		// }
		//产品中心
		$cloud = M('index')->where("label='cloud'")->order("id desc")->find();
		$this->assign('cloud',$cloud);
		//中部介绍
		$center = M('index')->where("label='center'")->getField('content');
		$this->assign('center',$center);
		//新闻中心
		$news = M('index')->where("label='news'&&ord=1")->select();
		$news3=array();
		foreach ($news as  $v) {
			$newsOne=M("article")->where("id={$v['conid']}")->find();
			array_push($news3, $newsOne);
		}
		$this->assign('news3',$news3);
		//成功案例
		$successList = M('quick')->where("type='1'&&ord='1'")->order('ord desc')->select();
		$this->assign('successList',$successList);
		//产品解决方案
		$solutionList = M('quick')->where("type='0'&&ord='1'")->order('ord desc')->select();
		$this->assign('solutionList',$solutionList);
		//seo 关键词 描述
		$system = M('system')->where("id='1'")->find();
		$this->assign('system',$system);
		//logo
		$logo = M('index')->where("label='logo'")->getField('other');
		$this->assign('logo',$logo);
		$this->display('');
    } 
    // // 登录的前置方法
    // public function _before_login(){
    // 	$name	  = I('name');
    // 	$user=M('user')->where('name={$name}')->find();
    // 	var_dump($user);exit;

    // 	}
	//登录页面
	public function login(){
		if($_POST){
			$name	  = I('name');
			$password = md5(I('password'));
			//验证账号
			$res = M('user')->where("name='{$name}'")->find();
			$state=M('user')->where("name='{$name}'")->getField("state");
			if($state){
				echo  "<script>alert('管理员审核中，请耐心等待 ！');window.location.href='Index/index';</script>";
			}else{
				if($res){
				//验证密码
				if($res['password']==$password){
					//用户信息存入session
					$_SESSION['user']=$res;
					echo  "<script>alert('恭喜您，登录成功！');window.location.href='Index/index';</script>";
					// $this->redirect("Index/index");
				}else{
					echo  "<script>alert('密码错误,请重新输入！');</script>";
				}
			}else{
				echo  "<script>alert('用户不存在,请注册！');window.location.href='registe.html';</script>";
			}
		 }
		}
		$this->Navigation();
		$this->display('');
	}
	//用户注册
	public function registe(){
		$this->Navigation();
		$this->display('');
	}
	public function addUser(){
		// var_dump($_POST); return false;
		//if()
		if($_POST){
			$name      =     I('name');
			$password  =   md5(I('password'));
			$addtime  =    date("Y-m-d H:i",time());
			$state='1';
			$data= array('name' => $name,'password'=>$password ,'addtime'=>$addtime,'state'=>$state);
			$exist=M("user")->where("name='{$name}'")->find();
			if($exist){
				// $this->error('');
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				echo  "<script type='text/javascript'>alert('用户名重复，请重新注册！');window.location.href='registe.html';</script>";
				// $this->redirect("Index/registe");
			}else{
				$result=M('user')->add($data);
				echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
				// echo "<script type='text/javascript'>alert('恭喜您，注册成功 !');window.location.href='login.html';</script>";
				echo "<script type='text/javascript'>alert('恭喜您，注册完成，请等待管理员审核！ ');window.location.href='Index/index';</script>";
				// $this->redirect("Index/login");
			}
			
		}
	}
	//搜索新闻
	public function search(){
		if($_GET['title']===''){
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo "<script>alert('搜索内容为空 !');</script>";
			echo "<script>window.history.go(-1);</script>";
		}else{
			$this->Navigation();
			$_GET['title']=trim($_GET['title']);
			// 搜索新闻部分
			$map['title|content'] = array("like","%".$_GET['title']."%");
			$kyw=$_SESSION['keyword']=explode('%', $map['title|content'][1])[1];
			$m=M("article");
			$p=getpage($m,$map,5);//每页显示条数	
			$searchList_a= $m->field(false)->where($map)->order('addtime desc')->select();

			// 搜索产品部分  三级页面下半部分
			$mp=M("product");
			$p=getpage($mp,$map,5);//每页显示条数
			$searchList_p= $mp->field(false)->where($map)->order('addtime desc')->select();
			foreach ($searchList_p as $k=>$v) {
				$pname =M("class")->where("id={$v['cid']}")->getField('name');
				$searchList_p[$k]['pname'] =$pname;
			}
			// 搜索解决方案  三级页面下半部分
			$msl=M("solution");
			$p=getpage($msl,$map,5);//每页显示条数
			$searchList_sl=$msl ->field(false)->where($map)->order('addtime desc')->select();
			foreach ($searchList_sl as $k=>$v) {
				$pname =M("class")->where("id={$v['cid']}")->getField('name');
				$searchList_sl[$k]['pname'] =$pname;
			}
			// 搜索成功案例部分   三级页面下半部分
			$msc=M("success");
			$p=getpage($msc,$map,5);//每页显示条数
			$searchList_sc=$msc->field(false)->where($map)->order('addtime desc')->select();
			foreach ($searchList_sc as $k=>$v) {
				$pname =M("class")->where("id={$v['cid']}")->getField('name');
				$searchList_sc[$k]['pname'] =$pname;
			}
			
			// 搜索产品 解决方案 成功案例  三级页面上半部分
			$where['title|content|name'] = array("like","%".$_GET['title']."%");
			$kyw2=$_SESSION['keyword']=explode('%', $where['title|content|name'][1])[1];
			$m=M("class");
			$p=getpage($m,$where,5);//每页显示条数		
			$searchList=$m->field(false)->where($where)->order('addtime desc')->select();
			foreach ($searchList as $k=> $v) {
				$count = substr_count($v['path'],',');
				$path=explode(',', $v['path'])[1];
				// var_dump($count);
				if($count===2){
					unset($searchList[$k]);
					$path=null;
				}
				$searchList[$k]['nav']=$path;
			}
			// var_dump($searchList);

			// $searchList=array_merge($searchList_a,$searchList_p,$searchList_sl,$searchList_sc);
			// var_dump($searchList);
			
			// $p=getpage($m_a,$map,10);//每页显示条数		
			//$searchList= $m->field(false)->where($map)->order('addtime desc')->select();
			// $this->searchList_a=$searchList_a;
			// $this->searchList=$searchList;
			$page=$this->page=$p->show();
			// $page2=$this->page=$p2->show();
			// $page=$this->page=$p_a->show();
			// $page=$this->page=$p_sc->show();
			// $page=$this->page=$p_sl->show();

			
			// $sql=M("article")->getLastSql();
			// var_dump($sql);
			// var_dump($searchList);
			foreach($searchList as $v){
				$v['title']=preg_replace("/($kyw2)/i","<b style='color:red'>\\1</b>",$v['title']);
				$sear[]=$v;
			}
			foreach($searchList_a as $v){
				$v['title']=preg_replace("/($kyw)/i","<b style='color:red'>\\1</b>",$v['title']);
				$sear_a[]=$v;
			}
			// foreach($searchList_h as $v){
			// 	$v['title']=preg_replace("/($kyw)/i","<b style='color:red'>\\1</b>",$v['title']);
			// 	$sear_h[]=$v;
			// }
			foreach($searchList_p as $v){
				$v['title']=preg_replace("/($kyw)/i","<b style='color:red'>\\1</b>",$v['title']);
				$sear_p[]=$v;
			}
			foreach($searchList_sl as $v){
				$v['title']=preg_replace("/($kyw)/i","<b style='color:red'>\\1</b>",$v['title']);
				$sear_sl[]=$v;
			}
			foreach($searchList_sc as $v){
				$v['title']=preg_replace("/($kyw)/i","<b style='color:red'>\\1</b>",$v['title']);
				$sear_sc[]=$v;
			}
			$this->assign('searchList',$sear);
			$this->assign('searchList_a',$sear_a);
			// $this->assign('searchList_h',$sear_h);
			$this->assign('searchList_p',$sear_p);
			$this->assign('searchList_sc',$sear_sc);
			$this->assign('searchList_sl',$sear_sl);
			// var_dump($sear);	
			if($searchList_a||$searchList_p||$searchList_sc||$searchList_sl||$searchList||$searchList_h){
				$this->display('search');
			}else{
				$this->display('search_no');
			}
		}
	}
	//产品中心
	public function products(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->two_article($_GET['id']);
		$this->other_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$menuList=M("class")->where("pid=2")->order("ord")->select();
		$this->assign("menuList",$menuList);
		$video=M("video")->where("cid={$_GET['id']}")->order("addtime desc")->limit("4")->select();
		$this->assign("video",$video);
		$this->display('');
	}
	//产品中心下的三级栏目
	public function products_view(){
		$this->Navigation();
		$this->three_class_view($_GET['pid']);
		$this->two_advertisement($_GET['pid']);
		$this->two_article($_GET['id']);
		$this->two_title($_GET['id']);
		$this->second_title($_GET['pid']);
		$this->assign("getId",$_GET['pid']);
		//$this->quick_three($_GET['id']);
		$quick_three = M('quick_three')->where("cid={$_GET['id']}")->select();
		// var_dump($quick_three);
		foreach ($quick_three as  $v) {
			//相关新闻
			$article=M('article')->where("cid={$v['acid']}&&id={$v['aid']}")->find();
			$list[]=$article;
			$this->assign("list",$list);

			//相关案例
			$path=M("class")->where("id={$v['acid']}")->getField("path");
			$pathArr=explode(',', $path);
			if(in_array('5', $pathArr)){
				$al_title[]=M("class")->where("id={$v['acid']}")->getField("name");
				$al_list[]=M('success')->where("cid={$v['acid']}&&id={$v['aid']}")->find();
			}
		}
		//把一维数组加到二维数组里面
		foreach($al_list as $k=>$v) {
			  $al_list[$k]['name'] = $al_title[$k];
		 }
		$this->assign("al_list",$al_list);
		$info=M("class")->where("id={$_GET['id']}")->find();
		$this->assign("info",$info);
		$show=M("product")->where("cid={$_GET['id']}")->order("addtime desc")->select();
		$this->assign("show",$show);
		// $menuList=M("class")->where("pid=2")->order('ord')->select();
		// $this->assign("menuList",$menuList);
		$this->display('products-view');
	}
	//解决方案
	public function solutions(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->two_article($_GET['id']);
		$this->other_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$video=M("video")->where("cid={$_GET['id']}")->order("addtime desc")->limit("4")->select();
		$this->assign("video",$video);
		$menuList=M("class")->where("pid=4")->order("ord")->select();
		$this->assign("menuList",$menuList);
		$this->display('');
	}
	//解决方案下的三级栏目
	public function solutions_view(){
		$this->Navigation();
		$this->three_class_view($_GET['pid']);
		$this->two_advertisement($_GET['pid']);
		$this->two_article($_GET['id']);
		$this->two_title($_GET['id']);
		$this->second_title($_GET['pid']);
		$this->assign("getId",$_GET['pid']);
		//$this->quick_three($_GET['id']);
		$quick_three = M('quick_three')->where("cid={$_GET['id']}")->select();
		foreach ($quick_three as  $v) {
			//相关新闻
			$article=M('article')->where("cid={$v['acid']}&&id={$v['aid']}")->find();
			$list[]=$article;
			$this->assign("list",$list);
			//相关案例
			$path=M("class")->where("id={$v['acid']}")->getField("path");
			$pathArr=explode(',', $path);
			if(in_array('5', $pathArr)){
				$al_title[]=M("class")->where("id={$v['acid']}")->getField("name");
				$al_list[]=M('success')->where("cid={$v['acid']}&&id={$v['aid']}")->find();
			}
		}
		//把一维数组加到二维数组里面
		foreach($al_list as $k=>$v) {
			  $al_list[$k]['name'] = $al_title[$k];
		 }
		$this->assign("al_list",$al_list);

		$info=M("class")->where("id={$_GET['id']}")->find();
		$this->assign("info",$info);
		// $menuList=M("class")->where("pid=4")->order('ord')->select();
		// $this->assign("menuList",$menuList);
		$show=M("solution")->where("cid={$_GET['id']}")->order("addtime desc")->select();
		$this->assign("show",$show);
		$this->assign("solutionId",$_GET['id']);
		$this->display('solutions-view');
	}
	//成功案例
	public function success_case(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->two_article($_GET['id']);
		$this->other_class($_GET['id']);
		$this->two_title($_GET['id']);
		

		$this->assign("getId",$_GET['id']);
		$video=M('video')->where("cid={$_GET['id']}")->order("addtime desc")->limit("4")->select();
		$this->assign("video",$video);
		$menuList=M("class")->where("pid=5")->order("ord")->select();
		$this->assign("menuList",$menuList);
		$this->display('success_case');
	}
	//成功案例下的三级栏目
	public function case_class(){
		$this->Navigation();
		$this->three_class_view($_GET['pid']);
		$this->two_advertisement($_GET['pid']);
		$this->two_article($_GET['id']);
		$this->two_title($_GET['id']);
		$this->second_title($_GET['pid']);
		$this->assign("getId",$_GET['pid']);
		//$this->quick_three($_GET['id']);
		$quick_three = M('quick_three')->where("cid={$_GET['id']}")->select();
		foreach ($quick_three as  $v) {
			//相关新闻
			$article=M('article')->where("cid={$v['acid']}&&id={$v['aid']}")->find();
			$list[]=$article;
			$this->assign("list",$list);

			//解决方案
			$path=M("class")->where("id={$v['acid']}")->getField("path");
			$pathArr=explode(',', $path);
			if(in_array('4', $pathArr)){
				$jj_title[]=M("class")->where("id={$v['acid']}")->getField("name");
				$jj_list[]=M('solution')->where("cid={$v['acid']}&&id={$v['aid']}")->find();
			}
		}
		//把一维数组加到二维数组里面
		foreach($jj_list as $k=>$v) {
			  $jj_list[$k]['name'] = $jj_title[$k];
		 }
		$this->assign("jj_list",$jj_list);
		$info=M("class")->where("id={$_GET['id']}")->find();
		$this->assign("info",$info);
		$show=M("success")->where("cid={$_GET['id']}")->order("addtime desc")->select();
		$this->assign("show",$show);
		// $menuList=M("class")->where("pid=5")->order('ord')->select();
		// $this->assign("menuList",$menuList);
		$this->display('case-class');
	}
	
	//新闻中心
	public function news(){
		$this->Navigation();
		$this->video($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->two_title($_GET['id']);
		$this->three_class($_GET['id']);
		$this->assign("getId",$_GET['id']);
		if($_GET['id']==25){
			$m=M('article');
			$where="pid=25";
			$p=getpage($m,$where,10);//每页显示条数
			$newList = $m->field(false)->where("pid=25")->order("addtime desc")->select();
			$this->newList=$newList;
			$page=$this->page=$p->show();
			// $newLast = M('article')->where("pid=25 && recommend = 1")->find();
		}elseif($_GET['id']==26){
			$m=M('article');
			$where="pid=26";
			$p=getpage($m,$where,10);//每页显示条数
			$newList = $m->field(false)->where("pid=26")->order("addtime desc")->select();
			$this->newList=$newList;
			$page=$this->page=$p->show();
			// $newLast = M('article')->where("pid=26 && recommend = 1")->find();

		}elseif($_GET['id']==27){
			$m=M('article');
			$where="pid=27";
			$p=getpage($m,$where,10);//每页显示条数
			$newList = $m->field(false)->where("pid=27")->order("addtime desc")->select();
			$this->newList=$newList;
			$page=$this->page=$p->show();
			// $newLast = M('article')->where("pid=27 && recommend = 1")->find();
		}
			// $top=M("class")->where("id={$newLast['cid']}")->getField("name");
			// $this->assign('top',$top);
			// $this->assign('newLast',$newLast);
			// $this->assign('newList',$newList);

			$menuList=M("class")->where("pid=7")->order("ord")->select();
		    $this->assign("menuList",$menuList);
		$this->display('');

	}
		
	//新闻详情
	public function news_view(){
		$this->Navigation();
		//$this->three_class_view($_GET['pid']);
		$this->three_class($_GET['id']);
		$this->quick_three($_GET['id']);
		$this->news_article($_GET['id']);
		
		$this->two_advertisement($_GET['id']);

		$checkId=M("article")->where("id={$_GET['id']}")->getField("pid");
		$this->assign("checkId",$checkId);
		$title = M('class')->where("id={$checkId}")->find();
		$this->assign("title",$title);
		
		$menuList=M("class")->where("pid=7")->select();
		$this->assign("menuList",$menuList);

		$new1 = M('article')->where("id={$_GET['id']}")->select();
		$this->assign('new1',$new1);
		$this->display('news-view');
	}
	//新闻中心列表
	public function news_class(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->three_class_view($_GET['pid']);
		$this->two_article($_GET['id']);
		$this->two_title($_GET['id']);
		$this->quick_three($_GET['id']);

		$show=M("article")->where("cid={$_GET['id']}")->order("addtime desc")->find();
		$this->assign("show",$show);
		$this->display('news-class');
	}

	 //技术支持
	public function service(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$this->two_advertisement($_GET['id']);
		$question=M("file_down")->where("cid=174")->order("addtime desc")->limit(10)->select();
		$this->assign("question",$question);
		$survey=M("file_down")->where("cid=175")->order("addtime desc")->limit(5)->select();
		$this->assign("survey",$survey);
		$menuList=M("class")->where("pid=8")->order("ord")->select();
		$this->assign("menuList",$menuList);//2017/4/18郭添加
		$this->display('');
	}
	//常见问题 客户调研 详情
	public function service_view(){
		//var_dump($_GET);exit;
		$this->Navigation();
		$this->two_title($_GET['id']);
		$pid=M("class")->where("id={$_GET['cid']}")->getField('pid');
		$this->two_advertisement($pid);
		$this->assign("checkId",$pid);
		//$this->quick_three($_GET['id']);
		$quick_three = M('quick_three')->where("cid={$_GET['cid']}")->select();
		foreach ($quick_three as  $v) {
			$al=M('success')->where("cid={$v['acid']}&&id={$v['aid']}")->find();
			$al_list[]=$al;
			$this->assign("al_list",$al_list);
			$jj=M('solution')->where("cid={$v['acid']}&&id={$v['aid']}")->find();
			$jj_list[]=$jj;
			$this->assign("jj_list",$jj_list);
		}
		
		if($_GET['cid']==174){
			$answer=M("file_down")->where("cid=174&&f_id={$_GET['id']}")->find();
		}else{
			$answer=M("file_down")->where("cid=175&&f_id={$_GET['id']}")->find();
		}
		$menuList=M("class")->where("pid=8")->select();
		$this->assign("menuList",$menuList);//2017/4/18郭添加
		$this->assign("answer",$answer);
		$this->display('service-view');
	}
	//在线交流
	public function online(){
		$this->Navigation();
		$this->two_advertisement($_GET['id']);
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		if(IS_POST){
			// 发送信息到数据库
			$data['name']=I('post.username');
			$data['email']=I('post.email');
			$data['message']=I('post.message');
			$res=M('message')->add($data);
			// 发送留言信息到邮箱
			$body= "姓名：". I('post.username') ."<br>".
					"留言者邮箱：".$_POST['email']."<br>".
					"留言内容：<p>".$_POST['message']."</p><br>";
$a = $this->think_send_mail('zhaopin@arcvideo.com','人事专员',$_POST['username'].'_留言板',$body,$attachment=null);
			// 
		

			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
			echo  "<script type='text/javascript'>alert('信息提交成功，我们会尽快联系您！');window.location.href='online?id=29';</script>";				
		}
		$menuList=M("class")->where("pid=8")->order("ord")->select();
		$this->assign("menuList",$menuList);
		$this->display('');
	}
	// 渠道云服务
	public function hongshiyun(){
		echo "<script>window.location.href='http://channel.hongshiyun.net/';</script>";
	}
	//企业荣誉
	public function honor(){
		$this->Navigation();
		$this->two_advertisement($_GET['id']);
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$list_qyry = M('honor')->where("style=1")->order("ord")->select();
		$this->assign('list_qyry',$list_qyry);
		$list_cpry = M('honor')->where("style=2")->order("ord")->select();
		$this->assign('list_cpry',$list_cpry);
		$list_xgry = M('honor')->where("style=3")->order("ord")->select();
		$this->assign('list_xgry',$list_xgry);
		$list_hyjx = M('honor')->where("style=4")->order("ord")->select();
		$this->assign('list_hyjx',$list_hyjx);
		// var_dump($honor_list);
		$menuList=M("class")->where("pid=9")->order("ord")->select();
		$this->assign("menuList",$menuList);//2017/4/18郭添加
		$this->display('');
	}
	//企业荣誉类别显示 不再开启新页面
	// public function honor_view(){
	// 	$this->Navigation();
	// 	$this->quick_three($_GET['id']);
	// 	$this->two_advertisement($_GET['id']);
	// 	$this->three_class($_GET['id']);
	// 	$this->three_class_view($_GET['pid']);
	// 	$this->two_title($_GET['id']);
	// 	if($_GET['id']==216){
	// 		$honor_list = M('honor')->where("style=1")->select();
	// 	}elseif ($_GET['id']==217) {
	// 		$honor_list = M('honor')->where("style=2")->select();
	// 	}elseif ($_GET['id']==218) {
	// 		$honor_list = M('honor')->where("style=3")->select();
	// 	}else{
	// 		$honor_list = M('honor')->select();
	// 	}
	// 	$this->assign('honor_list',$honor_list);
	// 	$this->display('');
	// }
	//管理团队
	public function team(){
		$this->Navigation();
		$this->video($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$boss = M('member')->order('num desc')->find();
		$this->assign('boss',$boss);
		$where['num'] = array('neq',$boss['num']);
		$member = M('member')->where($where)->order('num desc')->select();
		$this->assign('member',$member);
		$menuList=M("class")->where("pid=9")->order("ord")->select();
		$this->assign("menuList",$menuList);//2017/4/18郭添加
		$this->display('');
	}
	//关于我们
	public function about_us(){
		$this->Navigation();
		$this->video($_GET['id']);
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$company=M("company")->order("updatetime desc")->select();
		$this->assign("company" ,$company);
		$big=M("about_us")->where("cid=138")->order("addtime desc")->select();
		$this->assign("big",$big);
		$menuList=M("class")->where("pid=9")->order("ord")->select();
		$this->assign("menuList",$menuList);
		// var_dump($menuList);
		$this->display('about-us');
	}
	//投资管理
	public  function invest(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$invest=M("about_us")->where("cid=228")->order("addtime desc")->find();
		$this->assign("invest",$invest);
		$menuList=M("class")->where("pid=9")->order("ord")->select();//2017/4/18郭添加
		$this->assign("menuList",$menuList);
		$this->display('');
	}
	//网络营销
	public  function market(){
		$this->Navigation();
		$this->video($_GET['id']);
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->two_advertisement($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$shop=M("about_us")->where("cid=229")->order("addtime desc")->find();
		$this->assign("shop",$shop);
			$menuList=M("class")->where("pid=9")->order("ord")->select();
		$this->assign("menuList",$menuList);//2017/4/18郭添加
		$this->display('');
	}
	// //合作伙伴
	public  function friend(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$this->two_advertisement($_GET['id']);
		$menuList=M("class")->where("pid=9")->order("ord")->select();
		$this->assign("menuList",$menuList);

		$friendStyle=M("friend")->order("ord asc")->select();
		foreach ($friendStyle as $k=>$vv) {
			$friendSon[$vv['style']][]=$vv;
		}
		// var_dump(array_keys($friendSon));
		$styleName=array_keys($friendSon);
		$this->assign("friendSon",$friendSon);
		
		// $this->assign("styleName",$styleName);
		// $styleValue=array_values($friendSon);
		// var_dump($styleValue);
		// $this->assign("styleValue",$styleValue);
		// var_dump(reset($friendSon));
		// var_dump(key($friendSon));
		// var_dump($friendSon);
		// $this->assign("son",key($friendSon));
		// $this->assign("sonValue",reset($friendSon));

		// foreach ($friendSon as $key => $vv) {
		// 	// $this->assign("friendSon",key($friendSon));
		// 	key($friendSon);
		// }

		// $m=M('friend');
		// foreach ($styleName as  $v) {
		// 	$where['style']=array("like","%".$v."%");

		// 	$p=getpage($m,$where,12);//每页显示条数
		// 	$friend = $m->field(false)->where($where)->order("ord asc")->select();
		// 	$this->friend=$friend;
		// 	$page=$this->page=$p->show();
		// }
		

		// $sql=M("friend")->getLastSql();
		// var_dump($sql);
		// var_dump($friend);
		// $this->assign('friend',$friend);
		$this->display('');
	 }
	
	//联系我们
	public  function contact(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$this->two_advertisement($_GET['id']);
		$contact=M("company")->order("updatetime desc")->find();
		$this->assign("contact",$contact);
		$menuList=M("class")->where("pid=9")->order("ord")->select();//2017/4/18郭添加
		$this->assign("menuList",$menuList);
		$this->display('');
	}
	//人力资源
	public function rec(){
		$this->Navigation();
		$this->video($_GET['id']);
		$this->three_class($_GET['id']);
		$this->two_title($_GET['id']);
		$this->assign("getId",$_GET['id']);
		$this->two_advertisement($_GET['id']);
		$rec=M("class")->where('id=148')->find();
		$this->assign('rec',$rec);

		$yf_list=M('recruit')->where("style=1")->order("ord asc")->select();
		$this->assign("yf_list",$yf_list);
		$shop_list=M('recruit')->where("style=2")->order("ord asc")->select();
		$this->assign("shop_list",$shop_list);
		$services_list=M('recruit')->where("style=3")->order("ord asc")->select();
		$this->assign("services_list",$services_list);
		$xd_list=M('recruit')->where("style=4")->order("ord asc")->select();
		$this->assign("xd_list",$xd_list);

		$menuList=M("class")->where("pid=9")->order("ord")->select();
		$this->assign("menuList",$menuList);
		$this->display('');
	}
	//职位列表
	public function rec_list(){
		$this->Navigation();
		$this->three_class($_GET['id']);
		// 头部标题
		$title = M('class')->where("id={$_GET['lid']}")->find();
		$this->assign('title',$title);
		$this->two_advertisement($_GET['id']);
		$rec=M("class")->where('id=148')->find();
		$this->assign('rec',$rec);
		//$where['cid']=148;
		$where['id']=$_GET['id'];
		$list=M('recruit')->where($where)->select();
		$this->assign("list",$list);
		$menuList=M("class")->where("pid=9")->order("ord")->select();
		$this->assign("menuList",$menuList);
		$this->assign("lid",$_GET['lid']);
		$this->display('');
	}
	//添加求职信息
	public function rec_add(){
		$menuList=M("class")->where("pid=9")->order("ord")->select();
		$this->assign("menuList",$menuList);
		// 头部标题
		$httpStr=$_SERVER['HTTP_REFERER'];
		$id=array_pop(explode('=',$httpStr));
		$title = M('class')->where("id={$id}")->find();
		$this->assign('id',$id);
		$this->assign('title',$title);
		if(IS_POST){
			$this->upload(); 
			$data['job_name']=$_POST['job_name'];
			$data['city']=$_POST['city'];
			$data['name']=$_POST['username'];
			$data['sex']=$_POST['sex'];
			$data['telphone']=$_POST['telphone'];
			$data['email']=$_POST['email'];
			$data['school']=$_POST['school'];
			$data['major']=$_POST['major'];
			$data['record']=$_POST['record'];
			$data['upfile']=$_POST['upfile'];
			//发送邮件
			$sex=($_POST['sex']==0)?"女":"男";
			$body= "求职者姓名：". $_POST['username'] ."<br>".
					"性别：".$sex."<br>".
					"申请职位：".$_POST['job_name']."<br>".
					"申请邮箱：".$_POST['email']."<br>".
					"联系方式：".$_POST['telphone']."<br>".
					"学校：".$_POST['school']."<br>".
					"专业：".$_POST['major']."<br>".
					"学历：". $_POST['record'] ."<br>".
					"简历附件：".$_POST['upfile'] ."<br>";

					$_POST['upfile']=iconv("utf-8","gb2312//IGNORE",$_POST['upfile']);//解决文件名中文问题
$a = $this->think_send_mail('zhaopin@arcvideo.com','人事专员',$_POST['username'].'_求职申请',$body,$_POST['upfile']);
			 // zhaopin@arcvideo.com
			 $res=M('resume')->add($data);
			if($res){
				echo "<script>alert('申请成功！');</script>";
				echo "<script>window.location.href='rec?id=33';</script>";
			}
		}else{
		$this->Navigation();
		$this->three_class($_GET['id']);

		$this->two_advertisement($_GET['id']);
		$rec=M("class")->where('id=148')->find();
		$this->assign('rec',$rec);
		//$where['cid']=148;
		$where['id']=$_GET['id'];
		$list=M('recruit')->where($where)->select();
		$this->assign("list",$list);
		}
		
		$this->display('');
	}
	//发送简历到公司邮箱
	/**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题 
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean 
 */
public function think_send_mail($to, $name, $subject, $body, $attachment=null){
    $config = C('THINK_EMAIL');
    vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
    vendor('PHPMailer.class#smtp'); //从PHPMailer目录导class.smtp.php类文件
    $mail             = new \PHPMailer(); //PHPMailer对象
    $mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();  // 设定使用SMTP服务
    $mail->SMTPDebug  = 0;                     // 关闭SMTP调试功能
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl';                 // 使用安全协议
    $mail->Host       = $config['SMTP_HOST'];  // SMTP 服务器
    $mail->Port       = $config['SMTP_PORT'];  // SMTP服务器的端口号
    $mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名
    $mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码
    $mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
    $replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
    $replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];

    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($to);

    // $mail->AddAttachment('E:/wamp/www/arc1/public/Uploads/resume/'.$attachment);	//本地 添加附件
    $mail->AddAttachment('./Public/Uploads/resume/'.$attachment);	//服务器\Public\Uploads\resume
    return $mail->Send() ? true : $mail->ErrorInfo;

}

	//视频列表
	public function video($id){
		$video=M("video")->where("cid={$id}")->order("addtime desc")->limit("4")->select();
		$this->assign("video",$video);
	}
	//切换网页标题
	public  function two_title($id){	
	    if($_GET['cid']){
	    	$title = M('class')->where("id={$_GET['cid']}")->find();
	    }else{
	    	$title = M('class')->where("id={$_GET['id']}")->find();
	    }	
		$this->assign('title',$title);
	}
	
	//主导航
	public function Navigation(){
		$this->logo();
		$class = M('class')->where("pid='0'")->select();
		// 底部地址电话
		$contact=M("company")->order("updatetime desc")->find();
		$this->assign("contact",$contact);
		/* 当虹云标题换位置*/ 
		$product=$class[0];
		array_shift($class[0]);
		$class[0]=$class[3];
		array_unshift($class, $product);
		unset($class[4]);
		$this->assign('class',$class);
		/*系统信息*/ 
		$system = M('system')->find();
		$this->assign('system',$system);
		/*顶部用户名*/
		$this->assign("account",$_SESSION['user']['name']);
		/*导航栏目父id*/ 
		if($_SERVER['QUERY_STRING']){
			$sid=array_pop(explode('=', $_SERVER['QUERY_STRING']));
			if(is_numeric($sid)){
				$pid = M('class')->where("id={$sid}")->getField('pid');
				$this->assign('currentId',$pid);
				// var_dump($pid);
			}
		}
	}
	//三级导航
	public function three_class($id){
		$three = M('class')->where("pid={$_GET['id']}")->select();
		$this->assign('three',$three);
	}
	//三级页面下的三级导航
	public function three_class_view($pid){
		$three = M('class')->where("pid={$pid}")->select();
		$this->assign('three',$three);
	}
	//logo
	public function logo(){
		$logo = M('index')->where("label='logo'")->getField('other');
		$this->assign('logo',$logo);
	}
	//二级栏目广告
	public function two_advertisement($id){
		$two_pic = M('class')->where("id={$id}")->find();
		// var_dump($two_pic);
		$this->assign('two_pic',$two_pic);
	}
	//栏目文章
	public function two_article($id){
		$two_article = M('article')->where("cid={$id}")->select();
		$this->assign('two_article',$two_article);
	}
	//查新闻
	public function news_article($id){
		$news_article = M('article')->where("id={$id}")->find();
		$this->assign('news_article',$news_article);
	}
	//相关栏目应用
	public function other_class($id){
		$other = M('class')->where("pid={$id}")->order("ord2 asc")->select();
		// var_dump($other);//exit;
		$this->assign('other',$other);
	}
	// 二级栏目(三级页面用)
	public function second_title($id){
		$styleOne=M("class")->where("id={$id}")->find();
		$this->assign("styleOne",$styleOne);
	}
	//三级栏目下面的推送
	public function quick_three($id){
		$quick_three = M('quick_three')->where("cid={$id}")->select();
		$this->assign('quick_three',$quick_three);
		//var_dump($quick_three);

	}
	//分页函数
	  // public function page(){
	  // 		//$m $oject=> object 必须有
	  // 		$p=getpage($m,$where,10);//每页显示条数
			// 	$object=$m->field(true)->where($where)->order($order)->select();
			// 	$this->object=$object;
			// 	$this->page=$p->show();
   //        } 
			//文件分页列表
			public function filedown(){
				//var_dump($_GET['id']);
				$m=M('file_down');
				$this->Navigation();
				$this->three_class($_GET['id']);
				$this->two_title($_GET['id']);
				$this->two_advertisement($_GET['id']);
				$this->assign("getId",$_GET['id']);
				if($_GET['id']==150){
					// dkhj报错暂且隐藏
					// $detail=$m->where()->order("addtime desc")->find();
				}else{
					$detail=$m->where("f_id={$_GET['id']}")->find();
				}
				//var_dump($detail);
				$arr=explode('/', $_SERVER['SCRIPT_NAME']);
	         	$arr2=array_pop($arr);
	         	$detail['rootPath']=implode("/", $arr);
				//var_dump($_SERVER);
				//var_dump($detail['rootPath']);//return false;
				$where="cid=153";
				$p=getpage($m,$where,5);//每页显示条数
				$fileList=$m->field(true)->where("cid=153")->order('addtime desc')->select();
				$this->fileList=$fileList;
				$this->page=$p->show();
				//var_dump($fileList);
				$this->assign('detail',$detail);
				$this->assign('fileList',$fileList);
				$menuList=M("class")->where("pid=8")->order("ord")->select();
		        $this->assign("menuList",$menuList);//2017/4/18郭添加
				$this->display('');
			}
        
          //文档下载 pdf 压缩包
         public function downLoad(){
         	//判断是否登录，不登录本不能下载
				if(empty($_SESSION['user'])){
					$this->redirect("Index/login");
				}else{
					$sh=M('user')->where("id={$_SESSION['user']['id']}")->getField('state');
					if ($sh) {
						header("Content-type: text/html; charset=utf-8"); 
						echo "<script>alert('管理员审核通过后才可下载！'); window.history.go(-1); ;</script>";
					}else{
					$fileArr=M('file_down')->where("f_id={$_GET['id']}")->find();
		         	$file_name= $fileArr['pic'];
		         	$file_name=iconv("utf-8","gb2312",$file_name);//解决文件名中文问题
		         	$arr=explode('/', $_SERVER['SCRIPT_FILENAME']);
		         	array_pop($arr);
		         	$file_dir=implode("/", $arr);
		         	$file_dir=$file_dir."/Public/Uploads/content/";//下载文件存放目录 
					if (! file_exists ( $file_dir. $file_name )) {  // File Exists? 
						header("Content-type: text/html; charset=utf-8");   
					    //echo "File not found!"; 
					    echo "<script>alert('下载失败,文件未找到或文件格式不支持下载！');window.location.href='filedown/id/150';</script>"; 
					    return false;
					} else {   
					// Must be fresh start 
					  if( headers_sent() ) 
					    die('Headers Sent'); 

					  // Required for some browsers 
					  if(ini_get('zlib.output_compression')) 
					    ini_set('zlib.output_compression', 'Off'); 
					    // Parse Info / Get Extension 
					    $fsize = filesize( $file_dir. $file_name ); 
					    $path_parts = pathinfo( $file_dir. $file_name ); 
					    $ext = strtolower($path_parts["extension"]); 
					    
					    // Determine Content Type 
					    switch ($ext) { 
					      case "pdf": $ctype="application/pdf"; break; 
					      case "exe": $ctype="application/octet-stream"; break; 
					      case "zip": $ctype="application/zip"; break; 
					      case "rar": $ctype="application/rar"; break; 
					      case "doc": $ctype="application/msword"; break; 
					      case "docx": $ctype="application/msword"; break;
					      case "xls": $ctype="application/vnd.ms-excel"; break; 
					      case "ppt": $ctype="application/vnd.ms-powerpoint"; break; 
					      case "gif": $ctype="image/gif"; break; 
					      case "png": $ctype="image/png"; break; 
					      case "jpeg": 
					      case "jpg": $ctype="image/jpg"; break; 
					      default: $ctype="application/force-download"; 
					    } 

					    header("Pragma: public"); // required 
					    header("Expires: 0"); 
					    header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
					    header("Cache-Control: private",false); // required for certain browsers 
					    header("Content-Type: $ctype"); 
					  	header("Content-Disposition: attachment; filename=". $fileArr['title'].'.'. $ext.";" );
					    header("Content-Transfer-Encoding: binary"); 
					    header("Content-Length: ".$fsize); 
					    ob_clean(); 
					    flush(); 
					    readfile( $file_dir. $file_name  ); 

					 //    $file = fopen ( $file_dir . $file_name, "r" ); //打开文件  
					 //    print_r($file); 
					 //    // header('Content-Type: application/octet-stream');
					 //    header('Content-Type: application/pdf'); // PDF文件
						// header('Content-Disposition: attachment; filename='. $file_name);
						// header('Content-Transfer-Encoding: binary');


					    
					 //    // header('Content-Type: application/zip'); // ZIP文件
					 //    // Header ( "Content-type: application/octet-stream" );  //输入文件标签     
					 //    // Header ( "Accept-Ranges: bytes" );    
					 //    Header ( "Accept-Length: " . filesize ( $file_dir . $file_name ) );    
					 //    // Header ( "Content-Disposition: attachment; filename=" . $file_name );    
					 //    //输出文件内容     
					 //    //读取文件内容并直接输出到浏览器    
					 //    echo fread ( $file, filesize ( $file_dir . $file_name ) );    
					 //    fclose ( $file ); 	
					 //    echo "<script>alert('1下载完成！');window.location.href='#';</script>";  
					 //    exit ();    


			         } 
			        }
				}
         	
      	 }
      	 //图片上传 简历上传
	private function upload(){
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		//$upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->exts      =       array('jpg','jpeg', 'gif', 'png', 'zip','rar','pdf','doc','docx','xls','xlsx','txt');// 设置附件上传类型
		//$upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
		$upload->rootPath  =     './Public/Uploads/resume/'; // 设置附件上传根目录
		//$upload->saveName  =     '';//设置文件命名规则
		//$upload->savePath  =     $path; // 设置附件上传（子）目录
		$upload->autoSub  =      false; // 拒绝子目录创建
		// 上传文件 
		$info   =   $upload->upload();
		//var_dump($info);exit;
		if(!$info) {// 上传错误提示错误信息
			//$this->error($upload->getError());
		}else{// 上传成功
			//获取图片的名称  
			$picname=$info['upfile']['savename'];
		}
		$_POST['upfile']=$picname;
		
	}
	

}