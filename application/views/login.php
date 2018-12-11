<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<html>
   <head>
      <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
      <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
      <!------ Include the above in your HEAD tag ---------->
   </head>
   <body id="LoginForm">
      <div class="container">
         <div class="login-form">
            <div class="main-div">
               <div class="panel">
                  <h2>Chat Login</h2>
                  <p>Please enter your email and password</p>
               </div>
               <form id="login_form" method="post" action="<?=base_url('chatter/login_access');?>">
                  <?php if(!empty($message)){ ?>
                  <div class="alert alert-primary" role="alert">
                    <?php echo $message; ?>
                  </div>
                  <?php } ?>
                  <div class="form-group">
                     <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email Address" required="">
                  </div>
                  <div class="form-group">
                     <input type="password" name="password" class="form-control" id="inputPassword" placeholder="Password" required="">
                  </div>
                  <div class="forgot"></div>
                  <button type="submit" class="btn btn-primary">Login</button>
                  <a class="btn btn-default" href="<?=base_url('chatter/signup');?>"> Sign up </a>
               </form>
            </div>
            <p class="botto-text">Designed by Subash Diego</p>
         </div>
      </div>
      </div>
   </body>
   <style type="text/css">
      body#LoginForm{ background-image:url("https://hdwallsource.com/img/2014/9/blur-26347-27038-hd-wallpapers.jpg"); background-repeat:no-repeat; background-position:center; background-size:cover; padding:10px;}
      .form-heading { color:#fff; font-size:23px;}
      .panel h2{ color:#444444; font-size:18px; margin:0 0 8px 0;}
      .panel p { color:#777777; font-size:14px; margin-bottom:30px; line-height:24px;}
      .login-form .form-control {
      background: #f7f7f7 none repeat scroll 0 0;
      border: 1px solid #d4d4d4;
      border-radius: 4px;
      font-size: 14px;
      height: 50px;
      line-height: 50px;
      }
      .main-div {
      background: #ffffff none repeat scroll 0 0;
      border-radius: 2px;
      margin: 10px auto 30px;
      max-width: 38%;
      padding: 50px 70px 70px 71px;
      }
      .login-form .form-group {
      margin-bottom:10px;
      }
      .login-form{ text-align:center;}
      .forgot a {
      color: #777777;
      font-size: 14px;
      text-decoration: underline;
      }
      .login-form  .btn.btn-primary {
      background: #f0ad4e none repeat scroll 0 0;
      border-color: #f0ad4e;
      color: #ffffff;
      font-size: 14px;
      width: 100%;
      height: 50px;
      line-height: 50px;
      padding: 0;
      }
      .forgot {
      text-align: left; margin-bottom:30px;
      }
      .botto-text {
      color: #ffffff;
      font-size: 14px;
      margin: auto;
      }
      .login-form .btn.btn-primary.reset {
      background: #ff9900 none repeat scroll 0 0;
      }
      .back { text-align: left; margin-top:10px;}
      .back a {color: #444444; font-size: 13px;text-decoration: none;}
   </style>
</html>