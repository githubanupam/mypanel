<?php

if (!function_exists('getAdminRole')) {

    function getAdminRole($id) {
        $CI = &get_instance();
        $CI->load->model("user_model");
        $result = $CI->user_model->getAdminRole($id);
        return $result['role'];
    }

}



if (!function_exists('getAllModuleAndPage')) {

    function getAllModuleAndPage() {
        $CI = &get_instance();
        $CI->load->model("user_model");
        $result = $CI->user_model->getAllModuleAndPage();

        $accessArray = array();

        if ($result) {
            $moduleId = '';
            $pageId = '';

            foreach ($result as $ind => $row_pg) {
                if ($row_pg["moduleId"] != $moduleId) {
                    $moduleId = $row_pg["moduleId"];
                    $moduleName = $row_pg["moduleName"];
                    $moduleSeq = $row_pg["moduleSeq"];

                    $accessArray[$moduleId]['modId'] = $moduleId;
                    $accessArray[$moduleId]['moduleName'] = $moduleName;
                    $accessArray[$moduleId]['moduleSeq'] = $moduleSeq;
                    $accessArray[$moduleId]['page'] = array();
                }

                if ($row_pg["pageId"] != $pageId) {
                    $moduleId = $row_pg["moduleId"];
                    $pageId = $row_pg["pageId"];

                    $accessArray[$moduleId]['page'][$pageId]['pgId'] = $row_pg["pageId"];
                    $accessArray[$moduleId]['page'][$pageId]['pageName'] = $row_pg["pageName"];
                    $accessArray[$moduleId]['page'][$pageId]['pageFileName'] = $row_pg["pageFileName"];
                    $accessArray[$moduleId]['page'][$pageId]['pageSeq'] = $row_pg["pageSeq"];
                    $accessArray[$moduleId]['page'][$pageId]['action'] = array();
                }

                $moduleId = $row_pg["moduleId"];
                $pageId = $row_pg["pageId"];
                $actionId = $row_pg["actionId"];

                $accessArray[$moduleId]['page'][$pageId]['action'][$actionId]['id'] = $row_pg["id"];
                $accessArray[$moduleId]['page'][$pageId]['action'][$actionId]['actionId'] = $row_pg["actionId"];
                $accessArray[$moduleId]['page'][$pageId]['action'][$actionId]['actionName'] = $row_pg["actionName"];
                $accessArray[$moduleId]['page'][$pageId]['action'][$actionId]['actionSeq'] = $row_pg["actionSeq"];
            }
        }

        return $accessArray;
    }

}

if (!function_exists('getRoleBasedPageAccess')) {

    function getRoleBasedPageAccess($role) {
        $CI = &get_instance();
        $CI->load->model("user_model");
        $result = $CI->user_model->getRoleBasedPageAccess($role);

        $accessArray = array();

        if ($result) {
            $moduleId = '';

            foreach ($result as $ind => $row_pg) {
                if ($row_pg["moduleId"] != $moduleId) {
                    $moduleId = $row_pg["moduleId"];
                    $moduleName = $row_pg["moduleName"];
                    $moduleIcon = $row_pg["moduleIcon"];
                    $moduleSeq = $row_pg["moduleSeq"];

                    $accessArray[$moduleId]['modId'] = $moduleId;
                    $accessArray[$moduleId]['moduleName'] = $moduleName;
                    $accessArray[$moduleId]['moduleIcon'] = $moduleIcon;
                    $accessArray[$moduleId]['moduleSeq'] = $moduleSeq;
                    $accessArray[$moduleId]['page'] = array();
                }


                $moduleId = $row_pg["moduleId"];
                $pageId = $row_pg["pageId"];

                $accessArray[$moduleId]['page'][$pageId]['pgId'] = $row_pg["pageId"];
                $accessArray[$moduleId]['page'][$pageId]['pageName'] = $row_pg["pageName"];
                $accessArray[$moduleId]['page'][$pageId]['pageFileName'] = $row_pg["pageFileName"];
                $accessArray[$moduleId]['page'][$pageId]['pageSeq'] = $row_pg["pageSeq"];
            }
        }

        return $accessArray;
    }

}


if (!function_exists('getPageAccesed')) {

    function getPageAccesed($role) {
        $CI = &get_instance();
        $CI->load->model("user_model");
        $result = $CI->user_model->getPageAccesed($role);

        $pages = array();
        foreach ($result as $ind => $row_pg) {
            $pages[] = $row_pg['pageId'];
        }

        return $pages;
    }

}

if (!function_exists('getActionAccesed')) {

    function getActionAccesed($role) {
        $CI = &get_instance();
        $CI->load->model("user_model");
        $result = $CI->user_model->getActionAccesed($role);

        $pages = array();
        foreach ($result as $ind => $row_pg) {
            $pages[] = $row_pg['actionId'];
        }

        return $pages;
    }

}

if (!function_exists('getConnectionCount')) {

    function getConnectionCount() {
        $CI = & get_instance();
        $CI->load->model('employee_model');
        $result = $CI->employee_model->getConnectionCount();
        return $result;
    }

}

if (!function_exists('employeeName')) {

    function employeeName($id = NULL) {
        $CI = & get_instance();
        $CI->load->model('employee_model');
        $result = $CI->employee_model->getEmpName($id);
        return $result;
    }

}

if (!function_exists('DesignationName')) {

    function DesignationName($id) {
        $CI = & get_instance();
        $CI->load->model("employee_model");
        $result = $CI->employee_model->designationName($id);
        return $result['s_name'];
    }

}

if (!function_exists('empNameWithDesig')) {

    function empNameWithDesig($id = NULL) {
        $CI = & get_instance();
        $CI->load->model('employee_model');
        $result = $CI->employee_model->employeeNameWithRank($id);
        return $result;
    }

}

if (!function_exists('getAllUsertype')) {

    function getAllUsertype() {
        $CI = & get_instance();
        $CI->load->model('usertype_model');
        $result = $CI->usertype_model->getAllUsertype();
        return $result;
    }

}

if (!function_exists('psName')) {

    function psName($id = NULL) {
        $CI = & get_instance();
        $CI->load->model('employee_model');
        $result = $CI->employee_model->getPsName($id);
       
        return $result[0]['sec_fullname'];
    }

}

if (!function_exists('divName')) {

    function divName($id = NULL) {
        $CI = & get_instance();
        $CI->load->model('employee_model');
        $result = $CI->employee_model->getDivName($id);
        return $result['unit_fullname'];
    }

}
?>