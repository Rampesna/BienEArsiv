<div class="modal fade show" id="CreateSafeboxModal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
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
                        <h1 class="mb-3">Yeni Kasa & Banka Oluştur</h1>
                    </div>
                    <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="create_safebox_type_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Hesap Türü</span>
                                    </label>
                                    <select id="create_safebox_type_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="Hesap Türü" data-hide-search="true"></select>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="create_safebox_name" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Hesap Tanımı</span>
                                    </label>
                                    <input id="create_safebox_name" type="text" class="form-control form-control-solid" placeholder="Hesap Tanımı">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="create_safebox_account_number" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Hesap Numarası</span>
                                    </label>
                                    <input id="create_safebox_account_number" type="text" class="form-control form-control-solid" placeholder="Hesap Numarası">
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="create_safebox_branch" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Şube</span>
                                    </label>
                                    <input id="create_safebox_branch" type="text" class="form-control form-control-solid" placeholder="Şube">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="create_safebox_iban" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">IBAN</span>
                                    </label>
                                    <input id="create_safebox_iban" type="text" class="form-control form-control-solid" placeholder="IBAN">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3">İptal</button>
                        <button type="button" class="btn btn-primary" id="CreateSafeboxButton">Oluştur</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
