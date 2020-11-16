<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Nilai extends REST_Controller{

    function __construct($config = "rest"){
        parent::__construct($config);
        $this->load->database();
    }

    public function index_get(){

        $id = $this->get('id');
        $nilai=[];
        if($id == ''){
            $data = $this->db->get('nilai')->result();
            foreach($data as $row=>$key):
                $nilai[]=["idNilai"=>$key->idNilai,
                            "NilaiKuis"=>$key->NilaiKuis,
                            "NilaiUTS"=>$key->NilaiUTS,
                            "_links"=>[(object)["href"=>"nama/{$key->Npm}",
                                        "rel"=>"nama",
                                        "type"=>"GET"]]
                        ];
                    endforeach;
        }else{
            $this->db->where('idNilai', $id);
            $nilai = $this->db->get('nilai')->result();
        }
        $result=["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                 "code"=>200,
                 "message"=>"Response Successfully",
                 "data"=>$nilai];
        $this->response($result, 200);
    }

    public function index_post(){
        $data = array(
                    'idNilai' => $this->post('idNilai'),
                    'NilaiKuis' => $this->post('NilaiKuis'),
                    'NilaiUTS' => $this->post('NilaiUTS'));
        $insert = $this->db->insert('nilai', $data);
        if($insert){
            $result = ["took" => $_SERVER["REQUEST_TIME_FLOAT"],
                       "code"=>201,
                       "message"=>"Data has successfully added",
                       "data"=>$data];
            $this->response($result, 201);
        }else{
            $result = ["took"=>$_SERVER["REQUEST_TIME_FLOAT"],
                       "code"=>502,
                       "message"=>"Failed adding data",
                       "data"=>null];
            $this->response($result, 502);
        }
    }

    function index_put(){
        $id = $this->get('id');
        $data = array(
                    'idNilai' => $this->put('idNilai'),
                    'NilaiKuis' => $this->put('NilaiKuis'),
                    'NilaiUTS' => $this->put('NilaiUTS'));
                    
        $this->db->where('idNilai', $id);
        $update = $this->db->update('nilai', $data);
        if($update){
            $this->response($data, 200);
        } else{
            $this->response(array('status' => 'fail', 502));
        }
    }

    function index_delete() {
        $id = $this->get('id');
        $this->db->where('idNilai', $id);
        $delete = $this->db->delete('nilai');
        if ($delete) {
            $this->response(array('status' => 'success'), 201);
        } else{
            $this->response(array('status' => 'fail', 502));
        }
    }
}

?>