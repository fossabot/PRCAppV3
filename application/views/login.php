<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Management System</title>


    <style type="text/css">
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-weight: 300;
        }

        .login_container {
            max-width: 400px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            height: 100%;
        }

        body {
            background-image: url(/resources/login_bg.PNG);
            background-repeat: no-repeat;
            background-size: cover;
        }

        form input {
            font-size: 14px;
            outline: 0;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: #fff;
            width: 300px;
            border-radius: 5px;
            padding: 12px 15px;
            margin-bottom: 20px;
            display: block;
            text-align: left;
            color: #929191;
            -webkit-transition-duration: 0.25s;
            transition-duration: 0.25s;
            font-weight: 300;
        }

        form i {
            color: #cbcbcb;
            top: 13px;
            right: 6px;
            z-index: 99;
            position: absolute;
            display: block;
            width: 30px;
            height: 30px;
            text-align: center;
            font-size: 18px !important;
        }

        form button {
            outline: 0;
            background-color: white;
            border: 0;
            padding: 10px 15px;
            color: black;
            border-radius: 5px;
            width: 300px;
            cursor: pointer;
            font: 18px/1.5 Microsoft Yahei, Lucida Grande, Lucida Sans Unicode, Helvetica Neue, Hiragino Sans GB, sans-serif;
        }

        form button:hover {
            opacity: 0.9;
        }

        .login_span {
            font-size: 40px;
            color: white;

        }
    </style>
</head>
<body>
<div style="padding-top: 180px;margin:0 auto;width:800px;text-align:center;">
    <span class="login_span">Lab Management System( LMS )</span>
</div>

<?php
$ds_human_resource = $this->session->userdata('ds_human_resource');
if (!isset($ds_human_resource)) {
    $ds_human_resource = '';
}

?>

<div id="login-form" class="login_container">

<!--    <h3>MILWAUKEE</h3>-->

<!--    <fieldset>-->

        <?php echo form_open('verifylogin'); ?>
    <br>
    <br>
    <br>
        <input type="text" autocomplete="OFF" required value="<?PHP echo $ds_human_resource ?>"
               onBlur="if(this.value=='')this.value='<?PHP echo $ds_human_resource ?>'"
               onFocus="if(this.value=='Email')this.value='' " id="username" name="username">

        <input type="password" autocomplete="OFF" required value="" 
               id="password" name="password">

        <input type="submit" class="button" value="Login" >

        <footer class="clearfix">

            <p>    <?php echo validation_errors(); ?> </p>

        </footer>

        </form>

<!--    </fieldset>-->

</div> <!-- end login-form -->
</body>
</html>
