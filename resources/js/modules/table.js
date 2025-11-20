// Table Module
export function beartropyTable({ data, columns, perPage, sortable, searchable, paginated }) {
    return {
        original: data,
        columns: Array.isArray(columns) ? columns : Object.keys(columns),
        colLabels: columns,
        perPage,
        sortable,
        searchable,
        paginated: paginated === undefined ? true : paginated,

        search: '',
        sortBy: '',
        sortDesc: false,
        page: 1,

        get filtered() {
            let rows = this.original;
            if (this.search && this.searchable) {
                const s = this.search.toLowerCase();
                rows = rows.filter(r =>
                    this.columns.some(c =>
                        (r[c] ?? '').toString().toLowerCase().includes(s)
                    )
                );
            }
            return rows;
        },
        get sorted() {
            if (!this.sortable || !this.sortBy) return this.filtered;
            return [...this.filtered].sort((a, b) => {
                let vA = a[this.sortBy] ?? '';
                let vB = b[this.sortBy] ?? '';
                if (!isNaN(vA) && !isNaN(vB)) {
                    return this.sortDesc ? vB - vA : vA - vB;
                }
                return this.sortDesc
                    ? vB.toString().localeCompare(vA.toString())
                    : vA.toString().localeCompare(vB.toString());
            });
        },
        get paginatedRows() {
            if (!this.paginated) {
                return this.sorted;
            }
            return this.sorted.slice(this.start, this.start + this.perPage);
        },
        get totalPages() {
            return Math.max(1, Math.ceil(this.sorted.length / this.perPage));
        },
        get start() {
            return (this.page - 1) * this.perPage;
        },
        colLabel(col) {
            return this.colLabels[col] ?? col;
        },
        toggleSort(col) {
            if (!this.sortable) return;
            if (this.sortBy === col) {
                this.sortDesc = !this.sortDesc;
            } else {
                this.sortBy = col;
                this.sortDesc = false;
            }
        },
        gotoPage(p) {
            if (p >= 1 && p <= this.totalPages) this.page = p;
        },
        nextPage() {
            if (this.page < this.totalPages) this.page++;
        },
        prevPage() {
            if (this.page > 1) this.page--;
        },
        pagesToShow() {
            const total = this.totalPages;
            const current = this.page;
            const delta = 1;

            if (total <= 7) {
                return Array.from({ length: total }, (_, i) => i + 1);
            }

            let pages = [];
            pages.push(1);

            if (current - delta > 2) {
                pages.push('...');
            }

            let start = Math.max(2, current - delta);
            let end = Math.min(total - 1, current + delta);

            for (let i = start; i <= end; i++) {
                pages.push(i);
            }

            if (current + delta < total - 1) {
                pages.push('...');
            }

            pages.push(total);

            return pages;
        },
        init() {
            this.$watch('search', () => this.page = 1);
            this.$watch('sorted', () => this.page = 1);
        }
    }
}
