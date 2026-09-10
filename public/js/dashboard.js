(() => {
    "use strict";

    const $ = (selector, root = document) => root.querySelector(selector);
    const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];

    // Modais
    const importModal = $("#importModal");
    const budgetModal = $("#budgetModal");
    const statusModal = $("#statusModal");

    const importForm = $("#importForm");
    const excelFile = $("#excelFile");
    const submitImport = $("#submitImport");
    const toast = $("#toast");

    const statusForm = $("#statusForm");
    const statusItemName = $("#statusItemName");
    const serviceStatusSelect = $("#serviceStatusSelect");
    const serviceObservacaoInput = $("#serviceObservacaoInput");

    const searchInput = $("#searchInput");

    function normalize(value) {
        return String(value ?? "")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .trim();
    }

    // Busca global em tempo real sincronizando Curva ABC e Tabela de Serviços
    function applyLiveFilter() {
        const query = normalize(searchInput?.value);

        // 1. Filtrar Curva ABC
        $$('.abc-item').forEach(item => {
            const searchText = normalize(item.dataset.search || item.textContent);
            const matches = !query || searchText.includes(query);
            item.style.display = matches ? "" : "none";
        });

        // 2. Filtrar Tabela de Serviços & Cards Mobile
        let visibleCount = 0;
        $$('[data-order-row]').forEach(row => {
            const searchText = normalize(row.dataset.search || row.textContent);
            const matches = !query || searchText.includes(query);
            row.style.display = matches ? "" : "none";
            if (matches) visibleCount++;
        });

        $$('[data-order-card]').forEach(card => {
            const searchText = normalize(card.dataset.search || card.textContent);
            const matches = !query || searchText.includes(query);
            card.style.display = matches ? "" : "none";
        });

        const visibleCountElem = $("#visibleOrdersCount");
        if (visibleCountElem && visibleCountElem.tagName !== 'A') {
            visibleCountElem.textContent = `${visibleCount} ${visibleCount === 1 ? 'item' : 'itens'}`;
        }
    }

    searchInput?.addEventListener("input", applyLiveFilter);

    // Modal Importação
    function openImportModal() {
        if (!importModal) return;
        importModal.classList.add("open");
        document.body.style.overflow = "hidden";
        setTimeout(() => excelFile?.focus(), 50);
    }

    function closeImportModal() {
        if (!importModal) return;
        importModal.classList.remove("open");
        document.body.style.overflow = "";
    }

    // Modal Orçamento
    function openBudgetModal() {
        if (!budgetModal) return;
        budgetModal.classList.add("open");
        document.body.style.overflow = "hidden";
    }

    function closeBudgetModal() {
        if (!budgetModal) return;
        budgetModal.classList.remove("open");
        document.body.style.overflow = "";
    }

    // Modal Status
    function openStatusModal(button) {
        if (!statusModal || !statusForm) return;

        const id = button.dataset.id;
        const code = button.dataset.code;
        const item = button.dataset.item;
        const status = button.dataset.status;
        const observacao = button.dataset.observacao;

        statusForm.action = `/servicos/${id}/status`;
        if (statusItemName) {
            statusItemName.textContent = `${code} — ${item}`;
        }
        if (serviceStatusSelect) {
            serviceStatusSelect.value = status || 'delivered';
        }
        if (serviceObservacaoInput) {
            serviceObservacaoInput.value = observacao || '';
        }

        statusModal.classList.add("open");
        document.body.style.overflow = "hidden";
    }

    function closeStatusModal() {
        if (!statusModal) return;
        statusModal.classList.remove("open");
        document.body.style.overflow = "";
    }

    // Event Listeners para Importação
    $$('[data-action="open-import"]').forEach(btn => btn.addEventListener("click", openImportModal));
    $$('[data-action="close-import"]').forEach(btn => btn.addEventListener("click", closeImportModal));

    // Event Listeners para Orçamento
    $$('[data-action="open-budget-modal"]').forEach(btn => btn.addEventListener("click", openBudgetModal));
    $$('[data-action="close-budget-modal"]').forEach(btn => btn.addEventListener("click", closeBudgetModal));

    // Event Listeners para Status
    $$('[data-action="open-status-modal"]').forEach(btn => btn.addEventListener("click", (e) => {
        openStatusModal(e.currentTarget);
    }));
    $$('[data-action="close-status-modal"]').forEach(btn => btn.addEventListener("click", closeStatusModal));

    // Exportar Print
    $$('[data-action="print"]').forEach(btn => btn.addEventListener("click", () => window.print()));

    // Fechar modais ao clicar fora
    [importModal, budgetModal, statusModal].forEach(m => {
        m?.addEventListener("click", (event) => {
            if (event.target === m) {
                closeImportModal();
                closeBudgetModal();
                closeStatusModal();
            }
        });
    });

    // Fechar modais com ESC
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeImportModal();
            closeBudgetModal();
            closeStatusModal();
        }
    });

    // Validação de formulários
    importForm?.addEventListener("submit", () => {
        if (submitImport) {
            submitImport.disabled = true;
            submitImport.textContent = "Processando...";
        }
    });

})();
