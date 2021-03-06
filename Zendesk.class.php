<?php
/**
 * [PT] Classe PHP para integrar o seu aplicativo de login na plataforma Zendesk com "single sign-on" 
 * [EN] PHP Class to integrate your login application in Zendesk platform with "single sign-on"
 * 
 * Created on 2013-03-19
 * 
 * @author PauloSouza.info <paulosouza.info@gmail.com>
 * @package Zendesk
 * @category API
 * @copyright Copyright (c) 2013, PauloSouza.info 
 * @example README.md
 */
class Zendesk{
	//URL para login remoto
	//URL to remote auth
	private $urlZendeskAuth = "https://YOUR_SUBDOMAIN.zendesk.com/access/remoteauth";
	
	//Seu hash secreto gerado pelo sistema
	//Your secret token
	private $tokenZendesk = "abcd1234";

	//Array para transação de autenticação remota
	//Transaction Remote Auth array
	private $remoteData = array();

	//Array de envio de dados do usuário 
	//Send user data array
	public $userData = array();

	//Array entregue pelo sistema Zendesk
	//Send Zendesk data array
	public $zendeskData = array();
	
	//O construtor recebe os dados de usuário e dados do Zendesk e monta um array
	//Constructor receive a user data from your database and zendesk url get data
	public function __construct(){
		//Mount a remote data array
		$this->remoteData = array(
			//full name user in your application
			"name" => $this->userData["name"],
			//email user in your application
			"email" => $this->userData["email"],
			//primary key ID from your database
			"external_id" => $this->userData["external_id"],
			//organization about your user if you have this info
			"organization" => $this->userData["organization"],
			//tags to classified your user
			"tags" => $this->userData["tags"],
			//send user profile photo with absolute URL
			"remote_photo_url" => $this->userData["remote_photo_url"],
			//timestamp request from zendesk
			"timestamp" => $this->zendeskData["timestamp"],
			//absolute URL return to Zendesk application
			"return_to" => $this->zendeskData["return_to"],
			//other parameter passed from Zendesk application
			"locale_id" => $this->zendeskData["locale_id"],
			//hash based in infos above
			"hash" => $this->mountHash()
			);
	}

	//Monta novos parâmetros para retorno a aplicação Zendesk
	//Mount new parameters to return Zendesk application 
	private function zendeskParams(){
		$return = "";
		foreach($this->zendeskData as $k => $v):
			$return .= "&amp;{$k}={$v}";
		endforeach;

		return $return;
	}

	//Cria um hash baseado em MD5
	//Create hash MD5 based
	private function mountHash(){
		$goToHash = array(
			$this->userData['name'],
			$this->userData('email'),
			$this->userData('external_id'),
			$this->userData['organization'],
			$this->userData['tags'],
			$this->userData['remote_photo_url'],
			$this->tokenZendesk,
			$this->zendeskData['timestamp']
			);

		$this->goToZendesk = implode("|", $goToHash);

		return md5($this->goToZendesk);
	}

	//Finalmente montamos os outros parâmetros
	//We finally mount other parameters 
	private function mountParams(){
		$return = "";
		foreach($this->zendeskData as $k => $v):
			$return .= "&amp;{$k}={$v}";
		endforeach;

		return $return;
	}

	//Retorna a URL de redirecionamento completa para o login remoto
	//Return a redirect URL to Zendesk application
	public function urlMount(){
		return $this->urlZendeskAuth.'?'.$this->mountParams().$this->zendeskParams();
	}
}