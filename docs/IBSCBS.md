# IBS/CBS - Reforma Tributária

## Introdução

A partir da **Reforma Tributária Brasileira**, foram introduzidos novos impostos que substituirão gradualmente os impostos atuais:

-   **IBS** (Imposto sobre Bens e Serviços): substitui ICMS e ISS
-   **CBS** (Contribuição sobre Bens e Serviços): substitui PIS e COFINS

Este documento explica como usar as novas tags IBS/CBS na geração de NFe 4.0.

## Estrutura XML

### Nível do Item (det/imposto/IBSCBS)

```xml
<IBSCBS>
    <CST>000</CST>
    <cClassTrib>000001</cClassTrib>
    <gIBSCBS>
        <vBC>483.84</vBC>
        <gIBSUF>
            <pIBSUF>0.1000</pIBSUF>
            <vIBSUF>0.48</vIBSUF>
        </gIBSUF>
        <gIBSMun>
            <pIBSMun>0.0000</pIBSMun>
            <vIBSMun>0.00</vIBSMun>
        </gIBSMun>
        <vIBS>0.48</vIBS>
        <gCBS>
            <pCBS>0.9000</pCBS>
            <vCBS>4.35</vCBS>
        </gCBS>
    </gIBSCBS>
</IBSCBS>
```

### Nível do Total (total/IBSCBSTot)

```xml
<IBSCBSTot>
    <vBCIBSCBS>483.84</vBCIBSCBS>
    <gIBS>
        <gIBSUF>
            <vDif>0.00</vDif>
            <vDevTrib>0.00</vDevTrib>
            <vIBSUF>0.48</vIBSUF>
        </gIBSUF>
        <gIBSMun>
            <vDif>0.00</vDif>
            <vDevTrib>0.00</vDevTrib>
            <vIBSMun>0.00</vIBSMun>
        </gIBSMun>
        <vIBS>0.48</vIBS>
        <vCredPres>0.00</vCredPres>
        <vCredPresCondSus>0.00</vCredPresCondSus>
    </gIBS>
    <gCBS>
        <vDif>0.00</vDif>
        <vDevTrib>0.00</vDevTrib>
        <vCBS>4.35</vCBS>
        <vCredPres>0.00</vCredPres>
        <vCredPresCondSus>0.00</vCredPresCondSus>
    </gCBS>
    <gMono>
        <vIBSMono>0.00</vIBSMono>
        <vCBSMono>0.00</vCBSMono>
        <vIBSMonoReten>0.00</vIBSMonoReten>
        <vCBSMonoReten>0.00</vCBSMonoReten>
        <vIBSMonoRet>0.00</vIBSMonoRet>
        <vCBSMonoRet>0.00</vCBSMonoRet>
    </gMono>
</IBSCBSTot>
```

## Como Usar

### 1. Adicionar IBSCBS no Item

```php
use NFePHP\NFe\Make;

$nfe = new Make();

// ... configuração da NFe, emitente, destinatário, produto ...

// Adicionar IBS/CBS para o item 1
$std = new stdClass();
$std->item = 1; // Número do item (obrigatório)
$std->CST = '000'; // Código de Situação Tributária (obrigatório)
$std->cClassTrib = '000001'; // Código da Classificação Tributária (obrigatório)
$std->vBC = 483.84; // Base de cálculo (obrigatório)
$std->pIBSUF = 0.1000; // Alíquota IBS UF (obrigatório)
$std->vIBSUF = 0.48; // Valor IBS UF (obrigatório)
$std->pIBSMun = 0.0000; // Alíquota IBS Municipal (obrigatório)
$std->vIBSMun = 0.00; // Valor IBS Municipal (obrigatório)
$std->vIBS = 0.48; // Valor total IBS (obrigatório)
$std->pCBS = 0.9000; // Alíquota CBS (obrigatório)
$std->vCBS = 4.35; // Valor CBS (obrigatório)

$nfe->tagIBSCBS($std);
```

### 2. Totalização Automática

A biblioteca totaliza automaticamente os valores de IBS/CBS de todos os itens. Quando você chama o método `monta()`, se houver pelo menos um item com IBSCBS, a tag `IBSCBSTot` será gerada automaticamente no total.

```php
// Os valores são totalizados automaticamente
$xml = $nfe->monta();
```

### 3. Totalização Manual (Opcional)

Se você precisar sobrescrever os valores totalizados automaticamente ou adicionar valores adicionais (como créditos presumidos, valores monofásicos, etc.), pode chamar explicitamente:

```php
$std = new stdClass();
$std->vBCIBSCBS = 483.84; // Se não informado, usa o valor totalizado
$std->vIBSUF = 0.48; // Se não informado, usa o valor totalizado
$std->vIBSMun = 0.00; // Se não informado, usa o valor totalizado
$std->vIBS = 0.48; // Se não informado, usa o valor totalizado
$std->vCBS = 4.35; // Se não informado, usa o valor totalizado

// Valores adicionais (opcional)
$std->vDifIBSUF = 0.00; // Diferença IBS UF
$std->vDevTribIBSUF = 0.00; // Devolução tributária IBS UF
$std->vDifIBSMun = 0.00; // Diferença IBS Municipal
$std->vDevTribIBSMun = 0.00; // Devolução tributária IBS Municipal
$std->vCredPresIBS = 0.00; // Crédito presumido IBS
$std->vCredPresCondSusIBS = 0.00; // Crédito presumido condição suspensiva IBS
$std->vDifCBS = 0.00; // Diferença CBS
$std->vDevTribCBS = 0.00; // Devolução tributária CBS
$std->vCredPresCBS = 0.00; // Crédito presumido CBS
$std->vCredPresCondSusCBS = 0.00; // Crédito presumido condição suspensiva CBS
$std->vIBSMono = 0.00; // IBS Monofásico
$std->vCBSMono = 0.00; // CBS Monofásico
$std->vIBSMonoReten = 0.00; // IBS Monofásico Retido
$std->vCBSMonoReten = 0.00; // CBS Monofásico Retido
$std->vIBSMonoRet = 0.00; // IBS Monofásico Retorno
$std->vCBSMonoRet = 0.00; // CBS Monofásico Retorno

$nfe->tagIBSCBSTot($std);
```

## Campos

### tagIBSCBS (Item)

| Campo        | Tipo   | Descrição                                  | Obrigatório |
| ------------ | ------ | ------------------------------------------ | ----------- |
| `item`       | int    | Número do item                             | Sim         |
| `CST`        | string | Código de Situação Tributária              | Sim         |
| `cClassTrib` | string | Código da Classificação Tributária         | Sim         |
| `vBC`        | float  | Valor da Base de Cálculo                   | Sim         |
| `pIBSUF`     | float  | Alíquota do IBS Estadual (até 4 decimais)  | Sim         |
| `vIBSUF`     | float  | Valor do IBS Estadual                      | Sim         |
| `pIBSMun`    | float  | Alíquota do IBS Municipal (até 4 decimais) | Sim         |
| `vIBSMun`    | float  | Valor do IBS Municipal                     | Sim         |
| `vIBS`       | float  | Valor Total do IBS (UF + Mun)              | Sim         |
| `pCBS`       | float  | Alíquota da CBS (até 4 decimais)           | Sim         |
| `vCBS`       | float  | Valor da CBS                               | Sim         |

### tagIBSCBSTot (Total)

Todos os campos são opcionais. Se não informados, os valores totalizados dos itens serão usados.

| Campo                 | Tipo  | Descrição                                 |
| --------------------- | ----- | ----------------------------------------- |
| `vBCIBSCBS`           | float | Base de Cálculo Total                     |
| `vIBSUF`              | float | Valor Total IBS Estadual                  |
| `vIBSMun`             | float | Valor Total IBS Municipal                 |
| `vIBS`                | float | Valor Total IBS                           |
| `vCBS`                | float | Valor Total CBS                           |
| `vDifIBSUF`           | float | Diferença IBS UF                          |
| `vDevTribIBSUF`       | float | Devolução Tributária IBS UF               |
| `vDifIBSMun`          | float | Diferença IBS Municipal                   |
| `vDevTribIBSMun`      | float | Devolução Tributária IBS Municipal        |
| `vCredPresIBS`        | float | Crédito Presumido IBS                     |
| `vCredPresCondSusIBS` | float | Crédito Presumido Condição Suspensiva IBS |
| `vDifCBS`             | float | Diferença CBS                             |
| `vDevTribCBS`         | float | Devolução Tributária CBS                  |
| `vCredPresCBS`        | float | Crédito Presumido CBS                     |
| `vCredPresCondSusCBS` | float | Crédito Presumido Condição Suspensiva CBS |
| `vIBSMono`            | float | IBS Monofásico                            |
| `vCBSMono`            | float | CBS Monofásico                            |
| `vIBSMonoReten`       | float | IBS Monofásico Retido                     |
| `vCBSMonoReten`       | float | CBS Monofásico Retido                     |
| `vIBSMonoRet`         | float | IBS Monofásico Retorno                    |
| `vCBSMonoRet`         | float | CBS Monofásico Retorno                    |

## Exemplo Completo

Veja o arquivo `/examples/5.0testIBSCBS.php` para um exemplo completo de uso.

## Notas

1. As tags IBS/CBS são **opcionais** e só serão incluídas no XML se você adicioná-las explicitamente
2. A totalização é **automática** - os valores dos itens são somados automaticamente
3. Se nenhum item tiver IBSCBS, a tag `IBSCBSTot` não será gerada no total
4. Os valores são formatados automaticamente com 2 casas decimais (valores monetários) ou 4 casas decimais (alíquotas)
5. A posição da tag IBSCBS no XML segue a ordem: ICMS, IPI, II, ISSQN, PIS, PISST, COFINS, COFINSST, **IBSCBS**, ICMSUFDest

## Referências

-   Reforma Tributária Brasileira (EC 132/2023)
-   Manual de Orientação do Contribuinte NFe 4.0
-   Nota Técnica sobre IBS/CBS (quando disponibilizada pela SEFAZ)
