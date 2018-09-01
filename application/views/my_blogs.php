<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Section -->
<div class="page-default bg-grey">
    <div class="container">
        <div class="row">
            <div class="col-md-2 pull-right">
                <a href="<?php echo site_url("myblogs/add_new_blog") ?>" class="btn" title="<?php echo lang('blogs_add_new'); ?>"><i class="fa fa-plus"></i> <?php echo lang('blogs_add_new'); ?></a>
            </div>
        </div>
        <hr class="md"/>
    	<div class="row">
    		<?php if(!empty($my_blogs)) { ?>
    		<div class="col-md-12 table-responsive">
    			<table class="table table-striped table-bordered table-hover">
    				<thead>
	    				<tr class="my-be-tr">
	    					<th><?php echo lang('common_title'); ?></th>
	    					<th><?php echo lang('common_updated'); ?></th>
	    					<th><?php echo lang('common_status'); ?></th>
	    					<th><?php echo lang('action_action'); ?></th>
	    				</tr>
    				</thead>
    				<tbody>
    					<?php foreach($my_blogs as $val) { ?>
    					<tr class="my-be-tr">
    						<td class="text-capitalize">
                                <?php if($val->status == 1) { ?>
                                <a href="<?php echo site_url('blogs/').$val->slug ?>"><?php echo mb_substr($val->title, 0, 30, 'utf-8') ?></a>
                                <?php } else { ?>
                                <?php echo mb_substr($val->title, 0, 30, 'utf-8') ?>
                                <?php } ?>
                            </td>
    						<td><?php echo date("g:iA F j, Y", strtotime($val->date_updated)) ?></td>
    						<td><?php echo $val->status == 1 ? '<span class="label label-success">'.lang('blogs_status_published').'</span>' : ($val->status == 2 ? '<span class="label label-warning">'.lang('blogs_status_pending').'</span>' : '<span class="label label-default">'.lang('blogs_status_disabled').'</span>'); ?></td>
    						<td>
                                <div class="btn-group">
                                    <a href="<?php echo site_url("myblogs/edit_blog/".$val->id) ?>" class="btn btn-info" title="<?php echo lang('action_edit'); ?>"><i class="fa fa-edit"></i></a>
                                    <a role="button" class="btn btn-danger" onclick="ajaxDelete(`<?php echo $val->id ?>`, `<?php echo $val->title ?>`)" title="<?php echo lang('action_delete'); ?>"><i class="fa fa-trash"></i></a>
                                </div>
                            </td>
    					</tr>
    					<?php } ?>
    				</tbody>
    			</table>
    		</div>
    		<?php } else { ?>
    		<div class="col-md-12 text-center">
				<h3><?php echo lang('error_no_results') ?></h3>
				<p><a href="<?php echo site_url('') ?>"><?php echo lang('action_home'); ?></a></p>
			</div>
    		<?php } ?>
    	</div>
    </div>
</div>