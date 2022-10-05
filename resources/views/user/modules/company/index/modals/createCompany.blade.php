<div class="modal fade show" id="CreateCompanyModal" aria-modal="true" role="dialog" data-bs-backdrop="static">
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
                        <h1 class="mb-3">Yeni Cari Oluştur</h1>
                    </div>
                    <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                        <div class="row">
                            <div class="col-xl-4 mb-5">
                                <div class="form-group">
                                    <label for="create_company_types" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Hesap Türü</span>
                                    </label>
                                    <select multiple id="create_company_types" class="form-select form-select-solid select2Input" data-control="select2" aria-hidden="true">
                                        <option value="1">Müşteri</option>
                                        <option value="2">Tedarikçi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-4 mb-5">
                                <div class="form-group">
                                    <label for="create_company_tax_number" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">VKN/TCKN</span>
                                    </label>
                                    <input id="create_company_tax_number" type="text" class="form-control form-control-solid" placeholder="VKN/TCKN" maxlength="11" aria-hidden="true">
                                </div>
                            </div>
                            <div class="col-xl-4 mb-5">
                                <div class="form-group">
                                    <label for="create_company_tax_office" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Vergi Dairesi</span>
                                    </label>
                                    <input id="create_company_tax_office" type="text" class="form-control form-control-solid" placeholder="Vergi Dairesi" aria-hidden="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 mb-5">
                                <div class="form-group">
                                    <label for="create_company_title" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Firma Ünvanı</span>
                                    </label>
                                    <input id="create_company_title" type="text" class="form-control form-control-solid" placeholder="Firma Ünvanını Giriniz..." aria-hidden="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 mb-5">
                                <div class="form-group">
                                    <label for="create_company_manager_name" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Yetkili Adı</span>
                                    </label>
                                    <input id="create_company_manager_name" type="text" class="form-control form-control-solid" placeholder="Yetkili Adı" aria-hidden="true">
                                </div>
                            </div>
                            <div class="col-xl-6 mb-5">
                                <div class="form-group">
                                    <label for="create_company_manager_surname" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Yetkili Soyadı</span>
                                    </label>
                                    <input id="create_company_manager_surname" type="text" class="form-control form-control-solid" placeholder="Yetkili Soyadı" aria-hidden="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 mb-5">
                                <div class="form-group">
                                    <label for="create_company_email" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>E-posta</span>
                                    </label>
                                    <input id="create_company_email" type="text" class="form-control form-control-solid emailMask" placeholder="E-posta" aria-hidden="true">
                                </div>
                            </div>
                            <div class="col-xl-6 mb-5">
                                <div class="form-group">
                                    <label for="create_company_phone" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Telefon</span>
                                    </label>
                                    <input id="create_company_phone" type="text" class="form-control form-control-solid phoneMask" placeholder="Telefon" aria-hidden="true">
                                </div>
                            </div>
                        </div>
                        <hr class="text-muted">
                        <div class="row">
                            <div class="col-xl-6 mb-5">
                                <div class="form-group" id="country_container">
                                    <label for="create_company_country_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Ülke</span>
                                    </label>
                                    <select id="create_company_country_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="Ülke" aria-hidden="true">

                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-5">
                                <div class="form-group">
                                    <label for="create_company_province_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Şehir</span>
                                    </label>
                                    <select id="create_company_province_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="Şehir" aria-hidden="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 mb-5">
                                <div class="form-group">
                                    <label for="create_company_district_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>İlçe</span>
                                    </label>
                                    <select id="create_company_district_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="İlçe" aria-hidden="true">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-6 mb-5">
                                <div class="form-group">
                                    <label for="create_company_postcode" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Posta Kodu</span>
                                    </label>
                                    <input id="create_company_postcode" type="text" class="form-control form-control-solid" placeholder="Posta Kodu" aria-hidden="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 mb-5">
                                <div class="form-group">
                                    <label for="create_company_address" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Adres</span>
                                    </label>
                                    <textarea id="create_company_address" class="form-control form-control-solid" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3">İptal</button>
                        <button type="button" class="btn btn-primary" id="CreateCompanyButton">Oluştur</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
