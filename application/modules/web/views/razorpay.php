<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <button id="pay_button" style="display:none;">Pay with Razorpay</button>
    <form name='razorpayform' action="<?= base_url() ?>verfication" method="POST">
        <input type="hidden" name="razorpay_id" id="razorpay_id">
        <input type="hidden" name="signature" id="signature">
    </form>

    <!-- load a script file of checkout-->
    <script src="<?= base_url() ?>assets/js/checkout.js"></script>
    <!--Jquery file-->
    <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
    <script>
        var base_url= "<?= base_url() ?>";
        console.log(base_url);
        // Checkout details as a json
        var options = <?php echo json_encode($data); ?>;
        //After succesfull payment it send a response of payment 
        options.handler = function(response) {
            //append payment id and signature in form
            document.getElementById('razorpay_id').value = response.razorpay_payment_id;
            document.getElementById('signature').value = response.razorpay_signature;
            //Submit the form 
            document.razorpayform.submit();
        };
        options.modal = {
            "ondismiss": function() {
                window.location.href=base_url;
            }
        }

        var rzp = new Razorpay(options);
        $(document).ready(function() {
            //This function is used to open a razorpay
            $("#pay_button").click();
            rzp.open();
            e.preventDefault();
        });
    </script>
</body>

</html>