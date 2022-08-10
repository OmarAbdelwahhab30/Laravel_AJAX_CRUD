<!DOCTYPE html>
<html lang="{{App::getLocale()}}" dir="{{(App::isLocale('ar') ? 'rtl' : 'ltr')}}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{__("index.AJAX")}}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            @import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@300&family=Readex+Pro:wght@200&display=swap');
            *{
                font-family: 'IBM Plex Sans Arabic', sans-serif;
            }
        </style>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        <script>
            $( document ).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                GetEmployees()
            });
        </script>
    </head>
    <body>
    <nav class="navbar navbar-dark bg-dark fixed-top mb-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">{{__("index.Dashboard")}}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">{{__("index.Choose_lang")}}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a style="text-decoration: none; color: white" rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </nav>
        <div class="container mt-xl-5 pt-5">


            <div class="alert alert-success" id="success_msg" style="display: none;">
                {{__("index.success_msg")}}
            </div>

            <form method="POST" id="EmployeeForm" action="" enctype="application/x-www-form-urlencoded">
            @csrf
                <fieldset>
                    <legend>{{__("index.addnewempl")}}</legend>
                    <div class="mb-3 row">
                        <div>
                            <label for="name" class="col-sm-2 col-form-label">{{__("index.empname")}} </label>
                            <div class="col-sm-10">
                                <input type="Text" class="form-control" name="name" id="inputname" required>
                            </div>


                            <!--Employee Name Error--->
                            <small id="name_error" class="form-text text-danger"  style="display: none"></small>



                        </div>
                        <div>
                            <label for="name" class="col-sm-2 col-form-label">{{__("index.empphone")}}</label>
                            <div class="col-sm-10">
                                <input type="tel" class="form-control" name="phone" id="inputtel" required>
                            </div>

                            <!--Employee Phone Error--->
                            <small id="phone_error" class="form-text text-danger" style="display: none"></small>


                        </div>
                    </div>
                    <button type="submit" id="add" class="btn btn-outline-success">{{__("index.add")}}</button>
                </fieldset>
            </form>

            <hr>
                <input type="search" id="search" class="form-control" placeholder="{{__("index.searchplaceholder")}}">
            <hr>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__("index.name")}}</th>
                    <th scope="col">{{__("index.phone")}}</th>
                    <th scope="col">{{__("index.handle")}}</th>
                </tr>
                </thead>
                <tbody id="content">

                </tbody>
            </table>
        </div>
    </body>
    <!-- Hide All Employees  -->
    <script>
        function HideEmployees() {
            $("#content").empty();
        }
    </script>
    <!-- Display All Employees  -->
    <script>
        function GetEmployees() {
            $.ajax({
                type: "GET",
                url: "{{route("employee.get")}}",
                success: function (data) {
                    data.forEach(function (employee) {
                        content = '<tr>' +
                            "<td>" + employee.id + "</td>"
                            + "<td>" + employee.name + "</td>"
                            + "<td>" + employee.phone + "</td>"
                            + '<td>' +
                            '<button id="delete" data-id="' + employee.id + '"  class="btn btn-outline-danger m-2">' + '{{__("index.delete")}}' + '</button>' +
                            '<button id="edit1" data-id="' + employee.id + '"   class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#UpdateModal" >' + '{{__("index.edit")}}' + '</button>'
                            + '</td>' +
                            '</tr>';
                        $("#content").append(content);
                    });
                }
            })
        }
    </script>
    <!--Add Employee -->
    <script>
        $(document).on('click', '#add', function (e) {
            e.preventDefault();
            var formData = new FormData($('#EmployeeForm')[0]);
            $.ajax({
                type: 'post',
                enctype: 'application/x-www-form-urlencoded',
                url: "{{route('employee.store')}}",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,

                success: function (data) {
                        $("#name_error").hide();
                        $("#phone_error").hide();
                        $('#success_msg').show();
                        HideEmployees();
                        GetEmployees();
                },
                error: function (reject) {
                    var response = $.parseJSON(reject.responseText);
                    $.each(response.errors, function (key, val) {
                        $("#" + key + "_error").show();
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            });
        });
    </script>
    <!--Delete Employee -->
    <script>
        $(document).on('click', '#delete', function() {
            var EmployeeID = $(this).attr("data-id");
            $.ajax({
                type: 'POST',
                url: '{{route("employee.destroy")}}',
                data: {id: EmployeeID},
                cache: false,
                success: function (data) {
                    if (data === 'success'){
                        $("#success_msg").show();
                        HideEmployees()
                        GetEmployees()
                    }else if(data === "fail" ){
                        $("#fail").show();
                        $("#fail").html("{{__("index.thereerror")}}");
                    }
                },
            })
        })
    </script>
    <!---Update Modal-->
    <div class="modal fade" id="UpdateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__("index.employeeEdit")}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="UpdateForm" action="" enctype="application/x-www-form-urlencoded">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">{{__("index.name")}}</label>
                            <input type="text" class="form-control" name="name" id="name-edit" required>
                        </div>
                        <!--Employee Name Error--->
                        <small id="Uname_error" class="form-text text-danger"  style="display: none"></small>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">{{__("index.phone")}}</label>
                            <input type="tel" class="form-control" name="phone" id="phone-edit" required>
                        </div>
                        <!--Employee phone Error--->
                        <small id="Uphone_error" class="form-text text-danger"  style="display: none"></small>
                        <div class="mb-3">
                            <input type="hidden" name="id"  class="form-control" id="edit2" >
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__("index.close")}}</button>
                            <button type="submit" id="UpdateBTN" class="btn btn-outline-success">{{__("index.update")}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Update Employee -->
    <script>
        $(document).on("click", "#edit1", function () {
            let EmployeeID = $(this).attr("data-id");
            $("#edit2").attr("value",EmployeeID);
        });
        $("#UpdateBTN").on('click',function (e) {
            e.preventDefault();
            var formData = new FormData($('#UpdateForm')[0]);
            $.ajax({
                type: 'POST',
                enctype: 'application/x-www-form-urlencoded',
                url: "{{route('employee.update')}}",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                headers:
                    {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                success: function (data) {
                    $("#Uname_error").hide();
                    $("#Uphone_error").hide();
                    $('#success_msg').show();
                    HideEmployees();
                    GetEmployees();
                },
                error: function (reject) {
                    var response = $.parseJSON(reject.responseText);
                    $.each(response.errors, function (key, val) {
                        $("#U" + key + "_error").show();
                        $("#U" + key + "_error").text(val[0]);
                    });
                }
            });
        });

    </script>
    <!--Live-Search Employee -->
    <script>
        $("#search").on("keyup",function () {
            var word = $('#search').val();
            $.ajax({
                type:"GET",
                url:"{{route('employee.search', '')}}"+"/"+word,
                cache:false,
                success:function (data) {
                    if (data){
                        HideEmployees();
                    data.forEach(function (employee) {
                        content2 = '<tr>' +
                            "<td>" + employee.id + "</td>"
                            + "<td>" + employee.name + "</td>"
                            + "<td>" + employee.phone + "</td>"
                            + '<td>' +
                            '<button id="delete" data-id="' + employee.id + '"  class="btn btn-outline-danger m-2">' + '{{__("index.delete")}}' +'</button>' +
                            '<button id="edit1" data-id="' + employee.id + '"   class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#UpdateModal" >' + '{{__("index.edit")}}' +'</button>'
                            + '</td>' +
                            '</tr>';
                        $("#content").append(content2);
                    });
                    }
                },
                error:function (error){

                }
            })
        })
    </script>
 </html>
