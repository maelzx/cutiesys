<?php

$this->load->view('top_section');
$this->load->view('header_section');
$this->load->view('sidebar_section', array('page' => 'member'));

?>

    <style>
        .user-panel .info {
            left: 0px !important;
        }
    </style>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
        Dashboard
        <small>All user leave schedule</small>
      </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="box box-primary">
                        <div class="box-body no-padding">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

<?php

$this->load->view('bottom_section');

?>

<!-- modal for leave application -->
<div id="modal_leave_apply" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Apply Leave</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" id="form_apply_leave_user_id" value="<?php print $this->session->cutiesys_user_data['id']; ?>" />
                    <div class="form-group">
                        <label>Please select leave start date &amp; leave end date:</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right active" id="form_apply_leave_start_date_end_date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Please state your reason:</label>
                        <textarea id="form_apply_leave_reason" class="form-control" rows="3" placeholder=""></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button id="form_apply_leave_submit" type="button" class="btn btn-primary">Apply</button>
            </div>
        </div>
    </div>
</div>

<!-- modal for leave listing -->
<div id="modal_leave_list" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Leave Listing</h4>
            </div>
            <div class="modal-body">
                <table id="dtt_leave_list" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- modal for leave history listing -->
<div id="modal_leave_history_list" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Leave History Listing</h4>
            </div>
            <div class="modal-body">
                <table id="dtt_leave_history_list" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
<!-- modal for leave approval -->
<div id="modal_leave_approval_list" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Leave Approval Listing</h4>
            </div>
            <div class="modal-body">
                <table id="dtt_leave_approval_list" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Applied By</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="form_approve_leave_submit" type="button" class="btn btn-primary">Approve</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php

$this->load->view('script_section');

?>

<!-- Page specific script -->
<script>
    $(document).ready(function() {
        dtt_leave_list = '';
        dtt_leave_history = '';
        dtt_leave_approval = '';
        $("#link_leave_application").on('click', function() {
            $('#modal_leave_apply').modal();
            $(this).parent().addClass('active');
        });
        
        $("#form_apply_leave_submit").click(function(){
            //$(this).html('Submitting...');
            
            var data = {
                start_date_end_date : $("#form_apply_leave_start_date_end_date").val(),
                reason : $("#form_apply_leave_reason").val(),
                user_id : $("#form_apply_leave_user_id").val(),
                apply_leave : 'yes',
            }
            
            $.post('<?php print base_url(); ?>index.php/main/member_ajax', data, function(result){
                if (result.status == 'success')
                {
                    //data was successfully added
                    alert(result.success_message);
                    $('#modal_leave_apply').modal('hide');
                    refresh_dtt_leave_list();
                    refresh_dtt_leave_approval_list();
                }
                else if (result.status == 'fail'){
                    alert(result.error_message);
                }
            })
            
            //$(this).html('Apply');
        });
        
        <?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
        $("#form_approve_leave_submit").click(function(){
            //$(this).html('Submitting...');
            
            var checkboxValues = [];
            $('input[name=leave_id]:checked').map(function() {
                        checkboxValues.push($(this).val());
            });
            
            var data = {
                leave_id : checkboxValues,
                approve_leave : 'yes',
            }
            
            $.post('<?php print base_url(); ?>index.php/main/member_ajax', data, function(result){
                if (result.status == 'success')
                {
                    //data was successfully added
                    alert(result.success_message);
                    $('#modal_leave_approval_list').modal('hide');
                    refresh_dtt_leave_approval_list();
                    refresh_dtt_leave_list();
                    refresh_dtt_leave_history_list();
                }
                else if (result.status == 'fail'){
                    alert(result.error_message);
                }
            })
            
            //$(this).html('Apply');
        });
        <?php } ?>

        $("#link_leave_listing").on('click', function() {
            $('#modal_leave_list').modal();
            $(this).parent().addClass('active');
        });

        $("#link_leave_history").on('click', function() {
            $('#modal_leave_history_list').modal();
            $(this).parent().addClass('active');
        });
        
        <?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
        $("#link_approve_application").on('click', function() {
            $('#modal_leave_approval_list').modal();
            $(this).parent().addClass('active');
        });
        <?php } ?>

        $('#modal_leave_apply').on('hidden.bs.modal', function(e) {
            $("#link_leave_application").parent().removeClass('active');
        })
        
        $('#modal_leave_list').on('hidden.bs.modal', function(e) {
            $("#link_leave_listing").parent().removeClass('active');
        })
        
        $('#modal_leave_history_list').on('hidden.bs.modal', function(e) {
            $("#link_leave_history").parent().removeClass('active');
        })
        
        <?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
        $('#modal_leave_approval_list').on('hidden.bs.modal', function(e) {
            $("#link_approve_application").parent().removeClass('active');
        })
        <?php } ?>
        
        //Date range picker
        $('#form_apply_leave_start_date_end_date').daterangepicker({
            autoUpdateInput: false,
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": "-",
            }
        });
        
        $('#form_apply_leave_start_date_end_date').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#form_apply_leave_start_date_end_date').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
        
        dtt_leave_list = $('#dtt_leave_list').DataTable( {
            "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
                }],
            "columns": [
                { "data": "start_date" },
                { "data": "end_date" },
                { "data": "reason" },
                { "data": "status" },
                ],
            "ajax": '<?php print base_url() ?>index.php/main/member_ajax/leave_list',
            //"paging":   false,
            "ordering": false,
            "info":     false,
            "filter":   false
        } );
        
        dtt_leave_history = $('#dtt_leave_history_list').DataTable( {
            "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
                }],
            "columns": [
                { "data": "start_date" },
                { "data": "end_date" },
                { "data": "reason" }
                ],
            "ajax": '<?php print base_url() ?>index.php/main/member_ajax/leave_history',
            //"paging":   false,
            "ordering": false,
            "info":     false,
            "filter":   false
        } );
        
        <?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
        dtt_leave_approval = $('#dtt_leave_approval_list').DataTable( {
            "columnDefs": [{
                "defaultContent": "-",
                "targets": "_all"
                }],
            "columns": [
                { "data": "id" },
                { "data": "full_name" },
                { "data": "start_date" },
                { "data": "end_date" },
                { "data": "reason" },
                ],
            'columnDefs': [{
                'targets': 0,
                'searchable': false,
                'orderable': false,
                'className': 'dt-body-center',
                'render': function (data, type, full, meta){
                    return '<input type="checkbox" name="leave_id" value="' + $('<div/>').text(data).html() + '">';
                }
            }],
            'order': [[1, 'asc']],
            "ajax": '<?php print base_url() ?>index.php/main/member_ajax/leave_approval',
            //"paging":   false,
            "ordering": false,
            "info":     false,
            "filter":   false
        } );
        <?php } ?>
        
        function refresh_dtt_leave_list()
        {
            dtt_leave_list.ajax.reload();
        }
        
        function refresh_dtt_leave_history_list()
        {
            dtt_leave_history.ajax.reload();
        }
        
        <?php if ($this->session->cutiesys_user_data['is_approver'] == 1){ ?>
        function refresh_dtt_leave_approval_list()
        {
            dtt_leave_approval.ajax.reload();
        }
        <?php } ?>
        
    });

    $(function() {

        /* initialize the calendar
         -----------------------------------------------------------------*/
        //Date for the calendar events (dummy data)
        var date = new Date();
        var d = date.getDate(),
            m = date.getMonth(),
            y = date.getFullYear();
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                //right: 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: 'today',
                //month: 'month',
                //week: 'week',
                //day: 'day'
            },
            //Random default events
//             events: [{
//                 title: 'Ismail On Leave',
//                 start: new Date(y, m, 8),
//                 end: new Date(y, m, 13),
//                 allDay: true,
//             }, {
//                 title: 'Azizi On Leave',
//                 start: new Date(y, m, 4),
//                 end: new Date(y, m, 13),
//                 allDay: true,
//             }, {
//                 title: 'Farizul On Leave',
//                 start: new Date(y, m, 5),
//                 end: new Date(y, m, 12),
//                 allDay: true,
//             }, {
//                 title: 'Hari Raya Holiday',
//                 start: new Date(y, m, 6),
//                 end: new Date(y, m, 8),
//                 backgroundColor: "#f39c12",
//                 borderColor: "#f39c12",
//                 allDay: true,
//             }, {
//                 title: 'XXX Start On Leave',
//                 start: new Date(y, m, 1),
//                 end: new Date(y, m, 13),
//                 allDay: true,
//             }, {
//                 title: 'YYY On Leave',
//                 start: new Date(y, m, 11),
//                 end: new Date(y, m, 13),
//                 allDay: true,
//             }],
            events: '<?php print base_url('index.php/main/leave_ajax'); ?>/',
            editable: false,
            droppable: false,
        });
    });
</script>
</body>

</html>