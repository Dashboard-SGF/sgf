# 🏗️ SGF — Sistema de Gestão e Fiscalização de Obras

[![Deploy SGF to GitHub Pages](https://github.com/Dashboard-SGF/sgf/actions/workflows/deploy-pages.yml/badge.svg)](https://github.com/Dashboard-SGF/sgf/actions/workflows/deploy-pages.yml)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat\&logo=laravel)](https://laravel.com/)
[![GitHub Pages](https://img.shields.io/badge/Hospedado_no-GitHub_Pages-222222?style=flat\&logo=github)](https://dashboard-sgf.github.io/sgf/)

O **SGF** é um painel web intuitivo e responsivo desenvolvido para acompanhamento, gestão orçamentária e fiscalização física/financeira de obras públicas e privadas.

A aplicação realiza a análise consolidada da **Curva ABC**, detalhamento da composição de custos dos serviços e acompanhamento de registros de ocorrências da obra.

📍 **Acesse a demonstração estática ao vivo:**
https://dashboard-sgf.github.io/sgf/

---

## 📌 Contexto Acadêmico

Este projeto foi desenvolvido como requisito prático da disciplina de **Processos de Software**, ministrada pelo **Prof. Me. Eng. Rafael Bispo**, no curso de **Análise e Desenvolvimento de Sistemas** da **Universidade Católica do Salvador (UCSal)**.

O projeto teve como objetivo desenvolver uma solução baseada em um cenário real de gestão e fiscalização de obras, utilizando um dataset orçamentário fornecido no contexto da disciplina.

* **Disciplina:** Processos de Software
* **Professor:** [Rafael Bispo](https://github.com/eletricageoprocessamento)
* **Instituição:** [Universidade Católica do Salvador (UCSal)](https://github.com/ucsal)

---

## ⚠️ Observações Importantes sobre o Ambiente de Demonstração

### GitHub Pages

A versão disponível no **GitHub Pages** é uma exportação **estática** da aplicação, realizada por meio do `spatie/laravel-export`.

A demonstração online permite explorar a interface e consultar os dados previamente carregados, incluindo:

* Dashboard;
* Indicadores de desempenho;
* Curva ABC;
* Serviços;
* Informações da obra;
* Histórico de ocorrências.

Por se tratar de uma versão estática hospedada no GitHub Pages, funcionalidades que dependem de processamento no servidor ou persistência de dados em tempo real, como alterações, gravações e upload de novas planilhas, não estão disponíveis nessa versão.

### Aplicação completa

Para utilizar a aplicação em seu ambiente completo, com Laravel, banco de dados e funcionalidades dinâmicas, é necessário executar o projeto localmente seguindo as instruções de instalação abaixo.

### Formato da planilha

A arquitetura de importação dos dados foi desenvolvida considerando especificamente a estrutura da planilha oficial utilizada no projeto.

O arquivo `DATASET_13.xlsx`, localizado em `database/seeders/files/`, contém a estrutura de dados utilizada pelo `DatabaseSeeder` para popular o banco de dados durante a configuração da aplicação.

Portanto, a importação via Seeder foi projetada para trabalhar com o **formato e as colunas definidos no dataset oficial do projeto**, não sendo uma importação genérica de qualquer planilha Excel.

---

## 🚀 Funcionalidades Principais

* **Hub de Obras:** Visão geral e seleção do contrato/obra ativa (Obra padrão `44444B`).
* **Dashboard Consolidado:** Indicadores-chave de desempenho (KPIs), totais previstos x executados e avanço físico.
* **Curva ABC de Insumos:** Classificação automática dos insumos em faixas **A** (até 80%), **B** (até 95%) e **C** (restante), com suporte visual e gráfico.
* **Detalhamento de Serviços:** Listagem paginada e busca rápida por código ou descrição de serviços de engenharia.
* **Histórico de Ocorrências:** Registro e acompanhamento de imprevistos e diário de obra.

---

## 🛠️ Arquitetura & Tecnologias

* **Framework Backend:** Laravel 11 (PHP 8.2)
* **Banco de Dados:** SQLite
* **Alimentação do Banco:** `DatabaseSeeder` utilizando o dataset oficial `DATASET_13.xlsx`
* **Exportação Estática:** `spatie/laravel-export`
* **Automação & CI/CD:** GitHub Actions
* **Hospedagem da Demonstração:** GitHub Pages

O processo de CI/CD realiza automaticamente o build da aplicação, execução dos seeds, exportação dos ativos estáticos e reescrita dinâmica dos caminhos para adequação à subpasta `/sgf/` utilizada pelo GitHub Pages.

---

## 💻 Como Rodar o Projeto Localmente

### Pré-requisitos

* PHP 8.2 ou superior
* Composer
* Laravel

### Passo a Passo

#### 1. Clonar o repositório

```bash
git clone https://github.com/Dashboard-SGF/sgf.git
cd sgf
```

#### 2. Instalar as dependências

```bash
composer install
```

#### 3. Configurar o ambiente e o banco de dados

Copie o arquivo de configuração do ambiente, crie o banco SQLite e gere a chave da aplicação:

```bash
cp .env.example .env
touch database/database.sqlite
php artisan key:generate
```

#### 4. Executar as migrations e popular o banco

Execute as migrations e o `DatabaseSeeder` para criar as tabelas e carregar os dados do dataset oficial:

```bash
php artisan migrate:fresh --seed
```

#### 5. Iniciar o servidor local

```bash
php artisan serve
```

A aplicação estará disponível em:

`http://127.0.0.1:8000`

---

## 📂 Estrutura de Dados

O projeto utiliza o **SQLite** como banco de dados local.

Durante a execução do Seeder, o arquivo oficial `DATASET_13.xlsx` é processado e utilizado para alimentar as tabelas necessárias para o funcionamento do sistema.

Isso permite que qualquer pessoa que clone o projeto consiga reproduzir o ambiente de dados utilizado na aplicação sem depender de um banco de dados externo.

---

## 🔄 CI/CD

O projeto utiliza **GitHub Actions** para automatizar o processo de publicação da demonstração.

O fluxo realiza, de forma automatizada:

1. Configuração do ambiente PHP;
2. Instalação das dependências do Composer;
3. Configuração do banco SQLite;
4. Execução das migrations;
5. Alimentação do banco por meio do Seeder;
6. Exportação da aplicação Laravel para arquivos estáticos;
7. Ajuste dos caminhos dos assets para a subpasta `/sgf/`;
8. Publicação da aplicação no GitHub Pages.

O status do processo pode ser acompanhado pelo badge de deploy no início deste documento.

---

## 🌐 Demonstração

**Versão estática:**
https://dashboard-sgf.github.io/sgf/

**Repositório:**
https://github.com/Dashboard-SGF/sgf

---

## 🤝 Colaboradores

* **Cauã Lopes** — [@Caualopesrlp](https://github.com/Caualopesrlp)
* **Ryan Abade** — [@ryanoliv11](https://github.com/ryanoliv11)

---

## 🎓 Instituição e Referências Acadêmicas

**Universidade Católica do Salvador (UCSal)**
https://github.com/ucsal

**Prof. Me. Eng. Rafael Bispo**
https://github.com/eletricageoprocessamento
