<?php defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <!-- Page Heading -->
                <h2 class="text-uppercase p-l-3-em"><?php echo lang('menus_custom_menu'); ?></h2>
                
                <!-- Back Button -->
                <a href="<?php echo site_url($this->uri->segment(1)) ?>" class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-left"><i class="material-icons">arrow_back</i></a>
            </div>
            <div class="body">
            	<br>
                <div class="row">
				   	<div class="col-sm-3">
						<div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-col-<?php echo $this->settings->admin_theme ?>">
                                <div class="panel-heading" role="tab" id="headingOne_9">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" href="#collapseOne_9" aria-expanded="true" aria-controls="collapseOne_9">
                                            <?php echo lang('menu_pages');?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne_9" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_9">
                                    <div class="panel-body">
                                        <?php foreach($pages as $key => $page){?>
	                                	<input type="checkbox" id="md_checkbox_<?php echo $key+1; ?>" class="filled-in chk-col-<?php echo $this->settings->admin_theme ?>" data-type="page" data-title="<?php echo ucwords($page->title)?>" value="<?php echo $page->slug?>" />
                        				<label for="md_checkbox_<?php echo $key+1; ?>"><?php echo ucwords(strtolower($page->title))?></label><br>
										<?php }?>
										<hr>
										<button class="btn bg-<?php echo $this->settings->admin_theme ?> btn-block waves-effect addtomenu"> 
											<?php echo lang('menus_add_to_menu');?>
										 </button>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-col-<?php echo $this->settings->admin_theme ?>">
                                <div class="panel-heading" role="tab" id="headingTwo_9">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" href="#collapseTwo_9" aria-expanded="false"
                                           aria-controls="collapseTwo_9">
                                            <?php echo lang('menus_link');?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo_9" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo_9">
                                    <div class="panel-body">
                                    	<input type="hidden" class="info" data-type="link" />

										<label><?php echo lang('menus_url'); ?></label>
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control url" value="http://">
		                                    </div>
		                                </div>
										
										<label><?php echo lang('menus_link').' '.lang('menus_text');?></label>
		                                <div class="form-group">
		                                    <div class="form-line">
		                                        <input type="text" class="form-control link-text" placeholder="Menu Label"/>
		                                    </div>
		                                </div>

		                                <div class="form-group">
		                                	<button class="btn bg-<?php echo $this->settings->admin_theme ?> btn-block waves-effect addlinktomenu">
		                                		<?php echo lang('menus_add_to_menu');?>
											</button>
		                                </div>
										
                                    </div>
                                </div>
                            </div>                            
                        </div>

					</div><!--Col 5--3><!--Items-->
					<div class="col-sm-9">
						<?php echo @form_open(site_url('admin/menus/index/'.$main->id));?>
						<input type="hidden" id="menu-id" value="<?php echo @$main->id?>"/>
						<div class="card">
							<div class="header">
								<h2><?php echo lang('menus_title');?></h2>
								<div class="form-group">
									<div class="form-line">
										<input type="text" class="form-control" id="menu-title" name="title" value="<?php echo($main)?$main->title:''?>" />	
									</div>
                                </div>
							</div>
							<div class="body bg-<?php echo $this->settings->admin_theme ?>">
								<div class="row">
									<div class="col-sm-12">
										<h4 class="text-white"><?php echo lang('menus_structure');?></h4>
										<ol class="nestable">
											<?php echo @($main->content!='false' && !empty($main->content))? get_nested_menu($main->content) : '';?>
										</ol>
									</div>
								</div>
							</div><br>
							<div class="row">
								<div class="col-md-offset-1 col-md-2">
									<button type="button" class="btn bg-<?php echo $this->settings->admin_theme ?> waves-effect save-menu"><?php echo lang('action_save');?></button>
									<span id="submit_loader"></span>
								</div>
							</div>
						</div>
						<?php echo form_close();?>
					</div><!--Col 9--> <!--Menu's-->
            	</div>  
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var admin_url 	= '<?php echo base_url('/'); ?>';
var TMS 		= '<?php echo $highestmenu; ?>';
</script>