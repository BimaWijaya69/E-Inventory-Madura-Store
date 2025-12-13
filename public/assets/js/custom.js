$(document).ready(function () {
    $(".btn-decline").on("click", function (e) {
        e.preventDefault();

        const id = $(this).data("id");

        Swal.fire({
            title: "Transaksi Dikembalikan",
            input: "textarea",
            inputLabel: "Alasan Transaksi Material Dikembalikan",
            showCancelButton: true,
            cancelButtonText: "Batal",
            confirmButtonText: "Kirim",
            reverseButtons: true,
            background: "#",
            customClass: {
                popup: "swal2-custom-popup",
                input: "swal2-custom-input",
                confirmButton: "swal2-custom-confirm",
                cancelButton: "swal2-custom-cancel",
            },
            inputValidator: (value) => {
                if (!value) {
                    return "Alasan wajib diisi!";
                }
            },
            preConfirm: (message) => {
                Swal.showLoading();

                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: `/transaksi/${id}/decline`,
                        method: "POST",
                        contentType: "application/json",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        data: JSON.stringify({
                            message: message,
                        }),
                        success: function (response) {
                            resolve();
                        },
                        error: function () {
                            Swal.hideLoading();
                            Swal.showValidationMessage(
                                "Terjadi kesalahan saat mengirim data."
                            );
                        },
                    });
                });
            },
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    "Berhasil!",
                    "Alasan telah disampaikan.",
                    "success"
                ).then(() => {
                    location.reload();
                });
            }
        });
    });

    $(".btn-confirm").on("click", function (e) {
        e.preventDefault();

        const id = $(this).data("id");

        Swal.fire({
            title: "Yakin?",
            text: "Transaksi material disetujui",
            icon: "warning",
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonColor: "#18a342",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes",
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Memverifikasi...",
                    text: "Mohon tunggu sebentar",
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                $.ajax({
                    url: `/transaksi/${id}/confirm`,
                    method: "GET",
                    success: function (response) {
                        Swal.fire(
                            "Berhasil!",
                            "Data telah diverifikasi",
                            "success"
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function () {
                        Swal.fire(
                            "Gagal!",
                            "Terjadi kesalahan saat memverifikasi data",
                            "error"
                        );
                    },
                });
            }
        });
    });

    $(".btn-ajuan").on("click", function (e) {
        e.preventDefault();
        const id = $(this).data("id");
        $.ajax({
            url: `/transaksi/${id}/ajuan-kembali`,
            method: "GET",
            success: function (response) {
                Swal.fire(
                    "Berhasil!",
                    "Pengajuan telah berhasil",
                    "success"
                ).then(() => {
                    location.reload();
                });
            },
            error: function () {
                Swal.fire(
                    "Gagal!",
                    "Terjadi kesalahan pengajuan data",
                    "error"
                );
            },
        });
    });
    $(".urutkan").on("change", function (e) {
        const selected = $(this).val();
        const url = new URL(window.location.href);
        if (selected) {
            url.searchParams.set("sort", selected);
        } else {
            url.searchParams.delete("sort");
        }
        Swal.fire({
            title: "Memuat data...",
            text: "Mohon tunggu sebentar",
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
        window.location.href = url.toString();
    });

    $(".exportExcel").on("click", function (e) {
        e.preventDefault();

        $(".export").addClass("d-none");
        $(".btnText").addClass("d-none");
        $(".btnLoading").removeClass("d-none");
        $(".exportExcel").attr("disabled", true);

        let sort = $(".urutkan").val();
        let jenis = $(".jenis").val();

        let status =
            sort === "menunggu"
                ? 0
                : sort === "disetujui"
                ? 1
                : sort === "dikembalikan"
                ? 2
                : "";
        let queryParams = $.param({
            status: status,
        });

        window.location.href = `/export-transaksi/${jenis}?${queryParams}`;

        setTimeout(function () {
            $(".export").removeClass("d-none");
            $(".btnText").removeClass("d-none");
            $(".btnLoading").addClass("d-none");
            $(".exportExcel").attr("disabled", false);
        }, 3000);
    });
});
