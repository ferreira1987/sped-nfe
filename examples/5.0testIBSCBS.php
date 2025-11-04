<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\NFe\Make;

/**
 * Este exemplo demonstra o uso das novas tags IBS/CBS (Reforma Tributária)
 * IBS - Imposto sobre Bens e Serviços
 * CBS - Contribuição sobre Bens e Serviços
 */

try {
    // Instancia a classe Make
    $nfe = new Make();

    // CONFIGURAÇÃO DA NFe (IDE)
    $std = new stdClass();
    $std->versao = '4.00';
    $std->Id = null;
    $std->pk_nItem = null;
    $nfe->taginfNFe($std);

    $std = new stdClass();
    $std->cUF = 52;
    $std->cNF = '34767223';
    $std->natOp = 'VENDA DE MERCADORIA';
    $std->mod = 55;
    $std->serie = 9;
    $std->nNF = 9;
    $std->dhEmi = '2025-10-09T12:54:07-03:00';
    $std->dhSaiEnt = '2025-10-09T12:54:07-03:00';
    $std->tpNF = 1;
    $std->idDest = 1;
    $std->cMunFG = 5201405;
    $std->tpImp = 1;
    $std->tpEmis = 1;
    $std->cDV = 2;
    $std->tpAmb = 2;
    $std->finNFe = 1;
    $std->indFinal = 0;
    $std->indPres = 9;
    $std->indIntermed = 0;
    $std->procEmi = 0;
    $std->verProc = 'Teste 1.0';
    $nfe->tagide($std);

    // EMITENTE
    $std = new stdClass();
    $std->xNome = 'EMPRESA TESTE LTDA';
    $std->xFant = 'EMPRESA TESTE';
    $std->IE = '123456789';
    $std->CRT = 3;
    $std->CNPJ = '11761463000192';
    $nfe->tagemit($std);

    $std = new stdClass();
    $std->xLgr = 'Rua Teste';
    $std->nro = '100';
    $std->xBairro = 'Centro';
    $std->cMun = 5201405;
    $std->xMun = 'Aparecida de Goiania';
    $std->UF = 'GO';
    $std->CEP = '74993080';
    $std->cPais = 1058;
    $std->xPais = 'Brasil';
    $nfe->tagenderEmit($std);

    // DESTINATÁRIO
    $std = new stdClass();
    $std->xNome = 'CLIENTE TESTE';
    $std->indIEDest = 1;
    $std->IE = '109438566';
    $std->CNPJ = '46968997000179';
    $nfe->tagdest($std);

    $std = new stdClass();
    $std->xLgr = 'Rua Cliente';
    $std->nro = '200';
    $std->xBairro = 'Jardim';
    $std->cMun = 5201405;
    $std->xMun = 'Aparecida de Goiania';
    $std->UF = 'GO';
    $std->CEP = '74993080';
    $std->cPais = 1058;
    $std->xPais = 'Brasil';
    $nfe->tagenderDest($std);

    // PRODUTO 1
    $std = new stdClass();
    $std->item = 1;
    $std->cProd = '1434';
    $std->cEAN = '7898466604305';
    $std->xProd = 'PRODUTO TESTE';
    $std->NCM = '33059000';
    $std->cBenef = 'GO821005';
    $std->CFOP = '5101';
    $std->uCom = 'UN';
    $std->qCom = 192.0000;
    $std->vUnCom = 3.200000;
    $std->vProd = 614.40;
    $std->cEANTrib = '7898466604305';
    $std->uTrib = 'UN';
    $std->qTrib = 192.0000;
    $std->vUnTrib = 3.200000;
    $std->indTot = 1;
    $nfe->tagprod($std);

    // IMPOSTOS
    $std = new stdClass();
    $std->item = 1;
    $std->vTotTrib = 234.08;
    $nfe->tagimposto($std);

    // ICMS
    $std = new stdClass();
    $std->item = 1;
    $std->orig = 0;
    $std->CST = '20';
    $std->modBC = 0;
    $std->pRedBC = 52.39;
    $std->vBC = 292.52;
    $std->pICMS = 21.0000;
    $std->vICMS = 61.43;
    $nfe->tagICMS($std);

    // IPI
    $std = new stdClass();
    $std->item = 1;
    $std->cEnq = '999';
    $std->CST = '50';
    $std->vBC = 614.40;
    $std->pIPI = 4.55;
    $std->vIPI = 27.96;
    $nfe->tagIPI($std);

    // PIS
    $std = new stdClass();
    $std->item = 1;
    $std->CST = '02';
    $std->vBC = 552.97;
    $std->pPIS = 2.20;
    $std->vPIS = 12.17;
    $nfe->tagPIS($std);

    // COFINS
    $std = new stdClass();
    $std->item = 1;
    $std->CST = '02';
    $std->vBC = 552.97;
    $std->pCOFINS = 10.30;
    $std->vCOFINS = 56.96;
    $nfe->tagCOFINS($std);

    // ===== NOVO: IBS/CBS (REFORMA TRIBUTÁRIA) =====
    $std = new stdClass();
    $std->item = 1;
    $std->CST = '000';
    $std->cClassTrib = '000001';
    $std->vBC = 483.84;
    $std->pIBSUF = 0.1000;
    $std->vIBSUF = 0.48;
    $std->pIBSMun = 0.0000;
    $std->vIBSMun = 0.00;
    $std->vIBS = 0.48;
    $std->pCBS = 0.9000;
    $std->vCBS = 4.35;
    $nfe->tagIBSCBS($std);
    // ===== FIM IBS/CBS =====

    // TRANSPORTE
    $std = new stdClass();
    $std->modFrete = 0;
    $nfe->tagtransp($std);

    // PAGAMENTO
    $std = new stdClass();
    $std->vTroco = null;
    $nfe->tagpag($std);

    $std = new stdClass();
    $std->tPag = '01';
    $std->vPag = 642.36;
    $nfe->tagdetPag($std);

    // INFORMAÇÕES ADICIONAIS
    $std = new stdClass();
    $std->infCpl = 'Total aproximado de tributos conforme IBPT';
    $nfe->taginfAdic($std);

    // TOTALIZADOR OPCIONAL IBS/CBS
    // Você pode passar um stdClass com valores específicos ou deixar null
    // para usar os valores totalizados automaticamente
    // Se houver tags IBSCBS nos itens, o total será gerado automaticamente

    // MONTA O XML
    $xml = $nfe->monta();

    // Exibe o XML gerado
    header('Content-Type: application/xml; charset=utf-8');
    echo $xml;
} catch (\Exception $e) {
    echo "ERRO: " . $e->getMessage();
    echo "\n" . $e->getTraceAsString();
}
