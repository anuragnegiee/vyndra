<?php


class Qikink_Verfication_page {
    public function index (){
        ?>

        <h1 style='padding-left: 10px;'><img src='https://qikink.com/erp2/assets/images/logo.png' alt="Qikink Logo"></h1>
        <div class="qik_logindiv">
            <div class="row justify-content-center" >
            <h2 style="font-weight: 500; text-align: center; font-size: 50px"><b>Connect to your Qikink Store</b></h2>
            </div>
            <div class="row justify-content-center" >
                <div class="col-lg-4 ">
                    <form onsubmit="return false;">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control Username" placeholder="Qikink Username" name="qikink_email" id="qikink_email" required > 
                            
                            <button   class="btn btn-default Qikinput" type="submit">Connect!</button>
                            
                        </div>
                        
                    </form>
                </div><!-- /.col-lg-4 -->
            </div>
        </div>
        <div class="row justify-content-center qik_errordiv" style="height:100px; display: none">
            <div class="col-lg-4 ">
                <div class="alert alert-danger">
                     <strong>Error!  </strong><span id="qikink_error_txt"> </span>
                </div>
            </div>
        </div>
        <div class="qik_otpdiv" style="display:none">
            <h6 style="font-weight: 500; text-align: center; font-size: 25px"><b>We have sent a OTP to your registered mail id</b></h6>
            <div class="row justify-content-center " >
                <div class="col-lg-2">
                    <form onsubmit="return false;">
                        <div class="input-group mb-3">
                            <input  type="number" class="form-control qotp" placeholder="Enter OTP" name="qikink_otp" id="qikink_otp"/> 
                            <button  class="btn btn-default QikinkOtp" type="button">Submit</button>
                        </div><!-- /input-group -->
                    </form>
                </div><!-- /.col-lg-4 -->
            </div>
        </div>
        <div class="row justify-content-center qik otpsuccess" style="height:100px; display: none">
            <div class="col-lg-4 ">
                <div class="alert alert-success">
                    <strong>Thank you!</strong> Authentication Successfull
                </div>
            </div>
        </div>
        <div class="row justify-content-center qik otpfailure" style="height:100px; display: none">
            <div class="col-lg-4 ">
                <div class="alert alert-danger">
                    <strong>Invalid OTP!</strong>
                </div>
            </div>
        </div>
        <div class="container">
        <div class="row justify-content-center">
           
            <img class="img-fluid"  src="<?php echo plugin_dir_url(__FILE__) . '../assets/bg.jpg'; ?>" alt="alt"/>
          
        </div>
            </div>
        <p id='hidden_client_id'  style='display: none'></p>
        
        
    <?php }
}
$obj=new Qikink_Verfication_page();
?>