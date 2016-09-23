<?php
/**
 *
 * 广西广电用户中心接口（UTF-8）
 *
 * CURL通信基类
 *
 * @author        TigerLi
 * @copyright    BrightStar
 * @version        1.0
 *
 * create@2015-11-30,create
 *
 */

namespace Common\Lib\Hitv;

class GDUcenterSoap
{
    protected $_xmlStr;

    /**
     * 设置发送登录请求XML
     * @param $xmlFields xml参数
     * @return mixed
     */
    public function sendLoginData($xmlFields)
    {
        //定义xml字符串
        $this->_xmlStr .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:user="http://user.gxgd.com/UserCenter">';
        $this->_xmlStr .= "<soapenv:Header/>";
        $this->_xmlStr .= "<soapenv:Body>";
        $this->_xmlStr .= "<user:operReq>";
        $this->_xmlStr .= "<operateType>AUTH</operateType>";
        $this->_xmlStr .= "<requestSysCode>" . C('SrcSysID') . "</requestSysCode>";
        $this->_xmlStr .= "<operRequestXML><![CDATA[";
        $this->_xmlStr .= "<UAPRoot>";

        $this->_xmlStr .= "<SessionHeader>";
        $this->_xmlStr .= "<ServiceCode>UAP01001</ServiceCode>";    //接口服务编码
        $this->_xmlStr .= "<Version>1.0</Version>";
        $this->_xmlStr .= "<ActionCode>0</ActionCode>";
        $this->_xmlStr .= "<TransactionID>" . C('SrcSysID') . date("YmdHis", time()) . rand(1000, 9999) . "</TransactionID>";    //流水号
        $this->_xmlStr .= "<SrcSysID>" . C('SrcSysID') . "</SrcSysID>";    //发起方编码
        $this->_xmlStr .= "<DstSysID>" . C('DstSysID') . "</DstSysID>";    //落地方编码
        $this->_xmlStr .= "<ReqTime></ReqTime>";
        $this->_xmlStr .= "<Request>";
        $this->_xmlStr .= "<ReqType></ReqType>";
        $this->_xmlStr .= "<ReqCode></ReqCode>";
        $this->_xmlStr .= "<ReqDesc></ReqDesc>";
        $this->_xmlStr .= "</Request>";
        $this->_xmlStr .= "</SessionHeader>";

        $this->_xmlStr .= "<SessionBody>";
        $this->_xmlStr .= "<AuthReq>";
        $this->_xmlStr .= "<AuthInfo>";
        $this->_xmlStr .= "<AccountID>" . $xmlFields["AccountID"] . "</AccountID>";    //账号
        $this->_xmlStr .= "<PasswordType>20</PasswordType>";          //密码类型，20：账号密码，21：随机密码
        $this->_xmlStr .= "<Password>" . $xmlFields["Password"] . "</Password>";     //密码
        $this->_xmlStr .= "<CertyCode></CertyCode>";
        $this->_xmlStr .= "</AuthInfo>";
        $this->_xmlStr .= "</AuthReq>";
        $this->_xmlStr .= "</SessionBody>";

        $this->_xmlStr .= "</UAPRoot>";
        $this->_xmlStr .= "]]></operRequestXML>";
        $this->_xmlStr .= "<digitalSign></digitalSign>";
        $this->_xmlStr .= "</user:operReq>";
        $this->_xmlStr .= "</soapenv:Body>";
        $this->_xmlStr .= "</soapenv:Envelope>";

        $result = do_http_post(C('GDUCENTER_URL'), $this->_xmlStr);

        return $result;
    }

    /**
     * 设置忘记密码
     * @param $xmlFields xml参数
     * @return mixed
     */
    public function sendPasswordResetData($xmlFields)
    {
        //定义xml字符串
        $this->_xmlStr .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:user="http://user.gxgd.com/UserCenter">';
        $this->_xmlStr .= "<soapenv:Header/>";
        $this->_xmlStr .= "<soapenv:Body>";
        $this->_xmlStr .= "<user:operReq>";
        $this->_xmlStr .= "<operateType>RESETPWD</operateType>";
        $this->_xmlStr .= "<requestSysCode>" . C('SrcSysID') . "</requestSysCode>";
        $this->_xmlStr .= "<operRequestXML><![CDATA[";
        $this->_xmlStr .= "<UAPRoot>";

        $this->_xmlStr .= "<SessionHeader>";
        $this->_xmlStr .= "<ServiceCode>UAP04003</ServiceCode>";    //接口服务编码
        $this->_xmlStr .= "<Version>1.0</Version>";
        $this->_xmlStr .= "<ActionCode>0</ActionCode>";
        $this->_xmlStr .= "<TransactionID>" . C('SrcSysID') . date("YmdHis", time()) . rand(1000, 9999) . "</TransactionID>";    //流水号
        $this->_xmlStr .= "<SrcSysID>" . C('SrcSysID') . "</SrcSysID>";    //发起方编码
        $this->_xmlStr .= "<DstSysID>" . C('DstSysID') . "</DstSysID>";    //落地方编码
        $this->_xmlStr .= "<ReqTime></ReqTime>";
        $this->_xmlStr .= "<Request>";
        $this->_xmlStr .= "<ReqType></ReqType>";
        $this->_xmlStr .= "<ReqCode></ReqCode>";
        $this->_xmlStr .= "<ReqDesc></ReqDesc>";
        $this->_xmlStr .= "</Request>";
        $this->_xmlStr .= "</SessionHeader>";

        $this->_xmlStr .= "<SessionBody>";
        $this->_xmlStr .= "<ResetPassword>";
        $this->_xmlStr .= "<Account>{$xmlFields["Account"]}</Account>";
        $this->_xmlStr .= "<PasswordType>20</PasswordType>";
        $this->_xmlStr .= "<NewPassword>{$xmlFields["NewPassword"]}</NewPassword>";
        $this->_xmlStr .= "<RandomCodeID>{$xmlFields["RandomCodeID"]}</RandomCodeID>";
        $this->_xmlStr .= "<RandomCode>{$xmlFields["RandomCode"]}</RandomCode>";
        $this->_xmlStr .= "<IDCardCode>123</IDCardCode>";
        $this->_xmlStr .= "</ResetPassword>";
        $this->_xmlStr .= "</SessionBody>";

        $this->_xmlStr .= "</UAPRoot>";
        $this->_xmlStr .= "]]></operRequestXML>";
        $this->_xmlStr .= "<digitalSign></digitalSign>";
        $this->_xmlStr .= "</user:operReq>";
        $this->_xmlStr .= "</soapenv:Body>";
        $this->_xmlStr .= "</soapenv:Envelope>";

        $result = do_http_post(C('GDUCENTER_URL'), $this->_xmlStr);

        return $result;
    }


    /**
     * 设置发送短信请求XML
     * @param $xmlFields xml参数
     * @param $isReset bool 是否为重置密码
     * @return mixed
     */
    public function sendSMSData($xmlFields, $isReset = false)
    {
        //定义xml字符串
        $this->_xmlStr .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:user="http://user.gxgd.com/UserCenter">';
        $this->_xmlStr .= "<soapenv:Header/>";
        $this->_xmlStr .= "<soapenv:Body>";
        $this->_xmlStr .= "<user:operReq>";
        $this->_xmlStr .= "<operateType>SMS</operateType>";
        $this->_xmlStr .= "<requestSysCode>" . C('SrcSysID') . "</requestSysCode>";
        $this->_xmlStr .= "<operRequestXML><![CDATA[";
        $this->_xmlStr .= "<UAPRoot>";

        $this->_xmlStr .= "<SessionHeader>";
        $this->_xmlStr .= "<ServiceCode>UAP05001</ServiceCode>";    //接口服务编码
        $this->_xmlStr .= "<Version>1.0</Version>";
        $this->_xmlStr .= "<ActionCode>0</ActionCode>";
        $this->_xmlStr .= "<TransactionID>" . C('SrcSysID') . date("YmdHis", time()) . rand(1000, 9999) . "</TransactionID>";    //流水号
        $this->_xmlStr .= "<SrcSysID>" . C('SrcSysID') . "</SrcSysID>";    //发起方编码
        $this->_xmlStr .= "<DstSysID>" . C('DstSysID') . "</DstSysID>";    //落地方编码
        $this->_xmlStr .= "<ReqTime></ReqTime>";
        $this->_xmlStr .= "<Request>";
        $this->_xmlStr .= "<ReqType></ReqType>";
        $this->_xmlStr .= "<ReqCode></ReqCode>";
        $this->_xmlStr .= "<ReqDesc></ReqDesc>";
        $this->_xmlStr .= "</Request>";
        $this->_xmlStr .= "</SessionHeader>";

        $this->_xmlStr .= "<SessionBody>";
        $this->_xmlStr .= "<SMSReq>";
        $this->_xmlStr .= "<SMSInfo>";
        $this->_xmlStr .= "<AccountID>" . $xmlFields["AccountID"] . "</AccountID>";
        $type = $isReset ? "00B" : "00A";
        $this->_xmlStr .= "<ReqType>{$type}</ReqType>";
        $this->_xmlStr .= "<Operators>000</Operators>";
        $this->_xmlStr .= "<CityCode></CityCode>";
        $this->_xmlStr .= "</SMSInfo>";
        $this->_xmlStr .= "</SMSReq>";
        $this->_xmlStr .= "</SessionBody>";

        $this->_xmlStr .= "</UAPRoot>";
        $this->_xmlStr .= "]]></operRequestXML>";
        $this->_xmlStr .= "<digitalSign></digitalSign>";
        $this->_xmlStr .= "</user:operReq>";
        $this->_xmlStr .= "</soapenv:Body>";
        $this->_xmlStr .= "</soapenv:Envelope>";

        $result = do_http_post(C('GDUCENTER_URL'), $this->_xmlStr);

        return $result;
    }

    /**
     * 设置发送注册请求XML
     * @param $xmlFields xml参数
     * @return mixed
     */
    public function sendRegData($xmlFields)
    {
        //定义xml字符串
        $this->_xmlStr .= '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:user="http://user.gxgd.com/UserCenter">';
        $this->_xmlStr .= "<soapenv:Header/>";
        $this->_xmlStr .= "<soapenv:Body>";
        $this->_xmlStr .= "<user:operReq>";
        $this->_xmlStr .= "<operateType>REG</operateType>";
        $this->_xmlStr .= "<requestSysCode>" . C('SrcSysID') . "</requestSysCode>";
        $this->_xmlStr .= "<operRequestXML><![CDATA[";
        $this->_xmlStr .= "<UAPRoot>";

        $this->_xmlStr .= "<SessionHeader>";
        $this->_xmlStr .= "<ServiceCode>UAP04001</ServiceCode>";    //接口服务编码
        $this->_xmlStr .= "<Version>1.0</Version>";
        $this->_xmlStr .= "<ActionCode>0</ActionCode>";
        $this->_xmlStr .= "<TransactionID>" . C('SrcSysID') . date("YmdHis", time()) . rand(1000, 9999) . "</TransactionID>";    //流水号
        $this->_xmlStr .= "<SrcSysID>" . C('SrcSysID') . "</SrcSysID>";    //发起方编码
        $this->_xmlStr .= "<DstSysID>" . C('DstSysID') . "</DstSysID>";    //落地方编码
        $this->_xmlStr .= "<ReqTime></ReqTime>";
        $this->_xmlStr .= "<Request>";
        $this->_xmlStr .= "<ReqType></ReqType>";
        $this->_xmlStr .= "<ReqCode></ReqCode>";
        $this->_xmlStr .= "<ReqDesc></ReqDesc>";
        $this->_xmlStr .= "</Request>";
        $this->_xmlStr .= "</SessionHeader>";

        $this->_xmlStr .= "<SessionBody>";
        $this->_xmlStr .= "<AddAccount>";
        $this->_xmlStr .= "<AccountID>" . $xmlFields["AccountID"] . "</AccountID>";
        $this->_xmlStr .= "<CityCode></CityCode>";
        $this->_xmlStr .= "<ManageLevel></ManageLevel>";
        $this->_xmlStr .= "<Password>" . $xmlFields["Password"] . "</Password>";
        $this->_xmlStr .= "<CustCode>" . $xmlFields["CustCode"] . "</CustCode>";
        $this->_xmlStr .= "<RandomCodeID>" . $xmlFields["RandomCodeID"] . "</RandomCodeID>";
        $this->_xmlStr .= "<RandomCode>" . $xmlFields["RandomCode"] . "</RandomCode>";
        $this->_xmlStr .= "<CustInfo>";
        $this->_xmlStr .= "<CustName>" . $xmlFields["CustName"] . "</CustName>";
        $this->_xmlStr .= "<Mobile>" . $xmlFields["AccountID"] . "</Mobile>";
        $this->_xmlStr .= "<Address></Address>";
        $this->_xmlStr .= "<CertyType></CertyType>";
        $this->_xmlStr .= "<CertyCode></CertyCode>";
        $this->_xmlStr .= "<Notes></Notes>";
        $this->_xmlStr .= "<QuesionCode></QuesionCode>";
        $this->_xmlStr .= "<QuesionContent></QuesionContent>";
        $this->_xmlStr .= "<Answer></Answer>";
        $this->_xmlStr .= "</CustInfo>";
        $this->_xmlStr .= "</AddAccount>";
        $this->_xmlStr .= "</SessionBody>";

        $this->_xmlStr .= "</UAPRoot>";
        $this->_xmlStr .= "]]></operRequestXML>";
        $this->_xmlStr .= "<digitalSign></digitalSign>";
        $this->_xmlStr .= "</user:operReq>";
        $this->_xmlStr .= "</soapenv:Body>";
        $this->_xmlStr .= "</soapenv:Envelope>";

        $result = do_http_post(C('GDUCENTER_URL'), $this->_xmlStr);

        return $result;
    }

    /**
     * 将xml转成数组
     * @param $Xml_Data
     * @return array|string
     */
    public function xmlToArray($Xml_Data)
    {
        if (!$Xml_Data) {
            return 'Xml Data is empty!';
        }
        $Res_Xml = xml_parser_create("utf-8");
        xml_parser_set_option($Res_Xml, XML_OPTION_CASE_FOLDING, false);
        xml_parser_set_option($Res_Xml, XML_OPTION_SKIP_WHITE, 1);
        xml_parser_set_option($Res_Xml, XML_OPTION_TARGET_ENCODING, "utf-8");
        $Result_Array = array();
        if (!xml_parse_into_struct($Res_Xml, $Xml_Data, $Result_Array)) {
            return "XML error";
        };
        xml_parser_free($Res_Xml);

        //初始化变量
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();
        $priority = "";

        $current = &$xml_array;              //xml_array变量引用
        $repeated_tag_index = array();    //将拥有相同名称的多个标签组成一个数组
        foreach ($Result_Array as $data) {
            unset($attributes, $value);   //删除现有的值
            extract($data);
            $result = array();
            $attributes_data = array();
            if (isset($value)) {
                if ($priority == 'tag') {
                    $result = $value;
                } else {
                    $result['value'] = $value;
                }
            }

            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag') {
                        $attributes_data[$attr] = $val;
                    } else {
                        $result['attr'][$attr] = $val;
                    }
                }
            }

            if ($type == "open") {
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) {
                    $current[$tag] = $result;
                    if ($attributes_data) $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    $current = &$current[$tag];
                } else {
                    if (isset($current[$tag][0])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array($current[$tag], $result);
                        $repeated_tag_index[$tag . '_' . $level] = 2;
                        if (isset($current[$tag . '_attr'])) {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif ($type == "complete") {
                if (!isset($current[$tag])) {
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data) {
                        $current[$tag . '_attr'] = $attributes_data;
                    }
                } else {
                    if (isset($current[$tag][0]) and is_array($current[$tag])) {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else {
                        $current[$tag] = array($current[$tag], $result);
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) {
                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }
                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    }
                }
            } elseif ($type == 'close') {
                $current = &$parent[$level - 1];
            }
        }

        return ($xml_array);
    }

    public function test(){
        echo 'test';
    }
}