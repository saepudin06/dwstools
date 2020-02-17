<!-- breadcrumb -->
<ol class="breadcrumb page-breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
    <li class="breadcrumb-item">Administrator</li>
    <li class="breadcrumb-item active">My Profile</li>
</ol>
<!-- end breadcrumb -->

<div class="row">
    <div class="col-xl-6">
        <div id="panel-1" class="panel">
            <div class="panel-hdr">
                <h2>
                    Form <span class="fw-300"><i>Profile</i></span>
                </h2>
                <div class="panel-toolbar">
                    <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-offset="0,10" data-original-title="Collapse"></button>
                    <button class="btn btn-panel" data-action="panel-close" data-toggle="tooltip" data-offset="0,10" data-original-title="Close"></button>
                </div>
            </div>
            <div class="panel-container show">
                <div class="panel-content">
                    <form method="post" action="" class="form-horizontal" id="form-profile">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="id" value="<?php echo $this->session->userdata('user_id'); ?>">

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="username">Username</label>
                            <div class="col-md-12">
                                <input type="text" name="username" readonly="" class="form-control" value="<?php  echo $this->session->userdata('user_name'); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="email">Email</label>
                            <div class="col-md-12">
                                <input type="email" name="email" class="form-control required" value="<?php  echo $this->session->userdata('user_email'); ?>">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label" for="password">New Password</label>
                            <div class="col-md-12">
                                <input type="password" class="form-control" name="password" value="">
                                <i class="orange">Min.4 Characters</i>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label" for="password_confirmation">Confirm Password</label>
                            <div class="col-md-12">
                                <input type="password" class="form-control" name="password_confirmation" value="">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="submit" name="submit" value="Save Changes" class="btn btn-danger">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

$("#form-profile").on('submit', (function (e) {

    e.preventDefault();

    var data = $(this).serializeArray();
    $.ajax({
        url: "<?php echo WS_JQGRID."administration.users_controller/updateProfile"; ?>",
        type: "POST",
        data: data,
        dataType: "json",
        success: function (data) {
            if (data.success == true) {
                swal.fire("Sukses",data.message,"success");
                loadContentWithParams('profile',{});
            } else {
                swal.fire("Perhatian",data.message,"warning");
            }
        },
        error: function (xhr, status, error) {
            swal.fire({title: "Error!", text: xhr.responseText, html: true, type: "error"});
        }
    });

    return false;
}));

</script>