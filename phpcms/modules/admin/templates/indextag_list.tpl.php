<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header');?>
<style type="text/css">
<!--
#div-add {
  padding-bottom: 15px;
}
.indextag-order {
  width: 40px;
  height: 18px;
  padding: 0;
  text-align: center;
  border: 1px solid #aaa;
}
.indextag-delete {
  width: 50px;
  height: 20px;
  border: 1px solid #bd0040;
  background: #e30c5a;
  color: #fff;
  cursor:pointer;
  vertical-align: middle;
}
#setorder, #keyadd {
  width: 50px;
  height: 23px;
  border: 1px solid #aaa;
  background: #eee;
  color: #222;
  text-align: center;
  cursor:pointer;
}
#keyword, #keycat {
  margin: 5px;
  width: 200px;
}
#keyword {
  margin-left: 50px;
}
-->
</style>
<div class="pad_10">
  <div id="div-add">
    <input type="text" size="20" value="" id="keyword" />
    <select id="keycat">
      <option value="-1">--请选择栏目--</option>
      <option value="0">首页</option>
      <?php echo $addcate;?>
    </select>
    <input type="button" value="添 加" id="keyadd" />
  </div>
  <div class="table-list">
    <table width="100%" cellspacing="0">
      <thead>
        <tr>
          <th width="10%" align="center">排序</th>
          <th width="22%" align="left">关键字</th>
          <th width="22%" align="left">
            <input type="hidden" id="selectcatid" value="<?php echo isset($_GET['catid']) ? (int)$_GET['catid'] : -1;?>" />
            <select id="keyselect">
              <option value="-1">--所有栏目--</option>
              <option value="0">首页</option>
              <?php echo $addcate;?>
            </select>
          </th>
          <th width="24%" align="center">操作</th>
        </tr>
      </thead>
      <?php if(empty($indextags)){?>
      <tbody>
        <tr>
          <td colspan="4">没有设置关键字</td>
        </tr>
      </tbody>
      <?php } else {?>
      <tbody>
        <?php foreach($indextags as $indextag){?>
        <tr>
          <td width="10%" align="center">
            <input type="text" size="3" class="indextag-order" value="<?php echo $indextag['listorder'];?>" data-id="<?php echo $indextag['id'];?>" />
          </td>
          <td width="22%" align="left"><?php echo $indextag['name'];?></td>
          <td width="22%" align="left">
            <?php if($indextag['catid'] == 0){echo '首页';}else{echo isset($category[$indextag['catid']]['catname']) ? $category[$indextag['catid']]['catname'] : '';}?>
          </td>
          <td width="24%" align="center"><input type="button" value="删 除" class="indextag-delete" data-id="<?php echo $indextag['id'];?>"/></td>
        </tr>
        <?php }?>
      </tbody>
      <tfoot>
        <tr>
          <td width="10%" align="center"><input type="button" id="setorder" value="排 序" /></td>
          <td colspan="4"><div id="pages"><?php echo $pages?></div></td>
        </tr>
      </tfoot>
      <?php }?>
    </table>
  </div>
</body>
</html>
<script type="text/javascript">
<!--
var indextag = {
  add: function(keycat, keyval){
    $.ajax({
      type:     "POST",
      url:      "/index.php?m=admin&c=indextag&a=add&pc_hash=<?php echo $_GET['pc_hash'];?>",
      dataType: "json",
      data:     {keycat:keycat,keyval:keyval},
      success:  function(res){
        if(res.stat == 0){
          alert("栏目关键字添加成功！");
          window.location.reload();
        } else if(res.stat == 1){
          $("#keyval").focus();
          alert("请先输入关键字。");
        } else if(res.stat == 2){
          $("#keycat").focus();
          alert("请先选择栏目。");
        } else if(res.stat == 3){
          alert("数据库发生错误，请联系管理员。");
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        alert("服务器发生内部错误，请稍后再试...");
      }
    });
  },
  order: function(orderlist){
    $.ajax({
      type:     "POST",
      url:      "/index.php?m=admin&c=indextag&a=setorder&pc_hash=<?php echo $_GET['pc_hash'];?>",
      dataType: "json",
      data:     {data:orderlist},
      success:  function(res){
        if(res.stat == 0){
          alert("排序设置成功！");
          window.location.reload();
        } else {
          alert("排序设置失败，请稍后重试...");
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        alert("服务器发生内部错误，请稍后再试...");
      }
    });
  },
  delete: function(id){
    $.ajax({
      type:     "POST",
      url:      "/index.php?m=admin&c=indextag&a=delete&pc_hash=<?php echo $_GET['pc_hash'];?>",
      dataType: "json",
      data:     {id:id},
      success:  function(res){
        if(res.stat == 0){
          alert("关键字删除成功！");
          window.location.reload();
        } else {
          alert("关键字删除失败，请稍后重试...");
        }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown){
        alert("服务器发生内部错误，请稍后再试...");
      }
    });
  }
};
$(function(){
  var selectcatid = $("#selectcatid").val();
  $("#keyselect").val(selectcatid);
  $("#keyadd").click(function(){
    var keycat = $("#keycat").val();
    var keyval = $("#keyword").val();
    indextag.add(keycat, keyval);
    return false;
  });
  $("#keyselect").change(function(){
    var catid = $(this).val();
    var url = "/index.php?m=admin&c=indextag";
    url += "&catid="+catid;
    url += "&pc_hash=<?php echo $_GET['pc_hash'];?>";
    window.location.href = url;
  });
  $("#setorder").click(function(){
    var orderlist = new Object();
    $(".indextag-order").each(function(){
      var id = $(this).data("id");
      var val = $(this).val();
      orderlist[id] = val;
    });
    orderlist = JSON.stringify(orderlist);
    indextag.order(orderlist);
    return false;
  });
  $(".indextag-delete").click(function(){
    var id = $(this).data("id");
    art.dialog({
      width: 300,
      height: 50,
      title: '删除关键字',
      content: '是否确认删除关键字？',
      okValue: '确定',
      ok: function(){
        this.close();
        indextag.delete(id);
        return false;
      },
      cancelValue: '取消',
      cancel: function(){}
    });
  });
});
//-->
</script>