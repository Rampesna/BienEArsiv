<div class="modal fade show" id="EInvoiceInboxReportModal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mw-900px">
        <div class="modal-content rounded">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                <div class="form fv-plugins-bootstrap5 fv-plugins-framework">
                    <div class="mb-13 text-center">
                        <h1 class="mb-3">e-Arşiv Gelen Faturalar</h1>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="e_invoice_inbox_report_start_date" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Başlangıç Tarihi</span>
                                </label>
                                <input id="e_invoice_inbox_report_start_date" type="date" class="form-control form-control-solid">
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="form-group">
                                <label for="e_invoice_inbox_report_end_date" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Bitiş Tarihi</span>
                                </label>
                                <input id="e_invoice_inbox_report_end_date" type="date" class="form-control form-control-solid">
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-15">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3">İptal</button>
                        <button type="button" class="btn btn-info" id="EInvoiceInboxReportButton">Raporla</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
