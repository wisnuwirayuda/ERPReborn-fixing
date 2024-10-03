<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login ERP QDC</title>
    <link rel="shortcut icon" href="/AdminLTE-master/dist/img/favicon.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE-master/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    
    <style>
        #dis1 { border-radius: 20px; padding: 40px; }
        .form-control { height: 30px; }
        #loading {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.8);
            display: none;
        }
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -25px 0 0 -25px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="hold-transition login-page">
    <div id="loading">
        <div class="loader"></div>
    </div>

    <div class="login-box">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif

        @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
        @endif

        <div class="login-logo">
            <img src="/AdminLTE-master/dist/img/qdc.png" width="160" alt="">
        </div>
        
        <div class="card-body login-card-body" id="dis1">
            <form id="FormLogin" action="{{ route('loginStore') }}" method="post">
                @csrf
                <input type="hidden" class="user_RefID" name="user_RefID">
                <input type="hidden" class="varAPIWebToken" name="varAPIWebToken">
                <input type="hidden" class="personName" name="personName">
                <input type="hidden" class="workerCareerInternal_RefID" name="workerCareerInternal_RefID">
                <input type="hidden" class="organizationalDepartmentName" name="organizationalDepartmentName">

                <div class="input-group mb-4">
                    <input type="text" class="form-control username" placeholder="Username" name="username" required autocomplete="off" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                
                <div class="input-group mb-4">
                    <input type="password" class="form-control password" placeholder="Password" name="password" required autocomplete="off">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-eye-slash toggle-password" style="cursor: pointer;"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-4" id="branch" style="display: none;">
                    <select class="form-control branch_id" name="branch_id">
                        <option value="">Select Company Name</option>
                    </select>
                </div>
                
                <div class="input-group mb-4" id="role" style="display: none;">
                    <select class="form-control role_id" name="role_id">
                        <option value="">Select User Role</option>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary btn-block submit_button" type="submit">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('AdminLTE-master/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-master/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('AdminLTE-master/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.branch_id, .role_id').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            // Toggle password visibility
            $('.toggle-password').click(function() {
                const passwordInput = $('.password');
                const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                passwordInput.attr('type', type);
                $(this).toggleClass('fa-eye-slash fa-eye');
            });

            let loginInProgress = false;

            // Handle form submission
            $('#FormLogin').on('submit', function(e) {
                e.preventDefault();
                if (loginInProgress) return;
                loginInProgress = true;

                const $form = $(this);
                const $submitBtn = $form.find('.submit_button');
                $submitBtn.prop('disabled', true);
                $('#loading').show();

                $.ajax({
                    url: $form.attr('action'),
                    method: $form.attr('method'),
                    data: new FormData($form[0]),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        switch(response.status_code) {
                            case 1:
                                window.location.href = '/dashboard';
                                break;
                            case 2:
                                handleBranchSelection(response);
                                break;
                            default:
                                Swal.fire("Error", "Invalid credentials", "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error", "An error occurred", "error");
                    },
                    complete: function() {
                        loginInProgress = false;
                        $submitBtn.prop('disabled', false);
                        $('#loading').hide();
                    }
                });
            });

            function handleBranchSelection(response) {
                $('.varAPIWebToken').val(response.varAPIWebToken);
                $('.user_RefID').val(response.user_RefID);
                $('.personName').val(response.personName);
                $('.workerCareerInternal_RefID').val(response.workerCareerInternal_RefID);
                $('.organizationalDepartmentName').val(response.organizationalDepartmentName);

                $('.username, .password').prop('readonly', true);
                
                populateBranchSelect(response.data);
                $('#branch, #role').show();
            }

            function populateBranchSelect(branches) {
                const $branchSelect = $('.branch_id').empty().append('<option value="">Select Company Name</option>');
                branches.forEach(function(branch) {
                    $branchSelect.append(`<option value="${branch.Sys_ID}">${branch.Name}</option>`);
                });
            }

            // Handle branch selection
            $('.branch_id').change(function() {
                const branchId = $(this).val();
                if (!branchId) {
                    $('.role_id').empty().append('<option value="">Select User Role</option>');
                    $('.submit_button').prop('disabled', true);
                    return;
                }

                $('#loading').show();
                $.ajax({
                    url: '{{ route("getRoleLogin") }}',
                    method: 'GET',
                    data: {
                        user_RefID: $('.user_RefID').val(),
                        branch_id: branchId
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            populateRoleSelect(response.data);
                        } else {
                            Swal.fire("Error", "Failed to fetch roles", "error");
                        }
                    },
                    error: function() {
                        Swal.fire("Error", "Failed to fetch roles", "error");
                    },
                    complete: function() {
                        $('#loading').hide();
                    }
                });
            });

            function populateRoleSelect(roles) {
                const $roleSelect = $('.role_id').empty().append('<option value="">Select User Role</option>');
                roles.forEach(function(role) {
                    $roleSelect.append(`<option value="${role.Sys_ID}">${role.UserRoleName}</option>`);
                });
                
                if (roles.length === 1) {
                    $roleSelect.val(roles[0].Sys_ID).trigger('change');
                }
            }

            // Handle role selection
            $('.role_id').change(function() {
                $('.submit_button').prop('disabled', !$(this).val());
            });
        });
    </script>
</body>
</html>
