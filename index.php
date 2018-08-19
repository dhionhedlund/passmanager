<!DOCTYPE html>
	<html>
		<head>
			<title>Gestão de senhas</title>
				<meta charset="utf-8">
					<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
						<script type="text/javascript" src="jquery-3.2.1.js"></script>
<style>							

body {
  margin: 0;
  font-family: arial, helvetica, sans-serif;
  font-size: 11px;
  background-color: #fff;
}
h1, h2, h3, h4 {
	color: #9c2800;
}

table {
    border: 0;
    background-color: #c0c0c0;
    border-spacing: 1px;
    border-collapse: separate;
	margin: 0 50px;
}
thead {
    vertical-align: middle;
    border-color: inherit;	
}
thead > tr {
	background-color: #e2931a;
    color: white;
    font-style: normal;
}
thead > tr > th {
    padding: 5px;
    font-weight: normal;
    line-height: 18px;
    text-align: left;
    line-height: 16px;
}
tbody > tr {
    background-color: #ffffff;
    color: black;
    font-style: normal;
}
tbody > tr > td {
    padding: 5px;
    font-weight: normal;
    line-height: 16px;
    vertical-align: top;	
}
.caixa_menu_topo {
		padding: 5px;
		background: #e2931a;
		position: fixed;
		width: 100%;
}
.caixa_cabecalho_resultados {
		width:100%;
		text-align: center;	
}
#resultado {
		padding-top: 50px;
}
#DivJanela{
		position:fixed;
		margin:5% 40%;
		background-color: #e2931a;
		border: 5px solid #c0c0c0;
		padding:20px;
		display:none;
		min-width:20%;
}
.input_bt {
    font-family: arial, helvetica, sans-serif;
    font-size: 11px;
    color: #303030;
    padding: 2px;
	min-width: 70px;
}
.input_bt:active {
	padding:1px 0px 0px 0px;
}
.d-block {
  display: block !important;
  position:fixed;
}
.modal-overlay {
    position: fixed;
    top: 0; right: 0; bottom: 0; left: 0;
    background: rgba(#000, .4);
    z-index: 2;
}
#searchboxdiv {
    padding: 10px 10px;
    display: none;
    background: #e2931a;
    width: 25%;
	margin: 32px 1px;
}

textarea, select, input[type=text]{
	font-size: 12px;
    margin: 10px 0;
    vertical-align: top;
    border: solid #a0a0a0 1px;
    padding: 2px;
	font-family: inherit;
	width: 98%;
}							
</style>
<?php
//#############################################################################################################
// Faz a conexão com o banco de dados
	$SQLHOST = 'localhost' ; //Endereço do servidor
		$SQLLOGIN = 'root' ; //Login do usuario
			$SQLPASS = '' ; //Senha
				$DATABASE = 'inscricao1' ; //Nome do banco de dados
					$conectar = mysqli_connect("$SQLHOST","$SQLLOGIN","$SQLPASS", "$DATABASE") or die("Falha ao conectar no banco de dados"); 
						$TrazItens = mysqli_query($conectar, "SELECT * FROM plg_gs1 ORDER BY id");
						
//#############################################################################################################
//Define algumas variáveis globais
$BotaoFecharJanelaSuspensa = '<div style="width:100%;text-align:right;"><button type="button" onclick="AlternaVisibilidadeJanelaSuspensa();">Fechar</button></div>';
//#############################################################################################################
// Se existir dados recebidos via get url, e GET_['action'] ser igual a "view", exibe o formulario para exclusão abaixo
	if($_SERVER["REQUEST_METHOD"] != "POST" && isset($_GET['id']) && isset($_GET['action'])){ // É necessário manter a função isset de verificar se existe primeiro a variável action
		if($_GET['action'] == base64_encode("view")){ // Depois, aqui, verificamos se a variável action existente é igual à view criptografada
			// Define a variável ID recebida
			$b = base64_decode($_GET['id']);
				// Faz uma seleção no banco de dados
				$sql_query_all = mysqli_query($conectar, "SELECT * FROM plg_gs1 WHERE id =". $b) or die ("ocorreu um erro" . exit());
					// Pega as informações do BD 
					$array = mysqli_fetch_array($sql_query_all);
						// atribui valores à variáveis
						$varTudo = array(
							1 => $array["linkacesso"],
								2 => $array["login1"],
									3 => $array["login2"],
										4 => $array["login3"],
											5 => $array["categoria"],
												6 => $array["observacao"],
													7 => $array["titulo"]);
if($varTudo[5] == "ativo"){$s_a = "selected"; $s_i = "";} else {$s_i = "selected"; $s_a = "";};
	if(empty($varTudo[5])){$s_a = ""; $s_i = "";};
		echo $BotaoFecharJanelaSuspensa . ' 
			<h1>Visualizar registro</h1>
				<input name="titulo" type="text" id="titulo" value="' . $varTudo[7] . '" disabled/><br/>
					<textarea name="linkacesso" id="linkacesso"  disabled>' . $varTudo[1] . '</textarea><br/>
						<input name="login1" type="text" id="login1" value="' . $varTudo[2] . '" disabled/><br/>
							<input name="login2" type="text" id="login2" value="' . $varTudo[3] . '" disabled/><br/>
								<input name="login3" type="text" id="login3" value="' . $varTudo[4] . '" disabled/><br/>
									<select name="cat" id="cat" disabled>
										<option selected disabled hidden> -- Selecione o status -- </option>
											<option value="ativo" ' . $s_a . '> Ativo </option>
												<option value="inativo" ' . $s_i . '> Inativo </option>
													</select><br/>
														<textarea name="observacao" id="observacao" disabled>' . $varTudo[6] . '</textarea><br/>										
				';
		exit; };
	};
//#############################################################################################################
// Se existir dados recebidos via get url, e GET_['action'] ser igual a "edit", exibe o formulario para edição abaixo
	if($_SERVER["REQUEST_METHOD"] != "POST" && isset($_GET['id']) && isset($_GET['action'])){ // É necessário manter a função isset de verificar se existe primeiro a variável action
		if($_GET['action'] == base64_encode("edit")){ // Depois, aqui, verificamos se a variável action existente é igual à edit criptografada
			$b = base64_decode($_GET['id']); // Define a variável ID recebida
				$sql_query_all = mysqli_query($conectar, "SELECT * FROM plg_gs1 WHERE id =". $b) or die ("ocorreu um erro" . exit()); // Faz uma seleção no banco de dados com o ID recebido para edição
					$array = mysqli_fetch_array($sql_query_all); // Pega as informações do BD 
						$varTudo = array( // atribui valores à variáveis
										1 => $array["linkacesso"],
											2 => $array["login1"],
												3 => $array["login2"],
													4 => $array["login3"],
														5 => $array["categoria"],
															6 => $array["observacao"],
																7 => $array["titulo"]);
if($varTudo[5] == "ativo"){$s_a = "selected"; $s_i = "";} else {$s_i = "selected"; $s_a = "";};
	if(empty($varTudo[5])){$s_a = ""; $s_i = "";};
		echo $BotaoFecharJanelaSuspensa . ' 
				<h1>Editar registro</h1>
					<form action="" enctype="multipart/form-data" method="post">
						<input name="titulo" type="text" id="titulo" value="' . $varTudo[7] . '"/><br/>
							<textarea name="linkacesso" id="linkacesso">' . $varTudo[1] . '</textarea><br/>
								<input name="login1" type="text" id="login1" value="' . $varTudo[2] . '"/><br/>
									<input name="login2" type="text" id="login2" value="' . $varTudo[3] . '"/><br/>
										<input name="login3" type="text" id="login3" value="' . $varTudo[4] . '"/><br/>
											<select name="cat" id="cat">
												<option selected disabled hidden> -- Selecione o status -- </option>
													<option value="ativo" ' . $s_a . '> Ativo </option>
														<option value="inativo" ' . $s_i . '> Inativo </option>
															</select><br/>
																<textarea name="observacao" id="observacao">' . $varTudo[6] . '</textarea><br/>
																	<input name="funcao" type="hidden" id="funcao" value="editar"/>
																		<input name="id_registro" type="hidden" id="id_registro" value="' . $b . '"/>
																			<input name="salvardados" type="submit" id="salvardados" value="Salvar"/>
																				</form>
				';
		exit; };
	};
//#############################################################################################################
// Se existir dados recebidos via post, com hidden = editar, segue abaixo para tratar as informações recebidas do formulário
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['funcao'] == "editar"){ 
		$d = array(
			1 => $_POST['linkacesso'],
				2 => $_POST['login1'],
					3 => $_POST['login2'],
						4 => $_POST['login3'],
							5 => $_POST['cat'],
								6 => $_POST['observacao'],
									7 => $_POST['id_registro'],
										8 => $_POST['titulo'] );
$edita = "UPDATE `plg_gs1` SET titulo = '" . $d[8] . "', linkacesso = '" . $d[1] . "', login1 = '" . $d[2] . "', login2 = '" . $d[3] . "', login3 = '" . $d[4] . "', categoria = '" . $d[5] . "', observacao = '" . $d[6] . "' WHERE id =". $d[7];
	if(mysqli_query($conectar, $edita)) { echo '<script language="javascript">location.href="index.php";</script>';} else {echo "não foi atualizado.".mysqli_error($conectar);};	
		};
//#############################################################################################################
// Se existir dados recebidos via get url, e GET_['action'] ser igual a "delete", exibe o formulario para exclusão abaixo
if($_SERVER["REQUEST_METHOD"] != "POST" && isset($_GET['id']) && isset($_GET['action'])){ // É necessário manter a função isset de verificar se existe primeiro a variável action
	if($_GET['action'] == base64_encode("delete")){ // Depois, aqui, verificamos se a variável action existente é igual à delete criptografada
		// Define a variável ID recebida
			$b = base64_decode($_GET['id']);
				// Faz uma seleção no banco de dados
				$sql_query_all = mysqli_query($conectar, "SELECT * FROM plg_gs1 WHERE id =". $b) or die ("ocorreu um erro" . exit());
					// Pega as informações do BD 
					$array = mysqli_fetch_array($sql_query_all);
						// atribui valores à variáveis
						$varTudo = array(
							1 => $array["linkacesso"],
								2 => $array["login1"],
									3 => $array["login2"],
										4 => $array["login3"],
											5 => $array["categoria"],
												6 => $array["observacao"],
													7 => $array["titulo"]);
if($varTudo[5] == "ativo"){$s_a = "selected"; $s_i = "";} else {$s_i = "selected"; $s_a = "";};
	if(empty($varTudo[5])){$s_a = ""; $s_i = "";};
		echo $BotaoFecharJanelaSuspensa . ' 
			<h1>Excluir registro</h1>
				<form action="" enctype="multipart/form-data" method="post">
					<input name="titulo" type="text" id="titulo" value="' . $varTudo[7] . '" disabled/><br/>
						<textarea name="linkacesso" id="linkacesso"  disabled>' . $varTudo[1] . '</textarea><br/>
							<input name="login1" type="text" id="login1" value="' . $varTudo[2] . '" disabled/><br/>
								<input name="login2" type="text" id="login2" value="' . $varTudo[3] . '" disabled/><br/>
									<input name="login3" type="text" id="login3" value="' . $varTudo[4] . '" disabled/><br/>
										<select name="cat" id="cat" disabled>
											<option selected disabled hidden> -- Selecione o status -- </option>
												<option value="ativo" ' . $s_a . '> Ativo </option>
													<option value="inativo" ' . $s_i . '> Inativo </option>
														</select><br/>
															<textarea name="observacao" id="observacao" disabled>' . $varTudo[6] . '</textarea><br/>
																<input name="funcao" type="hidden" id="funcao" value="excluir"/>
																	<input name="id_registro" type="hidden" id="id_registro" value="' . $b . '"/>
																		<h2>Você tem certeza que deseja excluir?</h2>
																			<input name="salvardados" type="submit" id="salvardados" value="Sim"/>
																				</form>
		';
exit; };
};
//#############################################################################################################
// Se existir dados recebidos via post, com hidden = excluir, segue abaixo para tratar as informações recebidas do formulário
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['funcao'] == "excluir"){
	$exclui = "DELETE FROM `plg_gs1` WHERE id =". $_POST['id_registro'];
		if(mysqli_query($conectar, $exclui)) { echo '<script language="javascript">location.href="index.php";</script>';} else {echo "não foi excluído.".mysqli_error($conectar);};	
			};
//#############################################################################################################
// Exibe o formulario para inserção de dados abaixo
	if($_SERVER["REQUEST_METHOD"] != "POST" && isset($_GET['action'])){ // É necessário manter a função isset de verificar se existe primeiro a variável action
		if($_GET['action'] == base64_encode("add")){ // Depois, aqui, verificamos se a variável action existente é igual à add criptografada
		echo $BotaoFecharJanelaSuspensa .
		'<h1>Inserir registro</h1>
				<form action="" enctype="multipart/form-data" method="post">
					<input name="titulo" type="text" id="titulo" placeholder="Título" /><br/>
						<textarea name="linkacesso" id="linkacesso" placeholder="Link de acesso"></textarea><br/>
							<input name="login1" type="text" id="login1" placeholder="Login 1" /><br/>
								<input name="login2" type="text" id="login2" placeholder="Login 2"/><br/>
									<input name="login3" type="text" id="login3" placeholder="Login 3"/><br/>
										<select name="cat" id="cat">
											<option selected disabled hidden> -- Selecione o status -- </option>
												<option value="ativo"> Ativo </option>
													<option value="inativo"> Inativo </option>
														</select><br/>
															<textarea name="observacao" id="observacao" placeholder="Observações"></textarea><br/>
																<input name="funcao" type="hidden" id="funcao" value="inserir"/>
																	<input name="salvardados" type="submit" id="salvardados" value="Salvar"/>
																		</form>
																			';
																					exit; };
																						};
//#############################################################################################################
// Se existir dados recebidos via post, com hidden = inserir, segue abaixo para tratar as informações recebidas do formulário
	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['funcao'] == "inserir"){ 
		$c = array(
			1 => $_POST['linkacesso'],
				2 => $_POST['login1'],
					3 => $_POST['login2'],
						4 => $_POST['login3'],
							5 => $_POST['cat'],
								6 => $_POST['observacao'],
									7 => $_POST['titulo']);
$insere = "INSERT INTO plg_gs1 (titulo, linkacesso, login1, login2, login3, categoria, observacao) VALUES ('" . $c[7] . "','" . $c[1] . "','" . $c[2] . "','" . $c[3] . "','" . $c[4] . "','" . $c[5] . "','" . $c[6] . "')";
	if(mysqli_query($conectar, $insere)) { echo '<script language="javascript">location.href="index.php";</script>';} else {echo "não foi inserido.".mysqli_error($conectar);};
		};
//#############################################################################################################
// Se existir dados recebidos via get url, e GET_['action'] ser igual a "verificachavemestra", exibe o formulario abaixo
if($_SERVER["REQUEST_METHOD"] != "POST" && isset($_GET['action'])){ // É necessário manter a função isset de verificar se existe primeiro a variável action
	if($_GET['action'] == base64_encode("verificachavemestra")){ // Depois, aqui, verificamos se a variável action existente é igual à verificachavemestra criptografada
			echo $BotaoFecharJanelaSuspensa . '
				<h1>Verifique abaixo qual é a Chave Mestra</h1>
					<br/><h2>Sha1 - Criptografia de Mão Única</h2>
						<br/><h3>Chave Mestra 1</h3>
							<br/>Pergunta 1: Qual é a árvore que plantei quando era pequeno na casa em que houve enchente? (Com a primeira letra em maiúsculo, quando houver a letra E deve trocar pelo número 3, na letra final A deve trocar pelo arroba @)
								<br/><h3>Chave Mestra 2</h3>
									<br/>Pergunta 1: Qual é o nome do amigo de infância? (Somente as três primeiras letras, com a primeira letra em maiúsculo, quando houver a letra o deve trocar pelo número 0)
										<br/>Pergunta 2: Qual a data de aniversário do Igor? (Quando houver o número 1 deve trocar pelo ponto de exclamação)
										';
								exit; };
								};
//#############################################################################################################
// Se existir dados recebidos via get url, e GET_['action'] ser igual a "imprimirtudo", exibe o formulario abaixo
if($_SERVER["REQUEST_METHOD"] != "POST" && isset($_GET['action'])){ // É necessário manter a função isset de verificar se existe primeiro a variável action
	if($_GET['action'] == base64_encode("imprimirtudo")){ // Depois, aqui, verificamos se a variável action existente é igual à imprimirtudo criptografada
		$sqldefault = 'SELECT * FROM plg_gs1 ORDER BY id';
			$resultadodabusca = mysqli_query($conectar, $sqldefault);
				$cont_linhas = mysqli_num_rows($resultadodabusca);
					if ($cont_linhas > 0) {
						$html = '<div class="caixa_cabecalho_resultados"><h1>Resultados encontrados: ' . $cont_linhas . '</h1></div><br/>';
							while($CadaItem = mysqli_fetch_array($resultadodabusca)) {
								$a = array(
									1 => $CadaItem['id'],
										2 => $CadaItem['linkacesso'],
											3 => $CadaItem['login1'],
												4 => $CadaItem['login2'],
													5 => $CadaItem['login3'],
														6 => $CadaItem['categoria'],
															7 => $CadaItem['observacao'],
																8 => $CadaItem['titulo']);

// Abaixo definirá as cores do status de cada linha, dentro do WHILE
		if($a[6] == "ativo"){
			$s = "<span style='color:green;'>ATIVO</span>";
				} else {
					$s = "<span style='color:red;'>INATIVO</span>";}; if(empty($a[6])){$s = "";};	
																				
$html .= '
	<table width="90%">
		<tbody>
			<thead><th width="10%"></th><th></th></thead>
			<tr><td>Status:</td><td>' . $s . '</td></tr>
				<tr><td>ID:</td><td>' . $a[1] . '</td></tr>
					<tr><td>Título:</td><td>' . $a[8] . '</td></tr>
						<tr><td>Login1:</td><td>' . $a[3] . '</td></tr>
							<tr><td>Login2:</td><td>' . $a[4] . '</td></tr>
								<tr><td>Login3:</td><td>' . $a[5] . '</td></tr>
									<tr><td>Observação:</td><td>' . $a[7] . '</td></tr>
										</tbody></table><br/>';};
				}else{ $html = 'Não foram encontrados registros!'; exit;};

echo $html;
											exit; };
										};
//#############################################################################################################
?>
<script language="javascript">

// função para copiar as informações para a área de transferência do usuário
function copyToClipboard(element){
  var $temp = $("<input>");
	  $("body").append($temp);
	  $temp.val($(element).text()).select();
	  document.execCommand("copy");
	  $temp.remove();};
																	
// Função para criar um objeto XMLHTTPRequest, referente à caixa de pesquisa
 function CriaRequest() {
	try{request = new XMLHttpRequest();}
		catch (IEAtual){try{request = new ActiveXObject("Msxml2.XMLHTTP");}
			catch(IEAntigo){try{request = new ActiveXObject("Microsoft.XMLHTTP");}
				catch(falha){request = false;}}}
					if (!request) alert("Seu Navegador não suporta Ajax!"); else return request;}
 
 // Função para enviar os dados e solicitar a requisição que construirá a pesquisa que formará a tabela principal
 function getDados(){
	 
	// Declaração de Variáveis
	var termo   = document.getElementById("inputsearchterm").value;
	var campo   = document.getElementById("selectsearchfield").value;
	var status   = document.getElementById("checkboxsearchstatus").value;
	var result = document.getElementById("resultado");
	var xmlreq = CriaRequest();
	 
	// Exibi a imagem de progresso
     result.innerHTML = '<div class="caixa_cabecalho_resultados"><h1>Aguarde...</h1></div>';
	 
	// Iniciar uma requisição
	xmlreq.open("GET", "index.php?termo=" + termo + "&campo=" + campo + "&status=" + status, true);
     
	// Atribui uma função para ser executada sempre que houver uma mudança de ado
	xmlreq.onreadystatechange = function(){
         
	// Verifica se foi concluído com sucesso e a conexão fechada (readyState=4)
	if (xmlreq.readyState == 4) {
             
	// Verifica se o arquivo foi encontrado com sucesso
	if (xmlreq.status == 200) {
		result.innerHTML = xmlreq.responseText;
			}else{
				result.innerHTML = "Erro: " + xmlreq.statusText;
				}
			}
	};
	xmlreq.send(null);
 };

 //#############################################################################################################
 // Função para enviar os dados e solicitar a requisição que construirá a pesquisa que formará as Janelas Suspensas
 function JanelaSuspensa(id,action){
	 
	// Declaração de Variáveis
	var result = document.getElementById("DivJanela");
	var xmlreq = CriaRequest();
	 
	// Exibi a imagem de progresso
     result.innerHTML = '<div class="caixa_cabecalho_resultados"><h1>Aguarde...</h1></div>';
	 
	// Iniciar uma requisição
	xmlreq.open("GET", "index.php?id=" + id + "&action=" + action, true);
     
	// Atribui uma função para ser executada sempre que houver uma mudança de ado
	xmlreq.onreadystatechange = function(){
         
	// Verifica se foi concluído com sucesso e a conexão fechada (readyState=4)
	if (xmlreq.readyState == 4) {
             
	// Verifica se o arquivo foi encontrado com sucesso
	if (xmlreq.status == 200) {
		result.innerHTML = xmlreq.responseText;
			}else{
				result.innerHTML = "Erro: " + xmlreq.statusText;
				}
			}
	};
	xmlreq.send(null);
 };
  // Função para alternar entre ocultar e exibir as janelas suspensas
function AlternaVisibilidadeJanelaSuspensa(){
        $("#DivJanela").toggleClass("d-block"); // Altera a propriedade display do CSS entre none e block
    };
//#############################################################################################################
 
 
 // Função para exibir as opções da caixa de pesquisa
$(document).ready(function(){
    $("#btnopensearchbox").click(function(){
        $("#searchboxdiv").toggleClass("d-block"); // Altera a propriedade display do CSS entre none e block
    });
});

// A função abaixo insere o valor id de cada linha da tabela num campo input>hidden, para posteriormente repassar via GET URL a partir dos botões de ação no topo da tela
$(document).on('change', '[type="radio"]', function() {
    var currentlyValue = $(this).val(); // Retorna o valor do radio checado
	$("#armazena_id").val(currentlyValue); // Atualiza o campo hidden com o valor id do radio selecionado da linha da tabela
	
});

</script>
</head>
<body onload="getDados();">
<?php
//#############################################################################################################
// INICIA A RESPOSTA DA CAIXA DE PESQUISA, COM EXIT AO FINAL DE CADA FUNÇÃO
	if (isset($_GET["termo"])) { // Verifica se existe a variável GET URL 'termo' referente à caixa de pesquisa
		$termo = $_GET["termo"];
			$campo = $_GET["campo"];
				$status = $_GET["status"];				
					// Verifica se a variável está vazia
					if (empty($campo)){$campo = "titulo";};
						if (empty($termo)){$termo = "";};
							if (empty($status)){$status = "";};
								$sqldefault = "SELECT * FROM plg_gs1 WHERE " . $campo . " LIKE '%" . $termo . "%' " . $status . " ORDER BY id";
									$resultadodabusca = mysqli_query($conectar, $sqldefault);
										$cont_linhas = mysqli_num_rows($resultadodabusca);
// Verifica se a consulta retornou linhas: se sim, exibe a tabela abaixo
	if ($cont_linhas > 0) {
		// Atribui o código HTML para montar uma tabela: abaixo está o cabeçalho da tabela ainda fora do WHILE
			echo '<div class="caixa_cabecalho_resultados"><h1>Resultados encontrados: ' . $cont_linhas . '</h1></div><br/>
				<table id="tabela_principal">
					<thead><tr>
						<th scope="col">#</th>
							<th scope="col">ID</th>
								<th scope="col" style="min-width:50px">Status</th>
									<th scope="col" style="min-width:200px">Título</th>
										<th scope="col" style="min-width:200px">Login 1</th>
											<th scope="col" style="min-width:300px">Login 2</th>
												<th scope="col">Observações</th>
													</tr></thead>
														<tbody>';
				// Abaixo é criado o WHILE para montar cada linha da tabela									
					while($CadaItem = mysqli_fetch_array($resultadodabusca)) {
						$a = array(
							1 => $CadaItem['id'],
								2 => $CadaItem['linkacesso'],
									3 => $CadaItem['login1'],
										4 => $CadaItem['login2'],
											5 => $CadaItem['login3'],
												6 => $CadaItem['categoria'],
													7 => $CadaItem['observacao'],
														8 => $CadaItem['titulo']
															);
																// Abaixo definirá as cores do status de cada linha, dentro do WHILE
																	if($a[6] == "ativo"){
																		$s = "<span style='color:green;'>ATIVO</span>";
																			} else {
																				$s = "<span style='color:red;'>INATIVO</span>";}; if(empty($a[6])){$s = "";};
// Abaixo montará as linhas da tabela, dentro do WHILE
	echo '
		<tr>
			<td> <input type="radio" name="acao_a_executar" id="acao_a_executar" value="' . base64_encode($a[1]) . '"> </td>
				<td>' . $a[1] . '</td>
					<td>' . $s . '</td>
						<td><a href="' . $a[2] . '" target="_blank">' . $a[8] . '</a></td>
						<td><a href="javascript:void(0);" onclick="copyToClipboard(&quot;#login1' . $a[1] . '&quot;)"><div id="login1' . $a[1] . '">' . $a[3] . '</div></a></td>
						<td><a href="javascript:void(0);" onclick="copyToClipboard(&quot;#login2' . $a[1] . '&quot;)"><div id="login2' . $a[1] . '">' . $a[4] . '</div></a></td>
						<td>' . $a[7] . '</td>							
							</tr>';};
	echo '</tbody> </table>'; // Aqui fecha a tabela, fora do WHILE
		exit; // Aqui fecha a função que exibe resultados se a consulta retornar ao menos uma linha para montar a tabela
			} else {
				echo "Não foram encontrados registros!"; exit;};
				};
					// AQUI TERMINA O IF DA RESPOSTA DA CAIXA DE PESQUISA
//#############################################################################################################
?>
<!-- COMEÇA O CABEÇALHO -->		
	<div class="caixa_menu_topo">
		<input type="text" placeholder="Pesquisar (padrão: no campo título)" aria-label="Search" id="inputsearchterm" onchange="getDados();" style="width:200px; margin:0">
			<button class="input_bt"  type="button" id="btnopensearchbox">Opções de pesquisa</button>
				<button class="input_bt" type="button" onclick="getDados();"> Pesquisar </button>
					<button class="input_bt" type="button" onclick="javascript:document.getElementById('inputsearchterm').value='';getDados();"> Resetar</button>
						<button class="input_bt" type="button" onclick="AlternaVisibilidadeJanelaSuspensa();JanelaSuspensa(&quot;0&quot;,&quot;<?php echo base64_encode('add'); ?>&quot;);">Inserir novo </button>
							<input type="hidden" id="armazena_id">
								<button class="input_bt" type="button" onclick="javascript:AlternaVisibilidadeJanelaSuspensa();JanelaSuspensa(document.getElementById('armazena_id').value,&quot;<?php echo base64_encode('delete'); ?>&quot;);">Excluir </button>
									<button class="input_bt" type="button" onclick="javascript:AlternaVisibilidadeJanelaSuspensa();JanelaSuspensa(document.getElementById('armazena_id').value,&quot;<?php echo base64_encode('edit'); ?>&quot;);">Editar </button>
										<button class="input_bt" type="button" onclick="javascript:AlternaVisibilidadeJanelaSuspensa();JanelaSuspensa(document.getElementById('armazena_id').value,&quot;<?php echo base64_encode('view'); ?>&quot;);"> Exibir </button>
											<button class="input_bt" type="button" id="descer">Rodapé</button>
												<button class="input_bt" type="button" id="subir">Topo</button>	
													<button class="input_bt" type="button" id="chavemestra" onclick="javascript:AlternaVisibilidadeJanelaSuspensa();JanelaSuspensa(&quot;0&quot;,&quot;<?php echo base64_encode('verificachavemestra'); ?>&quot;);">Chave Mestra</button>
														<a href="index.php?action=<?php echo base64_encode("imprimirtudo");?>" target="_blank"><button class="input_bt" type="button" id="imprimetabela">Imprimir</button></a>
															</div>
																<!-- TERMINA O CABEÇALHO -->															
<!-- COMEÇA A CAIXA DE OPÇÕES DE PESQUISA -->
	<div id="searchboxdiv">
		<label for="selectsearchfield">Coluna:</label>
			<select id="selectsearchfield">
				<option value="titulo" selected>Título</option>
					<option value="login1" >Login 1</option>
						<option value="login2" >Login 2</option>
							<option value="login3" >Login 3</option>
								</select>
									<label for="checkboxsearchstatus">Status:</label>
										<select id="checkboxsearchstatus">
											<option value="" selected>Qualquer status</option>
												<option value="AND categoria='ativo'" >Ativo</option>
													<option value="AND categoria='inativo'" >Inativo</option>
														<option value="AND categoria=''" >Sem status</option>
															</select>
																</div>
																	<!-- TERMINA A CAIXA DE OPÇÕES DE PESQUISA -->
			<div id="DivJanela">
			</div>
<!-- Começa o conteúdo principal -->
	<div id="resultado"></div>
<!-- Termina o conteúdo principal -->

<div id="footer"></div>

			<script>
			$("#subir").on("click",function(){$('html, body').animate({scrollTop:0}, 'slow');});
			$("#descer").on("click",function(){$('html, body').animate({scrollTop:$('html, body')[0].scrollHeight}, 'slow');});
			</script>
</body>
</html>