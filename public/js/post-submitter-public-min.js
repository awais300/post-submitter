jQuery(document).ready(function(a){a("#post-form").submit(function(b){b.preventDefault();a("#notify").hide();disable_button(a);ajax_start(a);b=a(this);submit_post(a,b)});a(document).on("click","#closebtn",function(b){b.preventDefault();a(this).parent().hide()})});
function submit_post(a,b){file_data=a("#file").prop("files")[0];form_data=new FormData;form_data.append("featured_image",file_data);form_data.append("_ajax_nonce",LOCAL_OBJ._ajax_nonce);form_data.append("action","save_form");form_data.append("post_data",b.serialize());a.ajax({url:LOCAL_OBJ.ajax_url,type:"POST",dataType:"JSON",contentType:!1,processData:!1,data:form_data,success:function(c){1==c.error?(html='<div class="alert"><span id="closebtn" class="closebtn">\u00d7</span>'+c.message+"</div>",
a("#notify").html(html).show()):0==c.error&&(html='<div class="alert success"><span id="closebtn" class="closebtn">\u00d7</span>'+c.message+"</div>",a("#notify").html(html).show(),b[0].reset());enable_button(a);ajax_stop(a)},error:function(c,e,d){alert(d);enable_button(a);ajax_stop(a)}})}function disable_button(a){a("#submit").prop("disabled",!0)}function enable_button(a){a("#submit").prop("disabled",!1)}function ajax_start(a,b){a("#post #loading").show()}
function ajax_stop(a,b){a("#post #loading").hide()};