(() => {
    "use strict";

    const $ = (selector, root = document) => root.querySelector(selector);
    const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];

    const modal = $("#importModal") || $("#modal");
    const importForm = $("#importForm");
    const excelFile = $("#excelFile") || $("#file");
    const staticWarning = $("#staticWarning");
    const submitImport = $("#submitImport");
    const toast = $("#toast");

    const searchInput = $("#searchInput");
    const startDate = $("#startDate");
    const endDate = $("#endDate");
    const workFilter = $("#workFilter");
    const applyFiltersButton = $("#applyFilters");

    const orderRows = $$("[data-order-row]");
    const orderCards = $$("[data-order-card]");
    const ordersEmpty = $("#ordersEmpty");
    const visibleOrdersCount = $("#visibleOrdersCount");

    const isStaticMode =
        window.location.protocol === "file:" ||
        window.location.hostname.endsWith("github.io");

    function normalize(value) {
        return String(value ?? "")
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .toLowerCase()
            .trim();
    }

    function parseDate(value) {
        if (!value) return null;

        // Formato yyyy-mm-dd
        if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            const [year, month, day] = value.split("-").map(Number);

            return new Date(
                year,
                month - 1,
                day,
                12,
                0,
                0
            );
        }

        // Formato dd/mm/yyyy
        const br = value.match(
            /^(\d{2})\/(\d{2})\/(\d{4})$/
        );

        if (br) {
            return new Date(
                Number(br[3]),
                Number(br[2]) - 1,
                Number(br[1]),
                12,
                0,
                0
            );
        }

        return null;
    }

    function dateMatches(value) {
        const orderDate = parseDate(value);

        // Se não conseguir interpretar a data,
        // não escondemos o pedido.
        if (!orderDate) {
            return true;
        }

        const minDate = startDate?.value
            ? parseDate(startDate.value)
            : null;

        const maxDate = endDate?.value
            ? parseDate(endDate.value)
            : null;

        if (minDate && orderDate < minDate) {
            return false;
        }

        if (maxDate) {
            maxDate.setHours(23, 59, 59, 999);

            if (orderDate > maxDate) {
                return false;
            }
        }

        return true;
    }

    function elementMatchesFilters(element) {
        if (!element) {
            return false;
        }

        const query = normalize(
            searchInput?.value
        );

        const searchText = normalize(
            element.dataset.search
        );

        const selectedWork = normalize(
            workFilter?.value
        );

        const itemWork = normalize(
            element.dataset.work
        );

        const matchesSearch =
            !query ||
            searchText.includes(query);

        const matchesPeriod =
            dateMatches(
                element.dataset.date
            );

        /*
         * O Repository atual ainda pode não possuir
         * "obra" em cada pedido.
         *
         * Enquanto data-work estiver vazio,
         * o filtro de obra não esconde o item.
         */
        const matchesWork =
            !selectedWork ||
            !itemWork ||
            selectedWork === itemWork;

        return (
            matchesSearch &&
            matchesPeriod &&
            matchesWork
        );
    }

    function setVisible(element, visible) {
        if (!element) {
            return;
        }

        element.hidden = !visible;

        /*
         * Segurança extra caso algum CSS
         * sobrescreva o comportamento de [hidden].
         */
        element.style.display =
            visible ? "" : "none";
    }

    function applyFilters() {
        let visibleCount = 0;

        orderRows.forEach((row) => {
            const visible =
                elementMatchesFilters(row);

            setVisible(row, visible);

            if (visible) {
                visibleCount += 1;
            }
        });

        /*
         * No mobile os mesmos pedidos
         * aparecem em cards.
         *
         * Não incrementamos o contador novamente.
         */
        orderCards.forEach((card) => {
            setVisible(
                card,
                elementMatchesFilters(card)
            );
        });

        if (visibleOrdersCount) {
            visibleOrdersCount.textContent =
                `${visibleCount} ${visibleCount === 1
                    ? "pedido"
                    : "pedidos"
                }`;
        }

        if (ordersEmpty) {
            ordersEmpty.hidden =
                visibleCount !== 0;

            ordersEmpty.style.display =
                visibleCount === 0
                    ? ""
                    : "none";
        }
    }

    function openModal() {
        if (!modal) {
            return;
        }

        modal.classList.add("open");

        document.body.style.overflow =
            "hidden";

        /*
         * GitHub Pages não executa PHP,
         * então upload de Excel não pode funcionar.
         */
        if (staticWarning) {
            staticWarning.hidden =
                !isStaticMode;
        }

        if (submitImport) {
            submitImport.disabled =
                isStaticMode;

            if (isStaticMode) {
                submitImport.title =
                    "A importação real exige a versão Laravel em um servidor PHP.";
            } else {
                submitImport.removeAttribute(
                    "title"
                );
            }
        }

        window.setTimeout(() => {
            excelFile?.focus();
        }, 50);
    }

    function closeModal() {
        if (!modal) {
            return;
        }

        modal.classList.remove("open");

        document.body.style.overflow = "";
    }

    let toastTimer = null;

    function showToast(message) {
        if (!toast) {
            console.info(message);
            return;
        }

        toast.textContent = message;

        toast.classList.add("show");

        if (toastTimer) {
            window.clearTimeout(
                toastTimer
            );
        }

        toastTimer =
            window.setTimeout(() => {
                toast.classList.remove(
                    "show"
                );
            }, 2800);
    }

    function simulate() {
        if (isStaticMode) {
            showToast(
                "No GitHub Pages a importação fica desativada. Use a versão dinâmica do Laravel."
            );

            return;
        }

        if (!excelFile?.files?.length) {
            showToast(
                "Selecione uma planilha .xlsx ou .xls."
            );

            return;
        }

        showToast(
            "Arquivo selecionado. Na versão Laravel ele será enviado para o backend."
        );
    }

    /*
     * Botões do HTML novo
     */

    $$(
        '[data-action="open-import"]'
    ).forEach((button) => {
        button.addEventListener(
            "click",
            openModal
        );
    });

    $$(
        '[data-action="close-import"]'
    ).forEach((button) => {
        button.addEventListener(
            "click",
            closeModal
        );
    });

    $$(
        '[data-action="print"]'
    ).forEach((button) => {
        button.addEventListener(
            "click",
            () => window.print()
        );
    });

    /*
     * Fecha ao clicar fora do modal
     */

    modal?.addEventListener(
        "click",
        (event) => {
            if (event.target === modal) {
                closeModal();
            }
        }
    );

    /*
     * Fecha com ESC
     */

    document.addEventListener(
        "keydown",
        (event) => {
            if (
                event.key === "Escape" &&
                modal?.classList.contains(
                    "open"
                )
            ) {
                closeModal();
            }
        }
    );

    /*
     * Filtros
     */

    applyFiltersButton
        ?.addEventListener(
            "click",
            applyFilters
        );

    searchInput
        ?.addEventListener(
            "input",
            applyFilters
        );

    startDate
        ?.addEventListener(
            "change",
            applyFilters
        );

    endDate
        ?.addEventListener(
            "change",
            applyFilters
        );

    workFilter
        ?.addEventListener(
            "change",
            applyFilters
        );

    /*
     * Importação Excel
     */

    importForm?.addEventListener(
        "submit",
        (event) => {
            if (isStaticMode) {
                event.preventDefault();

                showToast(
                    "A importação de Excel só funciona na implantação dinâmica do Laravel."
                );

                return;
            }

            if (
                !excelFile
                    ?.files
                    ?.length
            ) {
                event.preventDefault();

                showToast(
                    "Selecione uma planilha .xlsx ou .xls."
                );

                return;
            }

            if (submitImport) {
                submitImport.disabled =
                    true;

                submitImport.textContent =
                    "Processando...";
            }

            /*
             * Na versão dinâmica,
             * NÃO usamos preventDefault().
             *
             * O formulário continua normalmente
             * para o POST /importar do Laravel.
             */
        }
    );

    /*
     * Marca visualmente que estamos
     * na versão estática.
     */

    if (isStaticMode) {
        document.documentElement.dataset.mode =
            "static";
    }

    /*
     * Compatibilidade com seu HTML antigo,
     * que usava:
     *
     * onclick="openModal()"
     * onclick="closeModal()"
     * onclick="simulate()"
     */

    window.openModal = openModal;
    window.closeModal = closeModal;
    window.simulate = simulate;

    /*
     * Caso queira chamar o filtro
     * manualmente pelo console ou HTML.
     */
    window.applyDashboardFilters =
        applyFilters;

    /*
     * Executa uma vez ao carregar.
     */
    applyFilters();
})();