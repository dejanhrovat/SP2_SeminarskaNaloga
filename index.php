<!DOCTYPE html>
<html>
    <head>
        <title>SP2</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
        <br/>
        <h3 align="center">SP2 Seminarska Naloga</h3>
        <br/>
        <div align="right" style="margin-bottom: 5px;">
            <button type="button" name="add_button" id="add_button" class="btn btn-success btn-xs">Add</button> 
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Ime</th>
                        <th>Priimek</th>
                        <th>Naslov</th>
                        <th>Edit</th>
                        <th>Delet</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        </div>
        <div id="addModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="form">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Add Data</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Ime</label>
                                <input type="text" name="first_name" id="first_name" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Priimek</label>
                                <input type="text" name="last_name" id="last_name" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Naslov</label>
                                <input type="text" name="homeAddress" id="homeAddress" class="form-control" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <input type="hidden" name="action" id="action" value="insert" />
                            <input type="submit" name="button_action" id="button_action" class="btn btn-info" value="Vstavi" />
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zapri</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                fetch_data();
                function fetch_data(){
                    $.ajax({
                        url:"fetch.php",
                        success:function(data)
                        {
                            $('tbody').html(data);
                        }
                    })
                }

                $('#add_button').click(function(){
                    $('#action').val('insert');
                    $('#first_name').val('');
                    $('#last_name').val('');
                    $('#homeAddress').val('');
                    $('#button_action').val('Vstavi');
                    $('.modal-title').text('Dodajanje uporabnika');
                    $('#addModal').modal('show');
                });

                $('#form').on('submit', function(event){
                    event.preventDefault();
                    if($('#first_name').val() == '' && $('#last_name').val() == '' && $('#homeAddress'.val()=='')){
                        alert("Vpišite podatke!");
                    }else if($('#first_name').val() == ''){
                        alert("Vpišite ime!");
                    }else if($('#last_name').val() == ''){
                        alert("Vpišite priimek!");
                    }else if($('#first_name').val()=='' && $('#last_name').val()==''){
                        alert("Vpišite ime in priimek!");
                    }else if($('#homeAddress').val()==''){
                        var confirmData=confirm("Ali želite nadeljevati brez naslova?");
                        if(confirmData==true){
                            var first_name=$('#first_name').val();
                            var last_name=$('#last_name').val();
                            var homeAddress=$('#homeAddress').val();
                            var confirm_Data = confirm("Preverite ali ste pravilno vnesli podatke!\n Ime: "+first_name+"\n Priimek: "+last_name+"\n Naslov: "+homeAddress);
                            if(confirm_Data==true){
                                var form_data=$(this).serialize();
                                $.ajax({
                                    url:"action.php",
                                    method:"POST",
                                    data:form_data,
                                    success:function(data){
                                        fetch_data();
                                        $('#form')[0].reset();
                                        $('#addModal').modal('hide');
                                        if(data=='insert'){
                                            alert("Podatki so bili uspešno vstavljeni z uporabo obrazca");
                                        }
                                        if(data=='update'){
                                            alert("Podatki uspešno spremenjeni z uporabo PHP API");
                                        }
                                    }
                                });
                            }
                        }
                    }else{
                        var first_name=$('#first_name').val();
                        var last_name=$('#last_name').val();
                        var homeAddress=$('#homeAddress').val();
                        var confirmData = confirm("Preverite ali ste pravilno vnesli podatke!\n Ime: "+first_name+"\n Priimek: "+last_name+"\n Naslov: "+homeAddress);
                        if(confirmData==true){
                            var form_data=$(this).serialize();
                            $.ajax({
                                url:"action.php",
                                method:"POST",
                                data:form_data,
                                success:function(data){
                                    fetch_data();
                                    $('#form')[0].reset();
                                    $('#addModal').modal('hide');
                                    if(data=='insert'){
                                        alert("Podatki so bili uspešno vstavljeni z uporabo obrazca");
                                    }
                                    if(data=='update'){
                                        alert("Podatki uspešno spremenjeni z uporabo PHP API");
                                    }
                                }
                            });
                        }
                    }
                });

                $(document).on('click', '.edit', function(){
                    var id=$(this).attr('id');
                    var action='fetch_single';
                    $.ajax({
                        url:"action.php",
                        method:"POST",
                        data:{id:id, action:action},
                        dataType:"json",
                        success:function(data){
                            $('#hidden_id').val(id);
                            $('#first_name').val(data.first_name);
                            $('#last_name').val(data.last_name);
                            $('#homeAddress').val(data.homeAddress);
                            $('#action').val('update');
                            $('#button_action').val('Uredi');
                            $('.modal-title').text('Uredite podatke');
                            $('#addModal').modal('show');
                        }
                    })
                });

                $(document).on('click', '.delete', function(){
                    var id=$(this).attr("id");
                    var action='delete';
                    if(confirm("Ali ste sigurni da želi izbrisati uporabnika?")){
                        $.ajax({
                            url:"action.php",
                            method:"POST",
                            data:{id:id, action:action},
                            success:function(data){
                                fetch_data();
                                alert("Podatki uporabnika so bili izbrisani!");
                            }
                        });
                    }
                });
            });
        </script>
    </body>
</html>