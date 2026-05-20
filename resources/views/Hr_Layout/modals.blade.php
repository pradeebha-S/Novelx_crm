 <div class="modal fade" id="logout" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 text-center p-4 py-5">
                <h5 class="fw-bold mb-2">Are you sure!!</h5>
                <p class="text-muted">Are you confirm to logout?</p>

                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">

                    <form action="{{ route('login') }}" method="GET" id="logoutForm">


                        <button type="button" class="btn btn-outline-primary p-3 fw-semibold me-3"
                            data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-danger p-3 fw-semibold" id="logout_btn">
                            Yes, Sure
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>


       <!-- delete -->
    <div class="modal fade" id="delete" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content rounded-4 text-center p-4 py-5">
                <h5 class="fw-bold mb-2">Are you sure!!</h5>
                <p class="text-muted">Are you confirm to delete?</p>

                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="btn btn-danger p-3 ms-2 fw-semibold">
                        Yes, Sure
                    </button>
                </div>
            </div>
        </div>
    </div>
     <div class="modal fade" id="view" tabindex="-1"  data-bs-keyboard="true">

        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rounded-4 text-center p-4 py-5">
                <img src="{{ asset('assets/img') }}/view.png" height="250px" width="400px" alt="warning image"
                    class="d-block mx-auto">
                <!-- <p><span class="text-danger">Note :</span>&nbsp;User cannot be deleted because they have active wallet
                    balance and purchase history.</p>
                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                    <button type="button" class="btn btn-outline-primary p-3 fw-semibold me-3" data-bs-dismiss="modal">
                        Cancel
                    </button>

                </div> -->
            </div>
        </div>
    </div>
