<?php
    /**
     * Created by PhpStorm.
     * User: Ironman
     * Date: 9/25/2015
     * Time: 4:12 PM
     */

?>
<script>

    jQuery(document).ready(function(){
        jQuery('#submit-payment-button').on('click',function(e){
            e.preventDefault();
            jQuery('#review').toggle();
            jQuery('#payment').toggle();
        })

        //jQuery('#checkout-button.btn').on('click',function(e){
        //    e.preventDefault();
        //    jQuery('#paymentinfo').submit();
        //});



        jQuery('#continue-shopping.btn').on('click',function(e){
            e.preventDefault();
            jQuery('#review').toggle();
            jQuery('#payment').toggle();
        });
    });
</script>



<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script>
    // We generated a client token for you so you can test out this code
    // immediately. In a production-ready integration, you will need to
    // generate a client token on your server (see section below).
    var clientToken = "<?php echo $this->model->paymentMethodToken;?>";

    braintree.setup(clientToken, "dropin", {
        container: "payment-form",
        onPaymentMethodReceived: function (obj) {
            // Do some logic in here.
            // When you're ready to submit the form:
            console.log(obj);
            jQuery('<input>', {
                type: 'hidden',
                id: 'foo',
                name: 'token',
                value: JSON.stringify(obj)
            }).appendTo(jQuery('#paymentinfo'));
            jQuery('#paymentinfo').submit();
        }
    });

</script>
<div id="sp4k_cart-container">
    <div id="payment">
        <?php if (!$this->model->items): ?>
            <h1>Your Cart Is Empty</h1>
        <?php else: ?>


            <form class="form-horizontal" id="paymentinfo" method="post">
                <?php
                    if(
                            isset($this->model->recurring)
                            &&
                            $this->model->recurring
                            &&
                            $this->model->orderTotals['cost_next'] > 0
                    ):
                        ?>
                        <fieldset>
                            <legend>Address</legend>
                            <div class="control-group">
                                <label class="control-label" for="textinput">Street Address 1</label>
                                <div class="controls">
                                    <input id="textinput" name="pl[address_street1]" type="text" placeholder="placeholder" class="input-xlarge">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="textinput">Street Address 2</label>
                                <div class="controls">
                                    <input id="textinput" name="pl[address_street2]" type="text" placeholder="placeholder" class="input-xlarge">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="textinput">City</label>
                                <div class="controls">
                                    <input id="textinput" name="pl[address_city]" type="text" placeholder="placeholder" class="input-xlarge">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="textinput">State</label>
                                <div class="controls">
                                    <input id="textinput" name="pl[address_state]" type="text" placeholder="placeholder" class="input-xlarge">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="textinput">Postal Code</label>
                                <div class="controls">
                                    <input id="textinput" name="pl[address_postalcode]" type="text" placeholder="placeholder" class="input-xlarge">
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <!-- Form Name -->
                            <legend>Direct Debit Billing Information</legend>

                            <!-- Text input-->
                            <div class="control-group">
                                <label class="control-label" for="textinput">Account Name</label>
                                <div class="controls">
                                    <input id="textinput" name="pl[accountHolderName]" type="text" placeholder="placeholder" class="input-xlarge">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="textinput">Account Number</label>
                                <div class="controls">
                                    <input id="textinput" name="pl[accountNumber]" type="text" placeholder="placeholder" class="input-xlarge">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="textinput">Sort Code</label>
                                <div class="controls">
                                    <input id="textinput" name="pl[bankSortCode]" type="text" placeholder="placeholder" class="input-xlarge">
                                </div>
                            </div>
                        </fieldset>
                    <?php endif; ?>

                <?php
                    if(
                            isset($this->model->paynow)
                            &&
                            $this->model->paynow
                            &&
                            $this->model->orderTotals['cost_now'] > 0
                    ):
                        ?>

                        <fieldset>
                            <!-- Form Name -->
                            <legend>Credit Card Information</legend>

                            <div id="payment-form"></div>
                            <input type="hidden" name="option" value="com_sp4k"/>
                            <input type="hidden" name="task" value="cart.process"/>
                        </fieldset>
                    <?php endif;?>


            <a id="submit-payment-button" class="btn btn-success">Continue</a>
        <?php endif; ?>
    </div>
    <div id="review" style="display:none;">
        <?php if(isset($this->model->items) && count($this->model->items)):?>
            <?php foreach($this->model->items as $item):?>
                <?php echo $item->description;?><br/>
                Pay Now:<?php echo $item->totals['cost_now'];?> via Credit Card
                1st Bill:<?php echo $item->totals['cost_next'];?> via Direct Debit
            <?php endforeach;?>
            <ul>
                <li style="display:inline-block;list-style-type:none;">
                    <input type="submit" value="Submit"/>
                    <a id="checkout-button" href class="btn btn-success">Submit Payment</a>
                </li>
                <li style="display:inline-block;list-style-type:none;">
                    <a id="continue-shopping" href class="btn btn-primary">Back</a>
                </li>
            </ul>
        <?php else: ?>
            <h2>Your Cart Is Empty</h2>
        <?php endif; ?>
    </div>

    </form>
</div>
