<?php
function mm_smtp($to, $subject, $message)
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://smtp1.mailmantra.com/api/mail/v1',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 20,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
      'api_key' => MM_MAIL_AUTH_KEY,
      'to' => $to,
      'subject' => $subject,
      'message' => $message,
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return $response;
}

function mm_smtp_balance()
{
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://smtp1.mailmantra.com/api/balance/v1',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 20,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
      'api_key' => MM_MAIL_AUTH_KEY,
    ),
  ));

  $response = curl_exec($curl);

  curl_close($curl);
  return json_decode($response, true);
}

function get_joining_mail_body_BLOCK($data)
{
  return '<!doctype html>
            <html>
              <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>My Unique Trade</title>
                <style>
                  /* -------------------------------------
                      GLOBAL RESETS
                  ------------------------------------- */
                  
                  /*All the styling goes here*/
                  
                  img {
                    border: none;
                    -ms-interpolation-mode: bicubic;
                    max-width: 100%; 
                  }

                  body {
                    background-color: #f6f6f6;
                    font-family: sans-serif;
                    -webkit-font-smoothing: antialiased;
                    font-size: 14px;
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                    -ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%; 
                  }

                  table {
                    border-collapse: separate;
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    width: 100%; }
                    table td {
                      font-family: sans-serif;
                      font-size: 14px;
                      vertical-align: top; 
                  }

                  /* -------------------------------------
                      BODY & CONTAINER
                  ------------------------------------- */

                  .body {
                    background-color: #f6f6f6;
                    width: 100%; 
                  }

                  /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                  .container {
                    display: block;
                    margin: 0 auto !important;
                    /* makes it centered */
                    max-width: 580px;
                    padding: 10px;
                    width: 580px; 
                  }

                  /* This should also be a block element, so that it will fill 100% of the .container */
                  .content {
                    box-sizing: border-box;
                    display: block;
                    margin: 0 auto;
                    max-width: 580px;
                    padding: 10px; 
                  }

                  /* -------------------------------------
                      HEADER, FOOTER, MAIN
                  ------------------------------------- */
                  .header {
                      background-color: #2e3262;
                  }
                  .img{
                      padding: 20px;
                  }
                  .main {
                    background: #ffffff;
                    border-radius: 3px;
                    width: 100%; 
                  }

                  .wrapper {
                    box-sizing: border-box;
                    padding: 20px; 
                  }

                  .content-block {
                    padding-bottom: 10px;
                    padding-top: 10px;
                  }

                  .footer {
                    clear: both;
                    margin-top: 10px;
                    text-align: center;
                    width: 100%; 
                  }
                    .footer td,
                    .footer p,
                    .footer span,
                    .footer a {
                      color: #999999;
                      font-size: 12px;
                      text-align: center; 
                  }

                  /* -------------------------------------
                      TYPOGRAPHY
                  ------------------------------------- */
                  h1,
                  h2,
                  h3,
                  h4 {
                    color: #000000;
                    font-family: sans-serif;
                    font-weight: 400;
                    line-height: 1.4;
                    margin: 0;
                    margin-bottom: 30px; 
                  }

                  h1 {
                    font-size: 35px;
                    font-weight: 300;
                    text-align: center;
                    text-transform: capitalize; 
                  }

                  p,
                  ul,
                  ol {
                    font-family: sans-serif;
                    font-size: 14px;
                    font-weight: normal;
                    margin: 0;
                    margin-bottom: 15px; 
                  }
                    p li,
                    ul li,
                    ol li {
                      list-style-position: inside;
                      margin-left: 5px; 
                  }

                  a {
                    color: #3498db;
                    text-decoration: underline; 
                  }

                  /* -------------------------------------
                      BUTTONS
                  ------------------------------------- */
                  .btn {
                    box-sizing: border-box;
                    width: 100%; }
                    .btn > tbody > tr > td {
                      padding-bottom: 15px; }
                    .btn table {
                      width: auto; 
                  }
                    .btn table td {
                      background-color: #ffffff;
                      border-radius: 5px;
                      text-align: center; 
                  }
                    .btn a {
                      background-color: #ffffff;
                      border: solid 1px #3498db;
                      border-radius: 5px;
                      box-sizing: border-box;
                      color: #3498db;
                      cursor: pointer;
                      display: inline-block;
                      font-size: 14px;
                      font-weight: bold;
                      margin: 0;
                      padding: 12px 25px;
                      text-decoration: none;
                      text-transform: capitalize; 
                  }

                  .btn-primary table td {
                    background-color: #2e3262; 
                  }

                  .btn-primary a {
                    background-color: #2e3262;
                    border-color: #2e3262;
                    color: #ffffff; 
                  }

                  /* -------------------------------------
                      OTHER STYLES THAT MIGHT BE USEFUL
                  ------------------------------------- */
                  .last {
                    margin-bottom: 0; 
                  }

                  .first {
                    margin-top: 0; 
                  }

                  .align-center {
                    text-align: center; 
                  }

                  .align-right {
                    text-align: right; 
                  }

                  .align-left {
                    text-align: left; 
                  }

                  .clear {
                    clear: both; 
                  }

                  .mt0 {
                    margin-top: 0; 
                  }

                  .mb0 {
                    margin-bottom: 0; 
                  }

                  .preheader {
                    color: transparent;
                    display: none;
                    height: 0;
                    max-height: 0;
                    max-width: 0;
                    opacity: 0;
                    overflow: hidden;
                    mso-hide: all;
                    visibility: hidden;
                    width: 0; 
                  }

                  .powered-by a {
                    text-decoration: none; 
                  }

                  hr {
                    border: 0;
                    border-bottom: 1px solid #f6f6f6;
                    margin: 20px 0; 
                  }

                  /* -------------------------------------
                      RESPONSIVE AND MOBILE FRIENDLY STYLES
                  ------------------------------------- */
                  @media only screen and (max-width: 620px) {
                    table.body h1 {
                      font-size: 28px !important;
                      margin-bottom: 10px !important; 
                    }
                    table.body p,
                    table.body ul,
                    table.body ol,
                    table.body td,
                    table.body span,
                    table.body a {
                      font-size: 16px !important; 
                    }
                    table.body .wrapper,
                    table.body .article {
                      padding: 10px !important; 
                    }
                    table.body .content {
                      padding: 0 !important; 
                    }
                    table.body .container {
                      padding: 0 !important;
                      width: 100% !important; 
                    }
                    table.body .main {
                      border-left-width: 0 !important;
                      border-radius: 0 !important;
                      border-right-width: 0 !important; 
                    }
                    table.body .btn table {
                      width: 100% !important; 
                    }
                    table.body .btn a {
                      width: 100% !important; 
                    }
                    table.body .img-responsive {
                      height: auto !important;
                      max-width: 100% !important;
                      width: auto !important; 
                    }
                  }

                  /* -------------------------------------
                      PRESERVE THESE STYLES IN THE HEAD
                  ------------------------------------- */
                  @media all {
                    .ExternalClass {
                      width: 100%; 
                    }
                    .ExternalClass,
                    .ExternalClass p,
                    .ExternalClass span,
                    .ExternalClass font,
                    .ExternalClass td,
                    .ExternalClass div {
                      line-height: 100%; 
                    }
                    .apple-link a {
                      color: inherit !important;
                      font-family: inherit !important;
                      font-size: inherit !important;
                      font-weight: inherit !important;
                      line-height: inherit !important;
                      text-decoration: none !important; 
                    }
                    #MessageViewBody a {
                      color: inherit;
                      text-decoration: none;
                      font-size: inherit;
                      font-family: inherit;
                      font-weight: inherit;
                      line-height: inherit;
                    }
                    .btn-primary table td:hover {
                      background-color: #34495e !important; 
                    }
                    .btn-primary a:hover {
                      background-color: #34495e !important;
                      border-color: #34495e !important; 
                    } 
                  }
                </style>
              </head>
              <body>
                <span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
                  <tr>
                    <td>&nbsp;</td>
                    <td class="container">
                      <div class="content">
                        
                        <!-- START CENTERED WHITE CONTAINER -->
                        <table role="presentation" class="main">
                            
                          <!-- START MAIN CONTENT AREA -->
                          <tr class="header">
                              <td class="img">
                                  <img src="https://myuniquetrade.com/trade-software/web-assets/images/logo.png" style="width: 150px; display: block; margin: 0 auto;">
                              </td>
                          </tr>
                          <tr>
                            <td class="wrapper">
                              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td>
                                    <p>Dear ' . $data['member_name'] . ',</p>
                                    <p>Welcome to My Unique Trade</p>
                                    <p>We are delighted to have you among us. On behalf of all the members and the management, we would like to extend our warmest welcome and good wishes! We believe that what a strong group of people can accomplish together is much larger, far greater, and will exceed what an individual can achieve alone. You can login to your account and manage your account accordingly.</p>
                                    <h3>Login details are as follows:</h3>
                                    <p><strong>Account ID:</strong> ' . $data['member_code'] . '</p>
                                    <p><strong>Login Password:</strong> ' . $data['password'] . '</p>
                                    <br/>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                      <tbody>
                                        <tr>
                                          <td align="left">
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                              <tbody>
                                                <tr>
                                                  <td> <a href="' . PROJECT_URL . 'trade-software/member" target="_blank">Login to Your Account</a> </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <p>Please write down your Account ID and password at the safe place and do not share it to anyone. Once again Congratulation, we wish you a very bright and successful future with My Unique Trade. For any type of help and support, contact us at info@myuniquetrade.com</p>
                                    <p>
                                        <strong>Best Regards</strong><br/>
                                        My Unique Trade<br/>
                                        www.myuniquetrade.com
                                    </p>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>

                        <!-- END MAIN CONTENT AREA -->
                        </table>
                        <!-- END CENTERED WHITE CONTAINER -->

                        <!-- START FOOTER -->
                        <div class="footer">
                          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td class="content-block">
                                <span class="apple-link">&copy; romanotoken.com</span>
                                <br> Do not like these emails? <a href="#">Unsubscribe</a>.
                              </td>
                            </tr>
                          </table>
                        </div>
                        <!-- END FOOTER -->

                      </div>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
              </body>
            </html>';
}

function get_joining_mail_body($data)
{
  return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>My Unique Trade</title>
            </head>
            <body>
            <div style="width:500px; height:430px; margin:0 auto; padding:20px 5px; background:#FFF">
                <div style="padding:0px 25px 15px 25px;">
                    <img src="https://myuniquetrade.com/trade-software/web-assets/images/logo.png" style="width: 100px; display: block; margin: 0 auto;">
                </div>
                <div style="width:400px; height:300px; margin:0 auto; padding:25px; background:#EE9957; color:#FFF; text-align: center">
                    <h5 style="font-family: Arial, Helvetica, sans-serif; font-size:30px; font-weight:bold; line-height:20px;">Welcome to My Unique Trade</h5>
                    
                    <p style="font-family: Arial, Helvetica, sans-serif; font-size:22px; color:#FFF;  line-height:20px;">User ID : ' . $data['member_name'] . ' </p>
                    <p style="font-family: Arial, Helvetica, sans-serif; font-size:22px; color:#FFF;  line-height:20px; margin-bottom:20px;">Password : ' . $data['password'] . ' </p>
                    <p style="font-family: Arial, Helvetica, sans-serif; font-size:14px; color:#FFF;  line-height:20px; padding-top:60px;">Please do not share with anyone.</p>
                </div>
                <p  style="font-family: Arial, Helvetica, sans-serif; font-size:14px; text-align:center; color:#FFF;  line-height:28px;">© 2022 www.myuniquetrade.com. All Rights Reserved </p>
            </div>
            </body>
            </html>';
}

function get_topup_mail_body($data)
{
  return '<!doctype html>
            <html>
              <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>My Unique Trade</title>
                <style>
                  /* -------------------------------------
                      GLOBAL RESETS
                  ------------------------------------- */
                  
                  /*All the styling goes here*/
                  
                  img {
                    border: none;
                    -ms-interpolation-mode: bicubic;
                    max-width: 100%; 
                  }

                  body {
                    background-color: #f6f6f6;
                    font-family: sans-serif;
                    -webkit-font-smoothing: antialiased;
                    font-size: 14px;
                    line-height: 1.4;
                    margin: 0;
                    padding: 0;
                    -ms-text-size-adjust: 100%;
                    -webkit-text-size-adjust: 100%; 
                  }

                  table {
                    border-collapse: separate;
                    mso-table-lspace: 0pt;
                    mso-table-rspace: 0pt;
                    width: 100%; }
                    table td {
                      font-family: sans-serif;
                      font-size: 14px;
                      vertical-align: top; 
                  }

                  /* -------------------------------------
                      BODY & CONTAINER
                  ------------------------------------- */

                  .body {
                    background-color: #f6f6f6;
                    width: 100%; 
                  }

                  /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
                  .container {
                    display: block;
                    margin: 0 auto !important;
                    /* makes it centered */
                    max-width: 580px;
                    padding: 10px;
                    width: 580px; 
                  }

                  /* This should also be a block element, so that it will fill 100% of the .container */
                  .content {
                    box-sizing: border-box;
                    display: block;
                    margin: 0 auto;
                    max-width: 580px;
                    padding: 10px; 
                  }

                  /* -------------------------------------
                      HEADER, FOOTER, MAIN
                  ------------------------------------- */
                  .header {
                      background-color: #2e3262;
                  }
                  .img{
                      padding: 20px;
                  }
                  .main {
                    background: #ffffff;
                    border-radius: 3px;
                    width: 100%; 
                  }

                  .wrapper {
                    box-sizing: border-box;
                    padding: 20px; 
                  }

                  .content-block {
                    padding-bottom: 10px;
                    padding-top: 10px;
                  }

                  .footer {
                    clear: both;
                    margin-top: 10px;
                    text-align: center;
                    width: 100%; 
                  }
                    .footer td,
                    .footer p,
                    .footer span,
                    .footer a {
                      color: #999999;
                      font-size: 12px;
                      text-align: center; 
                  }

                  /* -------------------------------------
                      TYPOGRAPHY
                  ------------------------------------- */
                  h1,
                  h2,
                  h3,
                  h4 {
                    color: #000000;
                    font-family: sans-serif;
                    font-weight: 400;
                    line-height: 1.4;
                    margin: 0;
                    margin-bottom: 30px; 
                  }

                  h1 {
                    font-size: 35px;
                    font-weight: 300;
                    text-align: center;
                    text-transform: capitalize; 
                  }

                  p,
                  ul,
                  ol {
                    font-family: sans-serif;
                    font-size: 14px;
                    font-weight: normal;
                    margin: 0;
                    margin-bottom: 15px; 
                  }
                    p li,
                    ul li,
                    ol li {
                      list-style-position: inside;
                      margin-left: 5px; 
                  }

                  a {
                    color: #3498db;
                    text-decoration: underline; 
                  }

                  /* -------------------------------------
                      BUTTONS
                  ------------------------------------- */
                  .btn {
                    box-sizing: border-box;
                    width: 100%; }
                    .btn > tbody > tr > td {
                      padding-bottom: 15px; }
                    .btn table {
                      width: auto; 
                  }
                    .btn table td {
                      background-color: #ffffff;
                      border-radius: 5px;
                      text-align: center; 
                  }
                    .btn a {
                      background-color: #ffffff;
                      border: solid 1px #3498db;
                      border-radius: 5px;
                      box-sizing: border-box;
                      color: #3498db;
                      cursor: pointer;
                      display: inline-block;
                      font-size: 14px;
                      font-weight: bold;
                      margin: 0;
                      padding: 12px 25px;
                      text-decoration: none;
                      text-transform: capitalize; 
                  }

                  .btn-primary table td {
                    background-color: #2e3262; 
                  }

                  .btn-primary a {
                    background-color: #2e3262;
                    border-color: #2e3262;
                    color: #ffffff; 
                  }

                  /* -------------------------------------
                      OTHER STYLES THAT MIGHT BE USEFUL
                  ------------------------------------- */
                  .last {
                    margin-bottom: 0; 
                  }

                  .first {
                    margin-top: 0; 
                  }

                  .align-center {
                    text-align: center; 
                  }

                  .align-right {
                    text-align: right; 
                  }

                  .align-left {
                    text-align: left; 
                  }

                  .clear {
                    clear: both; 
                  }

                  .mt0 {
                    margin-top: 0; 
                  }

                  .mb0 {
                    margin-bottom: 0; 
                  }

                  .preheader {
                    color: transparent;
                    display: none;
                    height: 0;
                    max-height: 0;
                    max-width: 0;
                    opacity: 0;
                    overflow: hidden;
                    mso-hide: all;
                    visibility: hidden;
                    width: 0; 
                  }

                  .powered-by a {
                    text-decoration: none; 
                  }

                  hr {
                    border: 0;
                    border-bottom: 1px solid #f6f6f6;
                    margin: 20px 0; 
                  }

                  /* -------------------------------------
                      RESPONSIVE AND MOBILE FRIENDLY STYLES
                  ------------------------------------- */
                  @media only screen and (max-width: 620px) {
                    table.body h1 {
                      font-size: 28px !important;
                      margin-bottom: 10px !important; 
                    }
                    table.body p,
                    table.body ul,
                    table.body ol,
                    table.body td,
                    table.body span,
                    table.body a {
                      font-size: 16px !important; 
                    }
                    table.body .wrapper,
                    table.body .article {
                      padding: 10px !important; 
                    }
                    table.body .content {
                      padding: 0 !important; 
                    }
                    table.body .container {
                      padding: 0 !important;
                      width: 100% !important; 
                    }
                    table.body .main {
                      border-left-width: 0 !important;
                      border-radius: 0 !important;
                      border-right-width: 0 !important; 
                    }
                    table.body .btn table {
                      width: 100% !important; 
                    }
                    table.body .btn a {
                      width: 100% !important; 
                    }
                    table.body .img-responsive {
                      height: auto !important;
                      max-width: 100% !important;
                      width: auto !important; 
                    }
                  }

                  /* -------------------------------------
                      PRESERVE THESE STYLES IN THE HEAD
                  ------------------------------------- */
                  @media all {
                    .ExternalClass {
                      width: 100%; 
                    }
                    .ExternalClass,
                    .ExternalClass p,
                    .ExternalClass span,
                    .ExternalClass font,
                    .ExternalClass td,
                    .ExternalClass div {
                      line-height: 100%; 
                    }
                    .apple-link a {
                      color: inherit !important;
                      font-family: inherit !important;
                      font-size: inherit !important;
                      font-weight: inherit !important;
                      line-height: inherit !important;
                      text-decoration: none !important; 
                    }
                    #MessageViewBody a {
                      color: inherit;
                      text-decoration: none;
                      font-size: inherit;
                      font-family: inherit;
                      font-weight: inherit;
                      line-height: inherit;
                    }
                    .btn-primary table td:hover {
                      background-color: #34495e !important; 
                    }
                    .btn-primary a:hover {
                      background-color: #34495e !important;
                      border-color: #34495e !important; 
                    } 
                  }
                </style>
              </head>
              <body>
                <span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
                  <tr>
                    <td>&nbsp;</td>
                    <td class="container">
                      <div class="content">
                        
                        <!-- START CENTERED WHITE CONTAINER -->
                        <table role="presentation" class="main">
                            
                          <!-- START MAIN CONTENT AREA -->
                          <tr class="header">
                              <td class="img">
                                  <img src="https://myuniquetrade.com/trade-software/web-assets/images/logo.png" style="width: 150px; display: block; margin: 0 auto;">
                              </td>
                          </tr>
                          <tr>
                            <td class="wrapper">
                              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                  <td>
                                    <p>Dear ' . $data['member_name'] . ',</p>
                                    <p>Welcome to My Unique Trade</p>
                                    <p>We are delighted to have you among us. On behalf of all the members and the management, we would like to extend ourwarmest welcome and good wishes to our Trading Training Institution. We believe that what a strong group of people can accomplish together is much larger, far greater, and will exceed what an individual can achieve alone. You can login to your account and manage your account accordingly.</p>
                                    <h3>Package details are as follows:</h3>
                                    <p><strong>Package Name:</strong> ' . $data['package_name'] . '</p>
                                    <p><strong>Package Amount:</strong> ' . $data['package_amount'] . '</p>
                                    <br/>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                                      <tbody>
                                        <tr>
                                          <td align="left">
                                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                              <tbody>
                                                <tr>
                                                  <td> <a href="' . PROJECT_URL . 'trade-software/member" target="_blank">Login to Your Account</a> </td>
                                                </tr>
                                              </tbody>
                                            </table>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                    <p>Please write down your Account ID and password at the safe place and do not share it to anyone. Once again Congratulation, we wish you a very bright and successful future with My Unique Trade. For any type of help and support, contact us at info@myuniquetrade.com</p>
                                    <p>
                                        <strong>Best Regards</strong><br/>
                                        My Unique Trade<br/>
                                        www.myuniquetrade.com
                                    </p>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>

                        <!-- END MAIN CONTENT AREA -->
                        </table>
                        <!-- END CENTERED WHITE CONTAINER -->

                        <!-- START FOOTER -->
                        <div class="footer">
                          <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td class="content-block">
                                <span class="apple-link">&copy; romanotoken.com</span>
                                <br> Do not like these emails? <a href="#">Unsubscribe</a>.
                              </td>
                            </tr>
                          </table>
                        </div>
                        <!-- END FOOTER -->

                      </div>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
                </table>
              </body>
            </html>';
}
