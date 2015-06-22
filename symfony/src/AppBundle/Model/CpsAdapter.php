<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 21/06/15
 * Time: 12:22
 */

namespace AppBundle\Model;

require_once(dirname(__FILE__) . '/../CpsApi/cps_api.php');

class CpsAdapter {

    // Connection hubs

    private $cspConn = null;

    private function getConnStrings()
    {
        return array(
            'tcp://cloud-eu-0.clusterpoint.com:9007',
            'tcp://cloud-eu-1.clusterpoint.com:9007',
            'tcp://cloud-eu-2.clusterpoint.com:9007',
            'tcp://cloud-eu-3.clusterpoint.com:9007'
        );
    }

    private function getCSPConnection()
    {
        if(!$this->$cspConn){
            return $this->$cspConn;
        }

        $this->$cspConn = new CPS_Connection(
            new CPS_LoadBalancer($this->getConnStrings()),
            'newsense',
            'diego_gullo@bizmate.biz',
            'Putenz01',
            //'document',
            'document/id',
            array('account' => 1127)
        );


        return $this->$cspConn;
    }

    public function getHistory()
    {

    }

    public function insertSearch($id, $obj){

        $insertRequest = new CPS_InsertRequest(array($id => $obj));
        $this->getCSPConnection()->sendRequest($insertRequest);
    }

    // Debug
    //$cpsConn->setDebug(true);

}