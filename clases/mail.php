<?php 
require_once 'mail_php/ext/Swift-4.2.1/lib/swift_required.php';
class mail{
	
	protected $puerto;
	protected $smtp;
	protected $from;
	protected $usuario;
	protected $pass;
	protected $asunto;
	protected $body;
	protected $seguridad;
	protected $to;
	protected $archivos;
//	protected

	function __construct($puerto,$smtp,$cuenta,$usuario,$pass,$asunto,$cuerpo_mensaje,$a,$seguridad=''){
		$this->puerto = $puerto;
		$this->smtp = $smtp;
		$this->from = $cuenta;
		$this->usuario =$usuario;
		$this->pass = $pass;
		$this->asunto = $asunto;
		$this->body = $cuerpo_mensaje;
		$this->seguridad = $seguridad;
		$this->to = $a;
		$this->archivos = array();
	}

	function agregar_archivo($archivo){
		$this->archivos[] = $archivo;
	}

	function enviar_mail_con_archivos(){
		$transport = Swift_SmtpTransport::newInstance($this->smtp, $this->puerto, 'tls')
		->setUsername($this->usuario)
		->setPassword($this->pass);
		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);
		// Create a message
		$message = Swift_Message::newInstance($this->asunto)//asunto
		->setFrom($this->from)
		->setTo($this->to)
		->setBody($this->body)
			->setContentType('text/html')	
		;//print_r('holaaa');
		// Send the message
		//$result = $mailer->send($message);
		for($i=0; $i < count($this->archivos); $i++){
			if($this->archivos[$i]!="")
				$message->attach(Swift_Attachment::fromPath($this->archivos[$i]));
		}
		//enviamos el mensaje
		if($mailer->send($message)){
			$resultado['respuesta'] = 'SI';
			//mysql_query($auditoria , $honorarios) or die(mysql_error());
		}else{
			$resultado['respuesta'] = 'PROBLEMA';
		}
		return $resultado;
		
	}
	
	function enviar_mail(){
		$transport = Swift_SmtpTransport::newInstance($this->smtp, $this->puerto)
		->setUsername($this->usuario)
		->setPassword($this->pass);
		// Create the Mailer using your created Transport
		$mailer = Swift_Mailer::newInstance($transport);
		// Create a message
		$message = Swift_Message::newInstance($this->asunto)//asunto
		->setFrom($this->from)
		->setTo($this->to)
		->setBody($this->body)
			->setContentType('text/html')	
		;//print_r('holaaa');
		// Send the message
		//$result = $mailer->send($message);
		
		//enviamos el mensaje
		if($mailer->send($message)){
			$resultado['respuesta'] = 'SI';
			//mysql_query($auditoria , $honorarios) or die(mysql_error());
		}else{
			$resultado['respuesta'] = 'PROBLEMA';
		}
		return $resultado;
	}
}
?>