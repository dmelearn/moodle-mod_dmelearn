<?php
// Minified Version of template_js.php
// Setup Variables
$coursepath = $page_data['data']['cert_data']['course_path'];
?>
<script>!function(e,s,o){var a=e.routes;a.base="<?=$constants['base_url']?>",a.course.path=a.base+"courses/<?=$coursepath?>/",a.course.img=a.base+"images/",a.course.resources=a.course.path+"resources/",a.course.images=a.course.resources+"images/"}(window),$(function(){var e="elmo_ajax_ws.php?request=";$.ajaxSetup({global:!0,beforeSend:function(s,o){var a=o.url,t=a.split("/client_api");1===$(t).length&&(t=null),null!==t?o.url=e+t[1]:0===a.indexOf("<?=$constants['elmo_env']?>")&&(o.url=e+a)}}),null!==typeof $.fn.elmo_multipleChoice,$(".question").elmo_multipleChoice({imagePath:window.routes.img,courseImages:window.routes.course.images});var s=$(".reset_button"),o=this,a=function(){s.on("click",function(){s.unbind("click");var e=$.ajax({type:"POST",url:"elmo_ajax_ws_reset.php",data:{course_path:"<?=$coursepath?>",user_id:"<?=$page_data['data']['cert_data']['user_id']?>"},success:function(e,o){1==e?(s.parents(".modal").modal("hide"),window.location.href="<?=$content_url?>",a()):(s.parents(".modal").modal("hide"),alert("Sorry something went wrong. This course assessment could not be reset."),a())}})})};a()});</script>