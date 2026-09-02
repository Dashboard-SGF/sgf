# Documento de Especificação de Requisitos (SGF - Módulo 01) V2.1

**Projeto:** Sistema de Gestão e Fiscalização de Obras (SGF)  
**Módulo:** 01 — Suprimentos e Gestão Financeira Global  
**Status:** Consolidado (Unidade 1 - Agile Frontend & Importação Dinâmica)  

---

## 1. Visão Geral e Escopo do Sistema
O Módulo 01 (Suprimentos e Financeiro) fornece visibilidade em tempo real sobre o orçamento global da obra, pedidos de compra de insumos, análise de risco orçamentário por impacto financeiro (Curva ABC) e gestão de ocorrências no canteiro de obras[cite: 5]. O diferencial técnico da solução é a capacidade de **importação e processamento dinâmico de planilhas Excel** via pacote Maatwebsite (Laravel-Excel) para recálculo instantâneo de todos os indicadores do painel[cite: 4, 5].

---

## 2. Requisitos Funcionais (RF)

| ID | Nome do Requisito | Descrição | Prioridade |
| :-: | :--- | :--- | :-: |
| **RF-01** | Cards Indicadores (KPIs) | Exibir indicadores no topo do painel: Total Gasto, Saldo Remanescente e Contagem de Pedidos por Status (Pendente, Aprovado, Entregue, Com Divergência)[cite: 5]. | **Alta** |
| **RF-02** | Curva ABC Interativa | Apresentar gráfico da Curva ABC categorizando os materiais por impacto no orçamento global da obra[cite: 5]. | **Alta** |
| **RF-03** | Listagem de Pedidos de Insumos | Exibir tabela detalhada dos últimos pedidos (Código, Fornecedor, Data, Valor Total e Badge de Status)[cite: 5]. | **Alta** |
| **RF-04** | Filtros Globais e Busca | Permite selecionar a obra (ou visão consolidada), filtrar por período e buscar insumos/pedidos por texto[cite: 5]. | **Média** |
| **RF-05** | Central de Ocorrências e Divergências | Notificações de insumos entregues com avaria, especificações incorretas ou entregas parciais[cite: 5]. | **Média** |
| **RF-06** | Importação Dinâmica de Excel | Upload de planilhas `.xlsx`/`.xls` via modal. O backend processa o arquivo via Maatwebsite e atualiza instantaneamente os KPIs, Curva ABC e tabelas[cite: 4, 5]. | **Alta** *(Diferencial)* |
| **RF-07** | Exportação de Resumo Executivo | Permitir o download/impressão em PDF do resumo analítico do dashboard para reuniões de fiscalização. | **Baixa** |

---

## 3. Requisitos Não-Funcionais (RNF)

| ID | Categoria | Descrição do Requisito | Critério de Aceite |
| :-: | :--- | :--- | :--- |
| **RNF-01** | Usabilidade / Mobile-First | Interface responsiva adaptada a celulares e tablets de campo, com ações rápidas acessíveis no canteiro. | Layout fluído e sem quebras visuais de 360px a 1920px. |
| **RNF-02** | Desempenho de Importação | O parse do Excel via Maatwebsite e atualização dos gráficos deve ser ágil[cite: 4]. | Tempo de resposta do upload e parse inferior a 3.0s para até 5.000 linhas[cite: 4]. |
| **RNF-03** | Padrão Monetário | Formatação padrão da moeda corrente brasileira (BRL - R$) em todos os indicadores[cite: 4]. | Exibição obrigatória no formato `R$ X.XXX,XX`[cite: 4]. |
| **RNF-04** | Arquitetura Híbrida | Suporte ao Plano A (Servidor Dinâmico PaaS) e Plano B (Exportação Estática para Contingência)[cite: 3, 4]. | Projeto compilável via `laravel-export` para o GitHub/Cloudflare Pages[cite: 3, 4]. |
| **RNF-05** | Disponibilidade e CI/CD | Pipeline de implantação automatizada acionada a cada entrega na branch principal[cite: 3, 4]. | Deploy contínuo configurado via Render.com / GitHub Actions[cite: 3, 4]. |

---

## 4. Regras de Negócio (RN)

* **RN-01 (Classificação Completa da Curva ABC):** A Curva ABC deve ordenar os insumos em ordem decrescente de custo total, aplicando as faixas acumuladas[cite: 5]:
  * **Classe A:** Acumulado de **0% a 80%** do custo (Alto impacto)[cite: 5].
  * **Classe B:** Acumulado de **80% a 95%** do custo (Médio impacto)[cite: 5].
  * **Classe C:** Acumulado de **95% a 100%** do custo (Baixo impacto)[cite: 5].
* **RN-02 (Validação de Cabeçalho da Planilha):** O importador deve validar a existência das colunas obrigatórias (`codigo_pedido`, `fornecedor`, `item`, `valor_unitario`, `quantidade`, `status`). Se incompatível, exibir alerta e abortar o parse[cite: 4, 5].
* **RN-03 (Alerta Visual de Estouro Orçamentário):** Se o total gasto ultrapassar o orçamento limite aprovado, o KPI de saldo e os cards correspondentes devem ser destacados visualmente na cor vermelha (`--danger`)[cite: 5].
* **RN-04 (Fallback Estático no Plano B):** Em ambiente estático de contingência (GitHub Pages), caso o upload de arquivos esteja indisponível, o sistema exibirá uma massa de dados pré-carregada para demonstração da interface[cite: 4].
