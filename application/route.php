<?php

use think\Route;
//首页导航
Route::get("api/:version/banner","api/:version.Banner/getBanner");

//动态列表
Route::get("api/:version/info/list","api/:version.Info/getInfoList");
Route::get("api/:version/info/clublist","api/:version.Info/getInfoClubList");
Route::get("api/:version/info/new","api/:version.Info/getNewInfo");

Route::get("api/:version/info/isnew","api/:version.Activity/getIsNewActList");
Route::get("api/:version/info/activity","api/:version.Info/getActivityList");
Route::get("api/:version/info/myfollow","api/:version.Info/getMyFollowInfo");

//获取社团/组织
Route::get("api/:version/club/sort_club","api/:version.Club/getClubSortList");
Route::get("api/:version/club/info","api/:version.Club/getClubInfo");
Route::get("api/:version/club/list","api/:version.Club/getClubList");
Route::get("api/:version/club/:id","api/:version.Club/getOne",[],['id'=>'\d+']);

//搜索社团或组织
Route::post("api/:version/search","api/:version.Club/getSearchClubList");

//获取分类
Route::get("api/:version/club/sort","api/:version.Club/getSortList");

//获取部门
Route::get("api/:version/clubson/list","api/:version.Clubson/getClubSonList");

//新闻
Route::get("api/:version/new/:id","api/:version.News/getOne",[],['id'=>'\d+']);

//活动
Route::get("api/:version/activity/:id","api/:version.Activity/getOne",[],['id'=>'\d+']);
Route::get("api/:version/activity/hot","api/:version.Activity/getHotApplyActivity");//修改
Route::get("api/:version/activity/acthot","api/:version.Activity/getHotActivity");

//申请活动
Route::post("api/:version/apply/submit","api/:version.Apply/submitApply");
Route::get("api/:version/apply/by_user","api/:version.Apply/getApplyByUser");

//分类
Route::get("api/:version/sort","api/:version.Sort/getSort");

//登录，token令牌等
Route::post("api/:version/token/user","api/:version.Token/getToken");
Route::post("api/:version/token/verify","api/:version.Token/verifyToken");
Route::post("api/:version/login/dologin","api/:version.Login/doLogin");
Route::get("api/:version/login/loginout","api/:version.Login/loginOut");

//关注
Route::post("api/:version/follow/:id","api/:version.Follow/addFollow",[],['id'=>'\d+']);
//收藏
Route::post("api/:version/collect/:id","api/:version.Collect/addCollect",[],['id'=>'\d+']);

//我的信息
Route::get("api/:version/myclub","api/:version.MyInfo/getMyClubInfo");
Route::get("api/:version/myfollow","api/:version.MyInfo/getMyFollow");
Route::get("api/:version/mycollect","api/:version.MyInfo/getMyCollect");
Route::get("api/:version/myinfo","api/:version.MyInfo/getMyInfo");
Route::get("api/:version/myapply","api/:version.Apply/getApplyByUser");
Route::get("api/:version/msg/list","api/:version.MyInfo/getMyMsgList");
Route::get("api/:version/msg/:id","api/:version.MyInfo/getMsgOne",[],['id'=>'\d+']);
Route::get("api/:version/read/:id","api/:version.MyInfo/isRead",[],['id'=>'\d+']);
Route::get("api/:version/openmsg/:id","api/:version.MyInfo/openMsg",[],['id'=>'\d+']);

//发送订阅消息
Route::get("api/:version/send/meeting/:id","api/:version.Send/sendMeetingMsg",[],['id'=>'\d+']);
Route::get("api/:version/send/activity/:id","api/:version.Send/sendActivityMsg",[],['id'=>'\d+']);
