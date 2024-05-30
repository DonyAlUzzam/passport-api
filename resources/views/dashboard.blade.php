<div class="container mt-5">
<h4>List Buku</h4>
    <div class="row mt-2">
        <table class="table table-responsive">
        <thead>
            <tr>
            <th scope="col">No</th>
            <th scope="col">Judul</th>
            <th scope="col">Category</th>
            <th scope="col">Quantity</th>
            <th scope="col" style="padding:20px; text-align:right;">Aksi</th>
            </tr>
        </thead>
        <tbody id="listBuku">
           
        </tbody>
    </table>
    </div>

</div>

    <form id="form-data" enctype="multipart/form-data"> 
        <div class="modal fade" id="modalData"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <input type="hidden" name="id" id="inputId" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="">Jumlah Pinjam <sup class="text-danger">*</sup></label>
                                <input type="number" class="form-control" name="inputJumlah" id="inputJumlah" min=0 required />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger font-medium waves-effect text-start" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x me-2 fs-4"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-outline-success font-medium waves-effect text-start">
                            <i class="ti ti-send me-2 fs-4"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>



<script>

    let modalTitle          = "";
    let isActionForm        = "create";
    let defaultLimitPage    = 10;
    let currentPage         = 1;
    let totalPage           = 1;
    let defaultAscending    = 0;
    let defaultSearch       = '';

    async function getListData(limit=10, page=1, ascending=0, search='') {
        loadingPage(true);
        const getDataRest = await CallAPI(
        'GET',
        '{{ env("API_SERVICE") }}/books/list',
        {
            page : page,
            limit : limit,
            ascending : ascending,
            search: search
        }
        ).then(function (response) {
            console.log(response,"ok")
            return response;
        }).catch(function (error) {
            console.log(error,"ok")

            loadingPage(false);
            let resp = error.response;
            notificationAlert('info','Pemberitahuan',resp.data.message);
            return resp;
        });
        
        if(getDataRest.status == 200) {
            loadingPage(false);
            totalPage = getDataRest.data.pagination.total;
            let data = getDataRest.data.data;
            let display_from = ((defaultLimitPage * getDataRest.data.pagination.current_page) + 1) - defaultLimitPage;
            let display_to = currentPage < getDataRest.data.pagination.total_pages ? data.length < defaultLimitPage ? data.length : (defaultLimitPage * getDataRest.data.pagination.current_page) : totalPage;
            $('#totalPage').text(getDataRest.data.pagination.total);
            $('#countPage').text(""+display_from+" - "+display_to+"");
            let appendHtml = "";
            let index_loop = display_from;
            let styleBtn = ""
            for (let index = 0; index < data.length; index++) {
                let element = data[index];
                styleBtn = element.quantity < 1 ? 'disabled' : ""
                appendHtml += `
                <tr>
                    <td>${index_loop}.</td>
                    <td><b>${element.title}</b></td>
                    <td>${element.category ? element.category.name : ''}</td>
                    <td>${element.quantity}</td>
                    <td style="padding:20px; text-align:right;">
                        <ul>
                            <li>
                            <a href="javascript:void(0)" class="btn btn-secondary pinjam-data ${styleBtn}" data-id="${element.id}"><i class="fa fa-cart-shopping"></i>  Pinjam</a>
                            </li>
                        </ul>
                    </td>
                </tr>`;
                index_loop++;
            }
            if (totalPage==0) {
                appendHtml = `
                    <tr >
                        <th class="text-center" colspan="${$('th').length}"> Tidak ada data. </th>
                    </tr>
                `;
                $('#countPage').text("0 - 0");
            }
            $('#listBuku tr').remove();
            $('#listBuku').append(appendHtml);
        }
    }

   
    function showListCategory() {
        $('#selectCategory').select2({
            dropdownParent: $('#modalData'),
            ajax: {
                url: '{{ env('API_SERVICE') }}/categories/list',
                dataType: 'json',
                delay: 500,
                headers: {
                    Authorization: `Bearer ${Cookies.get('auth_token')}`
                },
                data: function(params) {
                    let query = {
                        search: params.term,
                        page: 1,
                        limit: 30,
                        ascending: 1,
                    }
                    return query;
                },
                processResults: function(res) {
                    let data = res.data
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
            },
            allowClear: true,
            placeholder: 'Pilih Category'
        });
    }

    async function pinjamData() {
        $(document).on("click", ".pinjam-data", async function() {
            let id = $(this).attr("data-id");
            $("#inputId").val(id);
            modalTitle      = "Pinjam Buku";
            isActionForm    = "create";
            $("#modalData").modal("show");
        });
    }

    async function submitData() {
        $(document).on("submit", "#form-data", async function(e) {
            e.preventDefault();
            loadingPage(true);
            const data = {};
            let method = "POST";
            data["book_id"]= $("#inputId").val();
            data["jumlah"]= $("#inputJumlah").val();
            data['borrowed_at'] = moment().format('YYYY-MM-DD HH:mm:ss')
          
            const postDataRest = await CallAPI(
                method,
                `{{ env('APP_SERVICE') }}/transaksi/create`,
                data
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                loadingPage(false);
                let resp = error.response;
                notificationAlert('info', 'Pemberitahuan', resp.data.message);
                return resp;
            });

            if (postDataRest.status == 200) {
                loadingPage(false);
                await initDataOnTable(defaultLimitPage, currentPage, defaultAscending, defaultSearch);

                notificationAlert('success', 'Pemberitahuan', postDataRest.data.message);
                $(this).trigger("reset");
                $("#modalData").modal("hide");
            }
        });
    }

    async function initDataOnTable(defaultLimitPage, currentPage, defaultAscending, defaultSearch) {
        await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch);
    }


    async function initPageLoad() {
        await initDataOnTable(defaultLimitPage, currentPage, defaultAscending, defaultSearch);

        await Promise.all([
            getListData(),
            pinjamData(),
            submitData(),
        
        ]);
    }
</script>
@endsection