<?php
defined('BASEPATH') or exit('No direct script access allowed');
//Load a razorpay library
require_once(APPPATH . "libraries/razorpay-php/Razorpay.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class Payment_integration extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    //Load a payment view
    public function index()
    {
        $this->load->view('payment');
    }
    //Function is used for pay
    public function pay()
    {
        //Put your api key and id here
        $api = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);

        //create a order
        $razorpayOrder = $api->order->create(array(
            'receipt'         => rand(),
            'amount'          => $this->input->post('amount') * 100,
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ));
        //Set amount in session
        $this->session->set_userdata('amount', $this->input->post('amount'));

        $amount = $razorpayOrder['amount'];
        $razorpayOrderId = $razorpayOrder['id'];
        //Store All details in session
        $session =  array(
            'razorpay_order_id' => $razorpayOrderId,
            'name' => $this->input->post('name'),
            'email' => $this->input->post('email'),
            'contact' => $this->input->post('phonenumber'),
        );
        $this->session->set_userdata('data', $session);

        //Prepare a data for razorpay after preparing all data load in view
        $data = array(
            //Set razorpay key
            "key" => RAZOR_KEY_ID,
            //Set Amount
            "amount" => $amount,
            //Name of your company 
            "name" => "Razorpay testing",
            //Description about your company
            "description" => "",
            //Logo of your razorpay page
            "image" => "https://www.dreamstime.com/demo-icon-demo-image147077326",
            //Data show which comes  form
            "prefill" => array(
                "name"  => $session['name'],
                "email"  =>  $session['email'],
                "contact" => $session['contact']
            ),
            //Theme of your razorpay page
            "theme"  => array(
                "color"  => "#F37254"
            ),
            //Order ID
            "order_id" => $razorpayOrderId,
        );
        $this->load->view('razorpay', array('data' => $data));
    }


    //This funtion is used for verify the details of payment
    public function verifyData()
    {
        $details = $this->session->userdata('data');
        $success = true;
        $error = "payment_failed";
        if (empty($this->input->post('razorpay_id')) === false) {
            //Put your api key and id here
            $api = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);
            try {
                $attributes = array(
                    'razorpay_order_id' => $details['razorpay_order_id'],
                    'razorpay_payment_id' =>$this->input->post('razorpay_id'),
                    'razorpay_signature' => $this->input->post('signature')
                );
                $api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
                $error = 'Razorpay_Error : ' . $e->getMessage();
            }
        }
        if ($success === true) {
            //Fetch details of user after payment
            $name =  $details['name'];
            $email = $details['email'];
            $contact = $details['contact'];
            $amount = $this->session->userdata('amount');
            //Store all data into an array 
            $paymentData = array(
                'order_id' => $details['razorpay_order_id'],
                'name' => $name,
                'email' => $email,
                'contact' => $contact,
                'amount' => $amount,
                'razorpay_id' => $attributes['razorpay_payment_id'],
                'created_date'=> date('Y-m-d H:i:s')
            );
            //Save payment details into database
            $result = $this->db->insert('payment', $paymentData);
            //Set success message if payment is succesful 
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Payment Successful</div>');
            //Redirect on payment page
            redirect('web/payment_integration');
        } else {
            //Set success message if payment is not done
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Payment Not done</div>');
            //Redirect on payment page
            redirect('web/payment_integration');
        }
    }
}
