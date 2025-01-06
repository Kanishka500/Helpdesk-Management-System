
        <!-------------------container----------------------->
        <div id="main_content">
            <!--------------------contant here----------------------------->
            <div id="heding_section">
                <!--------------------heding_section----------------------------->
                <h1 class="txt_t1">View User Tracker Information</h1>
                <ul class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">System Staff</a></li>
                    <li><a href="#">View User Tracker</a></li>
                    <li>View User Tracker</li>
                </ul>
                <!--------------------heding_section----------------------------->
            </div>
            <div id="body_section">
                <!--------------------body_section----------------------------->
                <div id="div_section">
                    <h1 class="txt_t2">View User Tracker</h1>
                    <?php
                    if (!empty($date)) {
                        echo '<span class="mess">Yor are Search Information in Day :';
                        echo $date;
                        echo '</span>';
                    }
                    ?>
                </div>
                <div id="div_section">
                    <div>
                        <h2 class="txt_export">Exporting View User Tracker</h2>
                    </div>
                    <div class="export_butons" align="left">
                        <a href="#">Copy</a>
                        <a href="#">Excel</a>
                        <a href="#">CSV</a>
                        <a href="#">PDF</a>
                        <a href="#">Print</a>
                    </div>
                    <div align="right">
                        <form action="index.php?page=tracker" method="post">
                            <input type="date" name="date" required class="input">
                            <button type="submit" name="search" class="export_btn">
                                <i class="fa fa-search"></i></button>

                        </form>
                    </div>
                </div>
                <div id="div_section">
                    <!--------------------body_main----------------------------->
                    <!---------------------pagination------------------------------>
                    <?php
                    include("includes/connect.php");
                    $max = 10;
                    if (!empty($date)) {
                        $url = 'index.php?page=tracker&date=' . $date;
                        $statement = 'WHERE tracker_date=:date';
                        $sql = "SELECT * FROM user_tracker {$statement}";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute(['date' => $date]);
                    } else {
                        $url = 'index.php?page=tracker';
                        $statement = '';
                        $sql = "SELECT * FROM user_tracker {$statement}";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();
                    }
                    $rows = $stmt->rowCount();
                    $totalpages = ceil($rows / $max);
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
                    <!-- -------------------geting data---------------------------- -->
                    <div class="table_contaner">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Act Id</th>
                                    <th>Edit Id</th>
                                    <th>Action</th>
                                    <th>Action Date</th>
                                    <th>Action Time</th>
                                    <th>Act Ip</th>
                                    <th>Act Table</th>
                                </tr>
                            </thead>

                            <?php
                            if (!empty($date)) {
                                $sql = "SELECT * FROM user_tracker {$statement} LIMIT :limite,:max";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute(['date' => $date, 'limite' => $limite, 'max' => $max]);
                            } else {
                                $statement = '';
                                $sql = "SELECT * FROM user_tracker {$statement} LIMIT {$limite},{$max}";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                            }
                            $result = $stmt->fetchAll();
                            if (!empty($result)) {
                                foreach ($result as $row) {
                            ?>
                                    <tbody>
                                        <tr>
                                            <td data-label="Act Id">
                                                <?php echo $row['tracker_userid']; ?>
                                            </td>
                                            <td data-label="Edit Id">
                                                <?php echo $row['tracker_actid']; ?>
                                            </td>
                                            <td data-label="Action">
                                                <?php echo $row['tracker_action']; ?>
                                            </td>
                                            <td data-label="Action Date">
                                                <?php echo $row['tracker_date']; ?>
                                            </td>
                                            <td data-label="Action Time">
                                                <?php echo $row['tracker_time']; ?>
                                            </td>
                                            <td data-label="Act Ip">
                                                <?php echo $row['tracker_ip']; ?>
                                            </td>
                                            <td data-label="Act Table">
                                                <?php echo $row['tracker_actidtype']; ?>
                                            </td>

                                        </tr>
                                    </tbody>
                            <?php }
                            } else {

                                echo '<tbody><tr><td colspan="7">';
                                echo '<label class="message">There was Not Data</label>';
                                echo '</td>';
                                echo '</tr>';
                                echo '</tbody>';
                            } ?>



                        </table>

                    </div>
                    <!-- -------------------geting data---------------------------- -->
                </div>
                <div id="div_section">
                    <p class="txt_t3"><?php echo $textline1; ?></p>
                    <p class="txt_t3"><?php echo $textline2; ?></p>
                </div>
                <div id="div_section" class="pagination" align="right">
                    <?php if (!empty($result)) {
                        echo $paginationCtrls;
                    } ?>
                </div>
            </div>

            <!--------------------body_main----------------------------->

            <!--------------------body_section----------------------------->
            <div id="footer_section">
                <!--------------------footer_section----------------------------->
                <h1 class="txt_t4" align="right">
                    <?php
                    $pdo = null;
                    echo $footer_pag1;
                    ?>
                </h1>
                <!--------------------footer_section----------------------------->
            </div>
            <!---------------------contant here------------------------------>
        </div>
        <!-------------------container----------------------->
