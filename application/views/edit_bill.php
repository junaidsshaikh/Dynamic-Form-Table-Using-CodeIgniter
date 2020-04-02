<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css"/>
</head>

<body>

    <div class="container">
        <h3>Multiple Field Example</h3>
        <div id="user_message"></div>
        <form id="form_update_bill" method="post" autocomplete="off" accept-charset="utf-8"> 
            <div class="form-group">
            <input type="hidden" id="txtId" name="txtId" value="<?php echo $bill->id; ?>" />
            <input type="text" id="txtName" name="txtName" placeholder="Customer Name" required="required" class="form-control" value="<?php echo $bill->name; ?>" />
            </div>       
            <table id="cart_table" class="table table-sm table-stripped table-hover">
                <thead>
                    <tr>
                        <th width="19%">Title</th>
                        <th width="19%">Description</th>
                        <th width="19%">Count</th>
                        <th width="19%">Amount</th>
                        <th width="19%">Total</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                foreach($bill_cart as $row) {
                ?>
                    <tr>
                        <td>
                            <div class="form-group">
                                <input type="hidden" id="txtCartId" name="txtCartId" class="form-control txtCartId" value="<?php echo $row->id; ?>" />
                                <input type="text" name="txtTitle[]" placeholder="Title" required="required" class="form-control" value="<?php echo $row->title; ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" name="txtDescription[]" class="form-control" placeholder="Description" required="required" value="<?php echo $row->description; ?>"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" id="txtCount" name="txtCount[]" placeholder="Count" class="combat form-control" required="required" value="<?php echo $row->count; ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" id="txtItemAmount" name="txtItemAmount[]" placeholder="Amount" class="combat form-control" required="required" value="<?php echo $row->amount; ?>" />
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" id="txtTotal" name="txtTotal[]" placeholder="Total" class="forTotal form-control" readonly="readonly" value="<?php echo $row->total; ?>" />
                            </div>
                        </td>
                        <td>
                        <button type="button" class="btn btn-success btn-block btn-sm add_button"><i style="color:#fff" class="fa fa-plus-circle"></i></button>
                        <button type="button" class="btn btn-danger btn-block btn-sm remove_button"><i style="color:#fff;" class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                <?php    
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-center">
                            <input type="submit" id="btnSave" name="btnSave" value="Update" class="btn btn-md btn-success" />
                        </td>
                        <td>
                            <div class="form-group">
                                <input type="text" id="txtGrandTotal" name="txtGrandTotal" placeholder="Grand Total" class="forTotal form-control" readonly="readonly" value="<?php echo $bill->total; ?>" />
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </form>
        <h3>Display All Bills</h3>
        <table id="display_bill_table" class="table table-sm table-stripped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>CUSTOMER NAME</th>
                    <th>BILL AMOUNT</th>
                    <th>CREATED AT</th>
                    <th>ACTION</th>                    
                </tr>
            </thead>
        </table>
    </div>
    
    <script src="<?php echo base_url("assets/js/jquery.min.js"); ?>"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>

    <script type="text/javascript">
        var i = 1, max = 5;
        var cartTable = {
            options: {
                table: "#cart_table"
            },
            initialize: function() {
                this.setVars().events();
            },
            setVars: function() {
                this.$table = $(this.options.table);
                this.$totalLines = $(this.options.table).find('tr').length - 1;
                return this;
            },
            updateLines: function() {
                var totalLines = $(this.options.table).find('tr').length - 1;
                if (totalLines == 1) {
                    $('.add_button').show();
                    $('.remove_button').hide();
                }
                return this;
            },
            events: function() {
                var _self = this;
                _self.updateLines();
                this.$table.on('click', 'button.add_button', function(e) {
                    e.preventDefault();
                    if(max > i) {
                        var $tr = $(this).closest('tr');
                        var $clone = $tr.clone();
                        $clone.find(':text').val('');
                        $clone.find('.txtCartId').val('-');
                        $tr.after($clone);
                        if (_self.setVars().$totalLines > 1) {
                            $('.remove_button').show();
                            $('.add_button').show();
                        }
                        i++;
                    }
                }).on('click', 'button.remove_button', function(e) {
                    if (i > 1) {
                        e.preventDefault();
                        var $tr = $(this).closest('tr');
                        $tr.remove();
                        //if have delete last button with button add visible, add another button to last tr
                        if (_self.setVars().$totalLines > 1) {
                            _self.$table.find('tr:last').find('.add').show();
                        }
                        i--;
                    }
                });

                return this;
            }
        };

        function initializeCartTable() {
            cartTable.initialize();
        }
        window.addEventListener('load', initializeCartTable, false);
    </script>

    <script>
        var display_bill_table = "";
        $(document).ready(function() {

            display_bill_table = $('#display_bill_table').DataTable({
                "ajax": {
                    url : '<?php echo base_url("main/get_display_bills"); ?>',
					type : 'GET'
                },
            });

            $('#display_bill_table tbody').on( 'click', 'button', function () {
                if(this.name == "btnDelete") {
                    var isDelete = confirm("Once you delete the Bill, it will remove permanantly.");
                    if(isDelete) {
                        delete_bill(this.id);
                    } 
                }
            });

            $('#form_update_bill').submit(function(e) {
                e.preventDefault();
                var data = $("#form_update_bill").serialize();
                // alert(data);
                $.ajax({
                    type:"POST",
                    url:'<?php echo base_url("main/update_bill"); ?>',
                    data: data,
                    success: function(data) {
                        $("#user_message").html(data);
                        window.location.replace('<?php echo base_url("main/add") ?>');
                    },
                });
            });

            $('#cart_table').keyup(function(e) {
                calculate_total();
            });
            
        });

        function delete_bill(id) {
            $.ajax({
                    type:"POST",
                    url:'<?php echo base_url("main/delet_single_bill"); ?>',
                    data: {_id:id},
                    success: function(data) {
                        $("#user_message").html(data);
                        display_bill_table.ajax.reload();
                    },
                });
        }

        function calculate_total() {
            var grand_total = 0;
            $('#cart_table> tbody> tr').each(function(index, tr) { 
                var sum = 0;
                var amount = count = 0;
                $(this).find('.combat').each(function(inner_index) {
                    if(inner_index == 0) count = $(this).val();               
                    if(inner_index == 1) amount = $(this).val();     
                    if(amount != 0 && count != 0) {
                        sum = parseFloat(count) * parseFloat(amount);;
                    }
                });
                $('.forTotal',this).val(sum); 
                grand_total += sum; 
            });
            $("#txtGrandTotal").val(grand_total);
        }
    </script>

</body>

</html>