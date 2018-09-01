<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * My Function Helper
 *
 * @package		ANT
 * @subpackage	Helpers
 * @category	Helpers
 * @author		dpkpro
 */

function write_js_url_and_login_status()
{
	$CI =& get_instance();
	$urlseg = $CI->uri->segment(1);
    
	if($CI->uri->segment(2)!='')	
		$urlseg .='/'.$CI->uri->segment(2);
	echo "<script> 
		   var baseurl = '".site_url('/')."';
		   var current_url = '".current_url()."';
		   var img = '".img('/')."';
		   var js = '".js('/')."';
		   var us1 = '".$CI->uri->segment(1)."';
		   var us2 = '".$CI->uri->segment(2)."';
		   var us3 = '".$CI->uri->segment(3)."';
		   var urlseg = '".$urlseg."';\n";
		   if(isset($CI->user['id']) && !empty($CI->user['id']))
			  echo "var logged_in = true;\n";
		   else
		      echo "var logged_in = false;\n";
	echo "</script>\n"; 
}


function write_admin_js_url_and_login_status()
{
	$CI =& get_instance();
	$urlseg = $CI->uri->segment(1);
		  
    if($CI->uri->segment(2)!='')	
		$urlseg .='/'.$CI->uri->segment(2);
	echo "<script> 
		   var baseurl = '".site_url('/')."';
		   var admin_url = '".admin_url('/')."';
		   var current_url = '".current_url()."';
		   var admin_img = '".admin_img('/')."';
		   var admin_css = '".admin_css('/')."';
		   var admin_js = '".admin_js('/')."';
		   var us1 = '".$CI->uri->segment(1)."';
		   var us2 = '".$CI->uri->segment(2)."';
		   var us3 = '".$CI->uri->segment(3)."';
		   var urlseg = '".$urlseg."';
		   var are_you_sure = 'Are You Sure?';\n";
		   if(isset($CI->admin['id']) && !empty($CI->admin['id']))
			  echo "var logged_in = true;\n";
		   else
		      echo "var logged_in = false;\n";
	echo "</script>\n"; 
}


function call_alerts()
{
	$CI =& get_instance();
	$flash_message = $CI->session->flashdata('message');
	$flash_error  = $CI->session->flashdata('error');
	if(function_exists('validation_errors') && validation_errors()!= '')
		   $flash_error  = validation_errors();
			   
	if(!empty($flash_error) || !empty($flash_message)){
		echo '<div class="container">
						<div class="row">
							<div class="col-md-12">';
					  
					   if(!empty($flash_error)){
							echo '<div class="alert alert-danger mt20" style="width:95%;">
									<button type="button" class="close" data-dismiss="alert">×</button>
									'.$flash_error.' 
								  </div>';
						}
						
						if(!empty($flash_message)){
							echo '<div class="alert alert-info mt20" style="width:95%;">
										<button type="button" class="close" data-dismiss="alert">×</button>
										'.$flash_message.' 
								 </div>';
						}
					
			echo '</div></div></div>';
	}
}

function make_name($user)
{
	$user = json_decode(json_encode($user),true);
	return ucwords($user['firstname'].' '.$user['lastname']);
}

function embed_video($url)
{
	$html = '';
	if (preg_match('/youtube/',$url)){
		$code = substr(strrchr($url,'='),1);
		$html.='<div class="videoWrapper">
				  <iframe class="embed-responsive-item" src="//www.youtube.com/embed/'.$code.'" frameborder="0" style="min-height:200px"></iframe>
				</div>';
	}if (preg_match('/vimeo/',$url)){
		$code = substr(strrchr($url,'/'),1);	
		//<div class="embed-responsive embed-responsive-16by9">
		$html.='<div class="videoWrapper">
				  <iframe class="embed-responsive-item" src="//player.vimeo.com/video/'.$code.'" frameborder="0" style="min-height:200px"></iframe>
				</div>';
		/*$html.='<iframe src="//player.vimeo.com/video/'.$code.'" style="width:100%;height:180px;" frameborder="0" 
					webkitallowfullscreen mozallowfullscreen allowfullscreen> </iframe> ';*/
	}
	
	return $html;
}

function gDate($date=false)
{
	if($date)
		$date = date('Y-m-d',strtotime($date));
	else	
		$date = date('Y-m-d');
		
	return $date;	
}

function get_nested_menu($data=false)
{
	//$nestedMenu = '';
	$menu_array = (array)json_decode($data,true);
	$myTree = buildTree($menu_array, 0);
	return buildMenu($myTree);
}

function buildTree($itemList, $parentId) {
  // return an array of items with parent = $parentId
  $result = array();
  foreach ($itemList as $item) {
	if ($item['parent_id'] == $parentId) {
	  $newItem = $item;
	  $newItem['children'] = buildTree($itemList, $newItem['id']);
	  $result[] = $newItem;
	}
  }

  if (count($result) > 0) return $result;
  return null;
}

function buildMenu($array)
{
	  $i = 1;
	  if (!empty($array)){
		  foreach ($array as $item)
		  {
			echo '<li id="menuItem_'.$item['id'].'" class="mjs-nestedSortable-branch mjs-nestedSortable-expanded" style="display: list-item;">';
			echo get_menu_box($item);
			if (!empty($item['children']))
			{
			  echo '<ol>'; 
			  buildMenu($item['children']);
			  echo '</ol>';
			}
			echo '</li>';
		  
			
			$i++;
		  }
	}	  
}

function get_menu_box($menu)
{
	if($menu['type']=='link')
	{
		$html = '<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne_'.$menu['id'].'">
							  <h4 class="panel-title">
	                            <a role="button" data-toggle="collapse" href="#collapseOne_'.$menu['id'].'" aria-expanded="true" aria-controls="collapseOne_'.$menu['id'].'">'.$menu['label'].'</a>
	                          </h4>
						</div>
						<div id="collapseOne_'.$menu['id'].'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_'.$menu['id'].'">
							<div class="panel-body">
								<input type="hidden" name="type" value="'.$menu['type'].'"/>
								
								<div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control" name="value" value="'.$menu['value'].'" placeholder="Link"/>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <div class="form-line">
		                                <input type="text" class="form-control" name="label" value="'.$menu['label'].'" placeholder="Label"/>
		                            </div>
		                        </div>
		                        <div class="form-group">
		                            <a class="btn btn-default pull-right waves-effect remfrmenu"><i class="fa fa-minus"></i></a>
		                        </div>
							</div>
						</div>
					</div>';
	}
	else
	{
		$html = '<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="headingOne_'.$menu['id'].'">
				  <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" href="#collapseOne_'.$menu['id'].'" aria-expanded="true" aria-controls="collapseOne_'.$menu['id'].'">'.$menu['label'].'</a>
                  </h4>
				</div>
				<div id="collapseOne_'.$menu['id'].'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_'.$menu['id'].'">
					<div class="panel-body">
					  <input type="hidden" name="type" value="'.$menu['type'].'">
					  <input type="hidden" name="value" value="'.$menu['value'].'">
					  
					  <div class="form-group">
						<div class="form-line">
						  <input type="text" class="form-control" name="label" value="'.$menu['label'].'" placeholder="Label">
						</div>
					  </div>
					  <div class="form-group">
	                      <a class="btn btn-default pull-right waves-effect remfrmenu"><i class="fa fa-minus"></i></a>
	                   </div>
					</div>
				</div>
			  </div>';
	}
			  
	return $html;		  
}

function front_menu($type=false,$child_ul_class=false,$id='')
{	
	$CI =& get_instance();
	$CI->db->limit(1);
	if($type=='top')
		$data = $CI->db->get_where('menus',array('position'=>2))->row('content');
	else if($type=='footer')
		$data = $CI->db->get_where('menus',array('position'=>3))->row('content');	
	else if($type=='custom')
		$data = $CI->db->get_where('menus',array('position'=>1))->row('content');		
	if($id!=''){
		$data = $CI->db->get_where('menus',array('id'=>$id))->row('content');		
	}
	$menu_array = (array)json_decode($data,true);
	$myTree = buildTree($menu_array, 0);
	return make_front_menu_2($myTree,$child_ul_class,$id);
}

function make_front_menu_2($array = array(), $child_ul_class = FALSE, $id = NULL)
{ 	
	$dropdown = '';
	if (!empty($array)) 
	{ 
		$dropdown 		= "<ul class='dropdown-menu'>";
		foreach ($array as $item)
		{
			$url 		= site_url('cms/'). str_replace(' ', '+', $item['value']);
			$dropdown  .= '<li><a href="'.$url.'">'.$item['label'].'</a></li>';
		}
		$dropdown 	   .= "</ul>";
	}	

	return $dropdown;
}

function get_menu_by_id($menu_id=false,$child_ul_class=false)
{	
	$CI =& get_instance();
	$data = $CI->db->get_where('menus',array('id'=>$menu_id))->row('content');		
	$menu_array = (array)json_decode($data,true);
	$myTree = buildTree($menu_array, 0);
	return make_front_menu($myTree,$child_ul_class);
}


function make_front_menu($array,$child_ul_class=false,$id ='')
{ 	
	
	 $i = 1;
	 if (!empty($array)) 
	 { 
		  foreach ($array as $item)
		  {
			if($i==1 && $item['parent_id']!=0) 
				
				$url = '';
				if($item['type']=='page')
					$url = site_url('cms/'.$item['value']);
				if($item['type']=='category')
					$url = site_url('categories/'.$item['value']);	
				if($item['type']=='post')
					$url = site_url('posts/'.$item['value']);		
				if($item['type']=='link')
		            if(strpos($item['value'], "http://") !== false){
					     $url = $item['value'];
		            } else {
		                 $url = site_url($item['value']);    
		            }	
				if($item['type']=='other')
					$url = site_url($item['value']);
					
				$cart = '';	
				$dropdown = '';		
				if (!empty($item['children'])){
					$dropdown =  'class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"';
				}					
				if ($id != ''){
					$dropdown ='';
					$cart = ''; 
				}
				echo '<li class="dropdown'.(current_url()==$url?' active':'').'"> <a href="'.$url.'"'.$dropdown.'>'
				.ucfirst(strtolower($item['label'])).'</a>';
				
				if (!empty($item['children']))
				{
					echo '<ul class="'.$child_ul_class.'">'; 
				  	make_front_menu($item['children'],'dropdown-menu');
					echo '</ul>';
				}
				echo '</li>';
			
			$i++;
		  }
	}	
}



//Widgets Funtion
function get_table_record($table,$where_field=false,$where=false,$limit=false,$sortby=false,$sortorder='desc')
{
	$CI =& get_instance();
	if($where_field){
		if($table=='posts' && $where_field=='categories')
			$CI->db->like($where_field,'"'.$where.'"','both');
		else
			$CI->db->where($where_field,$where);
	}
	if($limit)
		$CI->db->limit($limit);
		
	if($sortby)
		$CI->db->order_by($sortby,$sortorder);	
	
	return $CI->db->get($table)->result();
}

function get_widget_area($position=false)
{
	$widget_area = get_table_record('widget_area','position',$position);
	//echo '<pre>';print_r($widget_area);exit;
	if(!$widget_area){
		return false;
	}
	$widgets = ($widget_area[0]->widgets!='')?json_decode($widget_area[0]->widgets,true):array();
	//echo '<pre>';print_r($widgets);exit;
	foreach($widgets as $widget){
		echo get_widget($widget,$position);
	}
}

function get_widget($widget,$position=false)
{
	$html = '';
	$CI =& get_instance();
	
	if($position=='bottom1')
	{
		if($widget['type']=='recent_posts'){
		   $html .='<div class="sml-blocks pull-left">
					  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
					  <ul>';
							$rows = get_table_record('posts',false,false,$widget['records'],'created');
							foreach($rows as $row){
							  $html.='<li><a href="'.site_url('posts/'.$row->slug).'">'.$row->title.'</a></li>';
							}
			 $html .='</ul>
				   </div>';  
		}
		if($widget['type']=='text'){
		   $html .='<div class="sml-blocks pull-left">
						<h3 style="padding:15px 0">'.$widget['title'].'</h3>
						'.$widget['value'].'
					</div>';
		}
		if($widget['type']=='video'){
		    if(!empty($widget['value'])){
				$html .='<div class="sideboxes" id="videobox">
						   <h3>'.$widget['title'].'</h3>
						   <div class="showvideo">';
				
				if (preg_match('/youtube/',$widget['value'])){
					$code = substr(strrchr($widget['value'],'='),1);
					$html.='<iframe style="width:100%;" height="180" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen>
							</iframe>';
				}if (preg_match('/vimeo/',$widget['value'])){
					$code = substr(strrchr($widget['value'],'/'),1);	
					$html.='<iframe src="//player.vimeo.com/video/'.$code.'" style="width:100%;height:180px;" frameborder="0" 
								webkitallowfullscreen mozallowfullscreen allowfullscreen> </iframe>';
				}
							  
				$html .=' 	</div>
						 </div>';
			}
		}
		if($widget['type']=='posts_by_category'){
			  $rows = get_table_record('posts','categories',$widget['value'],$widget['records']);
			  $category = get_table_record('categories','id',$widget['value']);
			  foreach($rows as $row){
				$post_title = (strlen($row->title)>28)?mb_substr($row->title,0,28,'UTF-8').'...':$row->title;	
				$excerpt = (strlen($row->excerpt)>31)?mb_substr($row->excerpt,0,31,'UTF-8').'...':$row->excerpt;	
				$html .='<div class="sml-blocks pull-left">
							<figure class="imgplace pull-left">
								<img style="border-radius:50%;" src="'.uploads('common/'.$row->image).'" alt="'.$row->title.'">
							</figure>
							<div class="desc pull-right">
							  <h3>'.$post_title.'</h3>
							  <h4>'.$excerpt.'</h4>
							  <a href="'.site_url('posts/'.$row->slug).'" class="more">'.lang('learn_more').'</a></div>
						  </div>';
			  } 
		}
		if($widget['type']=='categories'){
		   $html .='<div class="sml-blocks pull-left">
					  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
						<ul>';
							$rows = get_table_record('categories',false,false,$widget['records']);
							foreach($rows as $row){
							  $html.='<li><a href="'.site_url('categories/'.$row->slug).'">'.$row->title.'</a></li>';
							}
			   $html .='</ul>
				</div>';
		}
		if($widget['type']=='newsletter_form'){
			 if($CI->session->flashdata('error'))
                $html .='<h1 style="color: red;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
             if($CI->session->flashdata('message'))
                $html .='<h1 style="color: green;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
			 $html .='<div class="sml-blocks pull-left">
					    <form action="'.site_url('subcribe').'" method="post">
						  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
						  <input type="text" name="email" placeholder="'.$widget['value'].'">
						  <input type="hidden" name="redirect" placeholder="'.current_url().'">
						  <input type="submit" value="'.lang('ok').'">
					    </form>		  
					</div>';
		}	
		if($widget['type']=='custom_menu'){
			 $html .='<div class="sml-blocks pull-left">
						<h3 style="padding:15px 0">'.$widget['title'].'</h3>
						<ul>';
							$menu = get_table_record('menus','id',$widget['value'],$widget['records']);
							$rows = (!empty($menu[0]->content))?(array)json_decode($menu[0]->content,true):array();
							foreach($rows as $row){
							  if($row['type']=='post')
							  	$url = site_url('posts/'.$row['value']);
							  if($row['type']=='page')
							  	$url = site_url('page/'.$row['value']);
							  if($row['type']=='category')
							  	$url = site_url('categories/'.$row['value']);	
							  if($row['type']=='link')
							  	$url = $row['value'];		
									
							  	$html.='<li><a href="'.$url.'">'.$row['label'].'</a></li>';
							}
			  $html .='</ul>		  
					</div>';
		}		
		if($widget['type']=='search_box'){
			$html .='<div class="sml-blocks pull-left">
					    <form action="'.site_url('search').'" method="get">
						  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
						  <input type="text" name="query" placeholder="'.lang('search_our_site').'">
						  <input type="submit" value="'.lang('search').'">
					    </form>		  
					</div>';
		}	
		
	}
	
	
	//-------------Bottom 2 & 3--------------//
	if($position=='bottom2' || $position=='bottom3')
	{
		if($widget['type']=='recent_posts'){
		   $html .='<div class="sec03-block pull-left">
					  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
					  <ul>';
							$rows = get_table_record('posts',false,false,$widget['records'],'created');
							foreach($rows as $row){
							  $html.='<li><a href="'.site_url('posts/'.$row->slug).'">'.$row->title.'<a/></li>';
							}
			 $html .='</ul>
				</div>';
		}
		if($widget['type']=='text'){
		   $html .='<div class="sec03-block pull-left">
						'.$widget['value'].'
					</div>';
		}
		if($widget['type']=='video'){
		    if(!empty($widget['value'])){
				$html .='<div class="sideboxes" id="videobox">
						   <h3>'.$widget['title'].'</h3>
						   <div class="showvideo">';
				
				if (preg_match('/youtube/',$widget['value'])){
					$code = substr(strrchr($widget['value'],'='),1);
					$html.='<iframe style="width:100%;" height="180" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen>
							</iframe>';
				}if (preg_match('/vimeo/',$widget['value'])){
					$code = substr(strrchr($widget['value'],'/'),1);	
					$html.='<iframe src="//player.vimeo.com/video/'.$code.'" style="width:100%;height:180px;" frameborder="0" webkitallowfullscreen 
								mozallowfullscreen allowfullscreen> </iframe> ';
				}
							  
				$html .=' 	</div>
						 </div>';
			}
		}
		if($widget['type']=='posts_by_category'){
		   $html .='<div class="sec03-block pull-left">
					  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
						<ul>';
							$category = get_table_record('categories','id',$widget['value']);
							$category_url = (isset($category[0]))?site_url('categories/'.$category[0]->slug):'#'; 
							$rows = get_table_record('posts','categories',$widget['value'],$widget['records']);
							foreach($rows as $row){
							  $html.='<li><a href="'.$category_url.'">'.$row->title.'</a></li>';
							}
			  $html .='</ul>
				</div>';
		}
		if($widget['type']=='categories'){
		   $html .='<div class="sec03-block pull-left">
					  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
						<ul>';
							$rows = get_table_record('categories',false,false,$widget['records']);
							foreach($rows as $row){
							  $html.='<li><a href="'.site_url('categories/'.$row->slug).'">'.$row->title.'</a></li>';
							}
			   $html .='</ul>
				</div>';
		}
		if($widget['type']=='newsletter_form'){
			 if($CI->session->flashdata('error'))
                $html .='<h1 style="color: red;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
             if($CI->session->flashdata('message'))
                $html .='<h1 style="color: green;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
			 $html .='<div class="sec03-block pull-left">
					    <form action="'.site_url('subcribe').'" method="post">
						  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
						  <input type="text" name="email" placeholder="'.$widget['value'].'">
						  <input type="hidden" name="redirect" placeholder="'.current_url().'">
						  <input type="submit" value="'.lang('ok').'">
					    </form>		  
					</div>';
		}	
		if($widget['type']=='custom_menu'){
			 $html .='<div class="sec03-block pull-left">
						<h3 style="padding:15px 0">'.$widget['title'].'</h3>
						<ul>';
							$menu = get_table_record('menus','id',$widget['value'],$widget['records']);
							$rows = (!empty($menu[0]->content))?(array)json_decode($menu[0]->content,true):array();
							foreach($rows as $row){
							  if($row['type']=='post')
							  	$url = site_url('posts/'.$row['value']);
							  if($row['type']=='page')
							  	$url = site_url('page/'.$row['value']);
							  if($row['type']=='category')
							  	$url = site_url('categories/'.$row['value']);	
							  if($row['type']=='link')
							  	$url = $row['value'];		
									
							  	$html.='<li><a href="'.$url.'">'.ucfirst(strtolower($row['label'])).'</a></li>';
							}
			  $html .='</ul>		  
					</div>';
		}		
		if($widget['type']=='search_box'){
			$html .='<div class="sec03-block pull-left">
					    <form action="'.site_url('search').'" method="get">
						  <h3 style="padding:15px 0">'.$widget['title'].'</h3>
						  <input type="text" name="query" placeholder="'.lang('search_our_site').'">
						  <input type="submit" value="'.lang('search').'">
					    </form>		  
					</div>';
		}	
	}
	
	//Footer Areas
	if($position=='footer-1' || $position=='footer-2' || $position=='footer-3' || $position=='footer-4' || $position=='footer-5')
	{
		if($widget['type']=='recent_posts'){
		   $html .='<div class="ftboxes pull-left">
					  <h3>'.$widget['title'].'</h3>
					  <ul>';
							$rows = get_table_record('posts',false,false,$widget['records'],'created');
							foreach($rows as $row){
							  $html.='<li><a href="'.site_url('posts/'.$row->slug).'">'.$row->title.'</a></li>';
							}
			 $html .='</ul>
				  </div>';
		}
		if($widget['type']=='text'){
		   $html .='<header><h4>'.$widget['title'].'</h4></header>
				   '.$widget['value'];
		}
		if($widget['type']=='video'){
		    if(!empty($widget['value'])){
				$html .='<div class="sideboxes" id="videobox">
						   <h3>'.$widget['title'].'</h3>
						   <div class="showvideo">';
				
				if (preg_match('/youtube/',$widget['value'])){
					$code = substr(strrchr($widget['value'],'='),1);
					$html.='<iframe style="width:100%;" height="180" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen>
							</iframe>';
				}if (preg_match('/vimeo/',$widget['value'])){
					$code = substr(strrchr($widget['value'],'/'),1);	
					$html.='<iframe src="//player.vimeo.com/video/'.$code.'" style="width:100%;height:180px;" frameborder="0" 
								webkitallowfullscreen mozallowfullscreen allowfullscreen> </iframe> ';
				}
							  
				$html .=' 	</div>
						 </div>';
			}
		}
		if($widget['type']=='posts_by_category'){
		   $html .='<div class="ftboxes pull-left">
					  <h3>'.$widget['title'].'</h3>
					  <ul>';
							$category = get_table_record('categories','id',$widget['value']);
							$category_url = (isset($category[0]))?site_url('categories/'.$category[0]->slug):'#'; 
							$rows = get_table_record('posts','categories',$widget['value'],$widget['records']);
							foreach($rows as $row){
							  $html.='<li><a href="'.$category_url.'">'.$row->title.'</a></li>';
							}
			  $html .='</ul>
				</div>';
		}
		if($widget['type']=='categories'){
		   $html .='<div class="ftboxes pull-left">
					  <h3>'.$widget['title'].'</h3>
						<ul>';
							$rows = get_table_record('categories',false,false,$widget['records']);
							foreach($rows as $row){
							  $html.='<li><a href="'.site_url('categories/'.$row->slug).'">'.$row->title.'</a></li>';
							}
			   $html .='</ul>
				</div>';
		}
		if($widget['type']=='newsletter_form'){
			 if($CI->session->flashdata('error'))
                $html .='<h1 style="color: red;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
             if($CI->session->flashdata('message'))
                $html .='<h1 style="color: green;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
			 $html .='<div class="ftboxes pull-left">
					    <form action="'.site_url('subcribe').'" method="post">
						  <h3>'.$widget['title'].'</h3>
						  <input type="text" name="email" placeholder="'.$widget['value'].'">
						  <input type="hidden" name="redirect" placeholder="'.current_url().'">
						  <input type="submit" value="'.lang('ok').'">
					    </form>		  
					</div>';
		}	
		if($widget['type']=='custom_menu'){
			 $html .='<header><h4>'.$widget['title'].'</h4></header>
					  <ul>';
							$menu = get_table_record('menus','id',$widget['value'],$widget['records']);
							$rows = (!empty($menu[0]->content))?(array)json_decode($menu[0]->content,true):array();
							foreach($rows as $row){
							  if($row['type']=='post')
							  	$url = site_url('posts/'.$row['value']);
							  if($row['type']=='page')
							  	$url = site_url('page/'.$row['value']);
							  if($row['type']=='category')
							  	$url = site_url('categories/'.$row['value']);	
							  if($row['type']=='link')
							  	$url = $row['value'];		
							  if($row['type']=='other')
							  	$url = site_url($row['value']);		
									
							  	$html.='<li><a href="'.$url.'">'.ucfirst(strtolower($row['label'])).'</a></li>';
							}
			 $html .='</ul>';
		}		
		if($widget['type']=='search_box'){
			$html .='<header><h4>'.$widget['title'].'</h4></header>
					 <form action="'.site_url($widget['value']).'" method="get">
                     <div class="footer-search">
                       	 <input class="form-control" type="search" name="q" id="footer-search" />
                     </div>
					 </form>';
		}	
	}
	
	
	//------------Right Sidebar-------------//
	if($position=='right_side')
	{
		if($widget['type']=='recent_posts'){
		   $html .='<div class="sideboxes blueborderbox" id="rssfeedbox">
					  <div class="title-section clearfix">
						<h3>'.$widget['title'].'</h3>
					  </div>
					  <ul>';
							$rows = get_table_record('posts',false,false,$widget['records'],'created');
							foreach($rows as $row){
							  $html.='<li><a href="'.site_url('posts/'.$row->slug).'">'.$row->title.'</a></li>';
							}
			 $html .='</ul>
				</div>';
		}
		if($widget['type']=='text'){
		   $html .='<div class="sideboxes" id="videobox">
					  <h3>'.$widget['title'].'</h3>
					  <div class="showvideo">
						 '.$widget['value'].'
					   </div>
				   </div>';
		}
		if($widget['type']=='video'){
		    if(!empty($widget['value'])){
				$html .='<div class="sideboxes" id="videobox">
						   <h3>'.$widget['title'].'</h3>
						   <div class="showvideo">';
				
				if (preg_match('/youtube/',$widget['value'])){
					$code = substr(strrchr($widget['value'],'='),1);
					$html.='<iframe style="width:100%;" height="180" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen>
							</iframe>';
				}if (preg_match('/vimeo/',$widget['value'])){
					$code = substr(strrchr($widget['value'],'/'),1);	
					$html.='<iframe src="//player.vimeo.com/video/'.$code.'" style="width:100%;height:180px;" frameborder="0" 
								webkitallowfullscreen mozallowfullscreen allowfullscreen> </iframe> ';
				}
							  
				$html .=' 	</div>
						 </div>';
			}
		}
		if($widget['type']=='posts_by_category'){
		   $category = get_table_record('categories','id',$widget['value']);
		   $category_slug = (isset($category[0]))?$category[0]->slug:'';
		   $category_url = (isset($category[0]))?site_url('categories/'.$category[0]->slug):'#'; 
		   $html .='<div class="sideboxes blueborderbox" id="rssfeedbox">
					  <div class="title-section clearfix">
						<h3><a href="'.site_url('news').'">'.$widget['title'].'</a></h3>
						<a href="'.site_url('rss/'.$category_slug).'" target="_blank"><h6>RSS</h6></a>
					  </div>
					  <div class="feedbody">
							<ul>';
								$rows = get_table_record('posts','categories',$widget['value'],$widget['records']);
								foreach($rows as $row){
								  $excerpt = (strlen($row->excerpt)>100)?substr($row->excerpt,0,100).'...':$row->excerpt;	
								  $html.='<li><a href="'.site_url('posts/'.$row->slug).'"><h4>'.ucfirst($row->title).'</h4></a>'.$excerpt.'</li>';
								}
				  $html .='</ul>
					  </div>
					  <div class="bottombar"> <a href="'.site_url('news').'">'.lang('view_new_content').'</a> </div>
				</div>';
		}
		if($widget['type']=='categories'){
		   $html .='<div class="sideboxes blueborderbox" id="rssfeedbox">
					  <div class="title-section clearfix">
						<h3>'.$widget['title'].'</h3>
					  </div>
					  <div class="feedbody">
							<ul>';
								$rows = get_table_record('categories',false,false,$widget['records']);
								foreach($rows as $row){
								  $html.='<li><h4>'.$row->title.'</h4>'.$row->excerpt.'</li>';
								}
				  $html .='</ul>
					  </div>
				</div>';
		}
		if($widget['type']=='newsletter_form'){
			if($CI->session->flashdata('error'))
               $html .='<h1 style="color: red;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
            if($CI->session->flashdata('message'))
               $html .='<h1 style="color: green;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
			$html .='<div class="sideboxes blueborderbox text-center" id="newsletterbox">
					   <form action="'.site_url('subcribe').'" method="post">
						  <h3>'.$widget['title'].'</h3>
						  <input type="text" name="email" placeholder="'.$widget['value'].'">
						  <input type="hidden" name="redirect" placeholder="'.current_url().'">
						  <input type="submit" value="'.lang('ok').'">
					   </form>		  
					</div>';
		}
		if($widget['type']=='custom_menu'){
			 $html .='<div class="sideboxes blueborderbox" id="rssfeedbox">
						<div class="title-section clearfix">
						  <h3>'.$widget['title'].'</h3>
						</div>
						  <ul>';
							$menu = get_table_record('menus','id',$widget['value'],$widget['records']);
							$rows = (!empty($menu[0]->content))?(array)json_decode($menu[0]->content,true):array();
							foreach($rows as $row){
							  if($row['type']=='post')
							  	$url = site_url('posts/'.$row['value']);
							  if($row['type']=='page')
							  	$url = site_url('page/'.$row['value']);
							  if($row['type']=='category')
							  	$url = site_url('categories/'.$row['value']);	
							  if($row['type']=='link')
							  	$url = $row['value'];		
									
							  	$html.='<li><a href="'.$url.'">'.$row['label'].'</a></li>';
							}
			  $html .='</ul>		  
					</div>';
		}	
		if($widget['type']=='search_box'){
			$html .='<div class="sideboxes blueborderbox" id="newsletterbox">
						<div class="title-section clearfix">
						  <h3>'.$widget['title'].'</h3>
						</div>
					    <form action="'.site_url('search').'" method="get">
						  <input type="text" name="query" placeholder="'.lang('search_our_site').'">
						  <input type="submit" value="'.lang('search').'" style="width:auto;padding:0 10px;">
					    </form>		  
					</div>';
		}	
	}
	
	//------------Right Sidebar-------------//
	if($position=='left_side')
	{
		if($widget['type']=='recent_posts'){
		   $html .='<div class="sideboxes blueborderbox" id="rssfeedbox">
					  <div class="title-section clearfix">
						<h3>'.$widget['title'].'</h3>
					  </div>
					  <ul>';
							$rows = get_table_record('posts',false,false,$widget['records'],'created');
							foreach($rows as $row){
							  $html.='<li><a href="'.site_url('posts/'.$row->slug).'">'.$row->title.'</a></li>';
							}
			 $html .='</ul>
				</div>';
		}
		if($widget['type']=='video'){
		    if(!empty($widget['value'])){
				$html .='<div class="sideboxes" id="videobox">
						   <h3>'.$widget['title'].'</h3>
						   <div class="showvideo">';
				
				if (preg_match('/youtube/',$vid)){
					$code = substr(strrchr($vid,'='),1);
					$html.='<iframe style="width:100%;" height="315" src="//www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen>
							</iframe>';
				}if (preg_match('/vimeo/',$vid)){
					$code = substr(strrchr($vid,'/'),1);	
					$html.='<iframe src="//player.vimeo.com/video/'.$code.'" style="width:100%;height:314px" frameborder="0" 
								webkitallowfullscreen mozallowfullscreen allowfullscreen> </iframe> ';
				}
							  
				$html .=' 	</div>
						 </div>';
			}
		}
		if($widget['type']=='video'){
		   $html .='<div class="sideboxes" id="videobox">
					   <h3>'.$widget['title'].'</h3>
					  <div class="showvideo">
						 '.$widget['value'].'
					   </div>
				   </div>';
		}
		if($widget['type']=='posts_by_category'){
		   $category = get_table_record('categories','id',$widget['value']);
		   $category_slug = (isset($category[0]))?$category[0]->slug:'';	
		   $category_url = (isset($category[0]))?site_url('categories/'.$category[0]->slug):'#'; 
		   $html .='<div class="sideboxes blueborderbox" id="rssfeedbox">
					  <div class="title-section clearfix">
						<h3><a href="'.site_url('news').'">'.$widget['title'].'</a></h3>
						<a href="'.site_url('rss/'.$category_slug).'" target="_blank"><h6>RSS</h6></a>
					  </div>
					  <div class="feedbody">
							<ul>';
								$rows = get_table_record('posts','categories',$widget['value'],$widget['records']);
								foreach($rows as $row){
								   $excerpt = (strlen($row->excerpt)>100)?substr($row->excerpt,0,100).'...':$row->excerpt;		
								   $html.='<li><a href="'.site_url('posts/'.$row->slug).'"><h4>'.ucfirst($row->title).'</h4></a>'.$excerpt.'</li>';
								}
				  $html .='</ul>
					  </div>
					  <div class="bottombar"> <a href="'.site_url('news').'">'.lang('view_new_content').'</a> </div>
				</div>';
		}
		if($widget['type']=='categories'){
		   $html .='<div class="sideboxes blueborderbox" id="rssfeedbox">
					  <div class="title-section clearfix">
						<h3>'.$widget['title'].'</h3>
					  </div>
					  <div class="feedbody">
							<ul>';
								$rows = get_table_record('categories',false,false,$widget['records']);
								foreach($rows as $row){
								  $html.='<li><h4>'.$row->title.'</h4>'.$row->excerpt.'</li>';
								}
				  $html .='</ul>
					  </div>
				</div>';
		}
		if($widget['type']=='newsletter_form'){
			if($CI->session->flashdata('error'))
               $html .='<h1 style="color: red;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
            if($CI->session->flashdata('message'))
               $html .='<h1 style="color: green;padding:10px 0;">'.$CI->session->flashdata('error').'</h1>';
			$html .='<div class="sideboxes blueborderbox text-center" id="newsletterbox">
					   <form action="'.site_url('subcribe').'" method="post">
						  <h3>'.$widget['title'].'</h3>
						  <input type="text" name="email" placeholder="'.$widget['value'].'">
						  <input type="hidden" name="redirect" placeholder="'.current_url().'">
						  <input type="submit" value="'.lang('ok').'">
					   </form>		  
					</div>';
		}
		if($widget['type']=='custom_menu'){
			 $html .='<div class="sideboxes blueborderbox" id="rssfeedbox">
						<div class="title-section clearfix">
						  <h3>'.$widget['title'].'</h3>
						</div>
						  <ul>';
							$menu = get_table_record('menus','id',$widget['value'],$widget['records']);
							$rows = (!empty($menu[0]->content))?(array)json_decode($menu[0]->content,true):array();
							foreach($rows as $row){
							  if($row['type']=='post')
							  	$url = site_url('posts/'.$row['value']);
							  if($row['type']=='page')
							  	$url = site_url('page/'.$row['value']);
							  if($row['type']=='category')
							  	$url = site_url('categories/'.$row['value']);	
							  if($row['type']=='link')
							  	$url = $row['value'];		
									
							  	$html.='<li><a href="'.$url.'">'.$row['label'].'</a></li>';
							}
			  $html .='</ul>		  
					</div>';
		}	
		if($widget['type']=='search_box'){
			$html .='<header><h4>'.$widget['title'].'</h4></header>
					 <form action="'.site_url($widget['value']).'" method="get">
                     <div class="footer-search">
                       	 <input class="form-control" type="search" name="search" id="footer-search" />
                     </div>
					 </form>';
		}
	}
	return $html;
}

//Admin Widget Functions
function get_widget_box_edit($type,$title,$value,$records)
{
	$html = '<div class="box box-solid bg-olive collapsed-box">
				<div class="box-header">
					  <div class="pull-right box-tools">
						 <button class="btn bg-olive btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
						 <button class="btn bg-olive btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
					  </div>

					  <h3 class="box-title">'.$title.'</h3>
				</div>
				<div class="box-body">';
				   
				 if($type=='recent_posts'){
					$html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="hidden" name="value" value="">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>'.lang('records').'</label>
								 <select class="form-control" name="records">
									<option value="3" '; $html.=($records==3)?'selected':''; $html .='>3</option>
									<option value="5" '; $html.=($records==5)?'selected':''; $html .='>5</option>
									<option value="10" '; $html.=($records==10)?'selected':''; $html .='>10</option>
								 </select>
							   </div>
							</div>';
							
				 }else if($type=='search_box'){
					$html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="hidden" name="records" value="0">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>Search Relative Url</label>
								 <input type="text" class="form-control" name="value" placeholder="Relative Url" value="'.$value.'"/>
							   </div>
							</div>';	 
							
				}else if($type=='custom_menu'){
					$html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="hidden" name="records" value="0">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>'.lang('menu').'</label>
								 <select class="form-control" name="value">';
									$rows = get_table_record('menus');
									foreach($rows as $row){
										$html.='<option value="'.$row->id.'" ';
										$html.=($row->id==$value)?'selected':'';
										$html.='>'.$row->title.'</option>';
									}
						$html .='</select>
							   </div>
							</div>';
								 
				}else if($type=='categories'){
					$html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="hidden" name="value" value="">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>'.lang('records').'</label>
								 <select class="form-control" name="records">
									<option value="3" '; $html.=($records==3)?'selected':''; $html .='>3</option>
									<option value="5" '; $html.=($records==5)?'selected':''; $html .='>5</option>
									<option value="10" '; $html.=($records==10)?'selected':''; $html .='>10</option>
								 </select>
							   </div>
							</div>';
								 
				}else if($type=='offers'){
				   $html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="hidden" name="value" value="">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>'.lang('records').'</label>
								 <select class="form-control" name="records">
									<option value="3" '; $html.=($records==3)?'selected':''; $html .='>3</option>
									<option value="5" '; $html.=($records==5)?'selected':''; $html .='>5</option>
									<option value="10" '; $html.=($records==10)?'selected':''; $html .='>10</option>
								 </select>
							   </div>
							</div>';	 
							
				}else if($type=='newsletter_form'){
				   $html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="hidden" name="records" value="0">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>'.lang('excerpt').'</label>
								 <textarea class="form-control" name="value" rows="3">'.$value.'</textarea>
							   </div>
							</div>';	 
				}else if($type=='text'){
				  $html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="hidden" name="records" value="0">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt10">
								<div class="col-md-12">
									<a class="btn btn-primary btn-sm pull-right edit-content">Edit Content</a>
								</div>
							</div>
							<div class="row mt5" style="display:none">
							   <div class="col-md-12">
								 <label>Content</label>
								 <textarea class="form-control" name="value" rows="3">'.$value.'</textarea>
							   </div>
							</div>';	 
				}else if($type=='posts_by_category'){
				  $html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>'.lang('category').'</label>
								 <select class="form-control" name="value">';
									$rows = get_table_record('categories');
									foreach($rows as $row){
										$html.='<option value="'.$row->id.'" ';
										$html.=($row->id==$value)?'selected':'';
										$html.='>'.$row->title.'</option>';
									}
						$html .='</select>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>'.lang('records').'</label>
								 <select class="form-control" name="records">
									<option value="3" '; $html.=($records==3)?'selected':''; $html .='>3</option>
									<option value="5" '; $html.=($records==5)?'selected':''; $html .='>5</option>
									<option value="10" '; $html.=($records==10)?'selected':''; $html .='>10</option>
								 </select>
							   </div>
							</div>';		 
				}else if($type=='video'){
				   $html .= '<div class="row">
							   <div class="col-md-12">
								  <label>Widget Title</label>
								  <input type="hidden" name="type" value="'.$type.'">
								  <input type="hidden" name="records" value="0">
								  <input type="text" class="form-control" name="title" placeholder="'.lang('title').'" value="'.$title.'"/>
							   </div>
							</div>
							<div class="row mt5">
							   <div class="col-md-12">
								 <label>Video URL</label>
								 <input type="text" class="form-control" name="value" placeholder="'.lang('video_url').'" value="'.$value.'"/>
							   </div>
							</div>';	 
				}
				  
				   
	$html .='	 </div>
			</div>';
	
	return $html;
}

