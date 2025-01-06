<div id="overlay-background" class="overlay"></div>
<div id="main_content">
    <!--------------------content here----------------------------->
    <div id="heding_section">
        <!--------------------heading_section----------------------------->
        <div class="page_title">
            <p>Complain<span class="page_name"> > Complain Verification</span></p>
        </div>
        <!--------------------heading_section----------------------------->
    </div>

    <div id="body_section">
        <div class="page_subtitle">
            <p class="title">Manage Verification</p>
        </div>

        <div class="content">
            <h4>Complain Detail</h4>

            <div class="contactForm">
                <form action="#" class="form-card" method="post" id="complain-verification"><br><br>

                    <div class="col-25">
                        <label class="date">Date</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="date" name="date" value="<?php echo $date; ?>" readonly>
                    </div>
                    <br><br><br>

                    <div class="col-25">
                        <label class="assetno">Asset No</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="assetno" name="assetno" value="<?php echo $assetno; ?>" readonly>
                    </div>

                    <div class="col-25">
                        <label class="serialno">Serial No</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="serialno" name="serialno" value="<?php echo $serialno; ?>" readonly>
                    </div>

                    <div class="col-25">
                        <label class="empid">Employee ID</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="empid" name="empid" value="<?php echo $empid; ?>" readonly>
                    </div>

                    <div class="col-25">
                        <label class="regoffice">Regional Office</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="regoffice" name="regoffice" value="<?php echo $regoffice; ?>" readonly>
                    </div>

                    <div class="col-25">
                        <label class="observation">Observations</label>
                    </div>
                    <div class="col-75">
                        <textarea id="subject" name="observation" readonly style="height:100px"><?php echo $observation; ?></textarea>
                    </div>

                    <div class="col-25">
                        <label class="observation">Image</label>
                    </div>
                    <div class="col-75">
                        <?php if ($image): ?>
                            <img src="<?php echo $image; ?>" alt="Uploaded Image" style="max-width:100%; height:auto;">
                        <?php else: ?>
                            <p>No image uploaded.</p>
                        <?php endif; ?>
                    </div>

                    <br>
                    <div class="chk_col">
                        <label><input type="checkbox" name="checkbox" style="width:18px; height:18px;">&nbsp&nbsp&nbsp<b>I agree and accept the complain.</b></label>
                    </div>
                    <br>
                    <div class="row">
                        <button type="submit">Submit</button>
                        <button type="cancel" onclick="cancel()">Cancel</button>
                    </div>

                </form>

            </div>

        </div>
    </div>

    <!---------------------content here------------------------------>
</div>

<script>
    function showSuccessMessage() {
        Swal.fire({
            title: 'Recommendation Successful',
            text: 'Your Recommendation has been successfully submitted.',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Reset form fields
                document.getElementById('complain-verification').reset();
                // Redirect to home page
                window.location.href = 'index.php?page=home';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($success) && $success): ?>
            showSuccessMessage();
        <?php endif; ?>
    });
</script>