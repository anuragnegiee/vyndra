<?php
class Qikink_Signup{
    function _construct(){
        
    }
    function signup_function(){
        ?>
<style>
    #maindiv{
        text-align:center;
        padding-left: 680px;
        padding-right: 680px;
        padding-top: 30px;
    }
    #inputs{
        text-align: left;
        margin-bottom: 25px;
    }
    #input{
        width: 100%;
        height: 48px;
        padding:0 24px;
        background: #eeeef5;
        border-radius: 30px;
        cursor: text;
    }
    #body{
        background: white;
    }
    .col{
        position: relative;
        width: 100%;
        padding-right: 10px;
        padding-bottom: 10px;
    }
    .btn{
        cursor:pointer;
        font-size: .975rem;
        border-radius: 30px;
        color: #fff;
        background-color: #323e80;
        border-color: #081768;
        width: 100%;
        height: 46.98px;
    }

</style>
<body id="body">
    <h1 style='padding-left: 10px;'><img alt="company logo" src='https://qikink.com/erp2/assets/images/logo.png'></h1>"
    <h2 style="font-weight: 600; text-align: center; font-size: 50px"><b>Create Free Account</b></h2>
    <div  id="maindiv">
        <div id="inputs">
        <input id="input" name="email" type="text" required placeholder="Full Name">
        </div>
        <div id="inputs">
        <input id="input" name="email" type="text" required placeholder="Username">
        </div>
        <div id='inputs'>
        <input id="input" name="password" type="password" required placeholder="password">
        </div>
        <div class="col">
            <input type="checkbox"  required="required" id="customCheck1">
            <label  for="customCheck1">I agree to the
                <a target="_blank" href="https://qikink.com/dropship-terms/">Terms and conditions</a></label>
        </div>
        <div class="col">
            <button class="btn" type="submit">Sign In</button>
        </div>
        
    </div>
</body>
 <?php
    }
}
$obj=new Signup();
$obj->signup_function();
?>
