<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Admin Template
 */
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="application/json; charset=utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo base_url('/upload/institute/') ?>/logo.png" />
    <meta name="theme-color" content="#9c27b0">

    <title><?php echo $page_title; ?> - <?php echo $this->settings->site_name; ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css">

    <?php // CSS files ?>
    <?php if (isset($css_files) && is_array($css_files)) : ?>
        <?php foreach ($css_files as $css) : ?>
            <?php if ( ! is_null($css)) : ?>
                <link rel="stylesheet" href="<?php echo $css; ?>?v=<?php echo $this->settings->site_version; ?>"><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

</head>

<body class="theme-<?php echo $this->settings->admin_theme; ?>">
    <!-- Page Loader -->
    <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-<?php echo $this->settings->admin_theme; ?>">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="<?php echo lang('action_search'); ?>">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-header">
                <a href="javascript:void(0);" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false"></a>
                <a href="javascript:void(0);" class="bars"></a>
                <a class="navbar-brand" href="<?php echo site_url('admin'); ?>">
                    <?php echo $this->settings->site_name; ?>

                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    <!-- Call Search -->
                    <li><a href="javascript:void(0);" class="js-search" data-close="true"><i class="material-icons">search</i></a></li>
                    <!-- #END# Call Search -->
                    <!-- Notifications -->
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="material-icons">notifications</i>
                            <?php if(count($this->notifications)) { ?><span class="label-count"><?php echo count($this->notifications) ?></span><?php } ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?php echo lang('notifications') ?></li>
                            <li class="body">
                                <ul class="menu">
                                    <?php if(!empty($this->notifications)) { foreach($this->notifications as $key => $val) { ?>
                                    <li>
                                        <a href="javascript:read_notification(`<?php echo $val->n_type ?>`, `<?php echo $val->n_url ?>`);">
                                            <div class="icon-circle bg-<?php echo $this->settings->admin_theme ?>">
                                                <?php if($val->n_type == 'users') { ?>
                                                <i class="material-icons">person_add</i>
                                                <?php } else if($val->n_type == 'events' || $val->n_type == 'batches') { ?>
                                                <i class="material-icons">loupe</i>
                                                <?php } else if($val->n_type == 'bbookings' || $val->n_type == 'ebookings') { ?>
                                                <i class="material-icons">monetization_on</i>
                                                <?php } else if($val->n_type == 'b_cancellation' || $val->n_type == 'e_cancellation') { ?>
                                                <i class="material-icons">money_off</i>
                                                <?php } else if($val->n_type == 'contacts') { ?>
                                                <i class="material-icons">email</i>
                                                <?php } ?>
                                            </div>
                                            <div class="menu-info">
                                                <h4><?php echo $val->total.' '.lang('noti_new').' '.sprintf(lang(''.$val->n_content.''), lang('menu_'.$val->n_type.'')); ?></h4>
                                                <p><i class="material-icons">access_time</i> <?php echo time_elapsed_string($val->date_added); ?></p>
                                            </div>
                                        </a>
                                    </li>
                                    <?php } } else { ?>
                                    <li><a href="#" class="text-center"><?php echo lang('noti_no'); ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <!-- #END# Notifications -->

                    <!-- Language Dropdown -->
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="javascript:void(0);" data-toggle="dropdown" role="button">
                            <i class="material-icons">language</i>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header"><?php echo lang('languages') ?></li>
                            <li class="body">
                                <ul class="menu" id="session-language-dropdown">
                                    <?php foreach ($this->languages as $key=>$name) : ?>
                                    <li>
                                        <a href="#" rel="<?php echo $key; ?>">
                                            <?php if ($key == $this->session->language) : ?>
                                                <i class="fa fa-check selected-session-language"></i>
                                            <?php endif; ?>
                                            <?php echo $name; ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info bg-<?php echo $this->settings->admin_theme; ?>">
                <div class="image">
                    <img src="<?php echo base_url(); ?><?php echo ($_SESSION['logged_in']['image'] == '') ? '/themes/admin/img/avatar2.png' : '/upload/users/images/'.image_to_thumb($_SESSION['logged_in']['image']); ?>" width="48" height="48" alt="User Image">
                </div>
                <div class="info-container">
                    <div class="name text-capitalize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['logged_in']['first_name'].' '.$_SESSION['logged_in']['last_name']; ?></div>
                    <div class="email"><?php echo $_SESSION['logged_in']['email'] ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?php echo site_url('admin/users/form/').$_SESSION['logged_in']['id']; ?>"><i class="material-icons">person</i><?php echo lang('action_profile'); ?></a></li>
                            <li role="seperator" class="divider"></li>
                            <li><a href="<?php echo site_url('logout'); ?>"><i class="material-icons">input</i><?php echo lang('action_logout'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li>
                        <a href="<?php echo site_url('/'); ?>">
                            <i class="material-icons">home</i>
                            <span><?php echo lang('menu_access_website'); ?></span>
                        </a>
                    </li>
                    <li class="header"><?php echo lang('menu_main_navigation') ?></li>

                    <!-- Dashboard -->
                    <li class="<?php echo (uri_string() == 'admin' OR uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
                      <a href="<?php echo site_url('/admin'); ?>">
                        <i class="material-icons">dashboard</i>
                        <span><?php echo lang('menu_dashboard'); ?></span>
                      </a>
                    </li>

                    <!-- Events -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/events') || strstr(uri_string(), 'admin/eventtypes')) ? ' active' : ''; ?>">
                      <a href="#" class="menu-toggle">
                        <i class="material-icons">event</i>
                        <span><?php echo lang('menu_events'); ?></span>
                      </a>
                      <ul class="ml-menu">
                        <li class="<?php echo (strstr(uri_string(), 'admin/events')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/events'); ?>">
                            <?php echo lang('menu_events'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/eventtypes')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/eventtypes'); ?>">
                            <?php echo lang('menu_event_types'); ?>
                          </a>
                        </li>
                      </ul>
                    </li>

                    <!-- Bookings -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/bbookings') || strstr(uri_string(), 'admin/ebookings')) ? ' active' : ''; ?>">
                      <a href="#" class="menu-toggle">
                        <i class="material-icons">monetization_on</i>
                        <span><?php echo lang('menu_bookings'); ?></span>
                      </a>
                      <ul class="ml-menu">
                        <li class="<?php echo (strstr(uri_string(), 'admin/bbookings')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/bbookings'); ?>">
                            <?php echo lang('menu_b_bookings'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/ebookings')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/ebookings'); ?>">
                            <?php echo lang('menu_e_bookings'); ?>
                          </a>
                        </li>
                      </ul>
                    </li>

                    <!-- Users -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/users') || strstr(uri_string(), 'admin/groups') || strstr(uri_string(), 'admin/manageacl')) ? ' active' : ''; ?>">
                      <a href="#" class="menu-toggle">
                        <i class="material-icons">face</i>
                        <span><?php echo lang('menu_users'); ?></span>
                      </a>
                      <ul class="ml-menu">
                        <li class="<?php echo (strstr(uri_string(), 'admin/users')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/users'); ?>">
                            <?php echo lang('menu_users'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/groups')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/groups'); ?>">
                            <?php echo lang('menu_groups'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/manageacl')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/manageacl'); ?>">
                            <?php echo lang('menu_manage_acl'); ?>
                          </a>
                        </li>
                      </ul>
                    </li>


                    <!-- Contacts -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/contacts')) ? ' active' : ''; ?>">
                      <a href="<?php echo site_url('/admin/contacts'); ?>">
                        <i class="material-icons">contacts</i>
                        <span><?php echo lang('menu_contacts'); ?></span>
                      </a>
                    </li>

                    <!-- Testimonials -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/testimonials')) ? ' active' : ''; ?>">
                      <a href="<?php echo site_url('/admin/testimonials'); ?>">
                        <i class="material-icons">insert_comment</i>
                        <span><?php echo lang('menu_testimonials'); ?></span>
                      </a>
                    </li>

                    <!-- Galleries -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/gallaries')) ? ' active' : ''; ?>">
                      <a href="<?php echo site_url('/admin/gallaries'); ?>">
                        <i class="material-icons">photo_library</i>
                        <span><?php echo lang('menu_gallaries'); ?></span>
                      </a>
                    </li>

                  
                    <!-- CMS -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/pages') || strstr(uri_string(), 'admin/menus') || strstr(uri_string(), 'admin/faqs')) ? ' active' : ''; ?>">
                      <a href="#" class="menu-toggle">
                        <i class="material-icons">developer_board</i>
                        <span><?php echo lang('cms'); ?></span>
                      </a>
                      <ul class="ml-menu">
                        <li class="<?php echo (strstr(uri_string(), 'admin/pages')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/pages'); ?>">
                            <?php echo lang('menu_pages'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/menus')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/menus'); ?>">
                            <?php echo lang('menu_menus'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/faqs')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/faqs'); ?>">
                            <?php echo lang('menu_faqs'); ?>
                          </a>
                        </li>
                      </ul>
                    </li>

                    <!-- Masters -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/languages') || strstr(uri_string(), 'admin/emailtemplates') || strstr(uri_string(), 'admin/currencies') || strstr(uri_string(), 'admin/customfields') || strstr(uri_string(), 'admin/taxes')) ? ' active' : ''; ?>">
                      <a href="#" class="menu-toggle">
                        <i class="material-icons">lock_outline</i>
                        <span><?php echo lang('menu_admin') ?></span>
                      </a>
                      <ul class="ml-menu">
                        <li class="<?php echo (strstr(uri_string(), 'admin/languages')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/languages'); ?>">
                            <?php echo lang('menu_languages'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/emailtemplates')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/emailtemplates'); ?>">
                            <?php echo lang('menu_email_templates'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/currencies')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/currencies'); ?>">
                            <?php echo lang('menu_currencies'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/customfields')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/customfields'); ?>">
                            <?php echo lang('menu_custom_fields'); ?>
                          </a>
                        </li>
                        <li class="<?php echo (strstr(uri_string(), 'admin/taxes')) ? ' active' : ''; ?>">
                          <a href="<?php echo site_url('/admin/taxes'); ?>">
                            <?php echo lang('menu_taxes'); ?>
                          </a>
                        </li>
                      </ul>
                    </li>

                    <!-- Settings -->
                    <li class="<?php echo (strstr(uri_string(), 'admin/settings')) ? ' active' : ''; ?>">
                      <a href="<?php echo site_url('/admin/settings'); ?>">
                        <i class="material-icons">settings</i>
                        <span><?php echo lang('menu_settings'); ?></span>
                      </a>
                    </li>

                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="version">
                  <?php echo $this->settings->site_name; ?> v<?php echo $this->settings->site_version; ?>
                </div>
                <div class="copyright">&copy; 2017 <a href="http://classiebit.com">Classiebit</a></div>
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-md-3">
                    <div class="block-header">
                        <h2 class="text-uppercase"><?php echo $page_header; ?></h2>
                    </div>
                </div>
                <div class="col-md-9">
                     <!-- Show breadcrumb -->
                    <div class="breadc pull-right">
                        <ol class="breadcrumb" >
                            <?php
                                echo !$this->uri->segment(2) ? '<li class="active">'.lang('menu_dashboard').'</li>' : '<li><a href="'.site_url().'/'.$this->uri->segment(1).'">'.lang('menu_dashboard').'</a></li>';

                                if($this->uri->segment(2))
                                    echo !$this->uri->segment(3) ? '<li class="active">'.lang('menu_'.$this->uri->segment(2)).'</li>' : '<li><a href="'.site_url().'/'.$this->uri->segment(2).'">'.lang('menu_'.$this->uri->segment(2)).'</a></li>';


                                if($this->uri->segment(3))
                                    echo '<li class="active">'.($this->uri->segment(3) !== 'form' ? lang('action_'.$this->uri->segment(3)) : ($this->uri->segment(4) ? lang('action_edit') : lang('action_create'))).'</li>';
                            ?>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Ajax validation error -->
            <div class="alert alert-danger alert-dismissable" id="validation-error">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <p></p>
            </div>

            <!--  page content -->
            <?php echo $content; ?>
            <!-- /.page content -->
        </div>
    </section>

    <?php // Javascript files ?>
    <?php if (isset($js_files) && is_array($js_files)) : ?>
        <?php foreach ($js_files as $js) : ?>
            <?php if ( ! is_null($js)) : ?>
                <?php echo "\n"; ?><script type="text/javascript" src="<?php echo $js; ?>?v=<?php echo $this->settings->site_version; ?>"></script><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($js_files_i18n) && is_array($js_files_i18n)) : ?>
        <?php foreach ($js_files_i18n as $js) : ?>
            <?php if ( ! is_null($js)) : ?>
                <?php echo "\n"; ?><script type="text/javascript"><?php echo "\n" . $js . "\n"; ?></script><?php echo "\n"; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <script type="text/javascript">
        var admin_theme = "<?php echo $this->settings->admin_theme; ?>";
        var base_url    = "<?php echo base_url().'/'; ?>";
        var site_url    = "<?php echo site_url().'/'; ?>";
        var uri_seg_1   = "<?php echo $this->uri->segment(1); ?>";
        var uri_seg_2   = "<?php echo $this->uri->segment(2); ?>";
        var uri_seg_3   = "<?php echo $this->uri->segment(3); ?>";
        var csrf_name   = "<?php echo $this->security->get_csrf_token_name(); ?>";
        var csrf_token  = "<?php echo $this->security->get_csrf_hash(); ?>";

        /*System Notification*/
        $(function() {
            var message     = `<?php echo null !== $this->session->flashdata('message') ? $this->session->flashdata('message') : null ?>`;
            var error       = `<?php echo null !== $this->session->flashdata('error') ? $this->session->flashdata('error') : null ?>`;
            var v_errors    = `<?php echo null !== validation_errors() ? validation_errors() : null ?>`;
            var s_error     = `<?php echo null !== $this->error ? $this->error : null ?>`;

            if(message != '') show_success(message);
            if(error != '') show_danger(error);
            if(v_errors != '') show_danger(v_errors);
            if(s_error != '') show_danger(s_error);
        });
    </script>

</body>
</html>
