<script type="text/javascript">
    function ApproveButton(businessDocument_ID) {

        const swalWithBootstrapButtons = Swal.mixin({
            confirmButtonClass: 'btn btn-success btn-sm',
            cancelButtonClass: 'btn btn-danger btn-sm',
            buttonsStyling: true,
        })

        swalWithBootstrapButtons.fire({

            title: 'Are you sure?',
            text: "Approve this document?",
            type: 'question',

            showCancelButton: true,
            confirmButtonText: '<img src="{{ asset("AdminLTE-master/dist/img/save.png") }}" width="13" alt=""><span style="color:black;">Yes, Approve </span>',
            cancelButtonText: '<img src="{{ asset("AdminLTE-master/dist/img/cancel.png") }}" width="13" alt=""><span style="color:black;"> No, Cancel </span>',
            confirmButtonColor: '#e9ecef',
            cancelButtonColor: '#e9ecef',
            reverseButtons: true
        }).then((result) => {

            if (result.value) {

                const swalWithBootstrapButtons = Swal.mixin({
                    confirmButtonClass: 'btn btn-success btn-sm',
                    cancelButtonClass: 'btn btn-danger btn-sm',
                    buttonsStyling: true,
                })

                swalWithBootstrapButtons.fire({

                    title: 'Comment',
                    text: "Please write your comment here",
                    type: 'question',
                    input: 'textarea',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: '<span style="color:black;"> OK </span>',
                    confirmButtonColor: '#4B586A',
                    confirmButtonColor: '#e9ecef',
                    reverseButtons: true
                }).then((result) => {
                    // if (result.value) {

                        ShowLoading();

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: 'GET',
                            url: '{!! route("ApprovalDocument.ApprovalAccepted") !!}?businessDocument_ID=' + businessDocument_ID + '&comment=' + result.value,
                            success: function(data) {

                                if (data.status == "200") {
                                    swalWithBootstrapButtons.fire({

                                        title: 'Successful !',
                                        type: 'success',
                                        html: 'Document Has Been Approved',
                                        showCloseButton: false,
                                        showCancelButton: false,
                                        focusConfirm: false,
                                        confirmButtonText: '<span style="color:black;"> OK </span>',
                                        confirmButtonColor: '#4B586A',
                                        confirmButtonColor: '#e9ecef',
                                        reverseButtons: true
                                    }).then((result) => {
                                        ShowLoading();
                                        window.location.href = '/MyDocument';
                                    })
                                } else if (data.status == "Final") {
                                    swalWithBootstrapButtons.fire({

                                        title: 'Successful !',
                                        type: 'success',
                                        html: 'Docunent Has Been Final Approved',
                                        showCloseButton: false,
                                        showCancelButton: false,
                                        focusConfirm: false,
                                        confirmButtonText: '<span style="color:black;"> OK </span>',
                                        confirmButtonColor: '#4B586A',
                                        confirmButtonColor: '#e9ecef',
                                        reverseButtons: true
                                    }).then((result) => {
                                        ShowLoading();
                                        window.location.href = '/MyDocument';
                                    })
                                } else {
                                    Swal.fire("Error", "Data Error", "error");
                                }

                                HideLoading();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {

                                HideLoading();

                                Swal.fire("Error", "Data Error", "error");
                            }
                        });

                    // }
                })

            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                HideLoading();
                ErrorNotif("Data Cancel Approved");
            }
        })
    }
</script>



<script type="text/javascript">
    function RejectButton(businessDocument_ID, submitter_ID) {

        const swalWithBootstrapButtons = Swal.mixin({
            confirmButtonClass: 'btn btn-success btn-sm',
            cancelButtonClass: 'btn btn-danger btn-sm',
            buttonsStyling: true,
        })

        swalWithBootstrapButtons.fire({

            title: 'Are you sure?',
            text: "Reject this document?",
            type: 'question',

            showCancelButton: true,
            confirmButtonText: '<img src="{{ asset("AdminLTE-master/dist/img/save.png") }}" width="13" alt=""><span style="color:black;">Yes, Reject </span>',
            cancelButtonText: '<img src="{{ asset("AdminLTE-master/dist/img/cancel.png") }}" width="13" alt=""><span style="color:black;"> No, Cancel </span>',
            confirmButtonColor: '#e9ecef',
            cancelButtonColor: '#e9ecef',
            reverseButtons: true
        }).then((result) => {

            if (result.value) {

                const swalWithBootstrapButtons = Swal.mixin({
                    confirmButtonClass: 'btn btn-success btn-sm',
                    cancelButtonClass: 'btn btn-danger btn-sm',
                    buttonsStyling: true,
                })

                swalWithBootstrapButtons.fire({

                    title: 'Comment',
                    text: "Please write your comment here",
                    type: 'question',
                    input: 'textarea',
                    showCloseButton: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    confirmButtonText: '<span style="color:black;"> OK </span>',
                    confirmButtonColor: '#4B586A',
                    confirmButtonColor: '#e9ecef',
                    reverseButtons: true
                }).then((result) => {
                    // if (result.value) {

                        ShowLoading();

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: 'GET',
                            url: '{!! route("ApprovalDocument.ApprovalRejected") !!}?businessDocument_ID=' + businessDocument_ID + '&submitter_ID=' + submitter_ID + '&comment=' + result.value,
                            success: function(data) {

                                if (data.status == "200") {
                                    swalWithBootstrapButtons.fire({

                                        title: 'Successful !',
                                        type: 'success',
                                        html: 'Document Has Been Rejected',
                                        showCloseButton: false,
                                        showCancelButton: false,
                                        focusConfirm: false,
                                        confirmButtonText: '<span style="color:black;"> OK </span>',
                                        confirmButtonColor: '#4B586A',
                                        confirmButtonColor: '#e9ecef',
                                        reverseButtons: true
                                    }).then((result) => {
                                        ShowLoading();
                                        window.location.href = '/MyDocument';
                                    })
                                } else {
                                    Swal.fire("Error", "Data Error", "error");
                                }

                                HideLoading();
                            },
                            error: function(jqXHR, textStatus, errorThrown) {

                                HideLoading();

                                Swal.fire("Error", "Data Error", "error");
                            }
                        });

                    // }
                })

            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                HideLoading();
                ErrorNotif("Data Cancel Rejected");
            }
        })
    }
</script>
