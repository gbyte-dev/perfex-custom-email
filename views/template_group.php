        <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
        <?php init_head(); ?>
        <div id="wrapper">
        <div class="content">
        <div class="row">
        <div class="col-md-12">
        <div class="panel_s">
        <div class="panel-body">
        <div class="_buttons">
        <!-- Trigger the modal with a button -->
        <a href="#" data-toggle="modal" data-target="#myModal" class="btn btn-info mbot30">
        Add Template Group </a>
        <div class="clearfix"></div>
        <hr class="hr-panel-heading" />
        <div class="clearfix"></div>
        <?php render_datatable(array(
        _l('template_name'),
        'Created At',
        _l('options'),
        ),'template'); ?> 
        
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        
        <!-- Modal content-->
        <div class="modal-content">
        <form method="post" action="<?php echo admin_url();?>custom_email_and_sms_notifications/template/add_template_group">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Template Group</h4>
        </div>
        <div class="modal-body">
        <label for="group_name">Group Name</label>
        <input type="text" required placeholder="Enter Group Name" name="group_name" id="group_name" class="form-control">
        <input type="hidden" class="txt_csrfname" id="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <label for="template_group[]">Choose Templates</label>
        <?php 
        
        echo render_select('template_group[]', $templates, ['id', ['template_name']], '', $selected, ['multiple' => 'true','required' => 'true', 'data-actions-box' => true], [], '', '', false);
        ?>
        
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-success" >Add</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </form>
        </div>
        </div>
        
        </div>
        </div>
        
        </div>
        
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        
        <?php init_tail(); ?>
        <script>
        $(function(){
        initDataTable('.table-template', window.location.href, [1], [1]);
        });
        </script>
        </body>
        </html>
