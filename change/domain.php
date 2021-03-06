<?php

session_start();

    if (file_exists( '../includes/config.php' )) { require( '../includes/config.php'); }  else { header( 'Location: ../install' );};
    if(base64_decode($_SESSION['loggedin']) == 'true') {}
    else { header('Location: ../login.php'); }

    // Remove www. from domain and lowercase
    $v_domain = $_POST['v_domain'];

    // Define proxy extensions
    $v_proxy_ext = $_POST['v_prxext'];
    if ($v_proxy_ext == ''){ $v_proxy_ext = 'jpeg, jpg, png, gif, bmp, ico, svg, tif, tiff, css, js, htm, html, ttf, otf, webp, woff, txt, csv, rtf, doc, docx, xls, xlsx, ppt, pptx, odf, odp, ods, odt, pdf, psd, ai, eot, eps, ps, zip, tar, tgz, gz, rar, bz2, 7z, aac, m4a, mp3, mp4, ogg, wav, wma, 3gp, avi, flv, m4v, mkv, mov, mp4, mpeg, mpg, wmv, exe, iso, dmg, swf';}
    $proxy_ext = preg_replace("/\n/", ",", $v_proxy_ext);
    $proxy_ext = preg_replace("/\r/", ",", $proxy_ext);
    $proxy_ext = preg_replace("/\t/", ",", $proxy_ext);
    $proxy_ext = preg_replace("/ /", ",", $proxy_ext);
    $proxy_ext_arr = explode(",", $proxy_ext);
    $proxy_ext_arr = array_unique($proxy_ext_arr);
    $proxy_ext_arr = array_filter($proxy_ext_arr);
    $prxext = implode(",",$proxy_ext_arr);

    // Check Proxy option
    if (!empty($_POST['v_prxenabled'])) {
        $v_prxx = 'yes';
    } else {
        $v_prxx = 'no';
    }
    if (!empty($_POST['v_prxenabled-x'])) {
        $v_prxx_x = 'yes';
    } else {
        $v_prxx_x = 'no';
    }
    // Check SSL option
    if (!empty($_POST['v_sslenabled'])) {
        $v_sslx = 'yes';
    } else {
        $v_sslx = 'no';
    }
    // Check Let's Encrypt option
    if (!empty($_POST['v_leenabled'])) {
        $v_lex = 'yes';
    } else {
        $v_lex = 'no';
    }
    // Check Stats Auth option
    if (!empty($_POST['v_statsuserenabled'])) {
        $v_statsuserenabled = 'yes';
    } else {
        $v_statsuserenabled = 'no';
    }
    // Check FTP option
    if (!empty($_POST['v_ftpenabled'])) {
        $v_ftpx = 'yes';
    } else {
        $v_ftpx = 'no';
    }

    if ((!isset($v_domain)) || ($v_domain == '')) { header('Location: ../list/web.php?error=1');}
    else {
        if ($_POST['v_ip-x'] != $_POST['v_ip']){
            $postvars0 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-change-web-domain-ip','arg1' => $username,'arg2' => $v_domain, 'arg3' => $_POST['v_ip']);

            $curl0 = curl_init();
            curl_setopt($curl0, CURLOPT_URL, $vst_url);
            curl_setopt($curl0, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl0, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl0, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl0, CURLOPT_POST, true);
            curl_setopt($curl0, CURLOPT_POSTFIELDS, http_build_query($postvars0));
            $r0 = curl_exec($curl0);
        } else { $r0 = '0'; }
        // Change account aliases
        $valiases = explode(",", $_POST['v_alias-x']);
        $waliases = preg_replace("/\n/", " ", $_POST['v_alias']);
        $waliases = preg_replace("/,/", " ", $waliases);
        $waliases = preg_replace('/\s+/', ' ',$waliases);
        $waliases = trim($waliases);
        $aliases = explode(" ", $waliases);
        $v_aliases = str_replace(' ', "\n", $waliases);
        $result = array_diff($valiases, $aliases);
        $r1 = '0';
        foreach ($result as $alias) {
            if (!empty($alias)) {
                $postvars1 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-delete-web-domain-alias','arg1' => $username,'arg2' => $v_domain, 'arg3' => $alias, 'arg4' => 'no');

                $curl1 = curl_init();
                curl_setopt($curl1, CURLOPT_URL, $vst_url);
                curl_setopt($curl1, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl1, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl1, CURLOPT_POST, true);
                curl_setopt($curl1, CURLOPT_POSTFIELDS, http_build_query($postvars1));
                $r1 = curl_exec($curl1);
            }
        }
        $result = array_diff($aliases, $valiases);
        foreach ($result as $alias) {
            if (!empty($alias)) {
                $postvars1 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-add-web-domain-alias','arg1' => $username,'arg2' => $v_domain, 'arg3' => $alias, 'arg4' => 'no');

                $curl1 = curl_init();
                curl_setopt($curl1, CURLOPT_URL, $vst_url);
                curl_setopt($curl1, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl1, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl1, CURLOPT_POST, true);
                curl_setopt($curl1, CURLOPT_POSTFIELDS, http_build_query($postvars1));
                $r1 = curl_exec($curl1);
            }
        }
        if ($_POST['v_tpl-x'] != $_POST['v_tpl']){
            $postvars2 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-change-web-domain-tpl','arg1' => $username,'arg2' => $v_domain, 'arg3' => $_POST['v_tpl']);

            $curl2 = curl_init();
            curl_setopt($curl2, CURLOPT_URL, $vst_url);
            curl_setopt($curl2, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl2, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl2, CURLOPT_POST, true);
            curl_setopt($curl2, CURLOPT_POSTFIELDS, http_build_query($postvars2));
            $r2 = curl_exec($curl2);
        }
        else { $r2 = '0'; }
        if ($v_prxx != $v_prxx_x && $v_prxx == 'yes' && $v_prxx_x == 'no'){
            $postvars3 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-add-web-domain-proxy','arg1' => $username,'arg2' => $v_domain, 'arg3' => $_POST['v_prxtpl'], 'arg4' => $prxext, 'arg5' => 'no');

            $curl3 = curl_init();
            curl_setopt($curl3, CURLOPT_URL, $vst_url);
            curl_setopt($curl3, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl3, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl3, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl3, CURLOPT_POST, true);
            curl_setopt($curl3, CURLOPT_POSTFIELDS, http_build_query($postvars3));
            $r3 = curl_exec($curl3);
        } else { $r3 = '0'; }
        if ($v_prxx != $v_prxx_x && $v_prxx == 'no' && $v_prxx_x == 'yes'){
            $postvars3 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-delete-web-domain-proxy','arg1' => $username,'arg2' => $v_domain);

            $curl3 = curl_init();
            curl_setopt($curl3, CURLOPT_URL, $vst_url);
            curl_setopt($curl3, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl3, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl3, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl3, CURLOPT_POST, true);
            curl_setopt($curl3, CURLOPT_POSTFIELDS, http_build_query($postvars3));
            $r3 = curl_exec($curl3);
            if ($r3 == '3') { $r3 = '0';}
        } else { $r3 = '0'; }
        if ($v_prxx == 'yes' && $_POST['v_prxtpl-x'] != $_POST['v_prxtpl'] || $_POST['v_prxext-x'] != $prxext ){
            $postvars4 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-change-web-domain-proxy-tpl','arg1' => $username,'arg2' => $v_domain, 'arg3' => $_POST['v_prxtpl'], 'arg4' => $prxext, 'arg5' => 'no');

            $curl4 = curl_init();
            curl_setopt($curl4, CURLOPT_URL, $vst_url);
            curl_setopt($curl4, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl4, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl4, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl4, CURLOPT_POST, true);
            curl_setopt($curl4, CURLOPT_POSTFIELDS, http_build_query($postvars4));
            $r4 = curl_exec($curl4);
            if ($r4 == '3') { $r4 = '0';}
        } else { $r4 = '0'; }
        if (($_POST['v_webstats'] != 'none')  && ($_POST['v_webstats-x'] == 'none')){
            $postvars5 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-add-web-domain-stats','arg1' => $username,'arg2' => $v_domain, 'arg3' => $_POST['v_webstats']);

            $curl5 = curl_init();
            curl_setopt($curl5, CURLOPT_URL, $vst_url);
            curl_setopt($curl5, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl5, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl5, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl5, CURLOPT_POST, true);
            curl_setopt($curl5, CURLOPT_POSTFIELDS, http_build_query($postvars5));
            $r5 = curl_exec($curl5);
        }
        elseif (($_POST['v_webstats'] == 'none') && ($_POST['v_webstats'] != $_POST['v_webstats-x'])){
            $postvars5 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-delete-web-domain-stats','arg1' => $username,'arg2' => $v_domain);

            $curl5 = curl_init();
            curl_setopt($curl5, CURLOPT_URL, $vst_url);
            curl_setopt($curl5, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl5, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl5, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl5, CURLOPT_POST, true);
            curl_setopt($curl5, CURLOPT_POSTFIELDS, http_build_query($postvars5));
            $r5 = curl_exec($curl5);
        }
        elseif (($_POST['v_webstats'] != 'none') && ($_POST['v_webstats-x'] != 'none')){
            $postvars5 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-change-web-domain-stats','arg1' => $username,'arg2' => $v_domain, 'arg3' => $_POST['v_webstats']);

            $curl5 = curl_init();
            curl_setopt($curl5, CURLOPT_URL, $vst_url);
            curl_setopt($curl5, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl5, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl5, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl5, CURLOPT_POST, true);
            curl_setopt($curl5, CURLOPT_POSTFIELDS, http_build_query($postvars5));
            $r5 = curl_exec($curl5);
        } else { $r5 = '0'; }

        if ($_POST['v_statsuserenabled'] != '' && $_POST['v_statsuserenabled'] != $_POST['v_statsuserenabled-x'] && !empty($_POST['v_statsuname']) && !empty($_POST['v_statspassword'])) {
            $postvars6 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-add-web-domain-stats-user','arg1' => $username,'arg2' => $v_domain, 'arg3' => $_POST['v_statsuname'], 'arg4' => $_POST['v_statspassword']);

            $curl6 = curl_init();
            curl_setopt($curl6, CURLOPT_URL, $vst_url);
            curl_setopt($curl6, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl6, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl6, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl6, CURLOPT_POST, true);
            curl_setopt($curl6, CURLOPT_POSTFIELDS, http_build_query($postvars6));
            $r6 = curl_exec($curl6);
        } 
        elseif ($_POST['v_statsuserenabled'] == '' && $_POST['v_statsuserenabled'] != $_POST['v_statsuserenabled-x'] || $_POST['v_statuname'] == '' && $_POST['v_statsuname'] != $_POST['v_statsuname-x'] || $_POST['v_webstats'] == 'none' && $_POST['v_webstats'] != $_POST['v_webstats-x']) {
            $postvars6 = array('user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-delete-web-domain-stats-user','arg1' => $username,'arg2' => $v_domain, 'arg3' => $_POST['v_statsuname'], 'arg4' => $_POST['v_statspassword']);

            $curl6 = curl_init();
            curl_setopt($curl6, CURLOPT_URL, $vst_url);
            curl_setopt($curl6, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl6, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl6, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl6, CURLOPT_POST, true);
            curl_setopt($curl6, CURLOPT_POSTFIELDS, http_build_query($postvars6));
            $r6 = curl_exec($curl6);
        } else { $r6 = '0'; }
        if ($v_lex == 'yes' && $_POST['v_leenabled'] != $_POST['v_leenabled-x']) {
            $postvars7 = array('user' => $vst_username,'password' => $vst_password,'cmd' => 'v-schedule-letsencrypt-domain','arg1' => $username,'arg2' => $v_domain);

            $curl7 = curl_init();
            curl_setopt($curl7, CURLOPT_URL, $vst_url);
            curl_setopt($curl7, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl7, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl7, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl7, CURLOPT_POST, true);
            curl_setopt($curl7, CURLOPT_POSTFIELDS, http_build_query($postvars7));
            $r7 = curl_exec($curl7);
            if ($r7 == 'OK') { $r4 = '0';}   
        } 
        elseif ($v_lex == 'no' && $_POST['v_leenabled'] != $_POST['v_leenabled-x']) {
            $postvars7 = array('user' => $vst_username,'password' => $vst_password,'cmd' => 'v-delete-letsencrypt-domain','arg1' => $username,'arg2' => $v_domain, 'arg3' => 'no');

            $curl7 = curl_init();
            curl_setopt($curl7, CURLOPT_URL, $vst_url);
            curl_setopt($curl7, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl7, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl7, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl7, CURLOPT_POST, true);
            curl_setopt($curl7, CURLOPT_POSTFIELDS, http_build_query($postvars7));
            $r7 = curl_exec($curl7);
            if ($r7 == 'OK') { $r4 = '0';} 
        } else { $r7= '0'; }
        if ($v_sslx == 'no' && $_POST['v_sslenabled'] != $_POST['v_sslenabled-x']) {
            $postvars8 = array('user' => $vst_username,'password' => $vst_password,'cmd' => 'v-delete-web-domain-ssl','arg1' => $username,'arg2' => $v_domain, 'arg3' => 'no');

            $curl8 = curl_init();
            curl_setopt($curl8, CURLOPT_URL, $vst_url);
            curl_setopt($curl8, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl8, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl8, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl8, CURLOPT_POST, true);
            curl_setopt($curl8, CURLOPT_POSTFIELDS, http_build_query($postvars8));
            $r8 = curl_exec($curl8); 
        } else { $r8= '0'; }
    }

    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body class="fix-header">
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
            </svg>
        </div>
        
<form id="form" action="../edit/domain.php?domain=<?php echo $v_domain; ?>" method="post">
<?php 
    echo '<input type="hidden" name="r1" value="'.$r0.'">';
    echo '<input type="hidden" name="r2" value="'.$r1.'">';
    echo '<input type="hidden" name="r3" value="'.$r2.'">';
    echo '<input type="hidden" name="r4" value="'.$r3.'">';
    echo '<input type="hidden" name="r5" value="'.$r4.'">';
    echo '<input type="hidden" name="r6" value="'.$r5.'">';
    echo '<input type="hidden" name="r7" value="'.$r8.'">';
    echo '<input type="hidden" name="r8" value="'.$r7.'">';
    echo '<input type="hidden" name="r9" value="'.$r6.'">';
?>
</form>
<script type="text/javascript">
    document.getElementById('form').submit();
</script>
                    </body>
        <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
</html>