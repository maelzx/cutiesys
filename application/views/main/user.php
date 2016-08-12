<?php

$this->load->view('top_section');
$this->load->view('header_section');
$this->load->view('sidebar_section', array('page' => 'user'));

?>

    <style>
        .user-panel .info {
            left: 0px !important;
        }
        #btn_add_user {
            margin-bottom: 5px;
        }
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
        User Management
        <small>Manage All User</small>
      </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-2">
                            <button id="btn_add_user" class="btn btn-primary">Add User</button>
                        </div>
                    </div>
                    <div class="row">
                        <table id="dtt_user_list" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Login</th>
                                    <th>Full Name</th>
                                    <th>Approver</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php

$this->load->view('bottom_section');

?>

<!-- modal for adding user -->
<div id="modal_add_user" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New User</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <label>Full Name:</label>
                        <div class="input-group col-md-12">
                            <input type="text" class="form-control" id="txt_user_full_name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Login Name:</label>
                        <div class="input-group col-md-12">
                            <input type="text" class="form-control" id="txt_user_login">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Password:</label>
                        <div class="input-group col-md-12">
                            <input type="text" class="form-control" id="txt_user_password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Is Approver ?:</label>
                        <div class="input-group">
                            <input type="checkbox" id="chkbox_user_is_approver" value="1"> User is Approver
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="form_new_user_submit" type="button" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

<!-- modal for reset user password -->
<div id="modal_reset_password" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reset Password</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <label>New Password:</label>
                        <div class="input-group col-md-12">
                            <input type="hidden" id="hddn_reset_password_user_id" value="">
                            <input type="text" class="form-control" id="txt_reset_password_new">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="form_reset_password_submit" type="button" class="btn btn-primary">Reset</button>
            </div>
        </div>
    </div>
</div>

<?php

$this->load->view('script_section');

?>

<!-- Page specific script -->
<script>
    $(document).ready(function() {
        dtt_user_list = '';
        
        $("#btn_add_user").click(function(){
            $("#modal_add_user").modal();
        });
        
        $("#form_new_user_submit").click(function(){
            //$(this).html('Submitting...');
            
            var data = {
                full_name : $("#txt_user_full_name").val(),
                login : $("#txt_user_login").val(),
                password : $("#txt_user_password").val(),
                is_approver: $("#chkbox_user_is_approver:checked").val(),
                new_user : 'yes',
            }
            
            $.post('<?php print base_url(); ?>index.php/main/user_ajax', data, function(result){
                if (result.status == 'success')
                {
                    //data was successfully added
                    alert(result.success_message);
                    $('#modal_add_user').modal('hide');
                    refresh_dtt_user_list();
                }
                else if (result.status == 'fail'){
                    alert(result.error_message);
                }
            })
            
            //$(this).html('Apply');
        });
        
        $("#form_reset_password_submit").click(function(){
            //$(this).html('Submitting...');
            
            var data = {
                userid : $("#hddn_reset_password_user_id").val(),
                password : $("#txt_reset_password_new").val(),
                reset_password : 'yes',
            }
            
            $.post('<?php print base_url(); ?>index.php/main/user_ajax', data, function(result){
                if (result.status == 'success')
                {
                    //data was successfully added
                    alert(result.success_message);
                    $('#modal_reset_password').modal('hide');
                    refresh_dtt_user_list();
                }
                else if (result.status == 'fail'){
                    alert(result.error_message);
                }
            })
            
            //$(this).html('Apply');
        });
        
        dtt_user_list = $('#dtt_user_list').DataTable( {
            "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
                }],
            "columns": [
                { "data": "login" },
                { "data": "full_name" },
                { "data": "is_approver" },
                { "data": "id" },
                ],
            'columnDefs': [{
                'targets': 3,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                    return '<button class="btn btn-xs btn-warning btn-reset" data-userid="' + $('<div/>').text(data).html() + '">Reset Password</button>';
                }
            }],
            "ajax": '<?php print base_url() ?>index.php/main/user_ajax/user_list',
            //"paging":   false,
            "ordering": false,
            "info":     false,
            "filter":   false
        } );
        
        function refresh_dtt_user_list()
        {
            dtt_user_list.ajax.reload();
        }
        
        $("#dtt_user_list").on('click', 'button', function(){
            $("#modal_reset_password").modal();
            $("#hddn_reset_password_user_id").val($(this).data("userid"));
        });
        
    });
</script>
</body>

</html>