window.onload=function(){var a=1e3,b="ajax/"+type+".php",c="result_mains",d=1,e=!1;$("#"+c).load(b,{page:d,q:q},function(){d++}),$(window).scroll(function(){$(window).scrollTop()+$(window).height()==$(document).height()&&d<=a&&e===!1&&(e=!0,$(".animation_image").show(),$.post(b,{page:d,q:q},function(a){$("#"+c).append(a),$(".animation_image").hide(),d++,e=!1}).fail(function(a,b,c){alert(c),$(".animation_image").hide(),e=!1}))})};