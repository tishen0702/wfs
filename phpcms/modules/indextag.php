<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_class('admin','admin',0);
pc_base::load_sys_class('form', '', 0);
class indextag extends admin {
  private $db, $db_data, $db_content;
  function __construct(){
    parent::__construct();
    $this->sites = pc_base::load_app_class('sites');
    $this->siteid = $this->get_siteid();
    $this->db = pc_base::load_model('indextag_model');
  }

  public function init(){
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $catid = isset($_GET['catid']) ? (int)$_GET['catid'] : -1;
    $per = 20;
    if($catid >= 0){
      $indextags = $this->db->listinfo("`catid` = $catid", '`catid` ASC, `listorder` ASC, `id` ASC', $page, $per);
    } else {
      $indextags = $this->db->listinfo('1', '`catid` ASC, `listorder` ASC, `id` ASC', $page, $per);
    }
    $pages = $this->db->pages;
    $category = getcache('category_content_'.$this->siteid,'commons');
    $tree = pc_base::load_sys_class('tree');
    $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
    $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
    $tree->init($category);
    $str = "<option value='\$id'>\$spacer\$catname\$display_icon</option>";
    $addcate = $tree->get_tree(0, $str);
    $show_dialog = true;
    include $this->admin_tpl('indextag_list');
  }

  public function add(){
    $keycat = isset($_POST['keycat']) ? (int)$_POST['keycat'] : -1;
    $keyval = isset($_POST['keyval']) ? $_POST['keyval'] : '';
    if($keyval == ''){
      $this->res(1);
    } elseif($keycat < 0) {
      $this->res(2);
    } else {
      $indextag = array();
      $indextag['name'] = $keyval;
      $indextag['catid'] = $keycat;
      $indextag['listorder'] = 0;
      $tagid = $this->db->insert($indextag,true,true);
      if($tagid > 0){
        $this->res(0);
      } else {
        $this->res(3);
      }
    }
  }

  public function setorder(){
    $data = isset($_POST['data']) ? stripslashes($_POST['data']) : '';
    if($data != ''){
      $data = json_decode($data, true);
      foreach($data as $k => $v){
        $this->db->update(array('listorder'=>(int)$v),array('id'=>(int)$k));
      }
      $this->res(0);
    } else {
      $this->res(1);
    }
  }

  public function delete(){
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if($id > 0){
      $this->db->delete(array('id'=>$id));
      $this->res(0);
    } else {
      $this->res(1);
    }
  }

  public function res($stat = 0, $data = array()){
    $res = array();
    $res['stat'] = $stat;
    $res['data'] = $data;
    echo json_encode($res);
    exit;
  }
}