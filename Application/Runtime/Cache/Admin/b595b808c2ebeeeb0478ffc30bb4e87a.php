<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/arc/favicon.ico" >
<LINK rel="Shortcut Icon" href="/arc/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="/arc/Public/H-ui/lib/html5.js"></script>
<script type="text/javascript" src="/arc/Public/H-ui/lib/respond.min.js"></script>
<script type="text/javascript" src="/arc/Public/H-ui/lib/PIE_IE678.js"></script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="/arc/Public/H-ui/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/arc/Public/H-ui/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/arc/Public/H-ui/lib/Hui-iconfont/1.0.7/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/arc/Public/H-ui/lib/icheck/icheck.css" />
<link rel="stylesheet" type="text/css" href="/arc/Public/H-ui/static/h-ui.admin/skin/blue/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/arc/Public/H-ui/static/h-ui.admin/css/style.css" />
<!--[if IE 6]>
<script type="text/javascript" src="/arc/Public/H-ui/lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>当虹科技网站后台管理系统</title>
<meta name="keywords" content="">
<meta name="description" content="">
</head>
<body>
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"><span class="logo navbar-slogan f-l mr-10 hidden-xs">ARCVIDEO TECH 当虹科技网站管理系统</span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a> 
		
			<!--<nav class="nav navbar-nav">
				<ul class="cl">
					<li class="dropDown dropDown_hover"><a href="javascript:;" class="dropDown_A"><i class="Hui-iconfont">&#xe600;</i> 新增 <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" onclick="article_add('添加资讯','/arc/yeeadmin/Index/article-add')"><i class="Hui-iconfont">&#xe616;</i> 资讯</a></li>
							<li><a href="javascript:;" onclick="picture_add('添加资讯','/arc/yeeadmin/Index/picture-add')"><i class="Hui-iconfont">&#xe613;</i> 图片</a></li>
							<li><a href="javascript:;" onclick="product_add('添加资讯','/arc/yeeadmin/Index/product-add')"><i class="Hui-iconfont">&#xe620;</i> 产品</a></li>
							<li><a href="javascript:;" onclick="member_add('添加用户','/arc/yeeadmin/Index/member-add','','510')"><i class="Hui-iconfont">&#xe60d;</i> 用户</a></li>
						</ul>
					</li>
				</ul>
			</nav>-->
			<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
				<ul class="cl">
				<!-- <li><a href="/" target="_blank">站点首页</a></li> -->
					<li>欢迎您！</li>
					<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?php echo ($_SESSION['adminuser']['name']); ?> <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<!--<li><a href="#">个人信息</a></li>-->
							<!-- <li><a href="#">切换账户</a></li> -->
							<li><a href="/arc/yeeadmin/Index/session_del">退出</a></li>
						</ul>
					</li>
					
					<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
							<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
							<li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
							<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
							<li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
							<li><a href="javascript:;" data-val="orange" title="绿色">橙色</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header>
<aside class="Hui-aside">
	<input runat="server" id="divScrollValue" type="hidden" value="" />
	<div class="menu_dropdown bk_2">
		<dl id="menu-class">
			<dt><i class="Hui-iconfont">&#xe620;</i> 栏目管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
			<?php if(is_array($class_list)): foreach($class_list as $key=>$vo): ?><ul>
					<?php if($vo["id"] != 6): ?><li><a data-href="/arc/yeeadmin/Class/class_list?id=<?php echo ($vo["id"]); ?>" data-title="<?php echo ($vo["name"]); ?>" href="javascript:void(0)"><?php echo ($vo["name"]); ?></a></li><?php endif; ?>
					
				</ul><?php endforeach; endif; ?>
			<ul>
				<li><a data-href="/arc/yeeadmin/Class/class_navigation" data-title="主导航管理" href="javascript:void(0)">主导航管理</a></li>
			</ul>
			</dd>
		</dl>
		<dl id="menu-product">
			<dt><i class="Hui-iconfont">&#xe620;</i> 内容管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
			<?php if(is_array($class_list)): foreach($class_list as $key=>$vo): ?><ul>
					<?php if($vo["id"] != 6): ?><li><a data-href="/arc/yeeadmin/Article/article_list?id=<?php echo ($vo["id"]); ?>" data-title="<?php echo ($vo["name"]); ?>" href="javascript:void(0)"><?php echo ($vo["name"]); ?></a></li><?php endif; ?>

				</ul><?php endforeach; endif; ?>
				<!--<ul>
					<li><a data-href="/arc/yeeadmin/Index/article_list" data-title="文章列表" href="javascript:void(0)">文章列表</a></li>
				</ul>
				<ul>
					<li><a data-href="/arc/yeeadmin/Index/article_add" data-title="文章添加" href="javascript:void(0)">文章添加</a></li>
				</ul>-->
			</dd>
		</dl>
		<!-- <dl id="menu-product">
			<dt><i class="Hui-iconfont">&#xe620;</i> 视频管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
				<?php if(is_array($class_list)): foreach($class_list as $key=>$vo): ?><li><a data-href="/arc/yeeadmin/Video/video_list?id=<?php echo ($vo["id"]); ?>" data-title="<?php echo ($vo["name"]); ?>" href="javascript:void(0)"><?php echo ($vo["name"]); ?></a></li><?php endforeach; endif; ?>	
				</ul>
			</dd>
		</dl> -->
		<dl id="menu-admin">
			<dt><i class="Hui-iconfont">&#xe62d;</i> 内部管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a data-href="/arc/yeeadmin/Admin/admin_list" data-title="后台管理员" href="javascript:void(0)">后台管理员</a></li>
					<li><a data-href="/arc/yeeadmin/Admin/node_list" data-title="节点管理" href="javascript:void(0)">节点管理</a></li>
				</ul>
			</dd>
		</dl>
		<dl id="menu-admin">
			<dt><i class="Hui-iconfont">&#xe62d;</i> 其他管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a data-href="/arc/yeeadmin/Other/member_list" data-title="成员管理" href="javascript:void(0)">成员管理</a></li>
					<li><a data-href="/arc/yeeadmin/Other/recruit_list" data-title="招聘信息管理" href="javascript:void(0)">招聘信息管理</a></li>
					<li><a data-href="/arc/yeeadmin/Other/resume_list" data-title="求职信息管理" href="javascript:void(0)">求职信息管理</a></li>
					<li><a data-href="/arc/yeeadmin/Other/honor_list" data-title="企业荣誉管理" href="javascript:void(0)">企业荣誉管理</a></li>
					<li><a data-href="/arc/yeeadmin/Other/company_detail" data-title="企业介绍管理" href="javascript:void(0)">企业简介管理</a></li>
					<li><a data-href="/arc/yeeadmin/Other/online_list" data-title="在线交流管理" href="javascript:void(0)">在线交流管理</a></li>
					<li><a data-href="/arc/yeeadmin/Other/friend_list" data-title="客户/伙伴" href="javascript:void(0)">合作伙伴</a></li>
					<li><a data-href="/arc/yeeadmin/Other/user_list" data-title="会员管理" href="javascript:void(0)">会员管理</a></li>
				</ul>
			</dd>
		</dl>
		<dl id="menu-tongji">
			<dt><i class="Hui-iconfont">&#xe61a;</i> 首页推荐管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd> <li><a href="javascript:void(0)"></a></li>
				<ul>
					<li><a data-href="/arc/yeeadmin/Home/carousel" data-title="首页轮播图" href="javascript:void(0)">首页轮播图</a></li>
					<li><a data-href="/arc/yeeadmin/Home/cloud" data-title="首页产品中心" href="javascript:void(0)">首页产品中心</a></li>
					<li><a data-href="/arc/yeeadmin/Home/quick_list" data-title="首页解决方案" href="javascript:void(0)">首页解决方案</a></li>
					<li><a data-href="/arc/yeeadmin/Home/quick_al" data-title="首页成功案例" href="javascript:void(0)">首页成功案例</a></li>
					<li><a data-href="/arc/yeeadmin/Home/news_list" data-title="首页新闻中心" href="javascript:void(0)">首页新闻中心</a></li>
					<!--<li><a data-href="/arc/yeeadmin/Home/quick_video" data-title="视频入口" href="javascript:void(0)">视频入口</a></li>-->
					
				</ul>
			</dd>
		</dl>
		<dl id="menu-system">
			<dt><i class="Hui-iconfont">&#xe62e;</i> 系统管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
			<dd>
				<ul>
					<li><a data-href="/arc/yeeadmin/Bak/system_base" data-title="基本设置" href="javascript:void(0)">基本设置</a></li>
				</ul>
				<ul>
					<li><a data-href="/arc/yeeadmin/Bak/logo" data-title="网站LOGO" href="javascript:void(0)">网站LOGO</a></li>
				</ul>
				<ul>
					<li><a data-href="/arc/yeeadmin/Bak/index" data-title="数据库" href="javascript:void(0)">数据库</a></li>
					<li><a data-href="/arc/yeeadmin/Bak/search" data-title="查找替换" href="javascript:void(0)">查找替换</a></li>
				</ul>
			</dd>
		</dl>
	</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active"><span title="后台首页" data-href="/arc/yeeadmin/Index/welcome">后台首页</span><em></em></li>
			</ul>
		</div>
		<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
	</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="display:none" class="loading"></div>
			<iframe scrolling="yes" frameborder="0" src="/arc/yeeadmin/Index/welcome"></iframe>
		</div>
	</div>
</section>

<div class="contextMenu" id="myMenu1">
	<ul>
		<li id="open">Open </li>
		<li id="email">email </li>
		<li id="save">save </li>
		<li id="delete">delete </li>
	</ul>
</div>


<script type="text/javascript" src="/arc/Public/H-ui/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/arc/Public/H-ui/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="/arc/Public/H-ui/lib/jquery.contextmenu/jquery.contextmenu.r2.js"></script> 
<script type="text/javascript" src="/arc/Public/H-ui/static/h-ui/js/H-ui.js"></script> 
<script type="text/javascript" src="/arc/Public/H-ui/static/h-ui.admin/js/H-ui.admin.js"></script> 
<script type="text/javascript">
$(function(){
	$(".Hui-tabNav-wp").contextMenu('myMenu1', {
		bindings: {
			'open': function(t) {
				alert('Trigger was '+t.id+'\nAction was Open');
			},
			'email': function(t) {
				alert('Trigger was '+t.id+'\nAction was Email');
			},
			'save': function(t) {
				alert('Trigger was '+t.id+'\nAction was Save');
			},
			'delete': function(t) {
				alert('Trigger was '+t.id+'\nAction was Delete')
			}
		}
	});
});
/*资讯-添加*/
function article_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*图片-添加*/
function picture_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*产品-添加*/
function product_add(title,url){
	var index = layer.open({
		type: 2,
		title: title,
		content: url
	});
	layer.full(index);
}
/*用户-添加*/
function member_add(title,url,w,h){
	layer_show(title,url,w,h);
}
</script> 

</body>
</html>