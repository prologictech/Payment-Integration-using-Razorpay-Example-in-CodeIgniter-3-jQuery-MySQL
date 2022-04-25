<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/** load the CI class for Modular Extensions **/
require dirname(__FILE__) . '/Base.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link    http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright    Copyright (c) 2015 Wiredesignz
 * @version     5.5
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Controller
{
    public $autoload = array();

    /**
     * Desciption :
     * Global variable to check if the requesting client's Platform is Mobile
     * Application of web browser
     *
     *
     **/
    public $vPlatform;

    /**
     * Desciption :
     * Global variable to store the value if API_TOKEN
     * It will be sent in headers only from in API Call and will be blank for
     * Website, it will be used to validate API call for non existing customers
     *
     **/
    public $vApiToken;

    /**
     * Desciption :
     * Global variable to store the value if Access Token
     * It will be sent in headers only from in API Call and will be blank for
     * Website, it will be used to validate API call for existing customers
     *
     **/

    public $vAccessToken;

    /**
     * Desciption :
     * Global variable to store the type of response that will be sent to client
     * By default it is set to flase, it will be true only if platform is mobile
     * its value will be changed in controllers itself not here
     *
     **/
    public $api = false;

    public function __construct()
    {
        $class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
        log_message('debug', $class . " MX_Controller Initialized");
        Modules::$registry[strtolower($class)] = $this;

        /* copy a loader instance and initialize */
        $this->load = clone load_class('Loader');
        $this->load->initialize($this);

        /* autoload module items */
        $this->load->_autoloader($this->autoload);
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');

        $this->vPlatform = $this->input->get_request_header('vPlatform', true);
        $this->vApiToken = $this->input->get_request_header('vApiToken', true);
        $this->vDeviceType = $this->input->get_request_header('vDeviceType', true);
        $this->vAccessToken = $this->input->get_request_header('Access-Token', true);
    }

    public function __get($class)
    {
        return CI::$APP->$class;
    }

    // FUNCTION TO LOAD ADMIN VIEWS
    public function adminviews($viewname, $pagedata)
    {
        $this->load->view('admin/header.php', $pagedata);
        $this->load->view('admin/sidebar.php');
        $this->load->view($viewname);
        $this->load->view('admin/footer.php');
    }
    // FUNCTION TO LOAD USER VIEWS
    public function patientviews($viewname, $pagedata,$page_name=NULL)
    {
        $this->load->view('patient/header.php', $pagedata);
        if (isset($this->session->is_user['is_authenticated']) && $this->session->is_user['is_authenticated'] != null) {
            if($page_name === null){
                $this->load->view('patient/sidebar.php');
            }
        }
        $this->load->view($viewname);
        $this->load->view('patient/footer.php');

    }

    // FUNCTION TO LOAD USER VIEWS
    public function providerviews($viewname, $pagedata,$page_name=null)
    {
        $this->load->view('provider/header.php', $pagedata);
        if (isset($this->session->is_provider['is_authenticated']) && $this->session->is_provider['is_authenticated'] != null) {
            if($page_name === null){

                $this->load->view('provider/sidebar.php');
            }
        }
        $this->load->view($viewname);
        $this->load->view('provider/footer.php');
    }

    // FUNCTIO TO CONVERT TIME ZONE
    public function convertTimeZone($dateTime, $TimeZoneFrom = "America/New_York", $TimeZoneTo = "UTC")
    {
        return $this->db->query("SELECT CONVERT_TZ('$dateTime','$TimeZoneFrom','$TimeZoneTo') as ConvertedDateTime")->result();

    }


    // funcitionality to upload files for all

    // function to save file 
    public function save_files($post,$files){

        $post       = $this->security->xss_clean($post); 
        $files      = $this->security->xss_clean($files);

        
        $iCaseId    = decode($post["iCaseId"]);
        $iVisitId   = decode($post["iVisitId"]);
        $batch_data = [];

        #data to be uploaded in database are in $form_data
        $form_data["iPatientId"]     = $this->session->is_user['iUserId'];
        $form_data["iCaseId"]        = $iCaseId;

        if (isset($post["iVisitId"]) && !empty($post["iVisitId"])) {
            $form_data["iVisitId"]       = $iVisitId;
        }

        $i=1;
        foreach ($files as $f => $file) {

            #condition to check if file name is empty or not
            if (!empty($file["name"]) && $file["name"] != "") {

                // saving name of the file in database
                $form_data["vReportName"]   = $file["name"];
                // saving extension of file
                $extension                  = explode(".",$file["name"]);
                $extension                  = $extension[count($extension)-1];
                $form_data["vType"]         = strtolower($extension);

                #createing customize name before uploading reports
                # renaming file before uploading
                $type = explode("/", $_FILES[$f]['type']);
                $_FILES[$f]['name'] = "patient_report_" . $this->iUserId . "_" . microtime(true).$i . "." . $extension;
                #uploading file
                $file_name = $this->upload_files($f, $type[1]);
                #condidtion to check if error occure if yes then unlinking previously uploaded file
                if (isset($file_name["er_no"])) {
                    if ($this->unlink_files($batch_data)) {
                        $this->session->set_flashdata("error", $file_name["msg"] ?? "can not upload");
                        redirect_back();
                        die;
                    }
                }
                #creating array of all files to upload then in batch
                $form_data["vReportFile"] = $file_name["file_name"];
                $batch_data[] = $form_data;
            }
            $i++;
        }

        if (!empty($batch_data)) {

            $upload_res = $this->orm->_insert_batch("lab_test_report", $batch_data);
            create_logs( $this->session->is_user['iUserId'] ,"non-admin","New Document Added of Visit/Encounter ID-".$iVisitId." Case Id-".$iCaseId);
            if ($upload_res) {
                return true;
            }
        }
        return false;
    }
    
    // function to unlink the files
    public function unlink_files($files = null, $name = null)
    {

        if ($name != null) {

            foreach ($name as $n) {
                if (file_exists(PATIENT_REPORT_PATH . $n)) {
                    unlink(PATIENT_REPORT_PATH . $n);
                }
            }
            return true;
        } else {

            foreach ($files as $f) {
                if (file_exists(PATIENT_REPORT_PATH . $f["vReportFile"])) {
                    unlink(PATIENT_REPORT_PATH . $f["vReportFile"]);
                }
            }
            return true;
        }
    }

    // function to upload files
    public function upload_files($field_name, $extension)
    {
        // $ext_array = ['jpg', 'png', 'jpeg', 'pdf', 'doc', 'docx', 'JPG', 'PNG', 'PDF', 'DOC', 'DOCX', 'msword', 'vnd.openxmlformats-officedocument.wordprocessingml.document'];

        // if (in_array($extension, $ext_array)) {
            $path = PATIENT_REPORT_PATH; //setting image path of patient report
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx';
            $config['max_size'] = 16000;

            $this->load->library('upload');
            $this->upload->initialize($config);

            if (!$this->upload->do_upload($field_name)) {

                $error['msg'] = $this->upload->display_errors();
                $error['er_no'] = 0;
                custom_error_log('US/Central', 'Error Occur while uploading reports of the patient, id=: ' . $this->iUserId . 'msg : ' . $error['msg']);
                return $error;
            }
            $data_img = $this->upload->data();
            return $data_img;
        // } else {
        //     $error['msg'] = "The file type you are uploading is not allowed";
        //     $error['er_no'] = 0;
        //     custom_error_log('US/Central', 'Error Occur while uploading reports of the patient, id=: ' . $this->iUserId . 'msg : ' . $error['msg']);
        //     return $error;

        // }

    }    
}
