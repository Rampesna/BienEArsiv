<div class="modal fade show" id="CreateProductModal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static">
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
                        <h1 class="mb-3">Yeni Ürün Oluştur</h1>
                    </div>
                    <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="create_product_code" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Ürün Kodu</span>
                                    </label>
                                    <input id="create_product_code" type="text" class="form-control form-control-solid" placeholder="Ürün Kodu">
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="form-group">
                                    <label for="create_product_name" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Ürün Adı</span>
                                    </label>
                                    <input id="create_product_name" type="text" class="form-control form-control-solid" placeholder="Ürün Adı">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="create_product_unit_id" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Birim</span>
                                    </label>
                                    <select id="create_product_unit_id" class="form-select form-select-solid select2Input" data-control="select2" data-placeholder="Birim"></select>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="create_product_price" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Ürün Fiyatı</span>
                                    </label>
                                    <input id="create_product_price" type="text" class="form-control form-control-solid moneyMask" placeholder="0.0">
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="form-group">
                                    <label for="create_product_vat_rate" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">KDV Oranı</span>
                                    </label>
                                    <input id="create_product_vat_rate" type="text" class="form-control form-control-solid vatRate" placeholder="0">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="form-group">
                                    <label for="create_product_description" class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Açıklama</span>
                                    </label>
                                    <textarea rows="4" id="create_product_description" type="text" class="form-control form-control-solid"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-light me-3">İptal</button>
                        <button type="button" class="btn btn-primary" id="CreateProductButton">Oluştur</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
