# 🏗️ SGF — Sistema de Gestão e Fiscalização de Obras

[![Deploy SGF to GitHub Pages](https://github.com/Dashboard-SGF/sgf/actions/workflows/deploy-pages.yml/badge.svg)](https://github.com/Dashboard-SGF/sgf/actions/workflows/deploy-pages.yml)
[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel)](https://laravel.com/)
[![GitHub Pages](https://img.shields.io/badge/Hospedado_no-GitHub_Pages-222222?style=flat&logo=github)](https://dashboard-sgf.github.io/sgf/)

O **SGF** é um painel web intuitivo e responsivo desenvolvido para acompanhamento, gestão orçamentária e fiscalização física/financeira de obras públicas e privadas. 

A aplicação realiza a análise consolidada da **Curva ABC**, detalhamento da composição de custos dos serviços e acompanhamento de registros de ocorrências da obra.

📍 **Acesse a versão ao vivo:** [https://dashboard-sgf.github.io/sgf/](https://dashboard-sgf.github.io/sgf/)

---

## 🚀 Funcionalidades Principais

- **Hub de Obras:** Visão geral e seleção do contrato/obra ativa (Obra padrão `44444B`).
- **Dashboard Consolidado:** Indicadores-chave de desempenho (KPIs), totais previstos x executados e avanço físico.
- **Curva ABC de Insumos:** Classificação automática dos insumos em faixas **A** (até 80%), **B** (até 95%) e **C** (restante) com suporte visual e gráfico.
- **Detalhamento de Serviços:** Listagem paginada e busca rápida por código ou descrição de serviços de engenharia.
- **Histórico de Ocorrências:** Registro e acompanhamento de imprevistos e diário de obra.

---

## 🛠️ Arquitetura & Tecnologias

- **Framework Backend:** Laravel 11 (PHP 8.2)
- **Banco de Dados (CI/CD):** SQLite em memória/local alimentado via `DatabaseSeeder` a partir do `DATASET_13.xlsx` oficial.
- **Exportação Estática:** `spatie/laravel-export`
- **Automação & CI/CD:** GitHub Actions (Build zerado, execução de seeds, exportação de ativos estáticos e reescrita dinâmica de caminhos para subpasta `/sgf/`).

---

## 💻 Como Rodar o Projeto Localmente

### Pré-requisitos
- PHP 8.2 ou superior (com extensão `zip` e `sqlite3` ativas)
- Composer

### Passo a Passo

1. **Clonar o repositório:**
   ```bash
   git clone [https://github.com/Dashboard-SGF/sgf.git](https://github.com/Dashboard-SGF/sgf.git)
   cd sgf

```

2. **Instalar as dependências:**
```bash
composer install

```


3. **Configurar o ambiente e banco de dados:**
```bash
cp .env.example .env
touch database/database.sqlite
php artisan key:generate

```


4. **Executar as migrations e popular com a planilha oficial:**
```bash
php artisan migrate:fresh --seed

```


5. **Iniciar o servidor local:**
```bash
php artisan serve

```


Acesse a aplicação em `http://127.0.0.1:8000`.

---

## 🤝 Colaboradores

* **Cauã Lopes** ([@Caualopesrlp](https://github.com/Caualopesrlp))
* **Ryan Abade** ([@RyanAbade](https://www.google.com/search?q=https://github.com/RyanAbade))

```
