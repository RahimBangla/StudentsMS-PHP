<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 4.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : System_update.php
 * @copyright : Reserved RamomCoder Team
 */

class System_update extends Admin_Controller
{
    private $tmp_dir;
    private $tmp_update_dir;
    private $purchase_code;
    private $latest_version;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('system_update_model');
    }

    public function index()
    {
        if (!get_permission('system_update', 'is_add')) {
            access_denied();
        }
        if (!extension_loaded('curl')) {
            $this->data['curl_extension'] = 0;
        } else {
            if (!empty($this->system_update_model->getPurchaseCode()['purchase_code'])) {
                $this->data['purchase_code'] = true;
                if ($this->system_update_model->is_connected()) {
                    $this->data['internet'] = true;
                    $this->data['curl_extension'] = 1;
                    $get_update_info = $this->system_update_model->get_update_info();
                    if (strpos($get_update_info, 'Curl Error -') !== false) {
                        $this->data['update_errors'] = $get_update_info;
                        $this->data['latest_version'] = "0.0.0";
                        $this->data['support_expiry_date'] = "-/-/-";
                        $this->data['purchase_code'] = "";
                        $this->data['block'] = 0;
                    } else {
                        $get_update_info = json_decode($get_update_info);
                        $this->data['update_errors'] = "";
                        $this->data['get_update_info'] = $get_update_info;
                        $this->data['latest_version'] = $get_update_info->latest_version;
                        $this->data['support_expiry_date'] = $get_update_info->support_expiry_date;
                        $this->data['purchase_code'] = $get_update_info->purchase_code;
                        $this->data['block'] = $get_update_info->block;
                    }
                } else {
                    $this->data['internet'] = false;
                }
            } else {
                $this->data['purchase_code'] = false;
            }
        }

        if (!extension_loaded('zip')) {
            $this->data['zip_extension'] = 0;
        } else {
            $this->data['zip_extension'] = 1;
        }

        $this->data['current_version'] = $this->system_update_model->get_current_db_version();
        $this->data['title'] = translate('system_update');
        $this->data['sub_page'] = 'system_update/index';
        $this->data['main_menu'] = 'settings';
        $this->load->view('layout/index', $this->data);
    }

    public function update_install()
    {
        if (!get_permission('system_update', 'is_add')) {
            access_denied();
        }

        $getPurchaseCode = $this->system_update_model->getPurchaseCode();
        $get_current_version = $this->system_update_model->get_current_db_version();
        $getIP = $this->system_update_model->getIP();
        $latest_version = $this->input->post('latest_version');

        $this->latest_version = $latest_version;
        $this->purchase_code = $getPurchaseCode['purchase_code'];
        $tmp_dir = @ini_get('upload_tmp_dir');
        if (!$tmp_dir) {
            $tmp_dir = @sys_get_temp_dir();
            if (!$tmp_dir) {
                $tmp_dir = FCPATH . 'temp';
            }
        }

        $tmp_dir = rtrim($tmp_dir, '/') . '/';
        if (!is_writable($tmp_dir)) {
            $message = "Temporary directory not writable - <b>$tmp_dir</b><br />Please contact your hosting provider make this directory writable. The directory needs to be writable for the update files.";
            echo json_encode(['status' => 0, 'message' => $message]);
            exit();
        }

        $this->tmp_dir = $tmp_dir;
        $tmp_dir = $tmp_dir . 'v' . $latest_version . '/';
        $this->tmp_update_dir = $tmp_dir;

        if (!is_dir($tmp_dir)) {
            mkdir($tmp_dir);
            fopen($tmp_dir . 'index.html', 'w');
        }

        $zipFile = $tmp_dir . $latest_version . '.zip'; // Local Zip File Path
        $zipResource = fopen($zipFile, "w+");
        // Get The Zip File From Server
        $url = UPDATE_INSTALL_URL;
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, $this->agent->agent_string());
        curl_setopt($curl_handle, CURLOPT_FAILONERROR, true);
        curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl_handle, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl_handle, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 300);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_handle, CURLOPT_FILE, $zipResource);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
            'purchase_code' => $this->purchase_code,
            'item' => 'school',
            'current_version' => $get_current_version,
            'ip_address' => $getIP,
            'url' => base_url(), // please do not change the URL this is mandatory to setup the software
        ));
        $success = curl_exec($curl_handle);
        if (!$success) {
            fclose($zipResource);
            $this->cleanTmpFiles();
            $error = $this->getErrorByStatusCode(curl_getinfo($curl_handle, CURLINFO_HTTP_CODE));
            if ($error == '') {
                // Uknown error
                $error = curl_error($curl_handle);
            }
            echo json_encode(['status' => 0, 'message' => $error]);
            exit();
        }
        curl_close($curl_handle);

        $zip = new ZipArchive;
        if ($zip->open($zipFile) === true) {
            if (!$zip->extractTo('./')) {
                echo json_encode(['status' => 0, 'message' => 'Failed to extract downloaded zip file']);
                exit();
            }
            $zip->close();
        } else {
            echo json_encode(['status' => 0, 'message' => 'Failed to open downloaded zip file']);
            exit();
        }
        fclose($zipResource);
        $this->cleanTmpFiles();
        echo json_encode(['status' => '1', 'message' => 'Successfully Updated']);
    }

    private function cleanTmpFiles()
    {
        if (is_dir($this->tmp_update_dir)) {
            if (@!delete_dir($this->tmp_update_dir)) {
                @rename($this->tmp_update_dir, $this->tmp_dir . 'delete_this_' . uniqid());
            }
        }
    }

    private function getErrorByStatusCode($statusCode)
    {
        $error = '';
        if ($statusCode == 505) {
            $mailBody = 'Hello. I tried to upgrade to the latest version but for some reason the upgrade failed. Please remove the key from the upgrade log so i can try again. My installation URL is: ' . base_url() . '. Regards.';
            $mailSubject = 'Purchase Key Removal Request - [' . $this->purchase_code . ']';
            $error = 'Purchase key already used to download upgrade files for version ' . wordwrap($this->latest_version, 1, '.', true) . '. Performing multiple auto updates to the latest version with one purchase key is not allowed. If you have multiple installations you must buy another license.<br /><br /> If you have staging/testing installation and auto upgrade is performed there, <b>you should perform manually upgrade</b> in your production area<br /><br /> <h4 class="bold">Upgrade failed?</h4> The error can be shown also if the update failed for some reason, but because the purchase key is already used to download the files, you won’t be able to re-download the files again.<br /><br />Click <a href="mailto:ramomcoder@yahoo.com?subject=' . $mailSubject . '&body=' . $mailBody . '"><b>here</b></a> to send an mail and get your purchase key removed from the upgrade log.';
        } elseif ($statusCode == 506) {
            $error = 'This is not a valid purchase code.';
        } elseif ($statusCode == 507) {
            $error = 'Purchase key empty.';
        } elseif ($statusCode == 508) {
            $error = 'This purchase code is blocked.';
        }
        return $error;
    }

    public function database()
    {
        if (!get_permission('system_update', 'is_add')) {
            access_denied();
        }
        $db_update = $this->system_update_model->upgrade_database_silent();
        if ($db_update['success'] == false) {
            echo json_encode(['status' => '0', 'message' => $db_update['message']]);
            exit();
        }
        $message = '<div>
            <h4>Congratulations your Ramom software has been successfully updated ' . config_item('version') . '.</h4>
            <p>
                This window will reload automatically in 5 seconds. You are strongly recommended to manually clear your browser cache.
            </p>
        </div>';
        set_alert('success', translate('you_are_now_using_the_latest_version'));
        echo json_encode(['status' => '1', 'message' => $message]);
    }
}
