<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap.min.css">
</head>

<body>
    <div class="container text-center pt-3">
        <div class="form-body" style="margin-left: 40%;">
            <div class="row">
                <div class="form-holder">
                    <div class="form-content border">
                        <?= $this->session->flashdata('msg'); ?>
                        <div class="form-items">
                            <h3>Pay with Razorpay</h3>
                            <form class="requires-validation" action="<?= base_url() ?>pay" method="POST">
                                <div class="col-md-12 form-group">
                                    <input class="form-control" type="text" name="name" placeholder="Name" required>
                                </div>

                                <div class="col-md-12 form-group">
                                    <input class="form-control" type="email" name="email" placeholder="E-mail Address" required>
                                </div>

                                <div class="col-md-12 form-group">
                                    <input class="form-control" type="text" name="phonenumber" maxlength="10" placeholder="Phonenumber" required>
                                </div>

                                <div class="col-md-12 form-group">
                                    <input class="form-control" type="text" name="amount" placeholder="Amount" required>
                                </div>
                                <div class="form-button mt-3">
                                    <button id="submit" type="submit" class="btn btn-primary">Pay Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>