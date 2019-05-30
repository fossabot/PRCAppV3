<!DOCTYPE html>
<html>


     

<head>

     <style> 
     @charset "utf-8";
/* CSS Document */

/* ---------- GENERAL ---------- */

body {
	background: #eaeaea;
	color: #999;
	font: 100%/1.5em sans-serif;
	margin: 0;
}

h3 { margin: 0; }

a {
	color: #999;
	text-decoration: none;
}

a:hover { color: #1dabb8; }

fieldset {
	border: none;
	margin: 0;
}

input {
	border: none;
	font-family: inherit;
	font-size: inherit;
	margin: 0;
	-webkit-appearance: none;
}

input:focus {
  outline: none;
}

input[type="submit"] { cursor: pointer; }

.clearfix { *zoom: 1; }
.clearfix:before, .clearfix:after {
	content: "";
	display: table;	
}
.clearfix:after { clear: both; }

/* ---------- LOGIN-FORM ---------- */

#login-form {
	margin: 50px auto;
	width: 300px;
}

#login-form h3 {
	background-color: #282830;
	border-radius: 5px 5px 0 0;
	color: #fff;
	font-size: 14px;
	padding: 20px;
	text-align: center;
	text-transform: uppercase;
}

#login-form fieldset {
	background: #fff;
	border-radius: 0 0 5px 5px;
	padding: 20px;
	position: relative;
}

#login-form fieldset:before {
	background-color: #fff;
	content: "";
	height: 8px;
	left: 50%;
	margin: -4px 0 0 -4px;
	position: absolute;
	top: 0;
	-webkit-transform: rotate(45deg);
	-moz-transform: rotate(45deg);
	-ms-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg);
	width: 8px;
}

#login-form input {
	font-size: 14px;
}

#login-form input[type="text"],
#login-form input[type="password"] {
	border: 1px solid #dcdcdc;
	padding: 12px 10px;
	width: 238px;
}

#login-form input[type="text"] {
	border-radius: 3px 3px 0 0;
}

#login-form input[type="password"] {
	border-top: none;
	border-radius: 0px 0px 3px 3px;
}

#login-form input[type="submit"] {
	background: #1dabb8;
	border-radius: 3px;
	color: #fff;
	float: right;
	font-weight: bold;
	margin-top: 20px;
	padding: 12px 20px;
}

#login-form input[type="submit"]:hover { background: #198d98; }

#login-form footer {
	font-size: 12px;
	margin-top: 16px;
}

.info {
	background: #e5e5e5;
	border-radius: 50%;
	display: inline-block;
	height: 20px;
	line-height: 20px;
	margin: 0 10px 0 0;
	text-align: center;
	width: 20px;
}
     </style>


	<meta charset="utf-8">

	<title>MILWAUKEE</title>

	
</head>

<body>
   <?php 
   $ds_human_resource = $this->session->userdata('ds_human_resource');
   if (!isset($ds_human_resource)) {
       $ds_human_resource = '';
   }
   
   ?>
 
    <div id="login-form">

        <h3>MILWAUKEE</h3>

        <fieldset>

   <?php echo form_open('verifylogin');?> 

                <input type="text" autocomplete="OFF"  required value="<?PHP echo $ds_human_resource?>" onBlur="if(this.value=='')this.value='<?PHP echo $ds_human_resource?>'" onFocus="if(this.value=='Email')this.value='' " id="username" name="username"> 
                
                <input type="password" autocomplete="OFF" required value="Password" onBlur="if(this.value=='')this.value='Password'" onFocus="if(this.value=='Password')this.value='' " id="password" name="password" > 

                <input type="submit" class="button" value="Login">

                <footer class="clearfix">

                    <p>    <?php echo validation_errors(); ?> </p>

                </footer>

            </form>

        </fieldset>

    </div> <!-- end login-form -->

</body>
</html>

</body>

</html>