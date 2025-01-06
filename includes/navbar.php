<ul class="side-menu">
    <!-------Dashboard--------->
    <li class="nav-item">
        <a href="index.php?page=home" target="_parent">
            <span class="icon"><i class="fa fa-home"></i></span>
            <span class="title">Dashboard</span>
        </a>
    </li>
    <!--------Employee------->
    <!--------Complain------->
    <li class="nav-item">
        <a href="#">
            <span class="icon"><i class="fa fa-book"></i></span>
            <span class="title">Complain</span>
            <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </a>
        <ul class="sb-menu">
            <!------Add Complain------>
            <li><a href="index.php?page=add_com" target="_parent" <?php if(!in_array($role, $adcm)){echo 'style="pointer-events: none"';}?>>
                    <span class="icon"><i class="fa fa-file"></i></span>
                    <span class="title">Add Complain</span></a>
            </li>
            <!------Recommendation----->
            <li><a href="index.php?page=rm_recommendation" target="_parent" <?php if(!in_array($role, $recm)){echo 'style="pointer-events: none"';}?>>
                    <span class="icon"><i class="fa fa-check-double"></i></span>
                    <span class="title">Recommendation</span></a>
            </li>
        </ul>
    </li>
    <!--------IT Unit (Helpdesk Officer / System Administrater------->
    <!--------Service------->
    <li class="nav-item">
        <a href="#">
            <span class="icon"><i class="fa fa-futbol"></i></span>
            <span class="title">Service</span>
            <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </a>
        <ul class="sb-menu">
            <!------Manage Service------>

            <li><a href="index.php?page=manage_service" target="_parent" <?php if(!in_array($role, $mser)){echo 'style="pointer-events: none"';}?>>
                    <span class="icon"><i class="fa fa-cogs"></i></span>
                    <span class="title">Manage Service</span></a>
            </li>
            <!------Manage Job----->
            <li><a href="index.php?page=manage_job" target="_parent" <?php if(!in_array($role, $mjob)){echo 'style="pointer-events: none"';}?>>
                    <span class="icon"><i class="fa fa-cogs"></i></span>
                    <span class="title">Manage Job</span></a>
            </li>
        </ul>
    </li>
    <!--------IT Unit (Helpdesk Officer / System Administrater------->
    <!--------Quotation------->
    <li class="nav-item">
        <a href="#">
            <span class="icon"><i class="fa-solid fa-clipboard"></i></span>
            <span class="title">Quotation</span>
            <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </a>
        <ul class="sb-menu">
            <!------Manage Quotation------>
            <li><a href="index.php?page=manage_quotation" target="_parent" <?php if(!in_array($role, $mquo)){echo 'style="pointer-events: none"';}?>>
                    <span class="icon"><i class="fa fa-file"></i></span>
                    <span class="title">Manage Quotation</span></a>
            </li>
        </ul>
        <ul class="sb-menu">
            <!------Manage Quotation------>
            <li><a href="index.php?page=pro_decision" target="_parent" <?php if(!in_array($role, $vquo)){echo 'style="pointer-events: none"';}?>>
                    <span class="icon"><i class="fa fa-bars"></i></span>
                    <span class="title">Procument Decision</span></a>
            </li>
        </ul>

    </li>
    <!--------IT Unit (Helpdesk Officer / System Administrater------->
    <!--------Payment------->
    <li class="nav-item">
        <a href="#">
            <span class="icon"><i class="fa fa-coins"></i></span>
            <span class="title">Payment Status</span>
            <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </a>
        <ul class="sb-menu">
            <!------Manage Payment Status------>
            <li><a href="index.php?page=manage_payment" target="_parent" <?php if(!in_array($role, $mpay)){echo 'style="pointer-events: none"';}?>>
                    <span class="icon"><i class="fa fa-file"></i></span>
                    <span class="title">Manage Payment Status</span></a>
            </li>
        </ul>
    </li>
    <?php 
        if($role==3 OR $role==4){
    ?>
    <!--------Mange Staff------->
    <li class="nav-item">
        <a href="#">
            <span class="icon"><i class="fa fa-user"></i></span>
            <span class="title">Staff</span>
            <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </a>
        <ul class="sb-menu">
            <!--------------Mange Staff------------>
            <li><a href="index.php?page=manage_staff" target="_parent" >
                    <span class="icon"><i class="fa fa-cogs"></i></span>
                    <span class="title">Mange Staff</span></a>
            </li>
            <!-----------Mange Passwords User Name----->
            <li><a href="index.php?page=manage_pass" target="_parent">
                    <span class="icon"><i class="fa fa-cogs"></i></span>
                    <span class="title">Mange Passwords &<br> User Name</span></a>
            </li>
        </ul>
    </li>
    <!--------User Activity------->
    <li class="nav-item">
        <a href="#">
            <span class="icon"><i class="fa fa-address-book"></i></span>
            <span class="title">User Activity</span>
            <span class="arrow"><i class="fa fa-angle-right"></i></span>
        </a>
        <ul class="sb-menu">
            <!------System Login------>
            <li><a href="index.php?page=login_infor" target="_parent">
                    <span class="icon"><i class="fa fa-cogs"></i></span>
                    <span class="title">System Login</span></a>
            </li>
            <!------User Activity----->
            <li><a href="index.php?page=tracker" target="_parent">
                    <span class="icon"><i class="fa fa-cogs"></i></span>
                    <span class="title">User Activity</span></a>
            </li>
        </ul>
    </li>
    <!--------Setting------->
    <li class="nav-item">
        <a href="#">
            <span class="icon"><i class="fa fa-cog"></i></span>
            <span class="title">Setting</span>
        </a>
    </li>
    <?php 
    }
    ?>
</ul>