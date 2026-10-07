<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Thank_you
 *
 * @author USER
 */
class Qikink_Thank_you {
    public function qikinkindex($bname) {
        ?>
        <h1 style='padding-left: 10px;'><img src='https://qikink.com/erp2/assets/images/logo.png' alt="Qikink Logo"></h1>
        <div class="qik_logindiv">
            <h2 style="font-weight: 500; text-align: center; font-size: 50px"><b>Welcome! <?php echo esc_html($bname) ?></b></h2>
            <h2 style="font-weight: 300; text-align: center; font-size: 30px"><b>Your store is linked with Qikink successfully! Now you can push your products and pull your orders.</b></h2>
        </div>
        <div class="row justify-content-center">
           <div class="col-md-offset-3 col-lg-offset-3 col-6 col-md-6 col-lg-6 col-6">
            <img class="img-fluid"  src="<?php echo plugin_dir_url(__FILE__) . '../assets/bg.jpg'; ?>" alt="alt"/>
           </div>
        </div>
        
        

  <?php   }
}
$obj=new Qikink_Thank_you();
?>