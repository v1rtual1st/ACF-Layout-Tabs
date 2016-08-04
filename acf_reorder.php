<?php

/*
* Changes classic ACF field output when using tabs
* All tabs combined
* Screen -> post edit screen (post_type aswell)
* For install instructions pelase see acf_reorder_install.txt
* Feel free to use/modify this code for commercial purposes as stated within licence txt
* -> find custom css and js placeholder vars below
* Author - virtualist
*/

/*
* OPTIONS
--------------------------- */
$acf_enhanced_message_ID = "field_key-field_57a1e306ca843"; //change this with id of your field-key (you can see this by inspecting element)
// ^-PS: this is just for hiding field title... you can use your own way of doing this set this to ""

// to change base CSS just change CSS from $acf_reorder_css var down below
// to add custom CSS please input your rules into 'your CUSTOM CSS'section below
// to add custom JS please input code into 'your CUSTOM JS' section below
// to modify css added "on the fly" throught JS please look for "label_js_css" and modify css

// - your CUSTOM CSS
$acf_reorder_css_custom = '
	<style>

	</style>
';
// - your CUSTOM JS
$acf_reorder_js_custom = '
	<script type="text/javascript">
	jQuery(function($) {
		$(document).ready(function(){
				// WRITE YOUR CUSTOM JS HERE
		});
	});
	</script>
';


/* acf_reorder main css
* ------------------------- */
$acf_reorder_css = '
	<style type="text/css">
			.acf_new_tab {
				float: left;
		    margin-right: 40px;
		    color: #000;
		    text-shadow: 0px 0px 1px silver;
		    cursor: pointer;
		    width: 170px;
		    text-align: left;
		    background-color: #fff;
		    padding: 4px 10px;
		    box-shadow: 1px 1px 1px #cacaca;
		    transition: ease-in-out 0.5s;
		}
		.acf_new_tab:hover {
			transform: scale(1.01);
	    background-color: #e6e6e6;
		}
		.acf_reorder_content {

		}
		.'.$acf_enhanced_message_ID.' .label { display: none; }
		.acf_reorder_li_active {
			background-color: #3aca45;
	    color: #fff;
	    box-shadow: 0px 0px 6px #3ec73e;
		}
		.acf_reorder_li_active:before {
			content: "...";
    	float: right;
		}


	</style>
	';


/* holder elements
* ------------------------- */
$acf_reorder_tabs = '<div class="acf_reorder_tabs"></div>';
$acf_reorder_content = '<div class="acf_reorder_content"></div>';



/*  Javascript that does the magic
*
* 	The logic is simple
*		-> count tabs and create new tabs output
*		-> copy tab's field ID
* 	-> hide original tabs and content
*		-> call actions on new tabs click and trigger appropriate tab click
*			-> also hide new content element
*		-> settimeout copy original output into new output element
*		-> animate new output element to display data
--------------------------------------- */
$acf_reorder_js = '
		<script type="text/javascript">

		jQuery(function($) {

				$(document).ready(function(){

					// ---------------- START acf_reorder function
					function acf_reorder() {

							//prepare array of tabs - vars
							//two dimensional -> {{key,txt},{key,txt}}
							var tab_arr = [];
							var tab_single_key = "";
							var tab_single_txt = "";

									//prepare array of tabs - init
									var tabs = $(".acf-tab-button");
									tabs.each(function(){
										tab_single_key = $(this).attr("data-key");
										tab_single_txt = $(this).html();
										var tab_single_arr = [];
										tab_single_arr.push(tab_single_key);
										tab_single_arr.push(tab_single_txt);
										tab_arr.push(tab_single_arr);
									});


									//prepare tabs output
									var acf_r_tabs_output = "<ul class=\'acf_r_ul\'>";
									tab_arr.forEach(function(tab_single_arr){
										acf_r_tabs_output += "<li class=\'acf_new_tab\' data-key=\'"+ tab_single_arr[0] +"\'>";
										acf_r_tabs_output += tab_single_arr[1];
										acf_r_tabs_output += "</li>";
									});
									acf_r_tabs_output += "</ul>";

									//output tabs
									var tabs_new_holder = $(".acf_reorder_tabs");
									tabs_new_holder.html(acf_r_tabs_output);


									//call function to register click events on new tabs
									register_click_event();


					} // ---------------- END acf_reorder function


					//call functions after tabs are ready
					setTimeout(function(){
									//call function
									acf_reorder();
					},1000);
					// ^- 1000ms is enough for acf to "load" tabs - if tabs do not show just make this number bigger


					//hide all "inside" elements and apply css
					$("#postbox-container-2 .inside").each(function(){
						var is_new_tabs_element = $(this).find(".acf_reorder_tabs").length;
						if(!is_new_tabs_element){ //enhanced message field also contains "inide" element so rule this out
							$(this).css("display","none");
							$(this).css("float","left");
							$(this).css("width","98%");
							$(this).css("clear","both");
							$(this).css("margin-top","50px");
							$(this).css("border-width","1px");
							$(this).css("background-color","rgb(248, 255, 250)");
							$(this).css("padding","1%");
							$(this).css("border-style","solid");
							$(this).css("border-color","rgb(58, 202, 69)");
							$(this).css("clear","both");
						} // ^- feel free to modify this a stated within OPTIONS section (label_js_css)
					})



				}); // ------------ END docready

					//tab click
					function register_click_event(){
						$(".acf_r_ul li").click(function(){

							//toggle states
							$(".acf_r_ul li").each(function(){
								$(this).removeClass("acf_reorder_li_active");
							});
							$(this).addClass("acf_reorder_li_active");

							//find original tab button and perform click
							tab_single_key = $(this).attr("data-key");
							tab_original_button = $(".postbox-container").find("[data-key=\'" + tab_single_key + "\']");
							tab_original_button.each(function(){
								var is_new_tab = $(this).hasClass("acf_new_tab");
								console.log("is_new_tab " + is_new_tab);
								if(!is_new_tab){
									var tab_original_button_found = $(this);
									tab_original_button_found.trigger("click");

									//populate new container with data
									//acf keeps all tab data within "inside" element so we can copy that html into new container
									setTimeout(function(){

										var element_inside = tab_original_button_found.parent().parent().parent().parent();
										//hide other inside elements
										$("#postbox-container-2 .inside").each(function(){
											var is_new = $(this).find(".acf_reorder_tabs").length;
											if(!is_new){
												$(this).css("display","none");
											}
										});
										//display inside element
										element_inside.fadeIn("fast");
										element_inside.find(".acf-tab-wrap").css("display","none");

									},500);

								}
							})


						});
				}

		});


		</script>
	';



/* output
* ------------------------- */
//css
echo $acf_reorder_css;
echo $acf_reorder_css_custom;
//content
echo $acf_reorder_tabs;
echo $acf_reorder_content;
//js
echo $acf_reorder_js_custom;
echo $acf_reorder_js;



//the end :'(
?>
