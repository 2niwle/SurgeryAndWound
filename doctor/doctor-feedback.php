<?php
include("../config/autoload.php");
include("includes/session.inc.php");
include("includes/path.inc.php");
$current_link = htmlspecialchars($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include CSS_PATH; ?>
</head>

<body>
    <?php
    // $user_id = $_SESSION['sess_id'];
    $user_id = $sess_id;
    $enc = base64_encode($user_id);
    // $entryID = $_GET['f'];
    $entryID = base64_decode($_GET['f']); //entryID
    $sql = "SELECT * FROM progress_book_entry WHERE entryID = $entryID;";
    $sql2 = "SELECT um.m_name as dr_name, wif.* FROM wound_image_feedback wif 
        inner join progress_book_entry pbe on wif.progress_entry_id = pbe.entryID  
        inner join user_master um on um.m_id = wif.doctor_inCharge
        WHERE progress_entry_id = $entryID;";

    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!strpos($row['view_by'], strval($sess_id))) {
        $view_by = $row['view_by'] . "|" . $sess_id;
        $conn->query("UPDATE progress_book_entry set view_by = '$view_by' where entryID = $entryID");
    }

    $result2 = $conn->query($sql2);


    $imglocation = $row['progressImage'];
    $targetpatient = $row['masterUserid_fk'];

    $fulllocation = ("../uploads/patient_img/$targetpatient/$imglocation"); //this problem



    ?>
    <?php include NAVIGATION; ?>
    <!-- Page content holder -->
    <div class="page-content" id="content">
        <?php include HEADER; ?>

        <!-- Page content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <form class="row g-3 form_wrap_shadow" enctype="multipart/form-data">

                                    <div class="col-md-6">
                                        <div id="imgwidth" class="col-md-12 fw-bold">
                                            <p>Image Submitted: </p>
                                            <!-- <input class="ipimg" accept="image/*" type="file" name="file" required> -->
                                        </div>
                                        <div class="pt-3" align='left'>
                                            <img id="not_modal" data-toggle="modal" style="margin-left: 15px;" name="bsmd" data-target="#exampleModal" style="cursor: pointer;" class="outImg" alt="your image" />
                                        </div>
                                        <div class="col-md-12" align='left'>

                                            <!-- Button trigger modal -->
                                            <p data-toggle="modal" name="bsmd" data-target="#exampleModal" style="cursor: pointer;" hidden>
                                                Click to enlarge Image
                                            </p>

                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Image Preview</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                                        </div>
                                                        <div class="modal-body" style="overflow: auto;" align="center">
                                                            <img class="outImg" alt="your image" style="width: 1000px; height: auto" />
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="imgheight">

                                        <div class="col-md-12 py-1">
                                            <div class="form-outline">
                                                <label class="form-label" for="form12">Title</label>
                                                <input type="text" id="form12" class="form-control" name="title" value="<?php echo $row['progressTitle'] ?>" required />
                                            </div>
                                        </div>

                                        <div class="col-md-12 py-1">
                                            <div class="form-outline">
                                                <label class="form-label" for="textAreaExample">Description</label>
                                                <textarea class="form-control" name="comment" id="textAreaExample" rows="2" required><?php echo $row['progressDescription'] ?></textarea>
                                            </div>
                                            <!-- <small id="helpId" class="form-text text-muted">Write Comemnts Here</small> -->

                                        </div>

                                        <div class="col-md-12 py-2">
                                            <div class="row fw-bold">
                                                <div class="col-1 ">
                                                    1.
                                                </div>
                                                <div class="col-11 ">
                                                    Did fluid drain from wound?
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-1 ">
                                                </div>
                                                <div class="col-11 ">
                                                    <!-- <label for="">1. Did fluid drain from wound?</label><br> -->
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="fluidwound" id="fwyes" value="Yes" <?php if ($row['quesFluid'] == 'Yes') echo "checked" ?> required />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio1">Yes</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="fluidwound" id="fwno" value="No" <?php if ($row['quesFluid'] == 'No') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio2">No</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="fluidwound" id="fwnotsure" value="Not Sure" <?php if ($row['quesFluid'] == 'Not Sure') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio3">Not Sure</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 py-2">
                                            <div class="row fw-bold">
                                                <div class="col-1 ">
                                                    2.
                                                </div>
                                                <div class="col-11 ">
                                                    Rate your pain: <output style="font-weight: bold;"><?php echo $row['quesPain'] ?></output> <small> out of 5</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-1 ">
                                                </div>
                                                <div class="col-11 ">
                                                    <div class="range">
                                                        <input type="range" class="form-range" min="0" step="1" max="5" value="<?php echo $row['quesPain'] ?>" style="min-width: 300px !important;" id="customRange2" name="quespain" oninput="$('output').val(this.value)" />
                                                        <small id="helpId" class="form-text text-muted">Please slide here to rate your pain</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 py-2">
                                            <div class="row fw-bold">
                                                <div class="col-1 ">
                                                    3.
                                                </div>
                                                <div class="col-11 ">
                                                    Is there any redness around the wound?
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-1 ">
                                                </div>
                                                <div class="col-11 ">
                                                    <!-- <label for="">1. Did fluid drain from wound?</label><br> -->
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="redwound" id="rwworse" value="worse" <?php if ($row['quesRedness'] == 'worse') echo "checked" ?> required />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio1">Worse</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="redwound" id="rwsome" value="some" <?php if ($row['quesRedness'] == 'some') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio2">Some</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="redwound" id="rwbetter" value="better" <?php if ($row['quesRedness'] == 'better') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio3">better</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="redwound" id="rwunsure" value="unsure" <?php if ($row['quesRedness'] == 'unsure') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio3">unsure</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="redwound" id="rwnone" value="none" <?php if ($row['quesRedness'] == 'none') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio3">none</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 py-2">
                                            <div class="row fw-bold">
                                                <div class="col-1 ">
                                                    4.
                                                </div>
                                                <div class="col-11 ">
                                                    Is there any swelling?
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-1 ">
                                                </div>
                                                <div class="col-11 ">
                                                    <!-- <label for="">1. Did fluid drain from wound?</label><br> -->
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="swellwound" id="swworse" value="worse" <?php if ($row['quesSwelling'] == 'worse') echo "checked" ?> required />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio1">Worse</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="swellwound" id="swsome" value="some" <?php if ($row['quesSwelling'] == 'some') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio2">Some</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="swellwound" id="swbetter" value="better" <?php if ($row['quesSwelling'] == 'better') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio3">better</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="swellwound" id="swunsure" value="unsure" <?php if ($row['quesSwelling'] == 'unsure') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio3">unsure</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="swellwound" id="swnone" value="none" <?php if ($row['quesSwelling'] == 'none') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio3">none</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 py-2">
                                            <div class="row fw-bold">
                                                <div class="col-1 ">
                                                    5.
                                                </div>
                                                <div class="col-11 ">
                                                    Is there any odour from the wound?
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-1 ">
                                                </div>
                                                <div class="col-11 ">
                                                    <!-- <label for="">1. Did fluid drain from wound?</label><br> -->
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="odourwound" id="owyes" value="Yes" <?php if ($row['quesOdour'] == 'Yes') echo "checked" ?> required />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio1">Yes</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="odourwound" id="owno" value="No" <?php if ($row['quesOdour'] == 'No') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio2">No</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="odourwound" id="ownotsure" value="Not Sure" <?php if ($row['quesOdour'] == 'Not Sure') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio3">Not Sure</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 py-2">
                                            <div class="row fw-bold">
                                                <div class="col-1 ">
                                                    6.
                                                </div>
                                                <div class="col-11 ">
                                                    Do you have fever?
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-1 ">
                                                </div>
                                                <div class="col-11 ">
                                                    <!-- <label for="">1. Did fluid drain from wound?</label><br> -->
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="fever" id="owno" value="No" <?php if ($row['quesFever'] == 'No') echo "checked" ?> required />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio2">No</label>
                                                    </div>

                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="fever" id="owyes" value="Yes" <?php if ($row['quesFever'] == 'Yes') echo "checked" ?> />
                                                        <label class="form-check-label text-capitalize" for="inlineRadio1">Yes</label>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12 pt-3" align='right'>
                                            <button type="button" name="can_edit" class="btn btn-info btn-block" hidden> Cancel Edit </button>

                                            <button type="button" name="upd_img" class="btn btn-danger btn-block" hidden> Update </button>

                                            <!-- <button type="button" name="action_btn" class="btn btn-warning btn-block" data-toggle="dropdown" aria-expanded="false">
                                                Action <i class="fas fa-caret-down"></i><span name="clickUpload" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" hidden></span>
                                            </button> -->

                                            <div class="dropdown-menu">
                                                <li><button type="button" name="edt_img" class="dropdown-item"> Edit</button></li>
                                                <li><button type="button" name="ar_img" class="dropdown-item"> Archive</button></li>
                                                <li><button type="button" name="de_img" class="dropdown-item"> Delete</button></li>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6" id="imgheight" style="border-left: solid 1px grey; overflow-y:auto">
                                <div align='center'>
                                    <h3 style="text-decoration: underline; text-underline-offset : 8px;">Doctor's Feedback</h3>
                                </div>
                                <div id="doctor_feedback_box">
                                    <?php
                                    $emptyFeedback = true;
                                    while ($row2 = $result2->fetch_assoc()) : $emptyFeedback = false ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <?php

                                                        if ($row2['doctor_inCharge'] == $sess_id) {
                                                            echo '<div class="d-flex justify-content-between">
                                                                <div>' . $row2['feedback_text'] . '</div>
                                                                <div>
                                                                <button class="btn btn-outline-warning" data-toggle="modal" data-target="#editModal" data-feedback="' . escape_input($row2['feedback_text']) . '" data-wif="' . $row2['f_id'] . '" style="border-radius: 10%;">Edit</button>
                                                                <button class="btn btn-outline-danger delete-feedback"  data-feedback="' . $row2['feedback_text'] . '" data-wif="' . $row2['f_id'] . '" style="border-radius: 10%;">Delete</button>
                                                                </div>
                                                            </div>';
                                                        } else {
                                                            echo $row2['feedback_text'];
                                                        }

                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    endwhile ?>

                                    <?php if ($emptyFeedback) : ?>
                                        <div class="row">
                                            <div class="col-12" align='center'>
                                                <br>
                                                <br>
                                                *No Feedback Received Yet
                                            </div>
                                        </div>
                                    <?php endif ?>

                                </div>
                            </div>
                        </div>
                        <br>


                        <button type='button' data-pbe='<?= $row["entryID"] ?>' data-toggle='modal' data-target='#InsertModal' name='{$no}modalPop' style='padding-left:20px; padding-right:20px' class='btn btn-primary btn-block tFeedback'>Feedback</button>

                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Content -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Feedback: </label>
                                <input type="text" class="form-control" id="feedback" value="" required>
                                <input type="text" class="form-control" id="wif" value="" hidden>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id='saveFeedback' name="saveFeedback">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="InsertModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label">Feedback: </label>
                                <input type="text" class="form-control" id="newFeedback" value="" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id='saveNewFeedback' name="saveNewFeedback">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include JS_PATH; ?>
    <script>
        var imgchanged = 'false';

        $(".ipimg").change(function(e) {
            e.preventDefault();
            $('p[name="bsmd"]').attr("hidden", false);
            imgchanged = 'true';
            $(".outImg").attr("src", window.URL.createObjectURL(this.files[0]));
        });

        /* Start Image Resize Code */
        function image_resize() {
            $("img#not_modal").each(function() {
                var width = $('#imgwidth').width() - 95;
                var height = $('#imgheight').height() - 95;
                $(this).attr({
                    width: (width),
                    alt: "Your uploaded image will display here"
                });
            });
        }

        $("button[name='edt_img']").click(function(e) {
            e.preventDefault();
            $("form.form_wrap_shadow :input").prop('readonly', false);
            $("form.form_wrap_shadow input:radio").prop('disabled', false);
            $("form.form_wrap_shadow input[type='range']").prop('disabled', false);
            $("button[name='can_edit']").prop('hidden', false);
            $("button[name='upd_img']").prop('hidden', false);
            $("button[name='action_btn']").prop('hidden', true);
        });

        $("button[name='can_edit']").click(function(e) {
            e.preventDefault();
            $("form.form_wrap_shadow :input").prop('readonly', true);
            $("form.form_wrap_shadow input:radio").prop('disabled', true);
            $("form.form_wrap_shadow input[type='range']").prop('disabled', true);
            $("button[name='can_edit']").prop('hidden', true);
            $("button[name='upd_img']").prop('hidden', true);
            $("button[name='action_btn']").prop('hidden', false);
        });

        //Update image
        $("button[name='upd_img']").click(function(e) {
            e.preventDefault();
            var myform = $('form');
            var fd = new FormData(myform[0]);
            fd.append('type', '1entEdit');
            fd.append('imgchanged', imgchanged);
            fd.append('tgt', "<?php echo $enc ?>");
            fd.append('eid', "<?php echo $entryID ?>");
            Swal.fire({
                title: 'Do you want to save the changes?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Save',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "includes/1ent_conn.php",
                        type: "POST",
                        data: fd,
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function() {
                            $('span[name="clickUpload"]').attr("hidden", false);
                            $('ul li :button').attr("disabled", true);
                            // $('button[name="upimg"]').attr("disabled", true);
                        },
                        success: function(response) {
                            $('span[name="clickUpload"]').attr("hidden", true);
                            $('ul li :button').attr("disabled", false);
                            console.log(response);
                            successUpload("Succesfully updated");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                            console.log(textStatus);
                            failUpload("Oh no, something went wrong");
                        }
                    })
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })

        });

        //Archive image
        $("button[name='ar_img']").click(function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Do you want to archive this progress image?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    $.ajax({
                        url: "includes/1ent_conn.php",
                        type: "POST",
                        data: {
                            archive: "true",
                            type: "1entArchive",
                            tgt: "<?php echo $enc ?>",
                            eid: "<?php echo $entryID ?>",
                        },
                        // contentType: false,
                        cache: false,
                        // processData: false,
                        beforeSend: function() {
                            $('span[name="clickUpload"]').attr("hidden", false);
                            $('ul li :button').attr("disabled", true);
                            // $('button[name="upimg"]').attr("disabled", true);
                        },
                        success: function(response) {
                            $('span[name="clickUpload"]').attr("hidden", true);
                            $('ul li :button').attr("disabled", false);
                            console.log(response);
                            successUpload("Succesfully updated");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                            console.log(textStatus);
                            failUpload("Oh no, something went wrong");
                        }
                    })
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })

        });

        //de image
        $("button[name='de_img']").click(function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Do you want to delete this progress image?',
                // showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                console.log(result);
                if (result.isConfirmed) {
                    $.ajax({
                        url: "includes/1ent_conn.php",
                        type: "POST",
                        data: {
                            delete: "true",
                            type: "1entDelete",
                            tgt: "<?php echo $enc ?>",
                            eid: "<?php echo $entryID ?>",
                        },
                        // contentType: false,
                        cache: false,
                        // processData: false,
                        beforeSend: function() {
                            $('span[name="clickUpload"]').attr("hidden", false);
                            $('ul li :button').attr("disabled", true);
                            // $('button[name="upimg"]').attr("disabled", true);
                        },
                        success: function(response) {
                            $('span[name="clickUpload"]').attr("hidden", true);
                            $('ul li :button').attr("disabled", false);
                            console.log(response);
                            successUpload("Succesfully updated");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                            console.log(textStatus);
                            failUpload("Oh no, something went wrong");
                        }
                    })
                } else if (result.isDenied) {
                    Swal.fire('Changes are not saved', '', 'info')
                }
            })

        });

        //delete feedback
        $('.delete-feedback').click(function(e) {
            Swal.fire({
                title: 'Warning',
                text: 'Are you sure you want to delete this feedback?',
                icon: 'warning',
                confirmButtonText: 'Yes',
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    var wif = $(this).attr('data-wif');
                    $.ajax({
                        url: "includes/2tor_conn.php",
                        type: "POST",
                        data: {
                            wif: wif,
                            type: "DeleteFeedback",
                        },
                        cache: false,
                        success: function(response) {
                            console.log(response);
                            Swal.fire({
                                title: 'Success',
                                text: 'Feedback has been successfully deleted',
                                icon: 'success',
                                confirmButtonText: 'Okay'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            })
                        }
                    });
                }
            })

        });

        $("#saveFeedback").click(function(e) {
            e.preventDefault();
            var feedback = $('#feedback').val();
            var wif = $('#wif').val();
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "includes/2tor_conn.php",
                    type: "POST",
                    data: {
                        feedback: feedback,
                        wif: wif,
                        type: "SaveFeedback",
                    },
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            title: 'Success',
                            text: 'Feedback has been changed',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }
                });
            }
        });

        $("#saveNewFeedback").click(function(e) {
            e.preventDefault();
            var feedback = $('#newFeedback').val();
            var pbe = $('.tFeedback').attr('data-pbe');
            var din = `<?php echo base64_encode($sess_id) ?>`;
            console.log(pbe);
            if (confirm("Are you sure?")) {
                $.ajax({
                    url: "includes/2tor_conn.php",
                    type: "POST",
                    data: {
                        feedback: feedback,
                        pbe: pbe,
                        din: din,
                        type: "saveNewFeedback",

                    },
                    cache: false,
                    success: function(response) {
                        console.log(response);
                        Swal.fire({
                            title: 'Success',

                            text: 'Feedback has been Added',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        })
                    }
                });
            }
        });

        $(document).ready(function() {
            $(".outImg").attr("src", ("<?php echo $fulllocation ?>"));
            $('p[name="bsmd"]').attr("hidden", false);
            image_resize();
            $("form.form_wrap_shadow :input").prop('readonly', true);
            $("form.form_wrap_shadow input:radio").prop('disabled', true);
            $("form.form_wrap_shadow input[type='range']").prop('disabled', true);

            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var feedback = button.data('feedback')
                var wif = button.data('wif')

                $('#wif').val(wif);
                $('#feedback').val(feedback);
            })
            $('#InsertModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget) // Button that triggered the modal
                var feedback = button.data('feedback')
                var pbe = button.data('pbe')
                $('#feedback').val(feedback);
                $('#pbe').val(pbe);
            })
        });
    </script>
</body>

</html>