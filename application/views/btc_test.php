<?php defined('BASEPATH') OR exit('No direct script access allowed');
?><!-- Section -->
<div class="page-default">
    <div class="container">
    	<div class="row">
    		<div class="col-md-12 table-responsive">
          <div class="card">
              <div class="header">
                <h2>BTC Test</h2>
              </div>
              <div class="body">
                <div cass="row">
                  <h3>Generate Payment Address</h3>
                  <?php echo form_open('btc/create_payout');?>
                  <input type="text" name="payout_address" class="form-control" placeholder="payout_address"/>
                  <input type="text" name="callback" class="form-control" placeholder="callback"/>
                  <input type="text" name="confirmations" class="form-control" placeholder="confirmations"/>
                  <input type="text" name="fee_level" class="form-control" placeholder="fee_level"/>
                  <br>
                  <button class="btn" type="submit" value="submit">Submit</button>
                  <?php echo form_close();?>
                </div>
                <br>
                <hr>
                <br>
                  <h3>Generate QR Code</h3>
                  <?php echo form_open('btc/generate_qr');?>
                  <input type="text" name="value" class="form-control" placeholder="value"/>
                  <br>
                  <button class="btn" type="submit" value="submit">Submit</button>
                  <?php echo form_close();?>
                  <br>
                  <hr>
                  <br>
                    <h3>Redeem Payout Code</h3>
                    <?php echo form_open('btc/get_redeem_code');?>
                    <input type="text" name="redeem_code" class="form-control" placeholder="redeem_code"/>
                    <br>
                    <button class="btn" type="submit" value="submit">Submit</button>
                    <?php echo form_close();?>
                </div>
              </div>
    		  </div>
      	</div>
    </div>
  </div>
</div>
