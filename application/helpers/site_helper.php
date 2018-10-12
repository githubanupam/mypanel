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

//if (!function_exists('getConnectionCount')) {
//
//    function getConnectionCount() {
//        $CI = & get_instance();
//        $CI->load->model('employee_model');
//        $result = $CI->employee_model->getConnectionCount();
//        return $result;
//    }
//
//}
//
//if (!function_exists('employeeName')) {
//
//    function employeeName($id = NULL) {
//        $CI = & get_instance();
//        $CI->load->model('employee_model');
//        $result = $CI->employee_model->getEmpName($id);
//        return $result;
//    }
//
//}
//
//if (!function_exists('DesignationName')) {
//
//    function DesignationName($id) {
//        $CI = & get_instance();
//        $CI->load->model("employee_model");
//        $result = $CI->employee_model->designationName($id);
//        return $result['s_name'];
//    }
//
//}
//
//if (!function_exists('empNameWithDesig')) {
//
//    function empNameWithDesig($id = NULL) {
//        $CI = & get_instance();
//        $CI->load->model('employee_model');
//        $result = $CI->employee_model->employeeNameWithRank($id);
//        return $result;
//    }
//
//}
//
//if (!function_exists('getAllUsertype')) {
//
//    function getAllUsertype() {
//        $CI = & get_instance();
//        $CI->load->model('usertype_model');
//        $result = $CI->usertype_model->getAllUsertype();
//        return $result;
//    }
//
//}
//
//if (!function_exists('psName')) {
//
//    function psName($id = NULL) {
//        $CI = & get_instance();
//        $CI->load->model('employee_model');
//        $result = $CI->employee_model->getPsName($id);
//       
//        return $result[0]['sec_fullname'];
//    }
//
//}
//
//if (!function_exists('divName')) {
//
//    function divName($id = NULL) {
//        $CI = & get_instance();
//        $CI->load->model('employee_model');
//        $result = $CI->employee_model->getDivName($id);
//        return $result['unit_fullname'];
//    }
//
//}

if (!function_exists('getAllUnits')) {

    function getAllUnits() {
        $CI = & get_instance();
        $CI->load->model('unit_model');
        $result = $CI->unit_model->get_filtered_data();
        return $result;
    }

}

if (!function_exists('getAllSections')) {

    function getAllSections() {
        $CI = & get_instance();
        $CI->load->model('section_model');
        $result = $CI->section_model->get_filtered_data();
        return $result;
    }

}

if (!function_exists('getAllEmployees')) {

    function getAllEmployees() {
        $CI = & get_instance();
        $CI->load->model('staff_model');
        $result = $CI->staff_model->get_filtered_data();
        return $result;
    }

}

if (!function_exists('getConnectionCount')) {

    function getConnectionCount() {
        $CI = & get_instance();
        $CI->load->model('staff_model');
        $result = $CI->staff_model->getConnectionCount();
        return $result;
    }

}

if (!function_exists('employeeName')) {

    function employeeName($id = NULL) {
        $CI = & get_instance();
        $CI->load->model('staff_model');
        $result = $CI->staff_model->getEmpName($id);
        return $result;
    }

}

if (!function_exists('DesignationName')) {

    function DesignationName($id) {
        $CI = & get_instance();
        $CI->load->model("staff_model");
        $result = $CI->staff_model->designationName($id);
        return $result['s_name'];
    }

}

if (!function_exists('empNameWithDesig')) {

    function empNameWithDesig($id = NULL) {
        $CI = & get_instance();
        $CI->load->model('staff_model');
        $result = $CI->staff_model->getEmpNameWithDesig($id);
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
        $CI->load->model('staff_model');
        $result = $CI->staff_model->getPsName($id);
        return $result['sec_fullname'];
    }

}

if (!function_exists('divName')) {

    function divName($id = NULL) {
        $CI = & get_instance();
        $CI->load->model('staff_model');
        if ($id == NULL) {
            $result = $CI->staff_model->getDivName();
            return $result;
        } else {
            $result = $CI->staff_model->getDivName($id);
            return $result['unit_fullname'];
        }
    }

}

if (!function_exists('getTagParent')) {

    function getTagParent($id) {

        $CI = & get_instance();
        if ($id == 0) {
            return $result['tag_name'] = 'Root';
        } else {
            $CI->load->model("tag_model");
            $result = $CI->tag_model->getTagParent($id);
            return $result['tag_name'];
        }
    }

}


if (!function_exists('toWords')) {

    function toWords($number) {
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
        );
        $string = $dictionary[$number];
        return $string;
    }

}

if (!function_exists('slugify')) {

    function slugify($text, $table) {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text)) {
            $text = 'n-a';
        } else {
            return $text;
        }
        // $CI = & get_instance();
        // $CI->db->select('*');
        // $CI->db->where('slug', $text);
        // $CI->db->from($table);
        // $query = $CI->db->get();
        // // $query = $this->db->get();
        // // return $query->num_rows();
        // if ($query->num_rows() > 0) {
        //     return $text.'-'.time();
        // } else {
        //     return $text;
        // }
    }

}


if (!function_exists('generatePassword')) {

    function generatePassword($min = 6, $max = 18) {
        $arr = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k',
            'm', 'n', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'J', 'K', 'L',
            'M', 'N', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Y', 'Z',
            '1', '2', '3', '4', '5', '6',
            '7', '8', '9');
        $str = "";
        $length = rand($min, $max);
        $array_count = count($arr) - 1;

        for ($i = 0; $i < $length; ++$i) {
            $index = rand(0, $array_count);
            $str .= $arr[$index];
        }
        return $str;
    }

}
?>