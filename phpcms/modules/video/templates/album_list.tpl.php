<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<script type="text/javascript" src="<?php echo JS_PATH?>jquery.sgallery.js"></script>
<link href="<?php echo JS_PATH?>swfupload/swfupload.css" rel="stylesheet" type="text/css" />
<div class="bk20"></div>
<div class="pad-lr-10">
<form name="searchform" action="" method="get" >
<input type="hidden" value="video" name="m">
<input type="hidden" value="video" name="c">
<input type="hidden" value="video2content" name="a">
<table width="100%" cellspacing="0" class="search-form">
    <tbody>
		<tr>
		<td><div class="explain-col"> <?php echo L('video_title');?>  <input type="text" value="<?php echo $title?>" class="input-text" name="name">  <?php echo L('upload_time');?> <?php echo form::date('starttime',$_GET['starttime'])?>- <?php echo form::date('endtime',$_GET['endtime'])?> <label title="<?php echo L('site_upload');?>"><?php echo L('original');?> <input type="checkbox" name="userupload" value="1" id="userupload"<?php if($userupload){ ?> checked<?php }?>></label>  <input type="submit" value="<?php echo L('search')?>" class="button" name="dosubmit">
		</div>
		</td>
		</tr>
    </tbody>
</table>
</form>
<div class="bk20 hr"></div>
<ul class="attachment-list contentList" id="fsUploadProgress">
<?php foreach($infos as $r) {?>
<li>
	<div class="img-wrap">
		<a href="javascript:;" onclick="javascript:album_cancel(this,'<?php echo $r['videoid']?>','<?php echo $r['picpath']?>')"><div class="icon"></div><img src="<?php echo $r['picpath']?>" vid="<?php echo $r['videoid'];?>" path="<?php echo $r['picpath'];?>" width="120" title="<?php echo $r['title']?>"/></a>
	</div>
</li>
<?php } ?>
</ul>
<div id="pages" class="text-c"> <?php echo $pages?></div>
<div id="video-paths" class="hidden"></div>
<div id="video-ids" class="hidden"></div>
<div id="video-status-del" class="hidden"></div>
<div id="video-name" class="hidden"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	set_status_empty();
});	
function set_status_empty(){
	$('#video-paths').html('');
	$('#video-ids').html('');
	$('#video-name').html('');
}
function album_cancel(obj,id,source){
	var src = $(obj).children("img").attr("path");
	var vid = $(obj).children("img").attr("vid");
	var filename = $(obj).children("img").attr("title");
	if($(obj).hasClass('on')){
		$(obj).removeClass("on");
		var imgstr = $("#video-paths").html();
		var length = $("a[class='on']").children("img").length;
		var strs = filenames = vids = '';
			$.get('index.php?m=video&c=video&a=swfupload_json_del&id='+id+'&src='+source);
		for(var i=0;i<length;i++){
			strs += '|'+$("a[class='on']").children("img").eq(i).attr('path');
			vids += '|'+$("a[class='on']").children("img").eq(i).attr('vid');
			filenames += '|'+$("a[class='on']").children("img").eq(i).attr('title');
		}
		$('#video-paths').html(strs);
		$('#video-ids').html(vids);
		$('#video-name').html(filenames);
	} else {
		var num = $('#video-paths').html().split('|').length;
		$(obj).addClass("on");
		$.get('index.php?m=video&c=video&a=swfupload_json&id='+id+'&src='+source);
		$('#video-paths').append('|'+src);
		$('#video-ids').append('|'+vid);
		$('#video-name').append('|'+filename);
	}
}
</script>