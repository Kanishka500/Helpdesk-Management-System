<?php
// --------------------page configs-------------------------
$pag = "index.php?page=manage_payment";
$maxvalue = 10;
?>
<div id="main_content">
    <!--------------------content here----------------------------->
    <div id="heding_section">
        <!--------------------heading_section----------------------------->
        <div class="page_title">
            <p>Payment Status<span class="page_name"> > Manage Payment Status</span></p>
        </div>
        <!--------------------heading_section----------------------------->
    </div>
    <div id="body_section">
        <div class="page_subtitle">
            <p class="title">Issue Fixing & Payment Status</p>
        </div>

        <!--------------------content-------------------->
        <div class="content" style="padding-right:10px; padding: left 10px;">
            <div class="form-group row">
                <div class="well well-sm">
                    <form action="#" method="POST" class="col">
                        <div class="txtbox">
                            <label class="label" style="margin: 5px;">Complain ID</label>&nbsp;
                            <label class="input">
                                <input type="text" id="complain_id" name="search_val" required="required" style="border: 1px solid #BDBDBD; width:50%; height:20px; margin: 5px; padding: 5px;">
                            </label>
                        </div>
                        <div class="txtbox">
                            <button type="submit" class="search_btn" name="search">
                                <i class="fa fa-search"></i>
                            </button>
                            <button id="reload" onClick="relaod();" class="search_btn">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <?php
                if (!empty($mess)) {
                    echo $mess;
                }
                ?>
            </div>
            <!---------------------pagination------------------------------>
            <?php
            include("includes/connect.php");

            if (!empty($value)) {
                $url = $pag . '&sv=' . $value;
                $statement = "SELECT complain.*, 
           job.complain_id AS job_complain_id, 
           job.officer, 
           job.issue, 
           job.solution, 
           job.service_type, 
           job.job_date, 
           job.accept, 
           job.verification, 
           job.itemreplace_status, 
           service.complain_id AS service_complain_id, 
           service.reference_no, 
           service.company, 
           service.date,
           quotation.complain_id AS quotation_complain_id,
           quotation.quotation_no,
           quotation.quotation_amount,
           invoice.complain_id AS invoice_complain_id,
           invoice.fixed_date,
           invoice.invoice_no,
           invoice.invoice_amount,
           invoice.invoice_image,
           quotation.decision_confirm,
           invoice.verification_hd,
           invoice.certify_date,
           invoice.certified,
           invoice.sys_recommendation,
           invoice.dgm_recommendation,
           invoice.approval
    FROM complain
    LEFT JOIN job ON job.complain_id = complain.complain_id
    LEFT JOIN service ON service.complain_id = complain.complain_id
    LEFT JOIN quotation ON quotation.complain_id = complain.complain_id
    LEFT JOIN invoice ON invoice.complain_id = complain.complain_id
    WHERE complain.complain_id LIKE :value
    ORDER BY complain_id ASC";
                $stmt = $pdo->prepare($statement);
                try {
                    $stmt->execute(['value' => '%' . $value . '%']);
                } catch (PDOException $e) {
                    echo "Execution error: " . $e->getMessage();
                }
            } else {
                $url = $pag;
                $statement = "SELECT complain.*,
           job.complain_id AS job_complain_id,
           job.officer,
           job.issue,
           job.solution,
           job.service_type,
           job.job_date,
           job.accept,
           job.verification,
           job.itemreplace_status,
           service.complain_id AS service_complain_id,
           service.reference_no,
           service.company,
           service.date,
           quotation.complain_id AS quotation_complain_id,
           quotation.quotation_no,
           quotation.quotation_amount,
           quotation.quotation_image,
           quotation.decision_confirm,
           invoice.complain_id AS invoice_complain_id,
           invoice.fixed_date,
           invoice.invoice_no,
           invoice.invoice_amount,
           invoice.invoice_image,
           invoice.verification_hd,
           invoice.certify_date,
           invoice.certified,
           invoice.sys_recommendation,
           invoice.dgm_recommendation,
           invoice.approval,
           payment.complain_id AS payment_complain_id,
           payment.py_success
    FROM complain
    LEFT JOIN job ON job.complain_id = complain.complain_id
    LEFT JOIN service ON service.complain_id = complain.complain_id
    LEFT JOIN quotation ON quotation.complain_id = complain.complain_id
    LEFT JOIN invoice ON invoice.complain_id = complain.complain_id
    LEFT JOIN payment ON payment.complain_id = complain.complain_id
    ORDER BY complain_id ASC";
                try {
                    $stmt->execute();

                    // Get the number of rows returned
                    $rows = $stmt->rowCount();
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }

            $rows = $stmt->rowCount();
            if ($rows == 0) {
                $max = 0;
                $totalpages = 0;
            } else {
                $max = $maxvalue;
                $totalpages = ceil($rows / $max);
            }
            $lastpage = $totalpages;
            if (isset($_GET['pn']) && $_GET['pn'] != '') {
                $pn = preg_replace('#[^0-9]#', '', $_GET['pn']);
                if ($pn < 1) {
                    $pn = 1;
                } else if ($pn > $lastpage) {
                    $pn = $lastpage;
                }
            } else {
                $pn = 1;
            }
            $limite = ($pn - 1) * $max;
            // -----------------------------------------------------
            $textline1 = "Numbers of Records : <b>$rows</b>";
            $textline2 = "View Page <b>$pn</b> of <b>$lastpage</b> Pages";
            $paginationCtrls = '';
            if ($lastpage != 1) {
                if ($pn > 1) {
                    $previous = $pn - 1;
                    $paginationCtrls .= '<a href="' . $url . '&pn=' . $previous . '">Previous</a>';
                    for ($i = $pn - 4; $i < $pn; $i++) {
                        if ($i > 0) {
                            $paginationCtrls .= '<a href="' . $url . '&pn=' . $i . '">' . $i . '</a>';
                        }
                    }
                }
                $paginationCtrls .= '<a href="' . $url . '&pn=' . $pn . '"' . 'class=active' . '>' . $pn . '</a>';

                for ($i = $pn + 1; $i <= $lastpage; $i++) {
                    $paginationCtrls .= '<a href="' . $url . '&pn=' . $i . '">' . $i . '</a>';
                    if ($i >= $pn + 4) {
                        break;
                    }
                }

                if ($pn != $lastpage) {
                    $next = $pn + 1;
                    $paginationCtrls .= '<a href="' . $url . '&pn=' . $next . '">Next</a> ';
                }
            }
            ?>


            <!-- -------------------pagination---------------------------- -->

            <div class="form">
                <!---------------------------table-------------------------->
                <div class="table">
                    <table id="job">
                        <thead>
                            <tr>
                                <th>Complain ID</th>
                                <th>Complain Date</th>
                                <th>Office</th>
                                <th>Issue</th>
                                <th>Solution</th>
                                <th>Quotation No</th>
                                <th>Quotation Amount</th>
                                <th>Company Name</th>
                                <th>Option</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "{$statement}  LIMIT :limite, :max";
                            $stmt = $pdo->prepare($sql);

                            $limite = isset($limite) ? (int)$limite : 0;
                            $max = isset($max) ? (int)$max : 10;

                            // Bind parameters for pagination
                            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
                            $stmt->bindParam(':max', $max, PDO::PARAM_INT);

                            if (!empty($value)) {
                                // Bind 'service' and 'office' parameters for filtered search
                                $stmt->bindValue(':value', '%' . $value . '%', PDO::PARAM_STR);
                                $stmt->execute();
                            } else {
                                // Execute without additional parameters for full data
                                $stmt->execute();
                            }

                            $result = $stmt->fetchAll();
                            if (!empty($result)) {
                                foreach ($result as $row) {
                            ?>

                                    <tr role="row" id="<?php echo $row['complain_id']; ?>">

                                        <td role="cell" data-target="comid" data-label="complain_id"><?php echo $row['complain_id'] ?></td>
                                        <td role="cell" data-target="comdate" data-label="date"><?php echo $row['date'] ?></td>
                                        <td role="cell" data-target="comoffice" data-label="office_id"><?php echo $row['office_id'] ?></td>
                                        <td role="cell" data-target="comissue" data-label="issue"><?php echo $row['issue'] ?></td>
                                        <td role="cell" data-target="comsolution" data-label="solution"><?php echo $row['solution'] ?></td>
                                        <td role="cell" data-target="comquotationno" data-label="quotation_no"><?php echo $row['quotation_no'] ?></td>
                                        <td role="cell" data-target="comamount" data-label="quotation_amount"><?php echo $row['quotation_amount'] ?></td>
                                        <td role="cell" data-target="comcompany" data-label="company"><?php echo $row['company'] ?></td>
                                        <!-------------------hidding-------------------->
                                        <td role="cell" data-target="comasset" style='display:none'><?php echo $row['asset_id'] ?></td>
                                        <td role="cell" data-target="comserial" style='display:none'><?php echo $row['serial_no'] ?></td>
                                        <td role="cell" data-target="comobs" style='display:none'><?php echo $row['observations'] ?></td>
                                        <td role="cell" data-target="comempid" style='display:none'><?php echo $row['emp_id'] ?></td>
                                        <td role="cell" data-target="comrec" style='display:none'><?php echo $row['recommendation'] ?></td>
                                        <td role="cell" data-target="comimg" style='display:none'><?php echo $row['image'] ?></td>
                                        <!-------------------------job------------------>
                                        <td role="cell" data-target="jobofficer" style='display:none'><?php echo $row['officer'] ?></td>
                                        <td role="cell" data-target="jobissue" style='display:none'><?php echo $row['issue'] ?></td>
                                        <td role="cell" data-target="jobreco" style='display:none'><?php echo $row['service_type'] ?></td>
                                        <td role="cell" data-target="jobsolu" style='display:none'><?php echo $row['solution'] ?></td>
                                        <td role="cell" data-target="jobdate" style='display:none'><?php echo $row['job_date'] ?></td>
                                        <td role="cell" data-target="jobconf" style='display:none'><?php echo $row['verification'] ?></td>
                                        <!-----------------------invoice---------------->
                                        <td role="cell" data-target="invfixeddate" style='display:none'><?php echo $row['fixed_date'] ?></td>
                                        <td role="cell" data-target="invinno" style='display:none'><?php echo $row['invoice_no'] ?></td>
                                        <td role="cell" data-target="invinamount" style='display:none'><?php echo $row['invoice_amount'] ?></td>
                                        <td role="cell" data-target="invimg" style='display:none'><?php echo $row['invoice_image'] ?></td>
                                        <td role="cell" data-target="invver" style='display:none'><?php echo $row['verification_hd'] ?></td>
                                        <td role="cell" data-target="invdate" style='display:none'><?php echo $row['certify_date'] ?></td>
                                        <td role="cell" data-target="invcer" style='display:none'><?php echo $row['certified'] ?></td>
                                        <td role="cell" data-target="invsysrec" style='display:none'><?php echo $row['sys_recommendation'] ?></td>
                                        <td role="cell" data-target="invdgmrec" style='display:none'><?php echo $row['dgm_recommendation'] ?></td>
                                        <td role="cell" data-target="invapprove" style='display:none'><?php echo $row['approval'] ?></td>
                                        <!-----------------------Payment---------------->
                                        <td role="cell" data-target="vouno" style='display:none'><?php echo $row['voucher_no'] ?></td>
                                        <td role="cell" data-target="cheno" style='display:none'><?php echo $row['cheque_no'] ?></td>
                                        <td role="cell" data-target="pdate" style='display:none'><?php echo $row['paid_date'] ?></td>
                                        <td role="cell" data-target="success" style='display:none'><?php echo $row['py_success'] ?></td>
                                        <!-----------------------Quotation---------------->
                                        <td role="cell" data-target="qimg" style='display:none'><?php echo $row['quotation_image'] ?></td>
                                        <td role="cell" data-target="pag" style='display:none'><?php echo $pn ?></td>
                                        <td data-label="Option" width="30%">
                                            <div>
                                                <!-------------------view---------------------->
                                                <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="view_btn"><i class="fa fa-search"></i>View</a>

                                                <!-------------------verify-------------------->

                                                <?php
                                                if ($role == 2) {
                                                    if ($row['decision_confirm'] == 0) {
                                                        echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until PM provides procurement decision, the button is disabled.</span>';
                                                    } elseif ($row['verification_hd'] == 1) {
                                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Verified</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                        echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="Helpdesk Officer" class="verify"><i class="fa fa-search"></i>Verify</a>';
                                                    }
                                                } elseif ($row['verification_hd'] == 1) {
                                                    echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Verified</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                                } else {
                                                    // Disable "verify" button for other roles without displaying a message
                                                    echo '<a href="#" title="Helpdesk Officer" class="verify" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Verify</a>';
                                                }
                                                ?>

                                                <!-------------------certify-------------------->
                                                <?php
                                                if ($role == 4) {
                                                    if ($row['verification_hd'] == 0) {
                                                        echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until HDO fill payment details and provide verification the button is disabled.</span>';
                                                    } elseif ($row['certified'] == 1) {
                                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Certified</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                        echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="Information Technology Officer" class="submit_btn"><i class="fa fa-search"></i>Save & Submit</a>';
                                                    }
                                                } elseif ($row['certified'] == 1) {
                                                    echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Certified</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                } else {
                                                    echo '<a href="#" title="Information Technology Officer" class="submit_btn" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Save & Submit</a>';
                                                }
                                                ?>

                                                <!-------------------track-------------------->

                                                <a href="#" data-role="upload" data-id="<?php echo $row['complain_id']; ?>" class="position"><i class="fa fa-cog"></i>Track</a>
                                            </div>
                                            <br>
                                            <div>
                                                <hr>
                                                <h5>Final Recommendation for a complain</h5>
                                                <!-------------------verify_btn-------------------->
                                                <?php
                                                if ($role == 3) {
                                                    if ($row['certified'] == 0) {
                                                        echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until IT Officer certifies payment details, the button is disabled.</span>';
                                                    } elseif ($row['sys_recommendation'] == 1) {
                                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Sys Admin Recommended</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                        echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="System Administrator" class="verify_btn"><i class="fa fa-search"></i>Verify</a>';
                                                    }
                                                } elseif ($row['sys_recommendation'] == 1) {
                                                    echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Sys Admin Recommended</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                } else {
                                                    // Disable "sys admin recommendation" button for other roles without displaying a message
                                                    echo '<a href="#" title="System Administrator" class="verify_btn" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Verify</a>';
                                                }
                                                ?>

                                                <!-------------------finance_btn-------------------->
                                                <?php
                                                if ($role == 5) {
                                                    if ($row['sys_recommendation'] == 0) {
                                                        echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until System Admin recommend the payment details, the button is disabled.</span>';
                                                    } elseif ($row['dgm_recommendation'] == 1) {
                                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>DGM Finance Recommended</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                        echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="DGM Finance" class="finance_btn"><i class="fa fa-search"></i>Verify</a>';
                                                    }
                                                } elseif ($row['dgm_recommendation'] == 1) {
                                                    echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>DGM Finance Recommended</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                } else {
                                                    echo '<a href="#" title="DGM Finance" class="finance_btn" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Verify</a>';
                                                }
                                                ?>

                                                <!-------------------approve_btn-------------------->
                                                <?php
                                                if ($role == 6) {
                                                    if ($row['dgm_recommendation'] == 0) {
                                                        echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until DGM Finance recommend the payment details, the button is disabled.</span>';
                                                    } elseif ($row['approval'] == 1) {
                                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>GM Recommended</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                        echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" title="General Manager" class="approve_btn"><i class="fa fa-search"></i>Verify</a>';
                                                    }
                                                } elseif ($row['approval'] == 1) {
                                                    echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>GM Recommended</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                } else {
                                                    echo '<a href="#" title="General Manager" class="finance_btn" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-search"></i>Verify</a>';
                                                }
                                                ?>

                                                <!-------------------finish_btn-------------------->
                                                <?php
                                                if ($role == 2) {
                                                    if ($row['approval'] == 0) {
                                                        echo '<span class="search_mess"><i class="far fa-thumbs-up"></i>Until GM approved, the button is disabled.</span>';
                                                    } elseif ($row['py_success'] == 1) {
                                                        echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Payment Successful</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                                    } else {
                                                        echo '<a href="#" data-role="upload" data-id="' . $row['complain_id'] . '" class="finish_btn"><i class="fa fa-list-alt"></i>Procument</a>';
                                                    }
                                                } elseif ($row['py_success'] == 1) {
                                                    echo '<span class="verify_mess"><i class="far fa-thumbs-up"></i>Verified</span>&nbsp;&nbsp;&nbsp;&nbsp;';
                                                } else {
                                                    echo '<a href="#" class="finish_btn" style="pointer-events: none; background-color: gray; border:none;"><i class="fa fa-list-alt"></i>Procument</a>';
                                                }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                        </tbody>
                <?php }
                            } else {
                                echo '<tbody><tr><td colspan="9">';
                                echo '<label class="message">There was Not Data</label>';
                                echo '</td>';
                                echo '</tr>';
                                echo '</tbody>';
                            }
                ?>
                    </table>
                    <!-----------------------table-------------------------->
                    <br>
                </div>
                <div class="item_sec4">
                    <!--------------------pagination table----------------------->
                    <div>
                        <p class="txt_t4"><?php echo $textline1; ?></p>
                        <p class="txt_t4"><?php echo $textline2; ?></p>
                    </div>
                    <div id="pagination" align="right">
                        <?php if (!empty($result)) {
                            echo $paginationCtrls;
                        } ?>
                    </div>
                    <!--------------------pagination table----------------------->
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!--------------------------popup (VIEW)----------------------->
<div id="popup_view_btn" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="vieid"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="viecomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset No :</b></span>
                    <span class="infor_t2" id="viecomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="viecomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No :</b></span>
                    <span class="infor_t2" id="viecomemp"></span>
                </div>
                <br><br><br>

                <!--------------------service--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Service Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Solution Description :</b></span>
                            <span class="infor_t2" id="viesolu"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Job Start Date :</b></span>
                            <span class="infor_t2" id="viedate"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <!--------------------Issue Fixing & Payment Status--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Invoice Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Fixed Date :</b></span>
                            <span class="infor_t2" id="viefixdate"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice No :</b></span>
                            <span class="infor_t2" id="vieinno"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice Amount (Rs) :</b></span>
                            <span class="infor_t2" id="vieinamount"></span>
                        </div>
                    </div>
                    <br><br><br>

                    <div class="popup_head">
                        <h1 class="page_heding2">Documents</h1>
                    </div>
                    <div class="popup_container">
                        <div class="pro1" align="center">
                            <span class="infor_t2"></span>
                            <img id="vieimage" src="" alt="Invoice Image" style="max-width: 50%; height: auto;">
                            <img id="vieqimage" src="" alt="Quotation Image" style="max-width: 50%; height: auto;">
                        </div>
                    </div>
                    <br><br><br>

                    <div class="popup_head">
                        <h1 class="page_heding2">Officer Confirmation</h1>
                    </div>
                    <div class="popup_container">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Helpdesk Officer Confirmation :</b></span>
                            <span class="infor_t2" id="vieconfv"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Information Technology Officer Confirmation :</b></span>
                            <span class="infor_t2" id="vieitconf"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>System Administrator Confirmation :</b></span>
                            <span class="infor_t2" id="viesysconf"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>DGM Finance Confirmation :</b></span>
                            <span class="infor_t2" id="vieficonf"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>General Manager Approval :</b></span>
                            <span class="infor_t2" id="viegmconf"></span>
                        </div>
                        <!--------------------------------------->
                    </div>
                </div>
                <br>
            </div>
            <div class="popup_footer">
            </div>
        </div>
    </div>
    <!-------------------popup----------------------->
</div>

<!-------------------view (JS)----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".view_btn").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comempid = $('#' + id).children('td[data-target=comempid]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobdate = $('#' + id).children('td[data-target=jobdate]').text();
            var invfixeddate = $('#' + id).children('td[data-target=invfixeddate]').text();
            var invinno = $('#' + id).children('td[data-target=invinno]').text();
            var invinamount = $('#' + id).children('td[data-target=invinamount]').text();
            var invver = $('#' + id).children('td[data-target=invver]').text();
            var invcer = $('#' + id).children('td[data-target=invcer]').text();
            var invsysrec = $('#' + id).children('td[data-target=invsysrec]').text();
            var invdgmrec = $('#' + id).children('td[data-target=invdgmrec]').text();
            var invgmrec = $('#' + id).children('td[data-target=invapprove]').text();
            var invimage = $('#' + id).children('td[data-target=invimg]').text();
            var qimage = $('#' + id).children('td[data-target=qimg]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#vieid').text(comid);
            $('#viewpag').text(pag);
            $('#viecomdate').text(comdate);
            $('#viecomasset').text(comasset);
            $('#viecomserial').text(comserial);
            $('#viecomemp').text(comempid);
            $('#viesolu').text(jobsolu);
            $('#viedate').text(jobdate);
            $('#viefixdate').text(invfixeddate);
            $('#vieinno').text(invinno);
            $('#vieinamount').text(invinamount);
            $('#vieconfv').text(invver);
            $('#vieitconf').text(invcer);
            $('#viesysconf').text(invsysrec);
            $('#vieficonf').text(invdgmrec);
            $('#viegmconf').text(invgmrec);
            $('#vieimage').attr('src', '/invoice/' + invimage);
            $('#vieqimage').attr('src', '/quotation/' + qimage);
            document.getElementById('popup_view_btn').style.display = 'block';
        })

    });
</script>

<!--------------------------popup1 window (verify)----------------------->
<div id="popup_verify" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="viewid"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="viewcomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset No :</b></span>
                    <span class="infor_t2" id="viewcomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="viewcomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No :</b></span>
                    <span class="infor_t2" id="viewcomemp"></span>
                </div>
                <div class="popup_footer">
                </div>
            </div>
        </div>

        <!--------------------service--------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Service Information</h1>
            <p class="page_heding4">IT Department</p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Solution Description :</b></span>
                    <span class="infor_t2" id="viewsolu"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Job Start Date :</b></span>
                    <span class="infor_t2" id="viewdate"></span>
                </div>
                <div class="popup_footer">
                </div>
            </div>
        </div>

        <!--------------------Issue Fixing & Payment Status--------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Invoice Information</h1>
            <p class="page_heding4">IT Department</p>
            <!-----------important data------------->
            <input type="hidden" id="qutid" name="quotationid">
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Fixed Date :</b></span>
                </div>
                <div class="pro1">
                    <input type="date" class="formtxtare" name="fixeddate" id="fixeddate">
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Invoice No :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" class="formtxtare" name="invoiceno" id="invoiceno">
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Invoice Amount (Rs) :</b></span>
                </div>
                <div class="pro1">
                    <input type="text" class="formtxtare" name="invoiceamount" id="invoiceamount">
                </div>
                <div class="pro1">
                </div>
            </div>
            <!-- Add QUOTATION iMAGE -->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2">Upload Image</span>
                </div>
                <div class="pro1">
                    <input type="file" id="myFile" name="filename" multiple>
                    <p class="comment">Image size should be less than 1MB</p>
                </div>
                <div class="pro1">
                    <span id="confirm-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Recommendation :</b></span>
                </div>
                <div class="pro1">
                    <input type="radio" name="esucver" value="1">
                    <label class='form_radio'>Recommendation</label>
                    &nbsp;&nbsp;<input type="radio" name="esucver" value="0">
                    <label class='form_radio'>Not Recommendation</label>
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendsuccessverify();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="esuccessver-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Recommendation Status :</b></span>
                </div>
                <div class="pro1">
                    <textarea placeholder="Write Recommendation Status.." class="formtxtare" name="status" id="status"></textarea>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
        </div>
        <div class="popup_footer">
            <span id="status_message"></span>
        </div>
    </div>
</div>
<!-------------------verify (JS)----------------------->
<script type="text/javascript">
    //----------------------success_verification-----------------------------
    function sendsuccessverify() {
        var valid;
        valid = validatesuccessverify();
        if (valid) {
            var formData = new FormData();
            formData.append('invver', $('input[name="esucver"]:checked').val() || 0);
            formData.append('fixeddate', $('#fixeddate').val());
            formData.append('invoiceno', $('#invoiceno').val());
            formData.append('invoiceamount', $('#invoiceamount').val());
            formData.append('filename', $('#myFile')[0].files[0]); // Append the file
            formData.append('quotationid', $('#qutid').val());
            formData.append('status', $('#status').val());
            formData.append('userid', $('#userid').val());
            formData.append('setid', $('#eid').val());

            jQuery.ajax({
                url: "include_ajaxs/pay_hpd_recommend.php",
                data: formData,
                type: "POST",
                processData: false, // Prevent jQuery from processing data
                contentType: false, // Set contentType to false for file upload
                success: function(data) {
                    $("#status_message").html(data);
                },
                error: function() {
                    $("#status_message").html('<label class="error_mess">An error occurred while uploading.</label>');
                }
            });
        }
    }
    //--------------------------------------------------------
    function validatesuccessverify() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='esucver']:checked").length == 0) {
            $("#esuccessver-info").html("<i class='fa fa-times'></i>Verification is Required");
            valid = false;
        } else {
            $("#esuccessver-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".verify").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comempid = $('#' + id).children('td[data-target=comempid]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobdate = $('#' + id).children('td[data-target=jobdate]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            var comquotationno = $('#' + id).children('td[data-target=comquotationno]').text();
            $('#viewid').text(comid);
            $('#eid').val(comid);
            $('#viewpag').text(pag);
            $('#viewcomdate').text(comdate);
            $('#viewcomasset').text(comasset);
            $('#viewcomserial').text(comserial);
            $('#viewcomemp').text(comempid);
            $('#viewsolu').text(jobsolu);
            $('#viewdate').text(jobdate);
            $('#qutid').val(comquotationno);
            document.getElementById('popup_verify').style.display = 'block';
        })

    });
</script>

<!--------------------------popup2 window (Save & Submit button)----------------------->
<div id="popup_submit_btn" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="vid"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="vcomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset No :</b></span>
                    <span class="infor_t2" id="vcomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="vcomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No :</b></span>
                    <span class="infor_t2" id="vcomemp"></span>
                </div>
                <br><br><br>

                <!--------------------service--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Service Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Solution Description :</b></span>
                            <span class="infor_t2" id="vsolu"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Job Start Date :</b></span>
                            <span class="infor_t2" id="vdate"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <!--------------------Issue Fixing & Payment Status--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Invoice Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Fixed Date :</b></span>
                            <span class="infor_t2" id="vfixdate"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice No :</b></span>
                            <span class="infor_t2" id="vinno"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice Amount (Rs) :</b></span>
                            <span class="infor_t2" id="vinamount"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Helpdesk Officer Conformation :</b></span>
                            <span class="infor_t2" id="viconf"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Date :</b><input type="date" id="certify_date" name="certify_date" required="required" style="border: 1px solid #BDBDBD; width:20%; height:30px"></span>
                            <span class="infor_t2" id="vidate"></span>
                        </div>
                        <!--------------------------------------->
                        <div class="pro1_warp">
                            <div class="pro1" align="right">
                                <span class="infor_t2"><b>Recommendation :</b></span>
                            </div>
                            <div class="pro1">
                                <input type="radio" name="itrec" value="1">
                                <label class='form_radio'>Recommendation</label>
                                &nbsp;&nbsp;<input type="radio" name="itrec" value="0">
                                <label class='form_radio'>Not Recommendation</label>
                            </div>
                            <div class="pro1">
                                <button class="edit_btn" onclick="senditrecom();">
                                    <i class="fas fa-plus-square"></i>
                                </button>
                                <span id="itrec-info" class="error"></span>
                            </div>
                        </div>
                        <!--------------------------------------->
                        <div class="pro1_warp">
                            <div class="pro1" align="right">
                                <span class="infor_t2"><b>Recommendation Status :</b></span>
                            </div>
                            <div class="pro1">
                                <textarea placeholder="Write Recommendation Status.." class="formtxtare" name="statusit" id="statusit"></textarea>
                            </div>
                            <div class="pro1">
                            </div>
                        </div>
                        <!--------------------------------------->
                    </div>
                </div>
                <div class="popup_footer">
                    <span id="status_message_it"></span>
                </div>
            </div>
        </div>
    </div>
    <!-------------------popup2----------------------->
</div>
<!-------------------save & submit (JS)----------------------->
<script type="text/javascript">
    //----------------------jobconf-----------------------------
    function senditrecom() {
        var valid;
        valid = validatesitrecom();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/pay_it_recommend.php",
                data: 'invcer=' + $('input[name="itrec"]:checked').val() +
                    '&certify_date=' + $('#certify_date').val() +
                    '&statusit=' + $('#statusit').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_it").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatesitrecom() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='itrec']:checked").length == 0) {
            $("#itrec-info").html("<i class='fa fa-times'></i>Recommendation is Required");
            valid = false;
        } else {
            $("#itrec-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".submit_btn").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comempid = $('#' + id).children('td[data-target=comempid]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobdate = $('#' + id).children('td[data-target=jobdate]').text();
            var invfixeddate = $('#' + id).children('td[data-target=invfixeddate]').text();
            var invinno = $('#' + id).children('td[data-target=invinno]').text();
            var invinamount = $('#' + id).children('td[data-target=invinamount]').text();
            var invver = $('#' + id).children('td[data-target=invver]').text();
            var invdate = $('#' + id).children('td[data-target=invdate]').text();
            var invcer = $('#' + id).children('td[data-target=invcer]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();

            $('#vid').text(comid);
            $('#eid').val(comid);
            $('#viewpag').text(pag);
            $('#vcomdate').text(comdate);
            $('#vcomasset').text(comasset);
            $('#vcomserial').text(comserial);
            $('#vcomemp').text(comempid);
            $('#vsolu').text(jobsolu);
            $('#vdate').text(jobdate);
            $('#vfixdate').text(invfixeddate);
            $('#vinno').text(invinno);
            $('#vinamount').text(invinamount);
            $('#viconf').text(invver);
            $('#viecondate').text(invdate);
            $('#vieitconf').text(invcer);

            document.getElementById('popup_submit_btn').style.display = 'block';
        })

    });
</script>

<!-------------------popup3 (track)----------------------->
<div id="popup_position" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <div class="popup_head">
            <h1 class="page_heding2">Complain Position</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="did"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center" id="status_box">


                </div>
            </div>
        </div>
        <div class="popup_footer">
        </div>
    </div>
</div>

<!--------------------------popup3 window (Verify btn - Sys Ad.)----------------------->
<div id="popup_verify_btn" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="viid"></span>
            </p>
            <!-----------important data------------->
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="vicomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset No :</b></span>
                    <span class="infor_t2" id="vicomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="vicomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No :</b></span>
                    <span class="infor_t2" id="vicomemp"></span>
                </div>
                <br><br><br>

                <!--------------------service--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Service Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Solution Description :</b></span>
                            <span class="infor_t2" id="visolu"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Job Start Date :</b></span>
                            <span class="infor_t2" id="visdate"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <!--------------------Issue Fixing & Payment Status--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Invoice Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Fixed Date :</b></span>
                            <span class="infor_t2" id="vifixdate"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice No :</b></span>
                            <span class="infor_t2" id="viinno"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice Amount (Rs) :</b></span>
                            <span class="infor_t2" id="viinamount"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <div class="popup_head">
                    <h1 class="page_heding2">Documents</h1>
                </div>
                <div class="popup_container">
                    <div class="pro1" align="center">
                        <span class="infor_t2"></span>
                        <img id="vimage" src="" alt="Invoice Image" style="max-width: 50%; height: auto;">
                        <img id="vqimage" src="" alt="Quotation Image" style="max-width: 50%; height: auto;">
                    </div>
                </div>
            </div>
            <br><br><br>

            <!------------------Confirmation--------------------->
            <div class="popup_head">
                <h1 class="page_heding2">Officer Confirmation</h1>
                <p class="page_heding4">Confirmation</p>
            </div>
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Helpdesk Officer Confirmation :</b></span>
                    <span class="infor_t2" id="viconfhd"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Confirm Date :</b></span>
                    <span class="infor_t2" id="vicondate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Information Technology Officer Confirmation :</b></span>
                    <span class="infor_t2" id="viitconf"></span>
                </div>
                <br>
            </div>
            <div class="popup_container">
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>Recommendation :</b></span>
                    </div>
                    <div class="pro1">
                        <input type="radio" name="sysrec" value="1">
                        <label class='form_radio'>Recommendation</label>
                        &nbsp;&nbsp;<input type="radio" name="sysrec" value="0">
                        <label class='form_radio'>Not Recommendation</label>
                    </div>
                    <div class="pro1">
                        <button class="edit_btn" onclick="sendsysrecom();">
                            <i class="fas fa-plus-square"></i>
                        </button>
                        <span id="sysrec-info" class="error"></span>
                    </div>
                </div>
                <!--------------------------------------->
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>Recommendation Status :</b></span>
                    </div>
                    <div class="pro1">
                        <textarea placeholder="Write Recommendation Status.." class="formtxtare" name="statussys" id="statussys"></textarea>
                    </div>
                    <div class="pro1">
                    </div>
                </div>
            </div>
            <!--------------------------------------->
            <div class="popup_footer">
                <span id="status_message_sys"></span>
            </div>
        </div>
    </div>
</div>
</div>
<!-------------------verify (Sys Ad.) (JS)----------------------->
<script type="text/javascript">
    //----------------------invver-----------------------------
    function sendsysrecom() {
        var valid;
        valid = validatessysrecom();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/pay_sys_recommend.php",
                data: 'invsysrec=' + $('input[name="sysrec"]:checked').val() +
                    '&statussys=' + $('#statussys').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_sys").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatessysrecom() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='sysrec']:checked").length == 0) {
            $("#sysrec-info").html("<i class='fa fa-times'></i>Recommendation is Required");
            valid = false;
        } else {
            $("#sysrec-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".verify_btn").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comempid = $('#' + id).children('td[data-target=comempid]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobdate = $('#' + id).children('td[data-target=jobdate]').text();
            var invfixeddate = $('#' + id).children('td[data-target=invfixeddate]').text();
            var invinno = $('#' + id).children('td[data-target=invinno]').text();
            var invinamount = $('#' + id).children('td[data-target=invinamount]').text();
            var invimg = $('#' + id).children('td[data-target=invimg]').text();
            var invver = $('#' + id).children('td[data-target=invver]').text();
            var invdate = $('#' + id).children('td[data-target=invdate]').text();
            var invcer = $('#' + id).children('td[data-target=invcer]').text();
            var qimg = $('#' + id).children('td[data-target=qimg]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();

            $('#viid').text(comid);
            $('#eid').val(comid);
            $('#viewpag').text(pag);
            $('#vicomdate').text(comdate);
            $('#vicomasset').text(comasset);
            $('#vicomserial').text(comserial);
            $('#vicomemp').text(comempid);
            $('#visolu').text(jobsolu);
            $('#visdate').text(jobdate);
            $('#vifixdate').text(invfixeddate);
            $('#viinno').text(invinno);
            $('#viinamount').text(invinamount);
            $('#vimage').attr('src', '/invoice/' + invimg);
            $('#vqimage').attr('src', '/quotation/' + qimg);
            $('#viconfhd').text(invver);
            $('#vicondate').text(invdate);
            $('#viitconf').text(invcer);
            document.getElementById('popup_verify_btn').style.display = 'block';
        })
    });
</script>

<!--------------------------popup4 window (Verify btn - DGM(Finance) )----------------------->
<div id="popup_finance_btn" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="vaid"></span>
            </p>
            <!-----------important data------------->
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="vacomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset No :</b></span>
                    <span class="infor_t2" id="vacomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="vacomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No :</b></span>
                    <span class="infor_t2" id="vacomemp"></span>
                </div>
                <br><br><br>

                <!--------------------service--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Service Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Solution Description :</b></span>
                            <span class="infor_t2" id="vasolu"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Job Start Date :</b></span>
                            <span class="infor_t2" id="vadate"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <!--------------------Issue Fixing & Payment Status--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Invoice Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Fixed Date :</b></span>
                            <span class="infor_t2" id="vafixdate"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice No :</b></span>
                            <span class="infor_t2" id="vainno"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice Amount (Rs) :</b></span>
                            <span class="infor_t2" id="vainamount"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <div class="popup_head">
                    <h1 class="page_heding2">Documents</h1>
                </div>
                <div class="popup_container">
                    <div class="pro1" align="center">
                        <span class="infor_t2"></span>
                        <img id="vfimage" src="" alt="Invoice Image" style="max-width: 50%; height: auto;">
                        <img id="vfqimage" src="" alt="Quotation Image" style="max-width: 50%; height: auto;">
                    </div>
                </div>

            </div>
        </div>
        <br><br><br>

        <!------------------Confirmation--------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Officer Confirmation</h1>
            <p class="page_heding4">Confirmation</p>
        </div>

        <div class="popup_container">
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>Helpdesk Officer Confirmation :</b></span>
                <span class="infor_t2" id="vaconfhd"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>Confirm Date :</b></span>
                <span class="infor_t2" id="vacondate"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>Information Technology Officer Confirmation :</b></span>
                <span class="infor_t2" id="vaitconf"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>System Administrator Confirmation :</b></span>
                <span class="infor_t2" id="vasysconf"></span>
            </div><br>
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Recommendation :</b></span>
                </div>
                <div class="pro1">
                    <input type="radio" name="dgmrec" value="1">
                    <label class='form_radio'>Recommendation</label>
                    &nbsp;&nbsp;<input type="radio" name="dgmrec" value="0">
                    <label class='form_radio'>Not Recommendation</label>
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="senddgmrec();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="dgmrec-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Recommendation Status :</b></span>
                </div>
                <div class="pro1">
                    <textarea placeholder="Write Recommendation Status.." class="formtxtare" name="statusdgm" id="statusdgm"></textarea>
                </div>
                <div class="pro1">
                </div>
            </div>
        </div>
        <!--------------------------------------->
        <div class="popup_footer">
            <span id="status_message_dgm"></span>
        </div>
    </div>
</div>

</div>
<!-------------------finance(DGM - finance) (JS)----------------------->
<script type="text/javascript">
    //----------------------comrec-----------------------------
    function senddgmrec() {
        var valid;
        valid = validatesdgmrec();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/pay_dgm_recommend.php",
                data: 'invdgmrec=' + $('input[name="dgmrec"]:checked').val() +
                    '&statusdgm=' + $('#statusdgm').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_dgm").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatesdgmrec() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='dgmrec']:checked").length == 0) {
            $("#dgmrec-info").html("<i class='fa fa-times'></i>Recommendation is Required");
            valid = false;
        } else {
            $("#dgmrec-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".finance_btn").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comempid = $('#' + id).children('td[data-target=comempid]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobdate = $('#' + id).children('td[data-target=jobdate]').text();
            var invfixeddate = $('#' + id).children('td[data-target=invfixeddate]').text();
            var invinno = $('#' + id).children('td[data-target=invinno]').text();
            var invinamount = $('#' + id).children('td[data-target=invinamount]').text();
            var invver = $('#' + id).children('td[data-target=invver]').text();
            var invdate = $('#' + id).children('td[data-target=invdate]').text();
            var invcer = $('#' + id).children('td[data-target=invcer]').text();
            var invsysrec = $('#' + id).children('td[data-target=invsysrec]').text();
            var invimage = $('#' + id).children('td[data-target=invimg]').text();
            var qimage = $('#' + id).children('td[data-target=qimg]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();

            $('#vaid').text(comid);
            $('#eid').val(comid);
            $('#viewpag').text(pag);
            $('#vacomdate').text(comdate);
            $('#vacomasset').text(comasset);
            $('#vacomserial').text(comserial);
            $('#vacomemp').text(comempid);
            $('#vasolu').text(jobsolu);
            $('#vadate').text(jobdate);
            $('#vafixdate').text(invfixeddate);
            $('#vainno').text(invinno);
            $('#vainamount').text(invinamount);
            $('#vaconfhd').text(invver);
            $('#vacondate').text(invdate);
            $('#vaitconf').text(invcer);
            $('#vasysconf').text(invsysrec);
            $('#vfimage').attr('src', '/invoice/' + invimage);
            $('#vfqimage').attr('src', '/quotation/' + qimage);
            document.getElementById('popup_finance_btn').style.display = 'block';
        })

    });
</script>

<!--------------------------popup5 window (Verify btn - GM Approval )----------------------->
<div id="popup_approve_btn" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="valid"></span>
            </p>
            <!-----------important data------------->
            <input type="hidden" id="eid" name="setid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="valcomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset No :</b></span>
                    <span class="infor_t2" id="valcomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="valcomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No :</b></span>
                    <span class="infor_t2" id="valcomemp"></span>
                </div>
                <br><br><br>

                <!--------------------service--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Service Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Solution Description :</b></span>
                            <span class="infor_t2" id="valsolu"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Job Start Date :</b></span>
                            <span class="infor_t2" id="valdate"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <!--------------------Issue Fixing & Payment Status--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Invoice Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Fixed Date :</b></span>
                            <span class="infor_t2" id="valfixdate"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice No :</b></span>
                            <span class="infor_t2" id="valinno"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice Amount (Rs) :</b></span>
                            <span class="infor_t2" id="valinamount"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <div class="popup_head">
                    <h1 class="page_heding2">Documents</h1>
                </div>
                <div class="popup_container">
                    <div class="pro1" align="center">
                        <span class="infor_t2"></span>
                        <img id="vgimage" src="" alt="Invoice Image" style="max-width: 50%; height: auto;">
                        <img id="vgqimage" src="" alt="Quotation Image" style="max-width: 50%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
        <br><br><br>

        <!------------------Confirmation--------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Officer Confirmation</h1>
            <p class="page_heding4">Confirmation</p>
        </div>
        <div class="popup_container">
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>Helpdesk Officer Confirmation :</b></span>
                <span class="infor_t2" id="valconfhd"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>Confirm Date :</b></span>
                <span class="infor_t2" id="valcondate"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>Information Technology Officer Confirmation :</b></span>
                <span class="infor_t2" id="valitconf"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>System Administrator Confirmation :</b></span>
                <span class="infor_t2" id="valsysconf"></span>
            </div>
            <div class="pro1" align="center">
                <span class="infor_t2">
                    <b>DGM (Finance) Confirmation :</b></span>
                <span class="infor_t2" id="valdgmconf"></span>
            </div><br>
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Approval :</b></span>
                </div>
                <div class="pro1">
                    <input type="radio" name="approve" value="1">
                    <label class='form_radio'>Approved</label>
                    &nbsp;&nbsp;<input type="radio" name="approve" value="0">
                    <label class='form_radio'>Not Approved</label>
                </div>
                <div class="pro1">
                    <button class="edit_btn" onclick="sendaprvrecom();">
                        <i class="fas fa-plus-square"></i>
                    </button>
                    <span id="aprvrec-info" class="error"></span>
                </div>
            </div>
            <!--------------------------------------->
            <div class="pro1_warp">
                <div class="pro1" align="right">
                    <span class="infor_t2"><b>Approval Status :</b></span>
                </div>
                <div class="pro1">
                    <textarea placeholder="Write Approval Status.." class="formtxtare" name="statusgm" id="statusgm"></textarea>
                </div>
                <div class="pro1">
                </div>
            </div>
            <!--------------------------------------->
        </div>
        <div class="popup_footer">
            <span id="status_message_gm"></span>
        </div>
    </div>

</div>
<!-------------------approval_btn (GM Approval) (JS)----------------------->
<script type="text/javascript">
    //----------------------comrec-----------------------------
    function sendaprvrecom() {
        var valid;
        valid = validatesaprvrecom();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/pay_aprv_recommend.php",
                data: 'invapprove=' + $('input[name="approve"]:checked').val() +
                    '&statusgm=' + $('#statusgm').val() +
                    '&userid=' + $('#userid').val() +
                    '&setid=' + $('#eid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_gm").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatesaprvrecom() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='approve']:checked").length == 0) {
            $("#aprvrec-info").html("<i class='fa fa-times'></i>Recommendation is Required");
            valid = false;
        } else {
            $("#aprvrec-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".approve_btn").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comempid = $('#' + id).children('td[data-target=comempid]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobdate = $('#' + id).children('td[data-target=jobdate]').text();
            var invfixeddate = $('#' + id).children('td[data-target=invfixeddate]').text();
            var invinno = $('#' + id).children('td[data-target=invinno]').text();
            var invinamount = $('#' + id).children('td[data-target=invinamount]').text();
            var invver = $('#' + id).children('td[data-target=invver]').text();
            var invdate = $('#' + id).children('td[data-target=invdate]').text();
            var invcer = $('#' + id).children('td[data-target=invcer]').text();
            var invsysrec = $('#' + id).children('td[data-target=invsysrec]').text();
            var invdgmrec = $('#' + id).children('td[data-target=invdgmrec]').text();
            var invoiceimg = $('#' + id).children('td[data-target=invimg]').text();
            var qutimage = $('#' + id).children('td[data-target=qimg]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();

            $('#valid').text(comid);
            $('#eid').val(comid);
            $('#viewpag').text(pag);
            $('#valcomdate').text(comdate);
            $('#valcomasset').text(comasset);
            $('#valcomserial').text(comserial);
            $('#valcomemp').text(comempid);
            $('#valsolu').text(jobsolu);
            $('#valdate').text(jobdate);
            $('#valfixdate').text(invfixeddate);
            $('#valinno').text(invinno);
            $('#valinamount').text(invinamount);
            $('#valconfhd').text(invver);
            $('#valcondate').text(invdate);
            $('#valitconf').text(invcer);
            $('#valsysconf').text(invsysrec);
            $('#valdgmconf').text(invdgmrec);
            $('#vgimage').attr('src', '/invoice/' + invoiceimg);
            $('#vgqimage').attr('src', '/quotation/' + qutimage);
            document.getElementById('popup_approve_btn').style.display = 'block';
        })

    });
</script>



<!--------------------------popup6 window (Finish)----------------------->
<div id="popup_finish_btn" class="modal">
    <div class="modal_content animate">
        <div class="cancel_container">
            <div id="infor"></div>
            <div id="cancel" align="right">
                <button type="button" onClick="cancel_view();" class="cancelbtn"><i class="fas fa-times"></i></button>
            </div>
        </div>
        <!-------------------Complain-------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Complain Information</h1>
            <p class="page_heding4">Information about Complain Id :
                <span class="infor_t4" id="vfid"></span>
            </p>
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Complain Date :</b></span>
                    <span class="infor_t2" id="vfcomdate"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Asset No :</b></span>
                    <span class="infor_t2" id="vfcomasset"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Serial No :</b></span>
                    <span class="infor_t2" id="vfcomserial"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Employee No :</b></span>
                    <span class="infor_t2" id="vfcomemp"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Observation :</b></span>
                    <span class="infor_t2" id="vfcomobs"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>Recommendation :</b></span>
                    <span class="infor_t2" id="vfcomrec"></span>
                </div>
                <br><br><br>

                <!--------------------service--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Service Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Assigned Office :</b></span>
                            <span class="infor_t2" id="vfofficer"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Issue Description :</b></span>
                            <span class="infor_t2" id="vfissue"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Recommendation :</b></span>
                            <span class="infor_t2" id="vfrec"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Solution Description :</b></span>
                            <span class="infor_t2" id="vfsolu"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Job Start Date :</b></span>
                            <span class="infor_t2" id="vfdate"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Helpdesk Officer Confirmation :</b></span>
                            <span class="infor_t2" id="vfconf"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <!--------------------Issue Fixing & Payment Status--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Invoice Information</h1>
                    <p class="page_heding4">IT Department</p>
                </div>
                <div class="popup_container">
                    <div class="pro1_wapper">
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Fixed Date :</b></span>
                            <span class="infor_t2" id="vffixdate"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice No :</b></span>
                            <span class="infor_t2" id="vfinno"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Invoice Amount (Rs) :</b></span>
                            <span class="infor_t2" id="vfinamount"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Helpdesk Officer Confirmation :</b></span>
                            <span class="infor_t2" id="vfconfhd"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Confirm Date :</b></span>
                            <span class="infor_t2" id="vfcondate"></span>
                        </div>
                        <div class="pro1" align="center">
                            <span class="infor_t2">
                                <b>Information Technology Officer Confirmation :</b></span>
                            <span class="infor_t2" id="vfitconf"></span>
                        </div>
                    </div>
                </div>
                <br><br><br>

                <!------------------Confirmation--------------------->
                <div class="popup_head">
                    <h1 class="page_heding2">Officer Confirmation</h1>
                    <p class="page_heding4">Confirmation</p>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>System Administrator Confirmation :</b></span>
                    <span class="infor_t2" id="vfsysconf"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>DGM (Finance) Confirmation :</b></span>
                    <span class="infor_t2" id="vfdgmconf"></span>
                </div>
                <div class="pro1" align="center">
                    <span class="infor_t2">
                        <b>General Manager Confirmation :</b></span>
                    <span class="infor_t2" id="vfgmconf"></span>
                </div>
            </div>
        </div>
        <br><br><br>


        <!------------------Voucher details--------------------->
        <div class="popup_head">
            <h1 class="page_heding2">Voucher/Cheque Information</h1>
            <p class="page_heding4">Information</p>
            <!-----------important data------------->
            <input type="hidden" id="invid" name="invoiceid">
            <input type="hidden" id="veid" name="vsetid">
            <input type="hidden" name="pag" id="epag">
            <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>">
        </div>
        <div class="popup_container">
            <div class="pro1_wapper">
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>Voucher No :</b></span>
                    </div>
                    <div class="pro1">
                        <input type="text" class="voucherno" name="voucherno" id="voucherno">
                    </div>
                    <div class="pro1">
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>Cheque No :</b></span>
                    </div>
                    <div class="pro1">
                        <input type="text" class="chequeno" name="chequeno" id="chequeno">
                    </div>
                    <div class="pro1">
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"><b>Paid Date :</b></span>
                    </div>
                    <div class="pro1">
                        <input type="date" class="date" name="paiddate" id="paiddate">
                    </div>
                    <div class="pro1">
                    </div>
                </div>
                <div class="pro1_warp">
                    <div class="pro1" align="right">
                        <span class="infor_t2"></span>
                    </div>
                    <div class="pro1">
                        <input type="checkbox" name="epro" value="1">
                        <label class='form_radio'>I confirmed above details are correct.</label>
                    </div>
                    <div class="pro1">
                        <button class="edit_btn" onclick="sendpro();">
                            <i class="fas fa-plus-square"></i>
                        </button>
                        <span id="epro-info" class="error"></span>
                    </div>
                </div>
                <div class="popup_footer">
                    <span id="status_message_pro"></span>
                </div>

            </div>
        </div>
    </div>
</div>
<!-------------------finish_btn (JS)----------------------->
<script type="text/javascript">
    //----------------------comrec-----------------------------
    function sendpro() {
        var valid;
        valid = validatespro();
        if (valid) {
            jQuery.ajax({
                url: "include_ajaxs/add_procument.php",
                data: 'success=' + $('input[name="epro"]:checked').val() +
                    '&voucherno=' + $('#voucherno').val() +
                    '&chequeno=' + $('#chequeno').val() +
                    '&paiddate=' + $('#paiddate').val() +
                    '&invoiceid=' + $('#invid').val() +
                    '&userid=' + $('#userid').val() +
                    '&vsetid=' + $('#veid').val(),
                type: "POST",
                success: function(data) {
                    $("#status_message_pro").html(data);
                },
                error: function() {}
            });
        }
    }
    //--------------------------------------------------------
    function validatespro() {
        var valid = true;
        $(".error").html('');
        //--------------------------validate-----------------------------
        if ($("input[name='epro']:checked").length == 0) {
            $("#epro-info").html("<i class='fa fa-times'></i>Recommendation is Required");
            valid = false;
        } else {
            $("#epro-info").html('<i class="fas fa-check"></i>');
        }
        //----------------------------------------------------
        return valid;
    }
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".finish_btn").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comdate = $('#' + id).children('td[data-target=comdate]').text();
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var comasset = $('#' + id).children('td[data-target=comasset]').text();
            var comserial = $('#' + id).children('td[data-target=comserial]').text();
            var comempid = $('#' + id).children('td[data-target=comempid]').text();
            var comoffice = $('#' + id).children('td[data-target=comoffice]').text();
            var comobs = $('#' + id).children('td[data-target=comobs]').text();
            var comimg = $('#' + id).children('td[data-target=comimg]').text();
            var comrec = $('#' + id).children('td[data-target=comrec]').text();
            var jobofficer = $('#' + id).children('td[data-target=jobofficer]').text();
            var jobissue = $('#' + id).children('td[data-target=jobissue]').text();
            var jobreco = $('#' + id).children('td[data-target=jobreco]').text();
            var jobsolu = $('#' + id).children('td[data-target=jobsolu]').text();
            var jobdate = $('#' + id).children('td[data-target=jobdate]').text();
            var jobconf = $('#' + id).children('td[data-target=jobconf]').text();
            var invfixeddate = $('#' + id).children('td[data-target=invfixeddate]').text();
            var invinno = $('#' + id).children('td[data-target=invinno]').text();
            var invinamount = $('#' + id).children('td[data-target=invinamount]').text();
            var invver = $('#' + id).children('td[data-target=invver]').text();
            var invdate = $('#' + id).children('td[data-target=invdate]').text();
            var invcer = $('#' + id).children('td[data-target=invcer]').text();
            var invsysrec = $('#' + id).children('td[data-target=invsysrec]').text();
            var invdgmrec = $('#' + id).children('td[data-target=invdgmrec]').text();
            var invapprove = $('#' + id).children('td[data-target=invapprove]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();

            $('#vfid').text(comid);
            $('#veid').val(comid);
            $('#viewpag').text(pag);
            $('#vfcomdate').text(comdate);
            $('#vfcomasset').text(comasset);
            $('#vfcomserial').text(comserial);
            $('#vfcomemp').text(comempid);
            $('#vfcomoffice').text(comoffice);
            $('#vfcomobs').text(comobs);
            $('#vfcomrec').text(comrec);
            $('#vfofficer').text(jobofficer);
            $('#vfissue').text(jobissue);
            $('#vfrec').text(jobreco);
            $('#vfsolu').text(jobsolu);
            $('#vfdate').text(jobdate);
            $('#vfconf').text(jobconf);
            $('#vffixdate').text(invfixeddate);
            $('#vfinno').text(invinno);
            $('#invid').val(invinno);
            $('#vfinamount').text(invinamount);
            $('#vfconfhd').text(invver);
            $('#vfcondate').text(invdate);
            $('#vfitconf').text(invcer);
            $('#vfsysconf').text(invsysrec);
            $('#vfdgmconf').text(invdgmrec);
            $('#vfgmconf').text(invapprove);
            document.getElementById('popup_finish_btn').style.display = 'block';
        })

    });
</script>



<!-------------------position----------------------->
<!-------------------position----------------------->
<script type="text/javascript">
    $(document).ready(function() {
        $(".position").click(function() {
            var id = $(this).data('id');
            var id = id;
            var comid = $('#' + id).children('td[data-target=comid]').text();
            var pag = $('#' + id).children('td[data-target=pag]').text();
            $('#did').text(comid);
            jQuery.ajax({
                url: "include_ajaxs/get_position.php",
                data: 'setid=' + comid,
                type: "POST",
                success: function(data) {
                    $("#status_box").html(data);
                },
                error: function() {}
            });
            document.getElementById('popup_position').style.display = 'block';
        })

    });
</script>
<!-------------------position----------------------->

<!-------------------cancel btns----------------------->
<script>
    function cancel_view() {
        document.getElementById('popup_verify').style.display = 'none';
        location.reload();
    }

    function cancel_submit_btn() {
        document.getElementById('popup_submit_btn').style.display = 'none';
        location.reload();
    }

    function cancel_edit() {
        document.getElementById('popup_verify_btn').style.display = 'none';
        location.reload();
    }

    function cancel_del() {
        document.getElementById('popup_position').style.display = 'none';
        location.reload();
    }

    function relaod() {
        window.open('index.php?page=manage_payment', '_parent');
    }
</script>

<div id="footer_section">
    <!--------------------footer_section----------------------------->
    <script>
        $(document).ready(function() {
            // Add click event listener for all "View" buttons
            $(document).on('click', '.btn', function() {
                var referenceNo = $(this).closest('tr').find('td:first').text();

                // Make an AJAX request to fetch the data
                $.ajax({
                    url: 'fetch_refNo.php',
                    type: 'GET',
                    data: {
                        reference_no: referenceNo
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#reference_no').val(referenceNo);

                        // Display the popup
                        $('#qutPopup').show();
                    },
                    error: function() {
                        alert('Error fetching reference no.');
                    }
                });
            });
        });

        // Get the modal
        var modal = document.getElementById('qutPopup');

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        document.querySelectorAll('.btn').forEach(function(button) {
            button.addEventListener('click', function() {
                document.getElementById('qutPopup').style.display = 'block';
            });
        });

        function addRow() {
            // Get the table and its tbody element
            var table = document.getElementById("quotation_tbl").getElementsByTagName('tbody')[0];

            // Get the current number of rows to set the new row number
            var rowCount = table.rows.length + 1;

            // Insert a new row at the end of the table
            var newRow = table.insertRow();

            newRow.innerHTML = `<td>${rowCount}</td>
                            <td><input type="text" class="item"></td>
                            <td><input type="number" class="amount"></td>`;
        }

        function calculateTotal() {
            var total = 0;
            document.querySelectorAll('.amount').forEach(function(input) {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('amount').value = total.toFixed(2); // Set final amount
        }

        function saveQuotation() {
            var reference_no = document.getElementById('reference_no').value;
            var quotationNo = document.getElementById('quotationno').value;
            var finalAmount = document.getElementById('amount').value;

            var items = [];
            document.querySelectorAll('.item').forEach(function(itemInput, index) {
                var itemName = itemInput.value;
                var itemAmount = document.querySelectorAll('.amount')[index].value;
                items.push({
                    item: itemName,
                    amount: itemAmount
                });
            });

            // AJAX to save quotation data
            $.ajax({
                url: 'save_quotation.php',
                type: 'POST',
                data: {
                    reference_no: reference_no,
                    quotationNo: quotationNo,
                    finalAmount: finalAmount,
                    items: JSON.stringify(items)
                },
                success: function(response) {
                    try {
                        var jsonResponse = JSON.parse(response); // Parse the JSON response
                        if (jsonResponse.success) {
                            alert('Quotation saved successfully!');
                            document.getElementById('qutPopup').style.display = 'none';

                            // Disable the "Add Quotation" button for the current row
                            document.getElementById('add_btn_' + reference_no).disabled = true;
                        } else {
                            alert('Error saving quotation: ' + (jsonResponse.error || 'Unknown error'));
                        }
                    } catch (e) {
                        alert('Unexpected response: ' + response); // Catch JSON parse errors
                    }
                },
                error: function(xhr, status, error) {
                    alert('Failed to submit: ' + error); // More detailed error message
                }
            });
        }

        $(document).on('click', '.view', function() {
            const complainId = $(this).data('complain-id');

            // Make an AJAX request to fetch the data
            $.ajax({
                url: 'fetch_quotation.php',
                type: 'GET',
                data: {
                    complain_id: complainId
                },
                dataType: 'json',
                success: function(response) {
                    // Populate the popup form fields with the fetched data
                    $('#assetno').val(response.assetno);
                    $('#serialno').val(response.serialno);
                    $('#empId').val(response.empId);
                    $('#regOffice').val(response.regOffice);
                    $('#issue').val(response.issue);
                    $('#solution').val(response.solution);
                    $('#ref_no').val(response.reference_no);
                    $('#company').val(response.company);
                    $('#complain_id').val(complainId);

                    // Display the popup
                    $('#viewPopup').show();
                },
                error: function() {
                    alert('Error fetching details.');
                }
            });
        });
    </script>
    <!--------------------footer_section----------------------------->
</div>
</div>