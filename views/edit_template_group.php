            <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
            <?php init_head(); ?>
            <div id="wrapper">
            <div class="content">
            <div class="row">
            <div class="col-md-12">
            <div class="panel_s">
            <div class="panel-body">
            
            
            
            
            <form method="post" action="<?php echo admin_url();?>custom_email_and_sms_notifications/template/edit_template">
            
            <h4 >Edit Template Group</h4>
            <br><br>
            
            <label for="group_name">Group Name</label>
            <input type="text" required placeholder="Enter Group Name" name="group_name" id="group_name" class="form-control" value="<?php echo $records['name'];?>">
            <input type="hidden" name="id" value="<?php echo $records['id'];?>">
            <input type="hidden" class="txt_csrfname" id="txt_csrfname" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <label for="template_group[]">Choose Templates</label>
            <?php 
            $array = explode(',',$records['template_id']);
            
            $selected = $array;
            echo render_select('template_group[]', $templates, ['id', ['template_name']], '', $selected, ['multiple' => 'true','required' => 'true', 'data-actions-box' => true], [], '', '', false);
            ?>
            
            <br><br>
            
            <button type="submit" class="btn btn-success" >Edit</button>
            
            </form>
            
            
            </div>
            </div>
            </div>
            </div>
            </div>
            </div>
            
            <?php init_tail(); ?>
            
            </body>
            </html>
