<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SGF — Dashboard de Suprimentos e Gestão Financeira de Obras">
    <title>@yield('title', 'SGF — Suprimentos & Gestão Financeira')</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark" aria-hidden="true"></div>
                <div>
                    <strong>SGF</strong>
                    <small>Gestão & Fiscalização</small>
                </div>
            </div>

            <nav class="nav" aria-label="Navegação principal">
                <a href="{{ route('obras.index') }}" style="color: #4bb1a3; font-weight: 800; border: 1px solid rgba(75,177,163,0.3); margin-bottom: 8px;">
                    <span aria-hidden="true">←</span> Voltar ao Hub
                </a>
                <a class="{{ ($active_tab ?? '') === 'overview' ? 'active' : '' }}" href="{{ route('dashboard', $obra_ativa ?? '44444B') }}">
                    <span class="nav-dot"></span>Visão Financeira
                </a>
                <a class="{{ ($active_tab ?? '') === 'curva-abc' ? 'active' : '' }}" href="{{ route('dashboard.curva-abc', $obra_ativa ?? '44444B') }}">
                    <span class="nav-dot"></span>Curva ABC
                </a>
                <a class="{{ ($active_tab ?? '') === 'servicos' ? 'active' : '' }}" href="{{ route('dashboard.servicos', $obra_ativa ?? '44444B') }}">
                    <span class="nav-dot"></span>Serviços & Composição
                </a>
                <a class="{{ ($active_tab ?? '') === 'ocorrencias' ? 'active' : '' }}" href="{{ route('dashboard.ocorrencias', $obra_ativa ?? '44444B') }}">
                    <span class="nav-dot"></span>Ocorrências
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="topbar">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <a href="{{ route('obras.index') }}" class="btn ghost" style="font-weight: 700;">
                        <span aria-hidden="true">←</span>
                        <span class="button-label">Voltar ao Hub</span>
                    </a>
                    <div class="mobile-brand">
                        <div class="brand-mark" aria-hidden="true"></div>
                        <strong>SGF</strong>
                    </div>
                </div>

                <div class="top-actions">
                    <button class="btn" type="button" data-action="print">
                        <span aria-hidden="true">⇩</span>
                        <span class="button-label">Exportar PDF</span>
                    </button>
                    <button class="btn primary" type="button" data-action="open-import">
                        <span aria-hidden="true">＋</span>
                        <span class="button-label">Importar Excel</span>
                    </button>
                </div>
            </header>

            <div class="container">
                <div class="print-header">
                    <div>
                        <strong>SGF — Relatório de Gestão Financeira de Obras</strong>
                        <span style="display: block; font-size: 12px; font-weight: 500;">Obra: {{ $obra_ativa ?? 'Geral' }}</span>
                    </div>
                    <span style="font-size: 12px;">Emitido em: {{ date('d/m/Y H:i') }}</span>
                </div>

                @if (session('success'))
                    <div style="background: var(--ok-soft); color: var(--ok); padding: 12px 16px; border-radius: 12px; margin-bottom: 20px; font-weight: 700; border: 1px solid #c2e2d5;">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <nav class="mobile-nav" aria-label="Navegação mobile">
            <a href="{{ route('dashboard', $obra_ativa ?? '44444B') }}" class="{{ ($active_tab ?? '') === 'overview' ? 'active' : '' }}"><span>⌂</span>Resumo</a>
            <a href="{{ route('dashboard.curva-abc', $obra_ativa ?? '44444B') }}" class="{{ ($active_tab ?? '') === 'curva-abc' ? 'active' : '' }}"><span>▥</span>ABC</a>
            <a href="{{ route('dashboard.servicos', $obra_ativa ?? '44444B') }}" class="{{ ($active_tab ?? '') === 'servicos' ? 'active' : '' }}"><span>≡</span>Serviços</a>
            <a href="{{ route('dashboard.ocorrencias', $obra_ativa ?? '44444B') }}" class="{{ ($active_tab ?? '') === 'ocorrencias' ? 'active' : '' }}"><span>!</span>Ocorrências</a>
        </nav>
    </div>

    {{-- Modal de Importação Excel --}}
    <div class="modal" id="importModal" role="dialog" aria-modal="true" aria-labelledby="importTitle">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h2 id="importTitle">Importar planilha de custos</h2>
                    <p>Substituição limpa: apaga a carga anterior da mesma obra antes de salvar.</p>
                </div>
                <button class="btn icon ghost" type="button" data-action="close-import" aria-label="Fechar">×</button>
            </div>

            <form id="importForm" class="modal-body" action="{{ route('importar.excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="field" style="margin-bottom: 14px;">
                    <label for="codigoObraImport">Código da Obra</label>
                    <input class="control" id="codigoObraImport" name="codigo_obra" type="text" value="{{ $obra_ativa ?? '' }}" placeholder="Ex: 44444B">
                </div>

                <div class="dropzone">
                    <strong>Selecione a planilha legada</strong>
                    <p>Arquivos .xlsx ou .xls.</p>
                    <input id="excelFile" name="arquivo" type="file" accept=".xlsx,.xls" required>
                </div>

                <div class="modal-actions">
                    <button class="btn" type="button" data-action="close-import">Cancelar</button>
                    <button class="btn primary" id="submitImport" type="submit">Validar e processar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal de Edição de Orçamento Aprovado --}}
    <div class="modal" id="budgetModal" role="dialog" aria-modal="true" aria-labelledby="budgetTitle">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h2 id="budgetTitle">Definir Orçamento Aprovado</h2>
                    <p>Altere o teto orçamentário da obra <strong>{{ $obra_ativa ?? '' }}</strong>.</p>
                </div>
                <button class="btn icon ghost" type="button" data-action="close-budget-modal" aria-label="Fechar">×</button>
            </div>

            <form class="modal-body" action="{{ route('dashboard.orcamento', $obra_ativa ?? '44444B') }}" method="POST">
                @csrf
                <div class="field" style="margin-bottom: 14px;">
                    <label for="orcamentoAprovadoInput">Valor do Orçamento Aprovado (R$)</label>
                    <input class="control" id="orcamentoAprovadoInput" name="orcamento_aprovado" type="number" step="0.01" value="{{ $kpis['orcamento_total'] ?? 0 }}" required>
                </div>

                <div class="modal-actions">
                    <button class="btn" type="button" data-action="close-budget-modal">Cancelar</button>
                    <button class="btn primary" type="submit">Salvar Orçamento</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal de Alteração de Status do Serviço --}}
    <div class="modal" id="statusModal" role="dialog" aria-modal="true" aria-labelledby="statusTitle">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h2 id="statusTitle">Gerenciar Status e Ocorrência</h2>
                    <p id="statusItemName">Item do serviço</p>
                </div>
                <button class="btn icon ghost" type="button" data-action="close-status-modal" aria-label="Fechar">×</button>
            </div>

            <form id="statusForm" class="modal-body" action="" method="POST">
                @csrf
                @method('PATCH')
                <div class="field" style="margin-bottom: 14px;">
                    <label for="serviceStatusSelect">Status do Item</label>
                    <select class="control" id="serviceStatusSelect" name="status" required>
                        <option value="delivered">Entregue / Normal</option>
                        <option value="pending">Pendente</option>
                        <option value="divergence">Com Divergência</option>
                        <option value="approved">Aprovado</option>
                    </select>
                </div>

                <div class="field" style="margin-bottom: 14px;">
                    <label for="serviceObservacaoInput">Observação / Ocorrência</label>
                    <textarea class="control" id="serviceObservacaoInput" name="observacao" rows="3" placeholder="Descreva eventuais atrasos, avarias ou inconsistências..."></textarea>
                </div>

                <div class="modal-actions">
                    <button class="btn" type="button" data-action="close-status-modal">Cancelar</button>
                    <button class="btn primary" type="submit">Salvar Alteração</button>
                </div>
            </form>
        </div>
    </div>

    <div class="toast" id="toast" role="status" aria-live="polite"></div>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>

</html>

