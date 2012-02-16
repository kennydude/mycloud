/* dependency helper */
function ddo(on, name){
	return "*[data-depends-"+on+"=\"" + name + "\"], *[data-depends-"+on+"=\"" + name + "\"] input, *[data-depends-"+
		on+"=\"" + name + "\"] select";
}

/* checkbox dependency function */
function checkb(){
	if($(this).attr("checked")){
		$(ddo("off", $(this).attr("name")) ).attr("disabled", "disabled");
		$(ddo("on", $(this).attr("name")) ).removeAttr("disabled");
	} else{
		$(ddo("off", $(this).attr("name")) ).removeAttr("disabled");
		$(ddo("on", $(this).attr("name")) ).attr("disabled", "disabled");
	}
}

function activateTab($tab) {
	var $activeTab = $tab.closest('dl').find('a.active'),
			contentLocation = $tab.attr("href") + 'Tab';

	//Make Tab Active
	$activeTab.removeClass('active');
	$tab.addClass('active');

	//Show Tab Content
	$(contentLocation).closest('.tabs-content').children('li').hide();
	$(contentLocation).show();
}

/* make tag field */
function mktag(tag){
	d = $("<dd>");
	$("<span>").text(tag.trim()).appendTo(d);
	$("<a href='#'>").html("&cross;").click(function(){
		$(this).parent().remove();
		return false;
	}).appendTo(d);
	return d;
}

$(document).ready(function(){
	/* mycloud form helper */
	$("input[type=checkbox]").change(checkb).each(checkb);
	$(".tagfield").each(function(){
		tags = $("input", this).val().split(", ");
		for(tag in tags){
			tag = tags[tag];
			if(tag != ""){
				mktag(tag).prependTo(this);
			}
		}
		$("input", this).val('').keydown(function(e){
			console.log(e);
			if(e.keyCode == 13){
				tags = $(this).val().split(",");
				for(tag in tags){
					tag = tags[tag];
					if(tag != ""){
						mktag(tag).insertBefore($(this).parent());
					}
				}
				$(this).val('');
				return false;
			}
		});
	}).addClass("form-submit-event").bind("form-submit-event", function(){
		tags = [];
		$("dd", this).each(function(){
			tags[tags.length] = $("span", this).text();
		});
		$("input", this).val(tags.join(", "));
		console.log("tag done");
	});

	$("form").submit(function(){
		$(".form-submit-event", this).trigger("form-submit-event");
		$("button", this).each(function(){
			if($(this).attr("data-submiting")){
				$(this).text($(this).attr("data-submiting")).addClass("white").attr("disabled", "disabled");
			}
		});
		console.log("done");
	});
	$('dl.tabs').each(function () {
		//Get all tabs
		var tabs = $(this).children('dd').children('a');
		tabs.click(function (e) {
			activateTab($(this));
		});
	});
});